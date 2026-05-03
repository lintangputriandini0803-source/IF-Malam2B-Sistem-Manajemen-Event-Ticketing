@extends('layouts.admin')
@section('title', 'Transaksi')
@section('page-title', 'Transaksi')

@section('content')

{{-- ===================== INJECT CSS ===================== --}}
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap');

    :root {
        --brand: #6B0080;
        --brand-light: #8B00A8;
        --brand-pale: #f5eaf8;
        --brand-mid: #e4c8ed;
        --success: #059669;
        --success-bg: #ecfdf5;
        --danger: #dc2626;
        --danger-bg: #fef2f2;
        --warn: #d97706;
        --warn-bg: #fffbeb;
        --info: #2563eb;
        --info-bg: #eff6ff;
        --gray-50: #f9fafb;
        --gray-100: #f3f4f6;
        --gray-200: #e5e7eb;
        --gray-300: #d1d5db;
        --gray-400: #9ca3af;
        --gray-500: #6b7280;
        --gray-700: #374151;
        --gray-900: #111827;
        --radius: 12px;
        --shadow-sm: 0 1px 3px rgba(0,0,0,0.07), 0 1px 2px rgba(0,0,0,0.05);
        --shadow: 0 4px 16px rgba(107,0,128,0.08), 0 1px 4px rgba(0,0,0,0.06);
        --shadow-lg: 0 10px 32px rgba(107,0,128,0.12), 0 2px 8px rgba(0,0,0,0.07);
    }

    * { box-sizing: border-box; }

    .tx-page { font-family: 'Plus Jakarta Sans', sans-serif; color: var(--gray-900); }

    /* ---- PAGE HEADER ---- */
    .tx-header {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 28px;
    }
    .tx-header-title h1 {
        font-size: 22px;
        font-weight: 800;
        color: var(--gray-900);
        margin: 0 0 3px;
        letter-spacing: -0.4px;
    }
    .tx-header-title p {
        font-size: 13px;
        color: var(--gray-400);
        margin: 0;
    }
    .btn-export {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 18px;
        background: var(--brand);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        text-decoration: none;
        transition: background .15s, transform .1s, box-shadow .15s;
        box-shadow: 0 2px 8px rgba(107,0,128,0.25);
    }
    .btn-export:hover { background: var(--brand-light); transform: translateY(-1px); box-shadow: 0 4px 14px rgba(107,0,128,0.32); }
    .btn-export:active { transform: translateY(0); }
    .btn-export-csv {
        background: white;
        color: var(--brand);
        border: 1.5px solid var(--brand-mid);
        box-shadow: var(--shadow-sm);
    }
    .btn-export-csv:hover { background: var(--brand-pale); }

    /* ---- STAT CARDS ---- */
    .stat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }
    .stat-card {
        background: white;
        border-radius: var(--radius);
        padding: 20px 22px;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--gray-100);
        position: relative;
        overflow: hidden;
        transition: transform .15s, box-shadow .15s;
    }
    .stat-card:hover { transform: translateY(-2px); box-shadow: var(--shadow); }
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
        border-radius: var(--radius) var(--radius) 0 0;
    }
    .stat-card.purple::before { background: linear-gradient(90deg, var(--brand), #c84de0); }
    .stat-card.green::before  { background: linear-gradient(90deg, #059669, #34d399); }
    .stat-card.red::before    { background: linear-gradient(90deg, #dc2626, #fb7185); }
    .stat-card.blue::before   { background: linear-gradient(90deg, #2563eb, #60a5fa); }
    .stat-card .stat-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .7px;
        color: var(--gray-400);
        margin-bottom: 8px;
    }
    .stat-card .stat-value {
        font-size: 24px;
        font-weight: 800;
        letter-spacing: -0.8px;
        color: var(--gray-900);
        line-height: 1;
    }
    .stat-card .stat-value.mono { font-family: 'JetBrains Mono', monospace; font-size: 18px; }
    .stat-card .stat-sub {
        font-size: 12px;
        color: var(--gray-400);
        margin-top: 6px;
    }
    .stat-card .stat-icon {
        position: absolute;
        right: 18px; top: 18px;
        font-size: 26px;
        opacity: .15;
    }
    .stat-split { display: flex; gap: 16px; margin-top: 8px; }
    .stat-split-item { display: flex; flex-direction: column; gap: 2px; }
    .stat-split-item .num { font-size: 18px; font-weight: 800; letter-spacing: -0.5px; }
    .stat-split-item .lbl { font-size: 11px; color: var(--gray-400); font-weight: 600; }
    .num.green { color: var(--success); }
    .num.red   { color: var(--danger); }
    .num.warn  { color: var(--warn); }

    /* ---- FILTER BAR ---- */
    .filter-bar {
        background: white;
        border-radius: var(--radius);
        padding: 16px 20px;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--gray-100);
        margin-bottom: 20px;
    }
    .filter-bar-inner {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: flex-end;
    }
    .filter-group { display: flex; flex-direction: column; gap: 5px; flex: 1; min-width: 150px; }
    .filter-group label { font-size: 11px; font-weight: 700; color: var(--gray-500); text-transform: uppercase; letter-spacing: .5px; }
    .filter-group input,
    .filter-group select {
        padding: 8px 12px;
        border: 1.5px solid var(--gray-200);
        border-radius: 8px;
        font-size: 13px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: var(--gray-700);
        background: var(--gray-50);
        outline: none;
        transition: border-color .15s, box-shadow .15s;
        width: 100%;
    }
    .filter-group input:focus,
    .filter-group select:focus {
        border-color: var(--brand);
        box-shadow: 0 0 0 3px rgba(107,0,128,0.08);
        background: white;
    }
    .filter-group.search-group { flex: 2; min-width: 220px; }
    .search-wrap { position: relative; }
    .search-wrap .search-icon {
        position: absolute; left: 11px; top: 50%; transform: translateY(-50%);
        color: var(--gray-400); pointer-events: none; font-size: 14px;
    }
    .search-wrap input { padding-left: 34px; }
    .btn-filter {
        padding: 8px 18px;
        background: var(--brand);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        font-family: 'Plus Jakarta Sans', sans-serif;
        transition: background .15s;
        white-space: nowrap;
        align-self: flex-end;
    }
    .btn-filter:hover { background: var(--brand-light); }
    .btn-reset {
        padding: 8px 14px;
        background: white;
        color: var(--gray-500);
        border: 1.5px solid var(--gray-200);
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        font-family: 'Plus Jakarta Sans', sans-serif;
        transition: border-color .15s, color .15s;
        white-space: nowrap;
        align-self: flex-end;
        text-decoration: none;
    }
    .btn-reset:hover { border-color: var(--gray-400); color: var(--gray-700); }

    /* ---- TRANSACTION TABLE ---- */
    .tx-table-card {
        background: white;
        border-radius: var(--radius);
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--gray-100);
        overflow: hidden;
    }
    .tx-table-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 20px;
        border-bottom: 1px solid var(--gray-100);
    }
    .tx-table-header .ttitle {
        font-size: 14px;
        font-weight: 700;
        color: var(--gray-700);
    }
    .tx-table-header .tcount {
        font-size: 12px;
        color: var(--gray-400);
        font-weight: 500;
    }
    .tx-table-wrap { overflow-x: auto; }
    table.tx-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 900px;
    }
    table.tx-table thead tr {
        background: var(--gray-50);
    }
    table.tx-table th {
        text-align: left;
        padding: 11px 16px;
        font-size: 10.5px;
        font-weight: 700;
        color: var(--gray-400);
        text-transform: uppercase;
        letter-spacing: .6px;
        white-space: nowrap;
        border-bottom: 1px solid var(--gray-100);
    }
    table.tx-table td {
        padding: 13px 16px;
        font-size: 13px;
        color: var(--gray-700);
        border-bottom: 1px solid var(--gray-100);
        vertical-align: middle;
    }
    table.tx-table tbody tr { transition: background .1s; }
    table.tx-table tbody tr:hover { background: var(--gray-50); }
    table.tx-table tbody tr:last-child td { border-bottom: none; }

    /* ID cell */
    .tx-id {
        font-family: 'JetBrains Mono', monospace;
        font-size: 11.5px;
        color: var(--brand);
        font-weight: 600;
        white-space: nowrap;
    }
    /* buyer cell */
    .buyer-name { font-weight: 700; color: var(--gray-900); font-size: 13px; }
    .buyer-contact { font-size: 11.5px; color: var(--gray-400); margin-top: 2px; }
    /* event cell */
    .event-name { font-weight: 600; color: var(--gray-900); font-size: 13px; }
    .event-organizer { font-size: 11.5px; color: var(--gray-400); margin-top: 2px; }
    /* method badge */
    .method-badge {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 3px 9px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }
    .method-badge.bank  { background: #eff6ff; color: #1d4ed8; }
    .method-badge.ewallet { background: #fdf4ff; color: #7e22ce; }
    .method-badge.cc    { background: #f0fdf4; color: #15803d; }
    .method-badge.manual { background: #fffbeb; color: #92400e; }
    /* status badge */
    .status-badge {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11.5px;
        font-weight: 700;
        white-space: nowrap;
    }
    .status-badge .dot {
        width: 6px; height: 6px;
        border-radius: 50%;
        flex-shrink: 0;
    }
    .status-badge.success { background: var(--success-bg); color: var(--success); }
    .status-badge.success .dot { background: var(--success); }
    .status-badge.pending { background: var(--warn-bg); color: var(--warn); }
    .status-badge.pending .dot { background: var(--warn); animation: pulse-dot 1.4s infinite; }
    .status-badge.expired { background: var(--danger-bg); color: var(--danger); }
    .status-badge.expired .dot { background: var(--danger); }
    .status-badge.failed  { background: #fef2f2; color: #dc2626; }
    .status-badge.failed  .dot { background: #dc2626; }
    @keyframes pulse-dot {
        0%, 100% { opacity: 1; } 50% { opacity: 0.35; }
    }
    /* time cell */
    .tx-time-main { font-size: 12.5px; font-weight: 600; color: var(--gray-700); }
    .tx-time-sub  { font-size: 11px; color: var(--gray-400); margin-top: 2px; }
    /* amount cell */
    .tx-amount { font-family: 'JetBrains Mono', monospace; font-size: 13px; font-weight: 700; color: var(--brand); white-space: nowrap; }
    .tx-fee    { font-size: 11px; color: var(--gray-400); margin-top: 2px; }
    /* settlement */
    .settle-badge {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
    }
    .settle-badge.paid     { background: #ecfdf5; color: #059669; }
    .settle-badge.held     { background: #fffbeb; color: #b45309; }
    .settle-badge.refunded { background: #fef2f2; color: #dc2626; }
    /* proof link */
    .proof-link {
        display: inline-flex; align-items: center; gap: 4px;
        font-size: 12px; color: var(--brand); font-weight: 600;
        text-decoration: none;
    }
    .proof-link:hover { text-decoration: underline; }
    .no-proof { font-size: 12px; color: var(--gray-300); }
    /* action btn */
    .btn-action {
        padding: 5px 10px;
        border-radius: 7px;
        border: 1.5px solid var(--gray-200);
        background: white;
        font-size: 11px; font-weight: 700;
        color: var(--gray-600);
        cursor: pointer;
        font-family: 'Plus Jakarta Sans', sans-serif;
        transition: border-color .12s, color .12s, background .12s;
        text-decoration: none;
    }
    .btn-action:hover { border-color: var(--brand); color: var(--brand); background: var(--brand-pale); }
    .btn-action.danger:hover { border-color: var(--danger); color: var(--danger); background: var(--danger-bg); }

    /* ---- RECONCILIATION SECTION ---- */
    .reconcile-section {
        background: white;
        border-radius: var(--radius);
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--gray-100);
        margin-top: 24px;
        overflow: hidden;
    }
    .section-head {
        padding: 16px 20px;
        border-bottom: 1px solid var(--gray-100);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }
    .section-head .s-title {
        font-size: 14px;
        font-weight: 800;
        color: var(--gray-900);
    }
    .section-head .s-sub {
        font-size: 12px;
        color: var(--gray-400);
        margin-top: 2px;
    }
    .payout-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 0;
    }
    .payout-item {
        padding: 20px 24px;
        border-right: 1px solid var(--gray-100);
        border-bottom: 1px solid var(--gray-100);
    }
    .payout-item:last-child { border-right: none; }
    .payout-item .pi-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .6px; color: var(--gray-400); margin-bottom: 6px; }
    .payout-item .pi-value { font-size: 22px; font-weight: 800; font-family: 'JetBrains Mono', monospace; letter-spacing: -0.5px; }
    .payout-item .pi-value.purple { color: var(--brand); }
    .payout-item .pi-value.green  { color: var(--success); }
    .payout-item .pi-value.orange { color: var(--warn); }
    .payout-item .pi-sub { font-size: 12px; color: var(--gray-400); margin-top: 4px; }

    /* ---- EMPTY STATE ---- */
    .empty-state {
        text-align: center;
        padding: 80px 20px;
    }
    .empty-state .es-icon { font-size: 52px; }
    .empty-state .es-title { font-size: 16px; font-weight: 800; color: var(--gray-700); margin: 14px 0 6px; }
    .empty-state .es-sub   { font-size: 13px; color: var(--gray-400); }

    /* ---- PAGINATION ---- */
    .tx-pagination { padding: 14px 20px; border-top: 1px solid var(--gray-100); }

    /* ---- MODAL PROOF ---- */
    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.55);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(4px);
    }
    .modal-overlay.open { display: flex; }
    .modal-box {
        background: white;
        border-radius: 16px;
        padding: 24px;
        max-width: 520px;
        width: 94%;
        box-shadow: var(--shadow-lg);
        position: relative;
        animation: modal-in .2s ease;
    }
    @keyframes modal-in {
        from { opacity: 0; transform: scale(.95) translateY(8px); }
        to   { opacity: 1; transform: scale(1) translateY(0); }
    }
    .modal-close {
        position: absolute; top: 14px; right: 14px;
        background: var(--gray-100); border: none; border-radius: 50%;
        width: 30px; height: 30px; cursor: pointer;
        font-size: 16px; display: flex; align-items: center; justify-content: center;
        color: var(--gray-500); transition: background .12s;
    }
    .modal-close:hover { background: var(--gray-200); }
    .modal-title { font-size: 16px; font-weight: 800; color: var(--gray-900); margin-bottom: 4px; }
    .modal-sub   { font-size: 12px; color: var(--gray-400); margin-bottom: 16px; }
    .modal-img   { width: 100%; border-radius: 10px; border: 1px solid var(--gray-200); max-height: 400px; object-fit: contain; background: var(--gray-50); }

    /* ---- RESPONSIVE ---- */
    @media (max-width: 640px) {
        .stat-grid { grid-template-columns: 1fr 1fr; }
        .tx-header { flex-direction: column; align-items: flex-start; }
        .export-btns { display: flex; gap: 8px; flex-wrap: wrap; }
    }
</style>

<div class="tx-page">

    {{-- ===== PAGE HEADER ===== --}}
    <div class="tx-header">
        <div class="tx-header-title">
            <h1>Semua Transaksi</h1>
            <p>Riwayat pembelian tiket & rekonsiliasi pembayaran platform</p>
        </div>
        <div class="export-btns" style="display:flex;gap:8px;flex-wrap:wrap">
            <a href="{{ route('admin.transactions.export', array_merge(request()->query(), ['format'=>'xlsx'])) }}"
               class="btn-export btn-export-csv">
                📊 Export Excel
            </a>
            <a href="{{ route('admin.transactions.export', array_merge(request()->query(), ['format'=>'csv'])) }}"
               class="btn-export btn-export-csv">
                📄 Export CSV
            </a>
        </div>
    </div>

    {{-- ===== STAT CARDS ===== --}}
    @php
        $totalGMV       = $transactions->sum('total_price') ?? 0;
        $platformFee    = 2000; // Rp2.000 per tiket – sesuaikan dengan konstanta platform
        $totalSuccess   = $transactions->where('payment_status', 'settlement')->count()
                        + $transactions->where('payment_status', 'capture')->count();
        $totalPending   = $transactions->where('payment_status', 'pending')->count();
        $totalFailed    = $transactions->where('payment_status', 'expire')->count()
                        + $transactions->where('payment_status', 'cancel')->count()
                        + $transactions->where('payment_status', 'deny')->count();
        $totalRevenue   = $totalSuccess * $platformFee;
        $totalPayout    = $totalGMV - $totalRevenue;
        // Hitung dari semua (tanpa filter halaman)
        $allGMV         = \App\Models\Transaction::sum('total_price') ?? $totalGMV;
    @endphp

    <div class="stat-grid">
        {{-- GMV --}}
        <div class="stat-card purple">
            <div class="stat-icon">💰</div>
            <div class="stat-label">Total GMV (Halaman ini)</div>
            <div class="stat-value mono">Rp{{ number_format($totalGMV, 0, ',', '.') }}</div>
            <div class="stat-sub">Akumulasi nilai transaksi ditampilkan</div>
        </div>

        {{-- Transaksi Sukses vs Gagal --}}
        <div class="stat-card green">
            <div class="stat-icon">✅</div>
            <div class="stat-label">Status Transaksi</div>
            <div class="stat-split">
                <div class="stat-split-item">
                    <span class="num green">{{ $totalSuccess }}</span>
                    <span class="lbl">Berhasil</span>
                </div>
                <div class="stat-split-item">
                    <span class="num warn">{{ $totalPending }}</span>
                    <span class="lbl">Pending</span>
                </div>
                <div class="stat-split-item">
                    <span class="num red">{{ $totalFailed }}</span>
                    <span class="lbl">Gagal/Exp.</span>
                </div>
            </div>
            <div class="stat-sub" style="margin-top:8px">Total: {{ $transactions->count() }} transaksi</div>
        </div>

        {{-- Revenue Platform --}}
        <div class="stat-card blue">
            <div class="stat-icon">🏦</div>
            <div class="stat-label">Revenue Platform (Fee)</div>
            <div class="stat-value mono">Rp{{ number_format($totalRevenue, 0, ',', '.') }}</div>
            <div class="stat-sub">{{ $totalSuccess }} tiket × Rp{{ number_format($platformFee, 0, ',', '.') }}</div>
        </div>

        {{-- Total Payout --}}
        <div class="stat-card">
            <div class="stat-icon">🏷️</div>
            <div class="stat-label" style="color:var(--gray-400)">Dana ke Panitia (Estimasi)</div>
            <div class="stat-value mono" style="color:var(--success)">Rp{{ number_format($totalPayout, 0, ',', '.') }}</div>
            <div class="stat-sub">GMV dikurangi fee platform</div>
        </div>
    </div>

    {{-- ===== FILTER BAR ===== --}}
    <div class="filter-bar">
        <form method="GET" action="{{ route('admin.transactions.index') }}">
            <div class="filter-bar-inner">
                {{-- Search --}}
                <div class="filter-group search-group">
                    <label>Cari</label>
                    <div class="search-wrap">
                        <span class="search-icon">🔍</span>
                        <input type="text" name="search" placeholder="ID Invoice, nama, email, event..."
                               value="{{ request('search') }}">
                    </div>
                </div>
                {{-- Tanggal Mulai --}}
                <div class="filter-group">
                    <label>Dari Tanggal</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}">
                </div>
                {{-- Tanggal Akhir --}}
                <div class="filter-group">
                    <label>Sampai Tanggal</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}">
                </div>
                {{-- Status --}}
                <div class="filter-group">
                    <label>Status</label>
                    <select name="status">
                        <option value="">Semua Status</option>
                        <option value="settlement" {{ request('status')=='settlement'?'selected':'' }}>✅ Berhasil</option>
                        <option value="pending"    {{ request('status')=='pending'?'selected':'' }}>⏳ Pending</option>
                        <option value="expire"     {{ request('status')=='expire'?'selected':'' }}>⌛ Expired</option>
                        <option value="cancel"     {{ request('status')=='cancel'?'selected':'' }}>❌ Dibatalkan</option>
                    </select>
                </div>
                {{-- Filter Event --}}
                <div class="filter-group">
                    <label>Event</label>
                    <select name="event_id">
                        <option value="">Semua Event</option>
                        @foreach($events ?? [] as $ev)
                        <option value="{{ $ev->id }}" {{ request('event_id')==$ev->id?'selected':'' }}>
                            {{ Str::limit($ev->title, 35) }}
                        </option>
                        @endforeach
                    </select>
                </div>
                {{-- Metode Bayar --}}
                <div class="filter-group">
                    <label>Metode Bayar</label>
                    <select name="payment_type">
                        <option value="">Semua Metode</option>
                        <option value="bank_transfer" {{ request('payment_type')=='bank_transfer'?'selected':'' }}>🏦 Bank Transfer</option>
                        <option value="gopay"         {{ request('payment_type')=='gopay'?'selected':'' }}>💚 GoPay</option>
                        <option value="shopeepay"     {{ request('payment_type')=='shopeepay'?'selected':'' }}>🧡 ShopeePay</option>
                        <option value="credit_card"   {{ request('payment_type')=='credit_card'?'selected':'' }}>💳 Kartu Kredit</option>
                        <option value="manual"        {{ request('payment_type')=='manual'?'selected':'' }}>📸 Transfer Manual</option>
                    </select>
                </div>
                {{-- Buttons --}}
                <button type="submit" class="btn-filter">Filter</button>
                <a href="{{ route('admin.transactions.index') }}" class="btn-reset">Reset</a>
            </div>
        </form>
    </div>

    {{-- ===== TRANSACTION TABLE ===== --}}
    <div class="tx-table-card">
        <div class="tx-table-header">
            <div>
                <div class="ttitle">Log Transaksi</div>
                <div class="tcount">{{ $transactions->total() ?? $transactions->count() }} transaksi ditemukan</div>
            </div>
        </div>

        @if($transactions->isEmpty())
        <div class="empty-state">
            <div class="es-icon">📋</div>
            <div class="es-title">Tidak ada transaksi ditemukan</div>
            <div class="es-sub">Coba ubah filter atau rentang tanggal pencarian Anda.</div>
        </div>
        @else
        <div class="tx-table-wrap">
            <table class="tx-table">
                <thead>
                    <tr>
                        <th>ID Invoice</th>
                        <th>Pembeli</th>
                        <th>Event & Penyelenggara</th>
                        <th>Metode</th>
                        <th>Status</th>
                        <th>Waktu</th>
                        <th style="text-align:right">Total</th>
                        <th>Settlement</th>
                        <th>Bukti</th>
                        <th style="text-align:center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($transactions as $tx)
                @php
                    $status = strtolower($tx->payment_status ?? 'pending');
                    $settleStatus = $tx->payout_status ?? 'held'; // paid | held | refunded
                    $paymentType  = $tx->payment_type ?? 'bank_transfer';

                    // Normalize status label
                    $statusLabel = match($status) {
                        'settlement', 'capture' => ['label'=>'Berhasil','class'=>'success'],
                        'pending'               => ['label'=>'Pending','class'=>'pending'],
                        'expire'                => ['label'=>'Expired','class'=>'expired'],
                        'cancel', 'deny'        => ['label'=>'Dibatalkan','class'=>'failed'],
                        default                 => ['label'=>ucfirst($status),'class'=>'pending'],
                    };
                    $methodLabel = match($paymentType) {
                        'bank_transfer' => ['label'=>'Bank Transfer','class'=>'bank','icon'=>'🏦'],
                        'gopay'         => ['label'=>'GoPay','class'=>'ewallet','icon'=>'💚'],
                        'shopeepay'     => ['label'=>'ShopeePay','class'=>'ewallet','icon'=>'🧡'],
                        'ovo'           => ['label'=>'OVO','class'=>'ewallet','icon'=>'💜'],
                        'dana'          => ['label'=>'DANA','class'=>'ewallet','icon'=>'🔵'],
                        'credit_card'   => ['label'=>'Kartu Kredit','class'=>'cc','icon'=>'💳'],
                        'manual'        => ['label'=>'Transfer Manual','class'=>'manual','icon'=>'📸'],
                        default         => ['label'=>ucfirst($paymentType),'class'=>'bank','icon'=>'💳'],
                    };
                    $settleLabel = match($settleStatus) {
                        'paid'     => ['label'=>'Dicairkan','class'=>'paid'],
                        'refunded' => ['label'=>'Refund','class'=>'refunded'],
                        default    => ['label'=>'Ditahan','class'=>'held'],
                    };
                    $fee = ($status === 'settlement' || $status === 'capture') ? $platformFee : 0;
                @endphp
                <tr>
                    {{-- ID --}}
                    <td>
                        <div class="tx-id">#{{ strtoupper(Str::limit($tx->transaction_id ?? $tx->id, 14, '')) }}</div>
                    </td>
                    {{-- Pembeli --}}
                    <td>
                        <div class="buyer-name">{{ $tx->user->name ?? '-' }}</div>
                        <div class="buyer-contact">{{ $tx->user->email ?? ($tx->user->phone ?? '-') }}</div>
                    </td>
                    {{-- Event --}}
                    <td>
                        <div class="event-name">{{ Str::limit($tx->event->title ?? '-', 30) }}</div>
                        <div class="event-organizer">{{ $tx->event->organizer->name ?? $tx->event->user->name ?? '-' }}</div>
                    </td>
                    {{-- Metode --}}
                    <td>
                        <span class="method-badge {{ $methodLabel['class'] }}">
                            {{ $methodLabel['icon'] }} {{ $methodLabel['label'] }}
                        </span>
                    </td>
                    {{-- Status --}}
                    <td>
                        <span class="status-badge {{ $statusLabel['class'] }}">
                            <span class="dot"></span>
                            {{ $statusLabel['label'] }}
                        </span>
                    </td>
                    {{-- Waktu --}}
                    <td>
                        <div class="tx-time-main">{{ $tx->created_at->format('d M Y') }}</div>
                        <div class="tx-time-sub">
                            {{ $tx->created_at->format('H:i') }} WIB
                            @if($tx->paid_at)
                            · Lunas {{ $tx->paid_at->format('H:i') }}
                            @endif
                        </div>
                    </td>
                    {{-- Total --}}
                    <td style="text-align:right">
                        <div class="tx-amount">Rp{{ number_format($tx->total_price ?? 0, 0, ',', '.') }}</div>
                        @if($fee > 0)
                        <div class="tx-fee">Fee: Rp{{ number_format($fee, 0, ',', '.') }}</div>
                        @endif
                    </td>
                    {{-- Settlement --}}
                    <td>
                        <span class="settle-badge {{ $settleLabel['class'] }}">
                            {{ $settleLabel['label'] }}
                        </span>
                    </td>
                    {{-- Bukti --}}
                    <td>
                        @if(!empty($tx->payment_proof))
                        <a href="#" class="proof-link"
                           onclick="openProof('{{ asset('storage/'.$tx->payment_proof) }}','#{{ strtoupper(Str::limit($tx->transaction_id ?? $tx->id, 14,'')) }}');return false;">
                            🖼 Lihat
                        </a>
                        @else
                        <span class="no-proof">—</span>
                        @endif
                    </td>
                    {{-- Aksi --}}
                    <td style="text-align:center">
                        <div style="display:flex;gap:6px;justify-content:center">
                            <a href="{{ route('admin.transactions.show', $tx->id) }}" class="btn-action">Detail</a>
                            @if($status === 'pending' && $paymentType === 'manual')
                            <a href="{{ route('admin.transactions.verify', $tx->id) }}" class="btn-action"
                               onclick="return confirm('Verifikasi transaksi ini?')">✓ Verif</a>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @if(method_exists($transactions, 'hasPages') && $transactions->hasPages())
        <div class="tx-pagination">
            {{ $transactions->withQueryString()->links() }}
        </div>
        @endif
        @endif
    </div>

    {{-- ===== REKONSILIASI & PAYOUT ===== --}}
    <div class="reconcile-section">
        <div class="section-head">
            <div>
                <div class="s-title">💼 Laporan Rekonsiliasi & Pencairan (Payout)</div>
                <div class="s-sub">Ringkasan status dana yang perlu dicairkan ke masing-masing panitia event</div>
            </div>
            <a href="{{ route('admin.transactions.export', array_merge(request()->query(), ['format'=>'xlsx','type'=>'reconcile'])) }}"
               class="btn-export" style="font-size:12px;padding:7px 14px">
                📊 Export Rekonsiliasi
            </a>
        </div>
        <div class="payout-grid">
            <div class="payout-item">
                <div class="pi-label">Total GMV Terkonfirmasi</div>
                <div class="pi-value purple">Rp{{ number_format($totalGMV, 0, ',', '.') }}</div>
                <div class="pi-sub">Dari {{ $totalSuccess }} transaksi sukses</div>
            </div>
            <div class="payout-item">
                <div class="pi-label">Potongan Fee Platform</div>
                <div class="pi-value" style="color:var(--info)">Rp{{ number_format($totalRevenue, 0, ',', '.') }}</div>
                <div class="pi-sub">{{ $totalSuccess }} × Rp{{ number_format($platformFee, 0, ',', '.') }}/tiket</div>
            </div>
            <div class="payout-item">
                <div class="pi-label">Dana Bersih ke Panitia</div>
                <div class="pi-value green">Rp{{ number_format(max(0, $totalPayout), 0, ',', '.') }}</div>
                <div class="pi-sub">GMV dikurangi biaya layanan</div>
            </div>
            <div class="payout-item">
                <div class="pi-label">Status Dana</div>
                <div style="display:flex;gap:12px;margin-top:4px;flex-wrap:wrap">
                    <div>
                        <div style="font-size:18px;font-weight:800;color:var(--success)">
                            {{ $transactions->where('payout_status','paid')->count() }}
                        </div>
                        <div style="font-size:11px;color:var(--gray-400);font-weight:600">Dicairkan</div>
                    </div>
                    <div>
                        <div style="font-size:18px;font-weight:800;color:var(--warn)">
                            {{ $transactions->where('payout_status','held')->count() + $transactions->whereNull('payout_status')->count() }}
                        </div>
                        <div style="font-size:11px;color:var(--gray-400);font-weight:600">Ditahan</div>
                    </div>
                    <div>
                        <div style="font-size:18px;font-weight:800;color:var(--danger)">
                            {{ $transactions->where('payout_status','refunded')->count() }}
                        </div>
                        <div style="font-size:11px;color:var(--gray-400);font-weight:600">Refund</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>{{-- end tx-page --}}

{{-- ===== MODAL BUKTI BAYAR ===== --}}
<div class="modal-overlay" id="proofModal" onclick="if(event.target===this)closeProof()">
    <div class="modal-box">
        <button class="modal-close" onclick="closeProof()">✕</button>
        <div class="modal-title">Bukti Transfer Pembayaran</div>
        <div class="modal-sub" id="proofTxId">—</div>
        <img src="" id="proofImg" class="modal-img" alt="Bukti Bayar">
    </div>
</div>

<script>
    function openProof(src, txId) {
        document.getElementById('proofImg').src = src;
        document.getElementById('proofTxId').textContent = 'Invoice ' + txId;
        document.getElementById('proofModal').classList.add('open');
    }
    function closeProof() {
        document.getElementById('proofModal').classList.remove('open');
        document.getElementById('proofImg').src = '';
    }
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeProof();
    });
</script>

@endsection