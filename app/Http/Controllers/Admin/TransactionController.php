<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Registration;

class TransactionController extends Controller
{
    public function index(Request $request)
{
    $peserta = \App\Models\Registration::with(['details.ticketType', 'event'])->latest()->get();
    
    if ($request->filled('search')) {
        $q = $request->search;
        $query->where(function($query) use ($q) {
            $query->where('name', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%")
                  ->orWhere('reg_number', 'like', "%{$q}%");
        });
    }

    $peserta = $query->latest()->get(); 

    return view('panitia.report_peserta', compact('peserta'));
  }
}
