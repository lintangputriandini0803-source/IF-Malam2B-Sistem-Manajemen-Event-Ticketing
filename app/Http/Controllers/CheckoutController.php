<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\TicketType;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    /**
     * Terima data tiket dari detailEvent, tampilkan halaman checkout.
     */
    public function store(Request $request, Event $event)
    {
        $tickets = $request->input('tickets', []);

        // Filter hanya tiket dengan qty > 0
        $selectedTickets = [];
        $totalPrice = 0;

        foreach ($tickets as $ticketId => $qty) {
            $qty = (int) $qty;
            if ($qty <= 0) continue;

            $ticket = TicketType::find($ticketId);
            if (!$ticket || $ticket->event_id !== $event->id) continue;

            if (!$ticket->isAvailable($qty)) {
                return back()->withErrors(['error' => "Kuota tiket {$ticket->name} tidak mencukupi."]);
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
}
