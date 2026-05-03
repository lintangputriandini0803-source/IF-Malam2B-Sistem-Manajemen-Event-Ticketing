<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\Event;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    // Biaya platform per tiket (Rp)
    const PLATFORM_FEE = 2000;

    public function index(Request $request)
    {
        try {
            // ── Query dasar ──────────────────────────────────────────────────
            $query = Registration::with([
                'ticketType',
                'ticketType.event',
                'ticketType.event.user',
            ]);

            // ── Filter: Pencarian teks ────────────────────────────────────────
            if ($request->filled('search')) {
                $q = $request->search;
                $query->where(function ($sub) use ($q) {
                    $sub->where('name',        'like', "%{$q}%")
                        ->orWhere('email',     'like', "%{$q}%")
                        ->orWhere('phone',     'like', "%{$q}%")
                        ->orWhere('reg_number','like', "%{$q}%")
                        ->orWhere('order_ref', 'like', "%{$q}%");
                });
            }

            // ── Filter: Rentang tanggal ───────────────────────────────────────
            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            // ── Filter: Status ────────────────────────────────────────────────
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // ── Filter: Per Event ─────────────────────────────────────────────
            if ($request->filled('event_id')) {
                $query->whereHas('ticketType', function ($q) use ($request) {
                    $q->where('event_id', $request->event_id);
                });
            }

            // ── Filter: Metode bayar ──────────────────────────────────────────
            if ($request->filled('payment_method')) {
                $query->where('payment_method', $request->payment_method);
            }

            // ── Export CSV ────────────────────────────────────────────────────
            if ($request->filled('export')) {
                return $this->exportCsv($query->get());
            }

            // ── Statistik (seluruh data setelah filter, sebelum paginate) ─────
            $allFiltered  = (clone $query)->get();
            $totalGMV     = $allFiltered->sum('total_price');
            $totalSuccess = $allFiltered->where('status', 'confirmed')->count();
            $totalPending = $allFiltered->where('status', 'pending')->count();
            $totalFailed  = $allFiltered->where('status', 'cancelled')->count();
            $totalRevenue = $totalSuccess * self::PLATFORM_FEE;
            $totalPayout  = max(0, $totalGMV - $totalRevenue);

            // ── Paginate ──────────────────────────────────────────────────────
            $transactions = $query->latest()->paginate(20)->withQueryString();

            // ── Daftar event untuk dropdown filter ────────────────────────────
            $events = Event::orderBy('title')->get();

        } catch (\Exception $e) {
            $transactions = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20);
            $events       = collect();
            $totalGMV     = $totalSuccess = $totalPending = $totalFailed = 0;
            $totalRevenue = $totalPayout  = 0;
        }

        return view('admin.transactions.index', compact(
            'transactions',
            'events',
            'totalGMV',
            'totalSuccess',
            'totalPending',
            'totalFailed',
            'totalRevenue',
            'totalPayout',
        ));
    }

    // ── Export CSV ────────────────────────────────────────────────────────────
    private function exportCsv($rows)
    {
        $filename = 'transaksi_simetix_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () use ($rows) {
            $handle = fopen('php://output', 'w');
            // BOM UTF-8 agar Excel tidak kacak huruf
            fputs($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'No.', 'No. Registrasi', 'Order Ref', 'Nama Pembeli', 'Email', 'Telepon',
                'Event', 'Penyelenggara', 'Tipe Tiket', 'Jumlah Tiket',
                'Metode Bayar', 'Total (Rp)', 'Fee Platform (Rp)', 'Dana ke Panitia (Rp)',
                'Status', 'Tanggal', 'Jam',
            ]);

            foreach ($rows as $i => $tx) {
                $event   = optional($tx->ticketType)->event;
                $panitia = optional($event)->user;
                $isOk    = $tx->status === 'confirmed';
                $fee     = $isOk ? self::PLATFORM_FEE * $tx->quantity : 0;
                $payout  = max(0, $tx->total_price - $fee);

                fputcsv($handle, [
                    $i + 1,
                    $tx->reg_number,
                    $tx->order_ref ?? '-',
                    $tx->name,
                    $tx->email,
                    $tx->phone,
                    optional($event)->title   ?? '-',
                    optional($panitia)->name  ?? '-',
                    optional($tx->ticketType)->name ?? '-',
                    $tx->quantity,
                    $this->paymentLabel($tx->payment_method ?? '') ?: '-',
                    number_format($tx->total_price, 0, ',', '.'),
                    number_format($fee, 0, ',', '.'),
                    number_format($payout, 0, ',', '.'),
                    $this->statusLabel($tx->status),
                    $tx->created_at->format('d/m/Y'),
                    $tx->created_at->format('H:i'),
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function statusLabel(string $status): string
    {
        return match ($status) {
            'confirmed' => 'Berhasil',
            'pending'   => 'Pending',
            'cancelled' => 'Dibatalkan',
            default     => ucfirst($status),
        };
    }

    private function paymentLabel(string $method): string
    {
        return match (strtolower($method)) {
            'bca_va'     => 'BCA Virtual Account',
            'mdr'        => 'MDR',
            'mandiri_va' => 'Mandiri Virtual Account',
            'bni'        => 'BNI',
            'bni_va'     => 'BNI Virtual Account',
            'bri'        => 'BRI',
            'bri_va'     => 'BRI Virtual Account',
            'pmt'        => 'PMT',
            'permata_va' => 'Permata Virtual Account',
            default      => ucwords(str_replace('_', ' ', $method)),
        };
    }
}