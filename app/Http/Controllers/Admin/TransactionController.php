<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Registration;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

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
        $platformFeeRate = 0.025; // 2.5%

        $totalRevenue = $totalGMV * $platformFeeRate;

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

    /**
     * Bangun query transaksi berdasarkan filter yang sama dengan halaman index,
     * dipakai bersama oleh export() dan exportPanitia().
     */
    private function filteredTransactionsQuery(Request $request, bool $confirmedOnly = false)
    {
        $query = Registration::with(['event.user', 'ticketType'])->latest();

        if ($confirmedOnly) {
            $query->where('status', 'confirmed');
        }

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($q2) use ($q) {
                $q2->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('reg_number', 'like', "%{$q}%");
            });
        }

        if (!$confirmedOnly && $request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        return $query;
    }

    /**
     * Deskripsi ringkas filter yang sedang aktif, ditulis di bawah judul laporan.
     */
    private function describeFilters(Request $request): string
    {
        $parts = [];

        if ($request->filled('search')) {
            $parts[] = 'Kata kunci: "' . $request->search . '"';
        }
        if ($request->filled('status')) {
            $parts[] = 'Status: ' . ucfirst($request->status);
        }
        if ($request->filled('event_id')) {
            $event = Event::find($request->event_id);
            $parts[] = 'Event: ' . ($event->title ?? '-');
        }
        if ($request->filled('date_from') || $request->filled('date_to')) {
            $from = $request->date_from ?: '...';
            $to = $request->date_to ?: '...';
            $parts[] = "Periode: {$from} s/d {$to}";
        }

        return $parts ? implode('  |  ', $parts) : 'Semua data (tanpa filter)';
    }

    /**
     * Export laporan penjualan tiket (semua transaksi sesuai filter aktif)
     * ke format Excel (.xlsx) yang rapi dan profesional.
     */
    public function export(Request $request)
    {
        $transactions = $this->filteredTransactionsQuery($request)->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Penjualan Tiket');

        $columns = [
            'No',
            'No Registrasi',
            'Nama',
            'Email',
            'No Telepon',
            'Event',
            'Metode Pembayaran',
            'Jumlah Tiket',
            'Total Pembayaran',
            'Status',
            'Tanggal Transaksi'
        ];
        $lastCol = chr(ord('A') + count($columns) - 1); // K

        // ── JUDUL LAPORAN ──────────────────────────────────────────────────
        $sheet->mergeCells("A1:{$lastCol}1");
        $sheet->setCellValue('A1', 'LAPORAN PENJUALAN TIKET');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells("A2:{$lastCol}2");
        $sheet->setCellValue('A2', 'Dicetak pada: ' . now()->translatedFormat('d F Y, H:i') . ' WIB');
        $sheet->getStyle('A2')->getFont()->setItalic(true)->setSize(10)->getColor()->setRGB('666666');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells("A3:{$lastCol}3");
        $sheet->setCellValue('A3', $this->describeFilters($request));
        $sheet->getStyle('A3')->getFont()->setItalic(true)->setSize(10)->getColor()->setRGB('666666');
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // ── HEADER TABEL ───────────────────────────────────────────────────
        $headerRow = 5;
        $sheet->fromArray($columns, null, "A{$headerRow}");
        $headerRange = "A{$headerRow}:{$lastCol}{$headerRow}";
        $sheet->getStyle($headerRange)->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
        $sheet->getStyle($headerRange)->getFill()
            ->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('1F2A44');
        $sheet->getStyle($headerRange)->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension($headerRow)->setRowHeight(22);

        // ── ISI DATA ────────────────────────────────────────────────────────
        $row = $headerRow + 1;
        $totalNominal = 0;

        foreach ($transactions as $i => $tx) {
            $mLabel = match (strtolower($tx->payment_method ?? '')) {
                'bca_va' => 'BCA Virtual Account',
                'mandiri_va' => 'Mandiri Virtual Account',
                'bni_va' => 'BNI Virtual Account',
                'bri_va' => 'BRI Virtual Account',
                'permata_va' => 'Permata Virtual Account',
                default => $tx->payment_method ? ucwords(str_replace('_', ' ', $tx->payment_method)) : '-',
            };

            $sheet->setCellValue("A{$row}", $i + 1);
            $sheet->setCellValue("B{$row}", $tx->reg_number);
            $sheet->setCellValue("C{$row}", $tx->name);
            $sheet->setCellValue("D{$row}", $tx->email);
            $sheet->setCellValue("E{$row}", $tx->phone ?? '-');
            $sheet->setCellValue("F{$row}", optional($tx->event)->title ?? '-');
            $sheet->setCellValue("G{$row}", $mLabel);
            $sheet->setCellValue("H{$row}", $tx->quantity);
            $sheet->setCellValue("I{$row}", (float) $tx->total_price);
            $sheet->setCellValue("J{$row}", ucfirst($tx->status));
            $sheet->setCellValue("K{$row}", $tx->created_at->format('d-m-Y H:i'));

            // Format kolom nominal sebagai mata uang Rupiah
            $sheet->getStyle("I{$row}")->getNumberFormat()
                ->setFormatCode('"Rp" #,##0');

            // Warnai status: hijau (confirmed), kuning (pending), merah (cancelled)
            $statusColor = match ($tx->status) {
                'confirmed' => '15803D',
                'pending' => 'B45309',
                'cancelled' => 'B91C1C',
                default => '374151',
            };
            $sheet->getStyle("J{$row}")->getFont()->setBold(true)->getColor()->setRGB($statusColor);

            if ($tx->status === 'confirmed') {
                $totalNominal += (float) $tx->total_price;
            }

            $row++;
        }

        $lastDataRow = $row - 1;

        // ── BARIS TOTAL ─────────────────────────────────────────────────────
        if ($transactions->isNotEmpty()) {
            $sheet->mergeCells("A{$row}:H{$row}");
            $sheet->setCellValue("A{$row}", 'TOTAL GMV (Transaksi Berhasil)');
            $sheet->getStyle("A{$row}")->getFont()->setBold(true);
            $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

            $sheet->setCellValue("I{$row}", $totalNominal);
            $sheet->getStyle("I{$row}")->getNumberFormat()->setFormatCode('"Rp" #,##0');
            $sheet->getStyle("I{$row}")->getFont()->setBold(true);

            $totalRange = "A{$row}:{$lastCol}{$row}";
            $sheet->getStyle($totalRange)->getFill()
                ->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('E5E7EB');
        }

        $lastRow = $transactions->isNotEmpty() ? $row : $lastDataRow;

        // ── BORDER & RAPIKAN ─────────────────────────────────────────────────
        $fullRange = "A{$headerRow}:{$lastCol}{$lastRow}";
        $sheet->getStyle($fullRange)->getBorders()->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB('D1D5DB');

        foreach (range('A', $lastCol) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $sheet->freezePane('A' . ($headerRow + 1));
        $sheet->setSelectedCell('A1');

        $filename = 'laporan-penjualan-tiket-' . now()->format('Y-m-d_H-i-s') . '.xlsx';

        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    /**
     * Export laporan pembagian hasil penjualan tiket ke masing-masing panitia,
     * dalam format Excel (.xlsx) yang rapi dan profesional.
     */
    public function exportPanitia(Request $request)
    {
        $transactions = $this->filteredTransactionsQuery($request, confirmedOnly: true)->get();

        // ── KELOMPOKKAN PER PANITIA ──────────────────────────────────────────
        $feePerTicket = 2000;
        $grouped = [];

        foreach ($transactions as $tx) {
            $panitia = optional($tx->event)->user;
            $panitiaId = $panitia->id ?? 0;

            if (!isset($grouped[$panitiaId])) {
                $grouped[$panitiaId] = [
                    'nama' => $panitia->name ?? 'Tanpa Penyelenggara',
                    'email' => $panitia->email ?? '-',
                    'events' => [],
                    'jumlah_tx' => 0,
                    'jumlah_tiket' => 0,
                    'gmv' => 0,
                ];
            }

            $eventTitle = optional($tx->event)->title ?? '-';
            $grouped[$panitiaId]['events'][$eventTitle] = true;
            $grouped[$panitiaId]['jumlah_tx']++;
            $grouped[$panitiaId]['jumlah_tiket'] += (int) $tx->quantity;
            $grouped[$panitiaId]['gmv'] += (float) $tx->total_price;
        }

        uasort($grouped, fn($a, $b) => $b['gmv'] <=> $a['gmv']);

        // ── SUSUN SPREADSHEET ─────────────────────────────────────────────────
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Pembagian Hasil per Panitia');

        $columns = [
            'No',
            'Nama Panitia',
            'Email Panitia',
            'Jumlah Event',
            'Transaksi Berhasil',
            'Tiket Terjual',
            'Total Penjualan (GMV)',
            'Potongan Fee Platform',
            'Dana Diterima Panitia'
        ];
        $lastCol = chr(ord('A') + count($columns) - 1); // I

        // ── JUDUL LAPORAN ──────────────────────────────────────────────────
        $sheet->mergeCells("A1:{$lastCol}1");
        $sheet->setCellValue('A1', 'LAPORAN PEMBAGIAN HASIL PENJUALAN TIKET PER PANITIA');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(15);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells("A2:{$lastCol}2");
        $sheet->setCellValue('A2', 'Dicetak pada: ' . now()->translatedFormat('d F Y, H:i') . ' WIB · Fee platform: Rp ' . number_format($feePerTicket, 0, ',', '.') . '/tiket');
        $sheet->getStyle('A2')->getFont()->setItalic(true)->setSize(10)->getColor()->setRGB('666666');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells("A3:{$lastCol}3");
        $sheet->setCellValue('A3', $this->describeFilters($request) . '  |  Hanya transaksi berstatus Berhasil');
        $sheet->getStyle('A3')->getFont()->setItalic(true)->setSize(10)->getColor()->setRGB('666666');
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // ── HEADER TABEL ───────────────────────────────────────────────────
        $headerRow = 5;
        $sheet->fromArray($columns, null, "A{$headerRow}");
        $headerRange = "A{$headerRow}:{$lastCol}{$headerRow}";
        $sheet->getStyle($headerRange)->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
        $sheet->getStyle($headerRange)->getFill()
            ->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('14532D');
        $sheet->getStyle($headerRange)->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension($headerRow)->setRowHeight(24);

        // ── ISI DATA ────────────────────────────────────────────────────────
        $row = $headerRow + 1;
        $no = 1;
        $totalGmv = 0;
        $totalFee = 0;
        $totalPayout = 0;

        foreach ($grouped as $data) {
            $fee = $data['jumlah_tiket'] * $feePerTicket;
            $payout = $data['gmv'] - $fee;

            $totalGmv += $data['gmv'];
            $totalFee += $fee;
            $totalPayout += $payout;

            $sheet->setCellValue("A{$row}", $no++);
            $sheet->setCellValue("B{$row}", $data['nama']);
            $sheet->setCellValue("C{$row}", $data['email']);
            $sheet->setCellValue("D{$row}", count($data['events']));
            $sheet->setCellValue("E{$row}", $data['jumlah_tx']);
            $sheet->setCellValue("F{$row}", $data['jumlah_tiket']);
            $sheet->setCellValue("G{$row}", $data['gmv']);
            $sheet->setCellValue("H{$row}", $fee);
            $sheet->setCellValue("I{$row}", $payout);

            foreach (['G', 'H', 'I'] as $col) {
                $sheet->getStyle("{$col}{$row}")->getNumberFormat()->setFormatCode('"Rp" #,##0');
            }
            $sheet->getStyle("I{$row}")->getFont()->setBold(true)->getColor()->setRGB('15803D');

            $row++;
        }

        $lastDataRow = $row - 1;

        // ── BARIS TOTAL ─────────────────────────────────────────────────────
        if (!empty($grouped)) {
            $sheet->mergeCells("A{$row}:F{$row}");
            $sheet->setCellValue("A{$row}", 'TOTAL KESELURUHAN');
            $sheet->getStyle("A{$row}")->getFont()->setBold(true);
            $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

            $sheet->setCellValue("G{$row}", $totalGmv);
            $sheet->setCellValue("H{$row}", $totalFee);
            $sheet->setCellValue("I{$row}", $totalPayout);
            foreach (['G', 'H', 'I'] as $col) {
                $sheet->getStyle("{$col}{$row}")->getNumberFormat()->setFormatCode('"Rp" #,##0');
                $sheet->getStyle("{$col}{$row}")->getFont()->setBold(true);
            }

            $totalRange = "A{$row}:{$lastCol}{$row}";
            $sheet->getStyle($totalRange)->getFill()
                ->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('DCFCE7');
        }

        $lastRow = !empty($grouped) ? $row : $lastDataRow;

        // ── BORDER & RAPIKAN ─────────────────────────────────────────────────
        $fullRange = "A{$headerRow}:{$lastCol}{$lastRow}";
        $sheet->getStyle($fullRange)->getBorders()->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB('D1D5DB');

        foreach (range('A', $lastCol) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $sheet->freezePane('A' . ($headerRow + 1));
        $sheet->setSelectedCell('A1');

        $filename = 'laporan-pembagian-hasil-panitia-' . now()->format('Y-m-d_H-i-s') . '.xlsx';

        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}