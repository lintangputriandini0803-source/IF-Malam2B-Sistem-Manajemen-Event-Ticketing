<?php

namespace App\Http\Controllers;

use App\Mail\TicketPurchasedMail;
use App\Models\Event;
use App\Models\Registration;
use App\Models\TicketType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;

class CheckoutController extends Controller
{
    /** Lama waktu reservasi pending ditahan sebelum hangus (harus selaras dengan timer 15 menit di checkout.blade.php). */
    private const RESERVATION_MINUTES = 15;

    public function __construct()
    {
        Config::$serverKey    = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized  = true;
        Config::$is3ds        = true;
    }

    // ─── STEP 1: Tampilkan halaman checkout ──────────────────────────────────

    public function store(Request $request, Event $event)
    {
        // Bug fix (Critical): event yang sudah selesai tidak boleh lagi dicheckout.
        if ($event->isExpired()) {
            return redirect()->route('event.show', $event->slug)
                ->withErrors(['error' => 'Event ini sudah berakhir, pembelian tiket tidak tersedia lagi.']);
        }

        $tickets         = $request->input('tickets', []);
        $selectedTickets = [];
        $totalPrice      = 0;

        foreach ($tickets as $ticketId => $qty) {
            $qty = (int) $qty;
            if ($qty <= 0) continue;

            $ticket = TicketType::find($ticketId);
            if (! $ticket || $ticket->event_id !== $event->id) continue;

            $status = $ticket->getStatus();
            if ($status === 'sold_out')    return back()->withErrors(['error' => "Tiket {$ticket->name} sudah habis."]);
            if ($status !== 'available')   return back()->withErrors(['error' => "Tiket {$ticket->name} tidak tersedia."]);
            if ($ticket->getRemainingQuota() < $qty)
                return back()->withErrors(['error' => "Kuota tiket {$ticket->name} tidak mencukupi (sisa: {$ticket->getRemainingQuota()})."]);

            $selectedTickets[] = ['ticket' => $ticket, 'qty' => $qty];
            $totalPrice += $ticket->price * $qty;
        }

        if (empty($selectedTickets)) return back()->withErrors(['error' => 'Pilih minimal 1 tiket.']);

        // Bersihkan session lama
        session()->forget(['snap_token', 'order_ref', 'on_step']);

        return view('checkout', compact('event', 'selectedTickets', 'totalPrice'));
    }

    // ─── STEP 2: Proses form, simpan ke DB, dapat snap token, kembali ke checkout ──

    public function process(Request $request, Event $event)
    {
        // Bug fix (Critical): cegah checkout pada event yang sudah berakhir,
        // walau request dikirim langsung ke endpoint ini (bypass step 1).
        if ($event->isExpired()) {
            return back()->withErrors(['error' => 'Event ini sudah berakhir, pembelian tiket tidak tersedia lagi.']);
        }

        // Bug fix (High): spam checkout / klik berkali-kali dalam waktu singkat.
        // Dibatasi per kombinasi IP + email agar satu orang tidak bisa membuat
        // banyak order beruntun dalam hitungan detik.
        $throttleKey = 'checkout-process:' . $request->ip() . '|' . strtolower((string) $request->input('buyer_email'));
        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            return back()->withErrors(['error' => 'Terlalu banyak percobaan checkout. Silakan tunggu sebentar lalu coba lagi.']);
        }
        RateLimiter::hit($throttleKey, 30); // max 3 percobaan per 30 detik

        $request->validate([
            'buyer_name'   => 'required|string|max:255',
            // Bug fix (Medium): NIM/NIK harus alfanumerik, tidak boleh karakter bebas.
            'buyer_nim'    => ['required', 'string', 'max:50', 'regex:/^[A-Za-z0-9]+$/'],
            'buyer_email'  => 'required|email',
            // Bug fix (Medium): No HP harus berupa digit saja (boleh diawali +).
            'buyer_phone'  => ['required', 'string', 'max:20', 'regex:/^\+?[0-9]{8,20}$/'],
            'tickets'      => 'required|array',
        ], [
            'buyer_nim.regex'   => 'NIM/NIK hanya boleh berisi huruf dan angka.',
            'buyer_phone.regex' => 'Nomor HP hanya boleh berisi angka (boleh diawali +).',
        ]);

