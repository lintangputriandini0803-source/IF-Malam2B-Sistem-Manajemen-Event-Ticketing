<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use App\Models\TicketType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;

class CheckoutController extends Controller
{
    public function __construct()
    {
        // Konfigurasi Midtrans setiap kali controller dipakai
        Config::$serverKey    = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized  = true;
        Config::$is3ds        = true;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // STEP 1 — Terima pilihan tiket, tampilkan form checkout
    // ─────────────────────────────────────────────────────────────────────────

    public function store(Request $request, Event $event)
    {
        $tickets        = $request->input('tickets', []);
        $selectedTickets = [];
        $totalPrice      = 0;

        foreach ($tickets as $ticketId => $qty) {
            $qty = (int) $qty;
            if ($qty <= 0) continue;

            $ticket = TicketType::find($ticketId);
            if (! $ticket || $ticket->event_id !== $event->id) continue;

            $status = $ticket->getStatus();
            if ($status === 'sold_out')   return back()->withErrors(['error' => "Tiket {$ticket->name} sudah habis."]);
            if ($status === 'time_closed') return back()->withErrors(['error' => "Waktu pembelian tiket {$ticket->name} sudah berakhir."]);
            if ($status === 'not_open')    return back()->withErrors(['error' => "Tiket {$ticket->name} belum atau sudah tidak tersedia."]);
            if ($ticket->getRemainingQuota() < $qty) return back()->withErrors(['error' => "Kuota tiket {$ticket->name} tidak mencukupi (sisa: {$ticket->getRemainingQuota()})."]);

            $selectedTickets[] = ['ticket' => $ticket, 'qty' => $qty];
            $totalPrice += $ticket->price * $qty;
        }

        if (empty($selectedTickets)) return back()->withErrors(['error' => 'Pilih minimal 1 tiket.']);

        return view('checkout', compact('event', 'selectedTickets', 'totalPrice'));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // STEP 2 — Proses form → simpan ke DB → buat Midtrans Snap token
    // ─────────────────────────────────────────────────────────────────────────

    public function process(Request $request, Event $event)
    {
        $request->validate([
            'buyer_name'   => 'required|string|max:255',
            'buyer_nim'    => 'required|string|max:50',
            'buyer_email'  => 'required|email',
            'buyer_phone'  => 'required|string|max:20',
            'tickets'      => 'required|array',
            'tickets.*.id' => 'required|exists:ticket_types,id',
            'tickets.*.qty'=> 'required|integer|min:1',
        ]);

        $orderRef   = 'SIMETIX-' . strtoupper(Str::random(4)) . '-' . time();
        $totalPrice = 0;
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

            // Format item untuk Midtrans
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

            // Kurangi kuota atomik
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
            'item_details'  => $itemDetails,
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
            // Kalau Midtrans error, tetap lanjut ke payment page
            // dengan token null — fallback ke tampilan "menunggu"
            $snapToken = null;
            \Log::error('Midtrans error: ' . $e->getMessage());
        }

        // Simpan data ke session untuk halaman payment & summary
        session([
            'order_ref'    => $orderRef,
            'total_price'  => $totalPrice,
            'snap_token'   => $snapToken,
            'buyer_name'   => $request->buyer_name,
            'buyer_nim'    => $request->buyer_nim,
            'buyer_email'  => $request->buyer_email,
            'buyer_phone'  => $request->buyer_phone,
            'event_id'     => $event->id,
        ]);

        return redirect()->route('checkout.payment', $event->slug);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // STEP 3 — Halaman payment (tampilkan Snap popup)
    // ─────────────────────────────────────────────────────────────────────────

    public function payment(Event $event)
    {
        if (! session('order_ref')) {
            return redirect()->route('event.show', $event->slug);
        }

        $orderRef   = session('order_ref');
        $snapToken  = session('snap_token');
        $totalPrice = session('total_price');
        $clientKey  = config('midtrans.client_key');
        $snapUrl    = config('midtrans.snap_url');

        $registrations = Registration::where('order_ref', $orderRef)
            ->with('ticketType')
            ->get();

        return view('payment', compact(
            'event', 'orderRef', 'snapToken',
            'totalPrice', 'clientKey', 'snapUrl', 'registrations'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // STEP 4 — Ringkasan / Finish
    // ─────────────────────────────────────────────────────────────────────────

    public function summary(Event $event)
    {
        $orderRef = session('order_ref') ?? request('order_id');

        if (! $orderRef) {
            return redirect()->route('event.show', $event->slug);
        }

        // Update status registrasi jika ada transaction_status dari Midtrans
        $transactionStatus = request('transaction_status');
        if ($transactionStatus) {
            $newStatus = match ($transactionStatus) {
                'capture', 'settlement' => 'confirmed',
                'pending'               => 'pending',
                'cancel', 'expire'      => 'cancelled',
                default                 => 'pending',
            };

            Registration::where('order_ref', $orderRef)
                ->update(['status' => $newStatus]);
        }

        $registrations = Registration::where('order_ref', $orderRef)
            ->with('ticketType')
            ->get();

        $buyer = [
            'name'  => session('buyer_name')  ?? ($registrations->first()->name  ?? '-'),
            'nim'   => session('buyer_nim')   ?? ($registrations->first()->nim   ?? '-'),
            'email' => session('buyer_email') ?? ($registrations->first()->email ?? '-'),
            'phone' => session('buyer_phone') ?? ($registrations->first()->phone ?? '-'),
        ];

        $totalPrice = session('total_price') ?? $registrations->sum('total_price');

        return view('summary', compact('event', 'registrations', 'buyer', 'orderRef', 'totalPrice'));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // WEBHOOK — Midtrans notification (server-to-server)
    // ─────────────────────────────────────────────────────────────────────────

    public function notification(Request $request)
    {
        try {
            $notification = new \Midtrans\Notification();

            $orderId           = $notification->order_id;
            $transactionStatus = $notification->transaction_status;
            $fraudStatus       = $notification->fraud_status ?? null;

            $newStatus = 'pending';

            if ($transactionStatus === 'capture') {
                $newStatus = ($fraudStatus === 'challenge') ? 'pending' : 'confirmed';
            } elseif ($transactionStatus === 'settlement') {
                $newStatus = 'confirmed';
            } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
                $newStatus = 'cancelled';

                // Kembalikan kuota jika dibatalkan
                $registrations = Registration::where('order_ref', $orderId)
                    ->with('ticketType')
                    ->get();

                foreach ($registrations as $reg) {
                    if ($reg->ticketType) {
                        \DB::table('ticket_types')
                            ->where('id', $reg->ticket_type_id)
                            ->decrement('sold', $reg->quantity);
                    }
                }
            }

            Registration::where('order_ref', $orderId)
                ->update(['status' => $newStatus]);

            return response()->json(['status' => 'ok']);

        } catch (\Exception $e) {
            \Log::error('Midtrans notification error: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }
    }
}
