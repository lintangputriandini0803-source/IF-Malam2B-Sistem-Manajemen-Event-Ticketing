<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Registration;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $totalEvents     = Event::where('user_id', $userId)->count();
        $publishedEvents = Event::where('user_id', $userId)->where('status', 'published')->count();
        $draftEvents     = Event::where('user_id', $userId)->where('status', 'draft')->count();

        $myEventIds = Event::where('user_id', $userId)->pluck('id');

        $totalPeserta = Registration::whereIn('event_id', $myEventIds)->count();
        $totalRevenue = Registration::whereIn('event_id', $myEventIds)
            ->where('status', 'confirmed')->sum('total_price');
        $pendingPayment = Registration::whereIn('event_id', $myEventIds)
            ->where('status', 'pending')->count();

        $recentEvents = Event::where('user_id', $userId)
            ->with('category')->latest()->limit(5)->get();

        return view('panitia.dashboard', compact(
            'totalEvents', 'publishedEvents', 'draftEvents',
            'totalPeserta', 'totalRevenue', 'pendingPayment',
            'recentEvents'
        ));
    }
}
