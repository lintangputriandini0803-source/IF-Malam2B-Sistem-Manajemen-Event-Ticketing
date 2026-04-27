@extends('layouts.panitia')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

{{-- STAT CARDS --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">

    <div class="stat-card">
        <div>
            <p style="font-size:12px;color:#6b7280;font-weight:600;text-transform:uppercase;letter-spacing:.06em">Total Event</p>
            <p style="font-size:28px;font-weight:800;color:#111;margin-top:4px">{{ $totalEvents ?? 0 }}</p>
            <p style="font-size:12px;color:#22c55e;margin-top:2px;font-weight:600">↑ +2 bulan ini</p>
        </div>
        <div class="stat-icon" style="background:#f5eeff">
            <svg style="width:20px;height:20px;color:#6B0080" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
    </div>

    <div class="stat-card">
        <div>
            <p style="font-size:12px;color:#6b7280;font-weight:600;text-transform:uppercase;letter-spacing:.06em">Tiket Terjual</p>
            <p style="font-size:28px;font-weight:800;color:#111;margin-top:4px">{{ $ticketsSold ?? 0 }}</p>
            <p style="font-size:12px;color:#22c55e;margin-top:2px;font-weight:600">↑ +34 minggu ini</p>
        </div>
        <div class="stat-icon" style="background:#f0fdf4">
            <svg style="width:20px;height:20px;color:#16a34a" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
            </svg>
        </div>
    </div>

    <div class="stat-card">
        <div>
            <p style="font-size:12px;color:#6b7280;font-weight:600;text-transform:uppercase;letter-spacing:.06em">Pendapatan</p>
            <p style="font-size:28px;font-weight:800;color:#111;margin-top:4px">Rp{{ number_format($revenue ?? 0, 0, ',', '.') }}</p>
            <p style="font-size:12px;color:#22c55e;margin-top:2px;font-weight:600">↑ +12% bulan ini</p>
        </div>
        <div class="stat-icon" style="background:#fff7ed">
            <svg style="width:20px;height:20px;color:#ea580c" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
    </div>

    <div class="stat-card">
        <div>
            <p style="font-size:12px;color:#6b7280;font-weight:600;text-transform:uppercase;letter-spacing:.06em">Event Aktif</p>
            <p style="font-size:28px;font-weight:800;color:#111;margin-top:4px">{{ $activeEvents ?? 0 }}</p>
            <p style="font-size:12px;color:#6b7280;margin-top:2px;font-weight:600">Sedang berlangsung</p>
        </div>
        <div class="stat-icon" style="background:#eff6ff">
            <svg style="width:20px;height:20px;color:#2563eb" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
    </div>
</div>

{{-- MAIN GRID --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- EVENT TERBARU --}}
    <div style="grid-column: span 2; background:white; border-radius:14px; padding:20px">
        <div class="flex items-center justify-between mb-4">
            <h2 style="font-size:15px;font-weight:700;color:#111">Event Terbaru</h2>
            <a href="{{ route('panitia.events.index') }}"
               style="font-size:12px;color:#6B0080;font-weight:600;text-decoration:none">Lihat semua →</a>
        </div>

        @forelse($recentEvents ?? [] as $event)
        <div style="display:flex;align-items:center;gap:14px;padding:12px 0;border-bottom:1px solid #f3f4f6">
            <div style="width:52px;height:52px;border-radius:10px;background:#f5eeff;display:flex;align-items:center;justify-content:center;flex-shrink:0;overflow:hidden">
                @if($event->poster)
                    <img src="{{ asset('poster/'.$event->poster) }}" class="w-full h-full" style="object-fit:cover">
                @else
                    <span style="font-size:22px">🎫</span>
                @endif
            </div>
            <div style="flex:1;min-width:0">
                <p style="font-size:13px;font-weight:700;color:#111;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $event->title }}</p>
                <p style="font-size:12px;color:#9ca3af;margin-top:1px">{{ \Carbon\Carbon::parse($event->date)->format('d M Y') }} · {{ $event->location }}</p>
            </div>
            <span style="font-size:10px;font-weight:700;padding:3px 8px;border-radius:6px;
                {{ $event->status === 'published' ? 'background:#dcfce7;color:#16a34a' : ($event->status === 'cancelled' ? 'background:#fee2e2;color:#dc2626' : 'background:#f3f4f6;color:#6b7280') }}">
                {{ strtoupper($event->status ?? 'DRAFT') }}
            </span>
        </div>
        @empty
        <div style="text-align:center;padding:32px;color:#9ca3af">
            <span style="font-size:36px">📅</span>
            <p style="margin-top:8px;font-size:13px">Belum ada event. <a href="{{ route('panitia.events.create') }}" style="color:#6B0080;text-decoration:none;font-weight:600">Buat sekarang →</a></p>
        </div>
        @endforelse
    </div>

    {{-- SIDEBAR KANAN --}}
    <div style="display:flex;flex-direction:column;gap:14px">

        {{-- QUICK ACTION --}}
        <div style="background:white;border-radius:14px;padding:20px">
            <h2 style="font-size:15px;font-weight:700;color:#111;margin-bottom:14px">Quick Action</h2>
            <a href="{{ route('panitia.events.create') }}"
               style="display:flex;align-items:center;gap:10px;padding:11px 14px;background:#6B0080;color:white;border-radius:10px;text-decoration:none;font-size:13px;font-weight:600;margin-bottom:8px">
                <svg style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Buat Event Baru
            </a>
            <a href="{{ route('panitia.events.index') }}"
               style="display:flex;align-items:center;gap:10px;padding:11px 14px;background:#f5eeff;color:#6B0080;border-radius:10px;text-decoration:none;font-size:13px;font-weight:600">
                <svg style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                </svg>
                Kelola Event
            </a>
        </div>

        {{-- STATUS SUMMARY --}}
        <div style="background:white;border-radius:14px;padding:20px">
            <h2 style="font-size:15px;font-weight:700;color:#111;margin-bottom:14px">Status Event</h2>
            @php
                $statuses = [
                    ['label'=>'Draft',     'count'=>$draftCount ?? 0,     'color'=>'#6b7280','bg'=>'#f3f4f6'],
                    ['label'=>'Published', 'count'=>$publishedCount ?? 0, 'color'=>'#16a34a','bg'=>'#dcfce7'],
                    ['label'=>'Cancelled', 'count'=>$cancelledCount ?? 0, 'color'=>'#dc2626','bg'=>'#fee2e2'],
                ];
            @endphp
            @foreach($statuses as $s)
            <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid #f9fafb">
                <span style="font-size:13px;color:#374151;font-weight:500">{{ $s['label'] }}</span>
                <span style="font-size:12px;font-weight:700;padding:2px 10px;border-radius:20px;background:{{ $s['bg'] }};color:{{ $s['color'] }}">
                    {{ $s['count'] }}
                </span>
            </div>
            @endforeach
        </div>
    </div>
</div>

@endsection
