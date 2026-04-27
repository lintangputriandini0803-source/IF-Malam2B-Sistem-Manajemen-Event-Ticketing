<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        // Coba load Registration model jika ada
        $transactions = collect();
        try {
            $query = \App\Models\Registration::with(['user', 'event']);
            if ($request->filled('search')) {
                $q = $request->search;
                $query->whereHas('user', fn($q2) => $q2->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%"));
            }
            $transactions = $query->latest()->paginate(15);
        } catch (\Exception $e) {
            $transactions = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15);
        }

        return view('admin.transactions.index', compact('transactions'));
    }
}
