@extends('layouts.admin')
@section('title', 'Admin Dashboard')
@section('page-title', 'Admin Dashboard')
@section('page-sub', 'Pantau seluruh aktivitas platform')

@section('content')


{{-- ── STAT CARDS ── --}}
<div class="db-cards">
    {{-- Total Customer --}}
    <div class="db-card">
        <div class="db-card-info">
            <p class="label">Total Customer</p>
            <p class="val">{{ $totalUsers ?? 0 }}</p>
            <p class="sub">↑ +12 bulan ini</p>
        </div>
        <div class="db-card-icon" style="background:#eff6ff">
            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#2563eb" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-4a4 4 0 100-8 4 4 0 000 8zm6 4a4 4 0 10-6.32-3.32"/>
            </svg>
        </div>
    </div>

    {{-- Total Event --}}
    <div class="db-card">
        <div class="db-card-info">
            <p class="label">Total Event</p>
            <p class="val">{{ $totalEvents ?? 0 }}</p>
            <p class="sub">↑ +3 minggu ini</p>
        </div>
        <div class="db-card-icon" style="background:#f5eeff">
            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#6B0080" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
    </div>

    {{-- Tiket Terjual --}}
    <div class="db-card">
        <div class="db-card-info">
            <p class="label">Tiket Terjual</p>
            <p class="val">{{ $totalTickets ?? 0 }}</p>
            <p class="sub">↑ +89 hari ini</p>
        </div>
        <div class="db-card-icon" style="background:#f0fdf4">
            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#16a34a" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
            </svg>
        </div>
    </div>

    {{-- Total Revenue --}}
    <div class="db-card">
        <div class="db-card-info">
            <p class="label">Total Revenue</p>
            <p class="val" style="font-size:20px">Rp{{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</p>
            <p class="sub">↑ Semester ini</p>
        </div>
        <div class="db-card-icon" style="background:#fff7ed">
            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#ea580c" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
    </div>
</div>

{{-- ── MAIN GRID ── --}}
<div class="db-main">

    {{-- Grafik Penjualan Tiket --}}
    <div class="db-chart-card">
        <div class="db-chart-header">
            <h2>Grafik Penjualan Tiket</h2>
            <select class="db-chart-period" id="periodSelect" onchange="updateChart(this.value)">
                <option value="7">7 Hari Terakhir</option>
                <option value="30">30 Hari Terakhir</option>
            </select>
        </div>
        <div class="db-legend">
            <span class="db-legend-item">
                <span class="db-legend-dot" style="background:#6B0080;border:2px solid #6B0080"></span>
                Tiket Terjual
            </span>
            <span class="db-legend-item">
                <span class="db-legend-rect" style="background:#c084fc"></span>
                Pendapatan (Rp)
            </span>
            <span style="margin-left:auto;font-size:11px;color:#9ca3af;align-self:center">Pendapatan (Rp)</span>
        </div>
        <canvas id="salesChart" height="200"></canvas>

        {{-- Sub Stats --}}
        <div class="db-sub-stats">
            <div class="db-sub-stat">
                <p class="label">Total Tiket Terjual</p>
                <p class="val">{{ number_format($totalTickets ?? 0) }}</p>
                <p class="sub">↑ +15,2% dari 7 hari sebelumnya</p>
            </div>
            <div class="db-sub-stat">
                <p class="label">Total Pendapatan</p>
                <p class="val" style="font-size:16px">Rp{{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</p>
                <p class="sub">↑ +18,7% dari 7 hari sebelumnya</p>
            </div>
        </div>
    </div>

    {{-- RIGHT PANEL --}}
    <div class="db-right">

        {{-- Role Pengguna --}}
        <div class="db-panel">
            <h2>Role Pengguna</h2>
            <div class="role-row">
                <span class="role-label">
                    <span class="role-dot" style="background:#6B0080"></span>Admin
                </span>
                <span class="role-badge" style="background:#f5eeff;color:#6B0080">{{ $adminCount ?? 0 }}</span>
            </div>
            <div class="role-row">
                <span class="role-label">
                    <span class="role-dot" style="background:#2563eb"></span>Panitia
                </span>
                <span class="role-badge" style="background:#eff6ff;color:#2563eb">{{ $panitiaCount ?? 0 }}</span>
            </div>
            <div class="role-row">
                <span class="role-label">
                    <span class="role-dot" style="background:#16a34a"></span>Customer
                </span>
                <span class="role-badge" style="background:#f0fdf4;color:#16a34a">{{ $userCount ?? 0 }}</span>
            </div>
        </div>

        {{-- Quick Action --}}
        <div class="db-panel">
            <h2>Quick Action</h2>
            <a href="{{ route('admin.users.index') }}"
               class="qa-btn" style="background:#6B0080;color:#fff">
                <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-4a4 4 0 100-8 4 4 0 000 8z"/>
                </svg>
                Kelola Pengguna
            </a>
            <a href="{{ route('admin.users.index', ['role'=>'panitia','status'=>'pending']) }}"
               class="qa-btn" style="background:#eff6ff;color:#2563eb">
                ⏳ Panitia Pending
            </a>
            <a href="{{ route('admin.settings') }}"
               class="qa-btn" style="background:#f3f4f6;color:#374151">
                ⚙️ Pengaturan
            </a>
        </div>

    </div>
</div>

{{-- Chart.js --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
@php
    $labels7  = collect(range(6,0,-1))->map(fn($i) => now()->subDays($i)->format('d M'))->values();
    $labels30 = collect(range(29,0,-1))->map(fn($i) => now()->subDays($i)->format('d M'))->values();
    $tickets30 = collect(range(0,29))->map(fn($i) => rand(5,50))->values();
    $revenue30 = collect(range(0,29))->map(fn($i) => rand(25000,250000))->values();
@endphp
const data7 = {
    labels:  @json($labels7),
    tickets: [12, 18, 25, 32, 28, 35, 40],
    revenue: [60000, 90000, 125000, 160000, 140000, 175000, 200000],
};
const data30 = {
    labels:  @json($labels30),
    tickets: @json($tickets30),
    revenue: @json($revenue30),
};

const ctx = document.getElementById('salesChart').getContext('2d');
const chart = new Chart(ctx, {
    data: {
        labels: data7.labels,
        datasets: [
            {
                type: 'line',
                label: 'Tiket Terjual',
                data: data7.tickets,
                borderColor: '#6B0080',
                backgroundColor: 'transparent',
                pointBackgroundColor: '#6B0080',
                pointRadius: 4,
                tension: 0.35,
                yAxisID: 'y',
                borderWidth: 2.5,
            },
            {
                type: 'line',
                label: 'Pendapatan (Rp)',
                data: data7.revenue,
                borderColor: 'rgba(192,132,252,0)',
                backgroundColor: 'rgba(192,132,252,0.18)',
                fill: true,
                tension: 0.35,
                pointRadius: 0,
                yAxisID: 'y2',
                borderWidth: 0,
            }
        ]
    },
    options: {
        responsive: true,
        interaction: { mode: 'index', intersect: false },
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: ctx => ctx.datasetIndex === 0
                        ? `Tiket: ${ctx.parsed.y}`
                        : `Rp ${ctx.parsed.y.toLocaleString('id-ID')}`
                }
            }
        },
        scales: {
            x: { grid: { display: false }, ticks: { font: { size: 11 }, color: '#9ca3af' } },
            y: {
                position: 'left',
                grid: { color: '#f3f4f6' },
                ticks: { font: { size: 11 }, color: '#9ca3af', stepSize: 10 },
                title: { display: false }
            },
            y2: {
                position: 'right',
                grid: { drawOnChartArea: false },
                ticks: {
                    font: { size: 11 }, color: '#9ca3af',
                    callback: v => v >= 1000 ? (v/1000)+'K' : v
                }
            }
        }
    }
});

function updateChart(days) {
    const d = days == '7' ? data7 : data30;
    chart.data.labels = d.labels;
    chart.data.datasets[0].data = d.tickets;
    chart.data.datasets[1].data = d.revenue;
    chart.update();
}
</script>

@endsection