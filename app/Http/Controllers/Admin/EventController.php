<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with(['user', 'category']);

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($q2) use ($q) {
                $q2->where('title', 'like', "%{$q}%")
                   ->orWhere('location', 'like', "%{$q}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $events = $query->latest()->paginate(15);

        return view('admin.events.index', compact('events'));
    }

    public function approve(Event $event)
    {
        $event->update(['status' => 'published']);
        return back()->with('success', "Event \"{$event->title}\" berhasil dipublikasikan.");
    }

    public function reject(Event $event)
    {
        $event->update(['status' => 'cancelled']);
        return back()->with('success', "Event \"{$event->title}\" dibatalkan.");
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return back()->with('success', 'Event berhasil dihapus.');
    }
}
