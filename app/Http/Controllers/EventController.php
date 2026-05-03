<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventCategory;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $categories = EventCategory::orderBy('name')->get();

        $query = Event::with(['ticketTypes', 'category'])
            ->where('status', 'published');

        // Filter keyword (judul / lokasi)
        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($q2) use ($q) {
                $q2->where('title', 'like', "%{$q}%")
                   ->orWhere('location', 'like', "%{$q}%");
            });
        }

        // Filter kategori (via ikon filter)
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q2) use ($request) {
                $q2->where('id', $request->category);
            });
        }

        $events = $query->latest()->paginate(8)->withQueryString();

        return view('home', compact('events', 'categories'));
    }

    public function show(Event $event)
    {
        abort_if($event->status !== 'published', 404);
        $event->load(['ticketTypes', 'category', 'user']);
        return view('detailEvent', compact('event'));
    }

    public function event(Request $request)
    {
        $categories = EventCategory::orderBy('name')->get();

        $query = Event::with(['ticketTypes', 'category'])
            ->where('status', 'published');

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($q2) use ($q) {
                $q2->where('title', 'like', "%{$q}%")
                   ->orWhere('location', 'like', "%{$q}%");
            });
        }

        if ($request->filled('category')) {
            $query->whereHas('category', function ($q2) use ($request) {
                $q2->where('id', $request->category);
            });
        }

        $events = $query->latest()->paginate(12)->withQueryString();

        return view('homepage', compact('events', 'categories'));
    }
}
