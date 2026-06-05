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
        $totalRevenue   = Registration::where('status', 'confirmed')->sum('total_price');
        $totalPending   = Registration::where('status', 'pending')->count();
        $totalConfirmed = Registration::where('status', 'confirmed')->count();

        return view('admin.transactions.index', compact(
            'transactions', 'totalRevenue', 'totalPending', 'totalConfirmed'
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