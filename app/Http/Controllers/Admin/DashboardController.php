<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalEvents    = Event::count();
        $publishedEvents = Event::where('status', 'published')->count();
        $pendingPanitia = User::where('role', 'panitia')->where('status', 'pending')->count();
        $totalPanitia   = User::where('role', 'panitia')->count();

        return view('admin.dashboard', compact(
            'totalEvents', 'publishedEvents', 'pendingPanitia', 'totalPanitia'
        ));
    }
}
