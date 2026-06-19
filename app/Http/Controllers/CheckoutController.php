<?php

namespace App\Http\Controllers;

use App\Mail\TicketPurchasedMail;
use App\Models\Event;
use App\Models\Registration;
use App\Models\TicketType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;

class CheckoutController extends Controller
{
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
        $request->validate([
            'buyer_name'   => 'required|string|max:255',
            'buyer_nim'    => 'required|string|max:50',
            'buyer_email'  => 'required|email',
            'buyer_phone'  => 'required|string|max:20',
            'tickets'      => 'required|array',
        ]);

        $orderRef    = 'SIMETIX-' . strtoupper(Str::random(4)) . '-' . time();
        $totalPrice  = 0;
        $itemDetails = [];

        // ── Validasi kuota & hitung total ──────────────────────────────────

        foreach ($request->tickets as $item) {
            $ticket = TicketType::findOrFail($item['id']);
            $qty    = (int) $item['qty'];

            if (! $ticket->isAvailable($qty)) {
                return back()->withErrors(['error' => "Tiket {$ticket->name} tidak tersedia lagi saat proses."]);
            }

            $subtotal     = $ticket->price * $qty;
            $totalPrice  += $subtotal;

            $itemDetails[] = [
                'id'       => (string) $ticket->id,
                'price'    => (int) $ticket->price,
                'quantity' => $qty,
                'name'     => substr($event->title . ' - ' . $ticket->name, 0, 50),
            ];
        }

        // ── Simpan registrasi dengan status pending ────────────────────────

        foreach ($request->tickets as $item) {
            $ticket = TicketType::findOrFail($item['id']);
            $qty    = (int) $item['qty'];

            if (! $ticket->decreaseQuota($qty)) {
                return back()->withErrors(['error' => "Gagal memproses tiket {$ticket->name}, coba lagi."]);
            }

            Registration::create([
                'event_id'       => $event->id,
                'ticket_type_id' => $ticket->id,
                'order_ref'      => $orderRef,
                'name'           => $request->buyer_name,
                'nim'            => $request->buyer_nim,
                'email'          => $request->buyer_email,
                'phone'          => $request->buyer_phone,
                'quantity'       => $qty,
                'total_price'    => $ticket->price * $qty,
                'payment_method' => 'midtrans',
                'status'         => 'pending',
            ]);
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
            $snapToken = null;
            \Log::error('Midtrans error: ' . $e->getMessage());
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

        $transactionStatus = request('transaction_status');
        if ($transactionStatus) {
            $newStatus = match ($transactionStatus) {
                'capture', 'settlement' => 'confirmed',
                'cancel', 'expire'      => 'cancelled',
                default                 => 'pending',
            };
            Registration::where('order_ref', $orderRef)->update(['status' => $newStatus]);
        }

        $registrations = Registration::where('order_ref', $orderRef)->with(['ticketType', 'event'])->get();

        $buyer = [
            'name'  => session('buyer_name')  ?? ($registrations->first()->name  ?? '-'),
            'nim'   => session('buyer_nim')   ?? ($registrations->first()->nim   ?? '-'),
            'email' => session('buyer_email') ?? ($registrations->first()->email ?? '-'),
            'phone' => session('buyer_phone') ?? ($registrations->first()->phone ?? '-'),
        ];

        $totalPrice = session('total_price') ?? $registrations->sum('total_price');

        // Kirim email tiket jika status sudah confirmed dan belum pernah dikirim.
        // (Dijaga oleh kolom email_sent_at agar tidak dobel dengan trigger dari webhook notification().)
        if ($registrations->isNotEmpty() && $registrations->first()->status === 'confirmed') {
            $this->sendTicketEmailIfNeeded($registrations, $orderRef, $buyer, (float) $totalPrice);
        }

        return view('summary', compact('event', 'registrations', 'buyer', 'orderRef', 'totalPrice'))->with('info', 'Pembelian berhasi, Silakan cek email kamu');
    }

    // ─── Webhook Midtrans ─────────────────────────────────────────────────────

    public function notification(Request $request)
    {
        try {
            $notification  = new \Midtrans\Notification();
            $orderId       = $notification->order_id;
            $txStatus      = $notification->transaction_status;
            $fraudStatus   = $notification->fraud_status ?? null;

            $newStatus = 'pending';
            if ($txStatus === 'capture') {
                $newStatus = $fraudStatus === 'challenge' ? 'pending' : 'confirmed';
            } elseif ($txStatus === 'settlement') {
                $newStatus = 'confirmed';
            } elseif (in_array($txStatus, ['cancel', 'deny', 'expire'])) {
                $newStatus = 'cancelled';
                Registration::where('order_ref', $orderId)->with('ticketType')->each(function ($reg) {
                    if ($reg->ticketType) {
                        \DB::table('ticket_types')->where('id', $reg->ticket_type_id)->decrement('sold', $reg->quantity);
                    }
                });
            }

            Registration::where('order_ref', $orderId)->update(['status' => $newStatus]);

            if ($newStatus === 'confirmed') {
                $registrations = Registration::where('order_ref', $orderId)->with(['ticketType', 'event'])->get();

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
            }

            return response()->json(['status' => 'ok']);
        } catch (\Exception $e) {
            \Log::error('Midtrans notification error: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }
    }

    // ─── Kirim email tiket (PDF + ucapan terima kasih), dijaga anti-dobel ─────

    private function sendTicketEmailIfNeeded($registrations, string $orderRef, array $buyer, float $totalPrice): void
    {
        $alreadySent = $registrations->first()->email_sent_at !== null;
        if ($alreadySent) {
            return;
        }

        if (empty($buyer['email'])) {
            \Log::warning("Tidak bisa kirim email tiket untuk order {$orderRef}: email pembeli kosong.");
            return;
        }

        try {
            Mail::to($buyer['email'])->send(
                new TicketPurchasedMail($orderRef, $registrations, $buyer, $totalPrice)
            );

            Registration::where('order_ref', $orderRef)->update(['email_sent_at' => now()]);
        } catch (\Exception $e) {
            \Log::error("Gagal mengirim email tiket untuk order {$orderRef}: " . $e->getMessage());
        }
    }
}
