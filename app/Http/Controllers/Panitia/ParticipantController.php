<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Registration; 

class ParticipantController extends Controller
{
    public function report(Request $request)
    {
        // Ambil data dari Database 
        $query = Registration::whereHas('event', function($q) {
            $q->where('user_id', auth()->id()); 
        })->with(['details.ticketType', 'event']);

        // Logika Pencarian (Search)
        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('email', 'like', "%{$q}%")
                      ->orWhere('reg_number', 'like', "%{$q}%");
            });
        }

        $peserta = $query->latest()->paginate(10);

        return view('panitia.report_peserta', compact('peserta'));
    }
}