        // Bug fix (Critical): validasi ulang & kunci baris tiket di dalam transaksi
        // DB supaya tidak ada race condition antar checkout yang berbarengan,
        // dan supaya kuota TIDAK langsung berkurang permanen di sini — hanya
        // direservasi sementara via status 'pending' + expires_at.
        $orderRef    = 'SIMETIX-' . strtoupper(Str::random(4)) . '-' . time();
        $totalPrice  = 0;
        $itemDetails = [];
        $expiresAt   = now()->addMinutes(self::RESERVATION_MINUTES);

        try {
            $registrations = \DB::transaction(function () use ($request, $event, $orderRef, $expiresAt, &$totalPrice, &$itemDetails) {
                $created = [];

                foreach ($request->tickets as $item) {
                    // lockForUpdate agar dua request bersamaan tidak lolos validasi kuota yang sama.
                    $ticket = TicketType::where('id', $item['id'])->lockForUpdate()->firstOrFail();
                    $qty    = (int) $item['qty'];

                    if ($ticket->event_id !== $event->id) {
                        throw new \RuntimeException("Tiket {$ticket->name} tidak valid untuk event ini.");
                    }

                    if (! $ticket->isAvailable($qty)) {
                        throw new \RuntimeException("Tiket {$ticket->name} tidak tersedia lagi saat proses (sisa: {$ticket->getRemainingQuota()}).");
                    }

                    $subtotal    = $ticket->price * $qty;
                    $totalPrice += $subtotal;

                    $itemDetails[] = [
                        'id'       => (string) $ticket->id,
                        'price'    => (int) $ticket->price,
                        'quantity' => $qty,
                        'name'     => substr($event->title . ' - ' . $ticket->name, 0, 50),
                    ];

                    $created[] = Registration::create([
                        'event_id'       => $event->id,
                        'ticket_type_id' => $ticket->id,
                        'order_ref'      => $orderRef,
                        'name'           => $request->buyer_name,
                        'nim'            => $request->buyer_nim,
                        'email'          => $request->buyer_email,
                        'phone'          => $request->buyer_phone,
                        'quantity'       => $qty,
                        'total_price'    => $subtotal,
                        'payment_method' => 'midtrans',
                        'status'         => 'pending',
                        'expires_at'     => $expiresAt,
                    ]);
                }

                return $created;
            });
        } catch (\Throwable $e) {
            \Log::warning('Checkout gagal: ' . $e->getMessage());
            return back()->withErrors(['error' => $e->getMessage()]);
        }

        // ── Buat Midtrans Snap token ───────────────────────────────────────

        $params = [
            'transaction_details' => [
                'order_id'     => $orderRef,
                'gross_amount' => (int) $totalPrice,
            ],
            'item_details'     => $itemDetails,
            'customer_details' => [
                'first_name' => $request->buyer_name,
                'email'      => $request->buyer_email,
                'phone'      => $request->buyer_phone,
            ],
            'callbacks' => [
                'finish' => route('checkout.summary', $event->slug),
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
        } catch (\Exception $e) {
            \Log::error('Midtrans error: ' . $e->getMessage());

            // Bug fix (High): jika gagal dapat snap token, batalkan reservasi
            // yang baru dibuat supaya tidak menahan kuota orang lain secara
            // percuma, dan tampilkan pesan error yang jelas ke pembeli.
            Registration::where('order_ref', $orderRef)->update(['status' => 'cancelled']);

            return back()->withErrors([
                'error' => 'Gagal menghubungi layanan pembayaran (Midtrans). Pesanan dibatalkan otomatis, silakan coba lagi.',
            ]);
        }

        // ── Simpan ke session, redirect kembali ke checkout di step 2 ──────

        session([
            'snap_token'   => $snapToken,
            'order_ref'    => $orderRef,
            'total_price'  => $totalPrice,
            'buyer_name'   => $request->buyer_name,
            'buyer_nim'    => $request->buyer_nim,
            'buyer_email'  => $request->buyer_email,
            'buyer_phone'  => $request->buyer_phone,
            'event_id'     => $event->id,
            'on_step'      => 2,            // ← sinyal ke blade untuk skip ke step 2
        ]);

        // Rebuild selectedTickets untuk view
        $selectedTickets = [];
        foreach ($request->tickets as $item) {
            $ticket = TicketType::find($item['id']);
            if ($ticket) $selectedTickets[] = ['ticket' => $ticket, 'qty' => (int) $item['qty']];
        }

        // Kembali ke checkout.blade.php — blade akan auto-buka step 2 + Midtrans
        return view('checkout', compact('event', 'selectedTickets', 'totalPrice'));
    }

