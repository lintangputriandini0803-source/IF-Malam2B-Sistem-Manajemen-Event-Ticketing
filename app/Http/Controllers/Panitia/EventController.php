<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::where('user_id', Auth::id())->with('category');

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

        if ($request->filled('category')) {
            $query->whereHas('category', function ($q2) use ($request) {
                $q2->where('name', 'like', '%'.$request->category.'%');
            });
        }

        $events = $query->latest()->paginate(10);

        return view('panitia.index', compact('events'));
    }

    public function create()
    {
        $categories = EventCategory::all();
        return view('panitia.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'category_id' => 'required|exists:event_categories,id',
            'description' => 'required|string',
            'event_date'  => 'required|string',
            'location'    => 'required|string',
            'poster'      => 'nullable|image|max:2048',
        ]);

        $posterPath = null;
        if ($request->hasFile('poster')) {
            $posterPath = $request->file('poster')->getClientOriginalName();
            $request->file('poster')->move(public_path('poster'), $posterPath);
        }

        Event::create([
            'user_id'     => Auth::id(),
            'category_id' => $request->category_id,
            'title'       => $request->title,
            'slug'        => Str::slug($request->title) . '-' . time(),
            'description' => $request->description,
            'event_date'  => $request->event_date,
            'event_time'  => $request->event_time,
            'location'    => $request->location,
            'poster'      => $posterPath,
            'status'      => 'draft',
        ]);

        return redirect()->route('panitia.events.index')->with('success', 'Event berhasil dibuat!');
    }

    public function edit(Event $event)
    {
        abort_if($event->user_id !== Auth::id(), 403);
        $categories = EventCategory::all();
        return view('panitia.create', compact('event', 'categories'));
    }

    public function update(Request $request, Event $event)
    {
        abort_if($event->user_id !== Auth::id(), 403);

        $request->validate([
            'title'       => 'required|string|max:255',
            'category_id' => 'required|exists:event_categories,id',
            'description' => 'required|string',
            'event_date'  => 'required|string',
            'location'    => 'required|string',
            'poster'      => 'nullable|image|max:2048',
        ]);

        $posterPath = $event->poster;
        if ($request->hasFile('poster')) {
            $posterPath = $request->file('poster')->getClientOriginalName();
            $request->file('poster')->move(public_path('poster'), $posterPath);
        }

        $event->update([
            'category_id' => $request->category_id,
            'title'       => $request->title,
            'description' => $request->description,
            'event_date'  => $request->event_date,
            'event_time'  => $request->event_time,
            'location'    => $request->location,
            'poster'      => $posterPath,
        ]);

        return redirect()->route('panitia.events.index')->with('success', 'Event berhasil diperbarui!');
    }

    public function destroy(Event $event)
    {
        abort_if($event->user_id !== Auth::id(), 403);
        $event->delete();
        return back()->with('success', 'Event berhasil dihapus.');
    }

    public function publish(Event $event)
    {
        abort_if($event->user_id !== Auth::id(), 403);
        $event->update(['status' => 'published']);
        return back()->with('success', 'Event dipublikasikan!');
    }
}
