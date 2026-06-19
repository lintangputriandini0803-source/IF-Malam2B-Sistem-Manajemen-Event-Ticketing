<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
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

        // Filter event_id (dipakai dropdown di view)
        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        // Filter metode pembayaran
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Filter rentang tanggal
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transactions = $query->paginate(20);

        $events = \App\Models\Event::orderBy('title')->get();

        // Statistik ringkas
        $totalGMV = (int) Registration::where('status', 'confirmed')->sum('total_price');
        $totalSuccess = Registration::where('status', 'confirmed')->count();
        $totalPending = Registration::where('status', 'pending')->count();
        $totalFailed = Registration::where('status', 'cancelled')->count();
        $totalRevenue = $totalSuccess * 2000; // fee platform Rp2.000/tiket
        $totalPayout = $totalGMV - $totalRevenue;

        return view('admin.transactions.index', compact(
            'transactions',
            'events',
            'totalGMV',
            'totalSuccess',
            'totalPending',
            'totalFailed',
            'totalRevenue',
            'totalPayout'
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

    // Search
    if ($request->filled('search')) {
        $q = $request->search;

        $query->where(function ($q2) use ($q) {
            $q2->where('name', 'like', "%{$q}%")
                ->orWhere('email', 'like', "%{$q}%")
                ->orWhere('reg_number', 'like', "%{$q}%");
        });
    }

    // Status
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // Event
    if ($request->filled('event_id')) {
        $query->where('event_id', $request->event_id);
    }

    // Payment Method
    if ($request->filled('payment_method')) {
        $query->where('payment_method', $request->payment_method);
    }

    // Date Range
    if ($request->filled('date_from')) {
        $query->whereDate('created_at', '>=', $request->date_from);
    }

    if ($request->filled('date_to')) {
        $query->whereDate('created_at', '<=', $request->date_to);
    }

    $transactions = $query->get();

    $filename = 'laporan-penjualan-tiket-' . now()->format('Y-m-d_H-i-s') . '.csv';

    $headers = [
        'Content-Type' => 'text/csv; charset=UTF-8',
        'Content-Disposition' => "attachment; filename=\"$filename\"",
    ];

    $callback = function () use ($transactions) {

        $file = fopen('php://output', 'w');

        // UTF-8 BOM supaya Excel membaca karakter Indonesia dengan benar
        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

        // Header laporan
        fputcsv($file, [
            'No',
            'No Registrasi',
            'Nama',
            'Email',
            'No Telepon',
            'Event',
            'Metode Pembayaran',
            'Total Pembayaran',
            'Status',
            'Tanggal Transaksi'
        ]);

        foreach ($transactions as $index => $tx) {

            fputcsv($file, [
                $index + 1,
                $tx->reg_number,
                $tx->name,
                $tx->email,
                $tx->phone,
                $tx->event->title ?? '-',
                $tx->payment_method ?? '-',
                'Rp ' . number_format($tx->total_price, 0, ',', '.'),
                ucfirst($tx->status),
                $tx->created_at->format('d-m-Y H:i')
            ]);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}
}
