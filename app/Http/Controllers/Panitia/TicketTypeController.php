<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\TicketType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketTypeController extends Controller
{
    public function index(Event $event)
    {
        abort_if($event->user_id !== Auth::id(), 403);
        $tickets = $event->ticketTypes()->orderBy('price')->get();
        return view('panitia.tickets.index', compact('event', 'tickets'));
    }

    public function create(Event $event)
    {
        abort_if($event->user_id !== Auth::id(), 403);
        return view('panitia.tickets.create', compact('event'));
    }

    public function store(Request $request, Event $event)
    {
        abort_if($event->user_id !== Auth::id(), 403);

        $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'quota'       => 'required|integer|min:1',
            'description' => 'nullable|string',
            'sale_start'  => 'nullable|date',
            'sale_end'    => 'nullable|date|after_or_equal:sale_start',
        ]);

        $event->ticketTypes()->create([
            'name'        => $request->name,
            'description' => $request->description,
            'price'       => $request->price,
            'quota'       => $request->quota,
            'sale_start'  => $request->sale_start,
            'sale_end'    => $request->sale_end,
        ]);

        return redirect()->route('panitia.events.index')
            ->with('success', "Tipe tiket \"{$request->name}\" berhasil ditambahkan.");
    }

    public function edit(Event $event, TicketType $ticket)
    {
        abort_if($event->user_id !== Auth::id(), 403);
        return view('panitia.tickets.create', compact('event', 'ticket'));
    }

    public function update(Request $request, Event $event, TicketType $ticket)
    {
        abort_if($event->user_id !== Auth::id(), 403);

        $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'quota'       => 'required|integer|min:1',
            'description' => 'nullable|string',
            'sale_start'  => 'nullable|date',
            'sale_end'    => 'nullable|date|after_or_equal:sale_start',
        ]);

        $ticket->update($request->only(['name', 'description', 'price', 'quota', 'sale_start', 'sale_end']));

        return redirect()->route('panitia.events.index')
            ->with('success', "Tipe tiket \"{$ticket->name}\" berhasil diperbarui.");
    }

    public function destroy(Event $event, TicketType $ticket)
    {
        abort_if($event->user_id !== Auth::id(), 403);
        $ticket->delete();
        return back()->with('success', 'Tipe tiket berhasil dihapus.');
    }
}