    // ─── STEP 3: Summary / finish ────────────────────────────────────────────

    public function summary(Event $event)
    {
        $orderRef = session('order_ref') ?? request('order_id');

        if (! $orderRef) return redirect()->route('event.show', $event->slug);

        // Bug fix (Critical): JANGAN percaya parameter `transaction_status` dari
        // query string apa adanya (itu dikirim oleh browser/JS milik pembeli,
        // gampang dipalsukan). Verifikasi status transaksi yang sebenarnya
        // langsung ke server Midtrans sebelum mengubah status registrasi
        // atau menambah `sold`.
        if (request()->filled('transaction_status') || request()->filled('order_id')) {
            $this->verifyAndSyncStatus($orderRef);
        }

        $registrations = Registration::where('order_ref', $orderRef)->with(['ticketType', 'event'])->get();

        if ($registrations->isEmpty()) {
            return redirect()->route('event.show', $event->slug)
                ->withErrors(['error' => 'Pesanan tidak ditemukan.']);
        }

        $status = $registrations->first()->status;

        // Bug fix (Medium): halaman ringkasan tidak boleh bisa dibuka/dianggap
        // "berhasil" selama pembayaran belum benar-benar confirmed. Kalau
        // masih pending atau sudah cancelled/expired, arahkan ke pesan yang
        // sesuai daripada menampilkan ringkasan seolah sukses.
        if ($status === 'cancelled') {
            return redirect()->route('event.show', $event->slug)
                ->withErrors(['error' => 'Pesanan ini dibatalkan atau sudah kedaluwarsa.']);
        }

        $buyer = [
            'name'  => session('buyer_name')  ?? ($registrations->first()->name  ?? '-'),
            'nim'   => session('buyer_nim')   ?? ($registrations->first()->nim   ?? '-'),
            'email' => session('buyer_email') ?? ($registrations->first()->email ?? '-'),
            'phone' => session('buyer_phone') ?? ($registrations->first()->phone ?? '-'),
        ];

        $totalPrice = session('total_price') ?? $registrations->sum('total_price');

        if ($status !== 'confirmed') {
            // Masih pending (belum bayar / belum ada notifikasi Midtrans masuk).
            return view('summary', compact('event', 'registrations', 'buyer', 'orderRef', 'totalPrice'))
                ->with('info', 'Pembayaran kamu sedang diproses. Halaman ini akan menampilkan tiket setelah pembayaran terkonfirmasi.');
        }

        // Kirim email tiket jika status sudah confirmed dan belum pernah dikirim.
        // (Dijaga oleh kolom email_sent_at agar tidak dobel dengan trigger dari webhook notification().)
        $emailSent = $this->sendTicketEmailIfNeeded($registrations, $orderRef, $buyer, (float) $totalPrice);

        // Bug fix (Low): perbaiki typo "berhasi" → "berhasil".
        // Bug fix (Low): jika pengiriman email gagal, beri tahu pembeli secara
        // jujur alih-alih selalu bilang "cek email kamu".
        $message = $emailSent
            ? 'Pembelian berhasil. Silakan cek email kamu untuk tiket.'
            : 'Pembelian berhasil, namun email tiket gagal terkirim. Silakan hubungi panitia atau simpan halaman ini sebagai bukti pembelian.';

        return view('summary', compact('event', 'registrations', 'buyer', 'orderRef', 'totalPrice'))
            ->with($emailSent ? 'info' : 'warning', $message);
    }

    /**
     * Tanyakan langsung ke Midtrans status transaksi yang sebenarnya untuk
     * order_ref ini, lalu sinkronkan status registrasi & kuota `sold`
     * berdasarkan jawaban Midtrans — BUKAN berdasarkan parameter URL yang
     * dikirim oleh klien.
     */
    private function verifyAndSyncStatus(string $orderRef): void
    {
        try {
            $result      = Transaction::status($orderRef);
            $txStatus    = $result->transaction_status ?? null;
            $fraudStatus = $result->fraud_status ?? null;
        } catch (\Exception $e) {
            \Log::warning("Tidak bisa verifikasi status Midtrans untuk {$orderRef}: " . $e->getMessage());
            return;
        }

        $this->applyVerifiedStatus($orderRef, $txStatus, $fraudStatus);
    }

