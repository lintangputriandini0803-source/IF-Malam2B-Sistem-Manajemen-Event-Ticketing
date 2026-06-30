@extends('layouts.panitia')
@section('title', 'Peserta - ' . $event->title)
@section('page-title', 'Peserta Event')

@section('content')

<div style="margin-bottom:16px">
    <a href="{{ route('panitia.events.index') }}" style="font-size:13px;color:#6B0080;text-decoration:none;font-weight:600">
        ← Kembali ke Semua Event
    </a>
    <h1 style="font-size:20px;font-weight:800;color:#111;margin-top:6px">{{ $event->title }}</h1>
    <p style="font-size:13px;color:#9ca3af;margin-top:2px">📅 {{ $event->getFormattedDateRange() }} &nbsp;·&nbsp; 📍 {{ $event->location }}</p>
</div>

{{-- SUMMARY CARDS --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(190px, 1fr));gap:14px;margin-bottom:24px">

    <div style="background:white;border-radius:14px;padding:18px">
        <p style="font-size:12px;color:#9ca3af;font-weight:600">Tiket Terjual</p>
        <p style="font-size:24px;font-weight:800;color:#111;margin-top:4px">
            {{ $summary['total_terjual'] }}<span style="font-size:14px;color:#9ca3af;font-weight:600">/{{ $summary['total_kuota'] }}</span>
        </p>
    </div>

    <div style="background:white;border-radius:14px;padding:18px">
        <p style="font-size:12px;color:#9ca3af;font-weight:600">Total Pendapatan</p>
        <p style="font-size:22px;font-weight:800;color:#16a34a;margin-top:4px">
            Rp {{ number_format($summary['total_pendapatan'], 0, ',', '.') }}
        </p>
    </div>

    <div style="background:white;border-radius:14px;padding:18px">
        <p style="font-size:12px;color:#9ca3af;font-weight:600">Pembeli Unik</p>
        <p style="font-size:24px;font-weight:800;color:#111;margin-top:4px">{{ $summary['pembeli_unik'] }}</p>
    </div>

    <div style="background:white;border-radius:14px;padding:18px">
        <p style="font-size:12px;color:#9ca3af;font-weight:600;margin-bottom:8px">Per Tipe Tiket</p>
        @forelse($summary['per_tipe'] as $tipe)
            <p style="font-size:12px;color:#374151;margin-top:2px">
                {{ $tipe['nama'] }}: <strong>{{ $tipe['sold'] }}/{{ $tipe['quota'] }}</strong>
            </p>
        @empty
            <p style="font-size:12px;color:#9ca3af">Belum ada tipe tiket</p>
        @endforelse
    </div>

</div>

{{-- SEARCH --}}
<div style="background:white;border-radius:14px;padding:16px 20px;margin-bottom:16px">
    <form method="GET" style="display:flex;gap:10px;flex-wrap:wrap">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Cari nama, email, atau no. registrasi..."
               style="flex:1 1 220px;border:1.5px solid #e5e7eb;border-radius:10px;padding:10px 14px;font-size:13px">
        <button type="submit" style="background:#6B0080;color:white;padding:10px 20px;border-radius:10px;font-size:13px;font-weight:700;border:none">
            Cari
        </button>
        @if(request('search'))
        <a href="{{ route('panitia.participants', $event->id) }}"
           style="padding:10px 16px;border-radius:10px;font-size:13px;font-weight:600;text-decoration:none;background:#fee2e2;color:#dc2626">
            ✕ Reset
        </a>
        @endif
    </form>
</div>

{{-- BUYER LIST --}}
<div style="overflow-x:auto;border-radius:14px;border:1px solid #f3f4f6">
    <table style="width:100%;min-width:760px;border-collapse:collapse;background:white">
        <thead style="background:#f9fafb">
            <tr style="text-align:left;font-size:11px;text-transform:uppercase;color:#9ca3af">
                <th style="padding:12px 16px">Reg. Number</th>
                <th style="padding:12px 16px">Nama</th>
                <th style="padding:12px 16px">Email</th>
                <th style="padding:12px 16px">Tipe Tiket</th>
                <th style="padding:12px 16px">Qty</th>
                <th style="padding:12px 16px">Status</th>
                <th style="padding:12px 16px text-align:right">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pembeli as $p)
            <tr style="border-top:1px solid #f3f4f6;font-size:13px">
                <td style="padding:12px 16px;font-weight:700;color:#6B0080">{{ $p->reg_number }}</td>
                <td style="padding:12px 16px;font-weight:600;color:#111">{{ $p->name }}</td>
                <td style="padding:12px 16px;color:#6b7280">{{ $p->email }}</td>
                <td style="padding:12px 16px;color:#6b7280">{{ $p->ticketType->name ?? '-' }}</td>
                <td style="padding:12px 16px;color:#6b7280">{{ $p->quantity }}</td>
                <td style="padding:12px 16px">
                    @php $status = $p->status ?? 'pending'; @endphp
                    <span style="padding:3px 10px;border-radius:99px;font-size:10px;font-weight:700;text-transform:uppercase;
                        {{ $status === 'confirmed' ? 'background:#dcfce7;color:#16a34a' : ($status === 'cancelled' ? 'background:#fee2e2;color:#dc2626' : 'background:#fef9c3;color:#ca8a04') }}">
                        {{ $status }}
                    </span>
                </td>
                <td style="padding:12px 16px;text-align:right">
                    @if($status === 'confirmed')
                    <a href="{{ route('panitia.participants.ticket', [$event->id, $p->id]) }}"
                       style="font-size:12px;font-weight:700;color:#6B0080;text-decoration:none">
                        📄 Lihat/Unduh Tiket
                    </a>
                    @else
                    <span style="font-size:12px;color:#d1d5db">—</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="7" style="padding:40px;text-align:center;color:#9ca3af">Belum ada pembeli untuk event ini.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top:20px">
    {{ $pembeli->links() }}
</div>

@endsection
