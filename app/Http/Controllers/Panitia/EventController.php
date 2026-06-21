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
        $query = Event::where('user_id', Auth::id())
            ->with(['category', 'ticketTypes']);

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

    public function edit(Event $event)
    {
        abort_if($event->user_id !== Auth::id(), 403);
        $categories = EventCategory::all();
        $event->load('ticketTypes');
        return view('panitia.create', compact('event', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'category_id' => 'required|exists:event_categories,id',
            'description' => 'required|string',
            'event_date'  => 'required|date',
            'end_date'    => 'nullable|date|after_or_equal:event_date',
            'location'    => 'required|string',
            'poster'      => 'nullable|image|max:2048',
        ]);

        $posterPath = null;
        if ($request->hasFile('poster')) {
            $file = $request->file('poster');
            $posterPath = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('poster'), $posterPath);
        }

        // Auto-publish: jika event_date hari ini atau sudah lewat → langsung published
        $status = $this->resolveStatus($request);

        $event = Event::create([
            'user_id'     => Auth::id(),
            'category_id' => $request->category_id,
            'title'       => $request->title,
            'slug'        => Str::slug($request->title) . '-' . time(),
            'description' => $request->description,
            'event_date'  => $request->event_date,
            'end_date'    => $request->end_date,
            'event_time'  => $request->event_time,
            'location'    => $request->location,
            'poster'      => $posterPath,
            'status'      => $status,
        ]);

        if ($request->filled('tikets')) {
            foreach ($request->tikets as $tiket) {
                if (empty($tiket['nama'])) continue;
                $event->ticketTypes()->create([
                    'name'  => $tiket['nama'],
                    'price' => $tiket['price'] ?? 0,
                    'quota' => $tiket['quota'] ?? 0,
                    'sold'  => 0,
                ]);
            }
        }

        return redirect()->route('panitia.events.index')
            ->with('success', $status === 'published'
                ? 'Event berhasil dibuat dan langsung dipublikasi!'
                : 'Event berhasil disimpan sebagai draft.');
    }

    public function update(Request $request, Event $event)
    {
        abort_if($event->user_id !== Auth::id(), 403);

        $request->validate([
            'title'       => 'required|string|max:255',
            'category_id' => 'required|exists:event_categories,id',
            'description' => 'required|string',
            'event_date'  => 'required|date',
            'end_date'    => 'nullable|date|after_or_equal:event_date',
            'location'    => 'required|string',
            'poster'      => 'nullable|image|max:2048',
        ]);

        $posterPath = $event->poster;
        if ($request->hasFile('poster')) {
            $file = $request->file('poster');
            $posterPath = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('poster'), $posterPath);

            // Hapus poster lama agar tidak menumpuk di storage
            if ($event->poster && file_exists(public_path('poster/' . $event->poster))) {
                @unlink(public_path('poster/' . $event->poster));
            }
        }

        // Jika panitia manual pilih status, pakai itu — kecuali jika event_date sudah tiba dan masih draft → auto publish
        $status = $this->resolveStatus($request, $event->status);

        $event->update([
            'category_id' => $request->category_id,
            'title'       => $request->title,
            'description' => $request->description,
            'event_date'  => $request->event_date,
            'end_date'    => $request->end_date,
            'event_time'  => $request->event_time,
            'location'    => $request->location,
            'poster'      => $posterPath,
            'status'      => $status,
        ]);

        if ($request->filled('tikets')) {
            $existingIds  = $event->ticketTypes->pluck('id')->toArray();
            $submittedIds = [];

            foreach ($request->tikets as $tiket) {
                if (empty($tiket['nama'])) continue;

                $ticketId = $tiket['id'] ?? null;

                if ($ticketId && in_array($ticketId, $existingIds)) {
                    $existing = $event->ticketTypes()->find($ticketId);
                    $newQuota = max((int)$tiket['quota'], $existing->sold);
                    $existing->update([
                        'name'  => $tiket['nama'],
                        'price' => $tiket['price'] ?? 0,
                        'quota' => $newQuota,
                    ]);
                    $submittedIds[] = $ticketId;
                } else {
                    $new = $event->ticketTypes()->create([
                        'name'  => $tiket['nama'],
                        'price' => $tiket['price'] ?? 0,
                        'quota' => $tiket['quota'] ?? 0,
                        'sold'  => 0,
                    ]);
                    $submittedIds[] = $new->id;
                }
            }

            $toDelete = array_diff($existingIds, $submittedIds);
            $event->ticketTypes()->whereIn('id', $toDelete)->where('sold', 0)->delete();
        }

        return redirect()->route('panitia.events.index')
            ->with('success', $status === 'published'
                ? 'Event diperbarui dan dipublikasi!'
                : 'Event berhasil diperbarui.');
    }

    public function destroy(Event $event)
    {
        abort_if($event->user_id !== Auth::id(), 403);
        $event->delete();
        return redirect()->route('panitia.events.index')->with('success', 'Event berhasil dihapus.');
    }

    public function publish(Event $event)
    {
        abort_if($event->user_id !== Auth::id(), 403);
        $event->update(['status' => 'published']);
        return back()->with('success', 'Event berhasil dipublikasi!');
    }

    // ─── Helper ───────────────────────────────────────────────────────────────

    /**
     * Tentukan status event:
     * - Jika panitia tekan tombol "Publish" (action=publish) → published
     * - Jika event_date hari ini atau sudah lewat dan status masih draft → auto-publish
     * - Sisanya → pakai status dari form / status lama
     */
    private function resolveStatus(Request $request, string $currentStatus = 'draft'): string
    {
        // Tombol publish manual
        if ($request->action === 'publish') {
            return 'published';
        }

        // Status dari form (dropdown)
        $formStatus = $request->input('status', $currentStatus);

        // Auto-publish: kalau event_date sudah tiba dan status masih draft
        if ($formStatus === 'draft' && $request->filled('event_date')) {
            try {
                $eventDate = \Carbon\Carbon::parse($request->event_date)->startOfDay();
                if ($eventDate->lte(now()->startOfDay())) {
                    return 'published';
                }
            } catch (\Exception $e) {
                // parse gagal, biarkan saja
            }
        }

        return $formStatus;
    }
}