    /**
     * Terapkan status yang SUDAH diverifikasi (dari Midtrans, baik lewat
     * webhook notification() maupun verifyAndSyncStatus()) ke seluruh
     * registrasi dalam satu order_ref, sekaligus menambah `sold` secara
     * permanen hanya pada saat transisi pertama kali ke 'confirmed'.
     */
    private function applyVerifiedStatus(string $orderRef, ?string $txStatus, ?string $fraudStatus): void
    {
        $newStatus = match ($txStatus) {
            'capture'    => $fraudStatus === 'challenge' ? 'pending' : 'confirmed',
            'settlement' => 'confirmed',
            'cancel', 'deny', 'expire' => 'cancelled',
            default      => null, // status lain (pending, dsb) → tidak diubah
        };

        if ($newStatus === null) {
            return;
        }

        \DB::transaction(function () use ($orderRef, $newStatus) {
            $registrations = Registration::where('order_ref', $orderRef)
                ->where('status', '!=', $newStatus) // idempoten: skip kalau sudah di status ini
                ->lockForUpdate()
                ->get();

            foreach ($registrations as $reg) {
                $previousStatus = $reg->status;
                $reg->status = $newStatus;
                $reg->save();

                // Bug fix (Critical): kuota (`sold`) HANYA bertambah permanen
                // di sini, persis pada transisi ke 'confirmed' — bukan saat
                // checkout dibuat. Reservasi pending otomatis berhenti
                // menahan kuota begitu statusnya berubah dari 'pending'.
                if ($newStatus === 'confirmed' && $previousStatus !== 'confirmed' && $reg->ticketType) {
                    $reg->ticketType->confirmSold($reg->quantity);
                }
            }
        });
    }

    // ─── Webhook Midtrans ─────────────────────────────────────────────────────

    public function notification(Request $request)
    {
        try {
            $notification  = new \Midtrans\Notification();
            $orderId       = $notification->order_id;
            $txStatus      = $notification->transaction_status;
            $fraudStatus   = $notification->fraud_status ?? null;

            $this->applyVerifiedStatus($orderId, $txStatus, $fraudStatus);

            $registrations = Registration::where('order_ref', $orderId)->where('status', 'confirmed')->with(['ticketType', 'event'])->get();

            if ($registrations->isNotEmpty()) {
                $first = $registrations->first();
                $buyer = [
                    'name'  => $first->name,
                    'nim'   => $first->nim,
                    'email' => $first->email,
                    'phone' => $first->phone,
                ];
                $totalPrice = (float) $registrations->sum('total_price');

                $this->sendTicketEmailIfNeeded($registrations, $orderId, $buyer, $totalPrice);
            }

            return response()->json(['status' => 'ok']);
        } catch (\Exception $e) {
            \Log::error('Midtrans notification error: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }
    }

    // ─── Kirim email tiket (PDF + ucapan terima kasih), dijaga anti-dobel ─────

    /**
     * @return bool true jika email berhasil terkirim (atau memang sudah pernah terkirim sebelumnya), false jika gagal kirim.
     */
    private function sendTicketEmailIfNeeded($registrations, string $orderRef, array $buyer, float $totalPrice): bool
    {
        $alreadySent = $registrations->first()->email_sent_at !== null;
        if ($alreadySent) {
            return true;
        }

        if (empty($buyer['email'])) {
            \Log::warning("Tidak bisa kirim email tiket untuk order {$orderRef}: email pembeli kosong.");
            return false;
        }

        try {
            Mail::to($buyer['email'])->send(
                new TicketPurchasedMail($orderRef, $registrations, $buyer, $totalPrice)
            );

            Registration::where('order_ref', $orderRef)->update(['email_sent_at' => now()]);
            return true;
        } catch (\Exception $e) {
            // Bug fix (Low): sebelumnya hanya di-log dan pembeli tidak pernah
            // diberi tahu kalau email gagal terkirim. Sekarang status gagal
            // dikembalikan ke pemanggil supaya bisa ditampilkan di halaman summary.
            \Log::error("Gagal mengirim email tiket untuk order {$orderRef}: " . $e->getMessage());
            return false;
        }
    }
}
