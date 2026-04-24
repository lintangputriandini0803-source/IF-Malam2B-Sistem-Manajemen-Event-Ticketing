<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with(['ticketTypes', 'category'])
            ->where('status', 'published');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('location', 'like', '%' . $request->search . '%')
                  ->orWhereHas('category', function ($q2) use ($request) {
                      $q2->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $events = $query->latest()->paginate(8);
        return view('home', compact('events'));
    }

    public function show(Event $event)
    {
        abort_if($event->status !== 'published', 404);
        $event->load(['ticketTypes', 'category', 'user']);
        return view('detailEvent', compact('event'));
    }
}
