<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Registration::with(['details.ticketType', 'event'])->latest();

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($q2) use ($q) {
                $q2->where('name', 'like', "%{$q}%")
                   ->orWhere('email', 'like', "%{$q}%")
                   ->orWhere('reg_number', 'like', "%{$q}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('event')) {
            $query->whereHas('event', function ($q2) use ($request) {
                $q2->where('title', 'like', '%' . $request->event . '%');
            });
        }

        $transactions = $query->paginate(20);

        $events = \App\Models\Event::orderBy('title')->get();

        // Statistik ringkas
        $totalGMV     = Registration::where('status', 'confirmed')->sum('total_price');
        $totalSuccess = Registration::where('status', 'confirmed')->count();
        $totalPending = Registration::where('status', 'pending')->count();
        $totalFailed  = Registration::where('status', 'cancelled')->count();
        $totalRevenue = $totalSuccess * 2000;                    // fee platform Rp2.000/tiket
        $totalPayout  = $totalGMV - $totalRevenue;               // estimasi dana ke panitia

        return view('admin.transactions.index', compact(
            'transactions', 'events', 'totalGMV', 'totalSuccess', 'totalPending', 'totalFailed',
            'totalRevenue', 'totalPayout'
        ));
    }

    public function show(Registration $transaction)
    {
        $transaction->load(['details.ticketType', 'event']);
        return response()->json($transaction);
    }

    public function verify(Registration $transaction)
    {
        $transaction->update(['status' => 'confirmed']);
        return back()->with('success', "Transaksi {$transaction->reg_number} berhasil dikonfirmasi.");
    }

    public function export(Request $request)
    {
        $query = Registration::with(['details.ticketType', 'event'])->latest();

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($q2) use ($q) {
                $q2->where('name', 'like', "%{$q}%")
                   ->orWhere('email', 'like', "%{$q}%")
                   ->orWhere('reg_number', 'like', "%{$q}%");
            });
        }

        $transactions = $query->get();

        $format = $request->input('format', 'csv');

        // Export CSV
        $filename = 'transaksi-' . now()->format('Ymd-His') . '.csv';
        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($transactions) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['No. Registrasi', 'Nama', 'Email', 'Telp', 'Event', 'Total', 'Status', 'Tanggal']);
            foreach ($transactions as $tx) {
                fputcsv($handle, [
                    $tx->reg_number,
                    $tx->name,
                    $tx->email,
                    $tx->phone,
                    $tx->event->title ?? '-',
                    $tx->total_price,
                    $tx->status,
                    $tx->created_at->format('d/m/Y H:i'),
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
