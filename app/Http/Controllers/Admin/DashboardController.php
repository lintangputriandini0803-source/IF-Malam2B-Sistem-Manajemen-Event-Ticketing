<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\User;
use App\Models\Registration;

class DashboardController extends Controller
{
    public function index()
    {
        $totalEvents     = Event::count();
        $publishedEvents = Event::where('status', 'published')->count();
        $pendingPanitia  = User::where('role', 'panitia')->where('status', 'pending')->count();
        $totalPanitia    = User::where('role', 'panitia')->count();
        $totalUsers      = User::count();
        $adminCount      = User::where('role', 'admin')->count();
        $panitiaCount    = User::where('role', 'panitia')->count();
        $userCount       = User::where('role', 'user')->count();
        $recentUsers     = User::latest()->limit(5)->get();

        // Revenue & tickets — pakai Registration jika ada, fallback 0
        $totalTickets = 0;
        $totalRevenue = 0;
        if (class_exists(\App\Models\Registration::class)) {
            try {
                $totalTickets = \App\Models\Registration::count();
                $totalRevenue = \App\Models\Registration::sum('total_price') ?? 0;
            } catch (\Exception $e) {
                // tabel belum ada, biarkan 0
            }
        }

        return view('admin.dashboard', compact(
            'totalEvents', 'publishedEvents', 'pendingPanitia', 'totalPanitia',
            'totalUsers', 'adminCount', 'panitiaCount', 'userCount',
            'recentUsers', 'totalTickets', 'totalRevenue'
        ));
    }
}
