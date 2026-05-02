<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use App\Models\TicketType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    /**
     * Step 1: Terima pilihan tiket dari detailEvent, tampilkan halaman checkout.
     */
    public function store(Request $request, Event $event)
    {
        $tickets = $request->input('tickets', []);

        $selectedTickets = [];
        $totalPrice      = 0;

        foreach ($tickets as $ticketId => $qty) {
            $qty = (int) $qty;
            if ($qty <= 0) continue;

            $ticket = TicketType::find($ticketId);
            if (! $ticket || $ticket->event_id !== $event->id) continue;

            // Validasi ketersediaan (kuota + waktu)
            $status = $ticket->getStatus();
            if ($status === 'sold_out') {
                return back()->withErrors(['error' => "Tiket {$ticket->name} sudah habis."]);
            }
            if ($status === 'time_closed') {
                return back()->withErrors(['error' => "Waktu pembelian tiket {$ticket->name} sudah berakhir."]);
            }
            if ($status === 'not_open') {
                return back()->withErrors(['error' => "Tiket {$ticket->name} belum atau sudah tidak tersedia."]);
            }
            if ($ticket->getRemainingQuota() < $qty) {
                return back()->withErrors(['error' => "Kuota tiket {$ticket->name} tidak mencukupi (sisa: {$ticket->getRemainingQuota()})."]);
            }

            $selectedTickets[] = [
                'ticket' => $ticket,
                'qty'    => $qty,
            ];
            $totalPrice += $ticket->price * $qty;
        }

        if (empty($selectedTickets)) {
            return back()->withErrors(['error' => 'Pilih minimal 1 tiket.']);
        }

        return view('checkout', compact('event', 'selectedTickets', 'totalPrice'));
    }

    /**
     * Step 2: Proses pembayaran — simpan registrasi ke database.
     */
    public function process(Request $request, Event $event)
    {
        $request->validate([
            'buyer_name'       => 'required|string|max:255',
            'buyer_nim'        => 'required|string|max:50',
            'buyer_email'      => 'required|email',
            'buyer_phone'      => 'required|string|max:20',
            'payment_method'   => 'required|string',
            'tickets'          => 'required|array',
            'tickets.*.id'     => 'required|exists:ticket_types,id',
            'tickets.*.qty'    => 'required|integer|min:1',
        ]);

        $orderRef   = strtoupper(Str::random(2)) . '-' . rand(10000, 99999);
        $vaNumber   = $this->generateVA($request->payment_method);
        $totalPrice = 0;
        $registrations = [];

        foreach ($request->tickets as $item) {
            $ticket = TicketType::findOrFail($item['id']);
            $qty    = (int) $item['qty'];

            // Validasi ulang sebelum simpan (double-check)
            if (! $ticket->isAvailable($qty)) {
                return back()->withErrors(['error' => "Tiket {$ticket->name} tidak tersedia lagi saat proses."]);
            }

            $subtotal    = $ticket->price * $qty;
            $totalPrice += $subtotal;

            // Kurangi kuota atomik
            if (! $ticket->decreaseQuota($qty)) {
                return back()->withErrors(['error' => "Gagal memproses tiket {$ticket->name}, coba lagi."]);
            }

            // Simpan satu registrasi per jenis tiket
            $reg = Registration::create([
                'ticket_type_id'  => $ticket->id,
                'order_ref'       => $orderRef,
                'name'            => $request->buyer_name,
                'nim'             => $request->buyer_nim,
                'email'           => $request->buyer_email,
                'phone'           => $request->buyer_phone,
                'quantity'        => $qty,
                'total_price'     => $subtotal,
                'payment_method'  => $request->payment_method,
                'virtual_account' => $vaNumber,
                'status'          => 'pending',
            ]);

            $registrations[] = $reg;
        }

        // Simpan data ringkasan di session untuk halaman konfirmasi
        session([
            'order_ref'      => $orderRef,
            'va_number'      => $vaNumber,
            'total_price'    => $totalPrice,
            'payment_method' => $request->payment_method,
            'buyer_name'     => $request->buyer_name,
            'buyer_nim'      => $request->buyer_nim,
            'buyer_email'    => $request->buyer_email,
            'buyer_phone'    => $request->buyer_phone,
            'event_id'       => $event->id,
        ]);

        return redirect()->route('checkout.payment', $event->slug);
    }

    /**
     * Step 3: Tampilkan halaman pembayaran (VA).
     */
    public function payment(Event $event)
    {
        if (! session('order_ref')) {
            return redirect()->route('event.show', $event->slug);
        }

        $orderRef      = session('order_ref');
        $vaNumber      = session('va_number');
        $totalPrice    = session('total_price');
        $paymentMethod = session('payment_method');

        // Ambil semua registrasi dengan order_ref ini
        $registrations = Registration::where('order_ref', $orderRef)
            ->with('ticketType')
            ->get();

        return view('payment', compact('event', 'orderRef', 'vaNumber', 'totalPrice', 'paymentMethod', 'registrations'));
    }

    /**
     * Step 4: Ringkasan akhir (tiket berhasil).
     */
    public function summary(Event $event)
    {
        $orderRef = session('order_ref');
        if (! $orderRef) {
            return redirect()->route('event.show', $event->slug);
        }

        $registrations = Registration::where('order_ref', $orderRef)
            ->with('ticketType')
            ->get();

        $buyer = [
            'name'  => session('buyer_name'),
            'nim'   => session('buyer_nim'),
            'email' => session('buyer_email'),
            'phone' => session('buyer_phone'),
        ];

        $totalPrice = session('total_price');

        return view('summary', compact('event', 'registrations', 'buyer', 'orderRef', 'totalPrice'));
    }

    // ─── Helper ───────────────────────────────────────────────────────────────

    private function generateVA(string $bank): string
    {
        $prefix = match (strtolower($bank)) {
            'bca'     => '126',
            'mandiri' => '888',
            'bni'     => '988',
            'bri'     => '002',
            'permata' => '013',
            default   => '000',
        };
        return $prefix . ' ' . rand(1000, 9999) . ' ' . rand(100000, 999999);
    }
}
