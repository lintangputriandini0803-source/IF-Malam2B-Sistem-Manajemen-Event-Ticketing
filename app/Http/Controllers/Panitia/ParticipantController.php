<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Registration; 

class ParticipantController extends Controller
{
    public function report(Request $request)
    {
        // Ambil data event  untuk dropdown filter
        $events = \App\Models\Event::where('user_id', auth()->id())->get();

        // Ambil data dari Database 
        $query = Registration::whereHas('event', function($q) {
            $q->where('user_id', auth()->id()); 
        })->with(['details.ticketType', 'event']);

        // Logika Filter Event
        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        // logika filter rentang tanggal berdasarkan waktu regist
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00', 
                $request->end_date . ' 23:59:59'
            ]);
        }

        // Logika Pencarian (Search)
        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('email', 'like', "%{$q}%")
                      ->orWhere('reg_number', 'like', "%{$q}%");
            });
        }
        // Eksekusi query dengan pagination
        $peserta = $query->latest()->paginate(10)->withQueryString();

        return view('panitia.report_peserta', compact('peserta', 'event'));
    }
}