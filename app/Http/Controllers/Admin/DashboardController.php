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
        $adminCount      = User::where('role', 'admin')->count();
        $panitiaCount    = User::where('role', 'panitia')->count();
        $recentUsers     = User::latest()->limit(5)->get();

        $totalTickets = 0;
        $totalRevenue = 0;
        $userCount    = 0; // untuk Role Pengguna (kanan)
        $totalUsers   = 0; // untuk Stat Card "Total Customer"

        if (class_exists(\App\Models\Registration::class)) {
            try {
                $totalTickets = Registration::count();
                $totalRevenue = Registration::sum('total_price') ?? 0;

                // Jumlah unik pembeli tiket — dipakai di dua tempat
                $buyerCount  = Registration::distinct('user_id')->count('user_id');
                $userCount   = $buyerCount;
                $totalUsers  = $buyerCount;
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
