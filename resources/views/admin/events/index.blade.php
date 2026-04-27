@extends('layouts.admin')
@section('title', 'Semua Event')
@section('page-title', 'Semua Event')

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px">
    <div>
        <h1 style="font-size:20px;font-weight:800;color:#111">Semua Event</h1>
        <p style="font-size:13px;color:#9ca3af;margin-top:2px">Pantau dan kelola seluruh event di platform</p>
    </div>
    <span style="font-size:13px;color:#9ca3af;font-weight:600">{{ $events->total() }} event</span>
</div>

{{-- FILTER --}}
<div style="background:white;border-radius:14px;padding:16px 20px;margin-bottom:16px">
    <form method="GET" action="{{ route('admin.events.index') }}" style="display:flex;gap:10px;flex-wrap:wrap;align-items:center">
        <div style="display:flex;flex:1;min-width:240px;border:1.5px solid #e5e7eb;border-radius:10px;overflow:hidden">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari judul atau lokasi..."
                   style="flex:1;padding:10px 14px;border:none;font-size:13px;outline:none">
            <button type="submit" style="padding:0 18px;background:#6B0080;color:white;border:none;cursor:pointer;font-size:13px;font-weight:700">Cari</button>
        </div>
        <div style="display:flex;gap:6px">
            @foreach([''=>'Semua','draft'=>'Draft','published'=>'Published','cancelled'=>'Cancelled'] as $val=>$label)
            <a href="{{ route('admin.events.index', array_merge(request()->query(), ['status'=>$val])) }}"
               style="padding:8px 14px;border-radius:8px;font-size:12px;font-weight:600;text-decoration:none;{{ request('status')===$val ? 'background:#6B0080;color:white' : 'background:#f3f4f6;color:#6b7280' }}">
                {{ $label }}
            </a>
            @endforeach
        </div>
    </form>
</div>

{{-- TABLE --}}
<div style="background:white;border-radius:14px;overflow:hidden">
    <table style="width:100%;border-collapse:collapse">
        <thead style="background:#f8fafc">
            <tr>
                <th style="text-align:left;padding:14px 20px;font-size:11px;font-weight:700;color:#9ca3af;text-transform:uppercase">Event</th>
                <th style="text-align:left;padding:14px 20px;font-size:11px;font-weight:700;color:#9ca3af;text-transform:uppercase">Panitia</th>
                <th style="text-align:left;padding:14px 20px;font-size:11px;font-weight:700;color:#9ca3af;text-transform:uppercase">Tanggal</th>
                <th style="text-align:left;padding:14px 20px;font-size:11px;font-weight:700;color:#9ca3af;text-transform:uppercase">Status</th>
                <th style="text-align:right;padding:14px 20px;font-size:11px;font-weight:700;color:#9ca3af;text-transform:uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($events as $event)
            <tr style="border-top:1px solid #f3f4f6" onmouseover="this.style.background='#fafafa'" onmouseout="this.style.background='white'">
                <td style="padding:14px 20px">
                    <p style="font-size:13px;font-weight:700;color:#111">{{ $event->title }}</p>
                    <p style="font-size:12px;color:#9ca3af;margin-top:2px">📍 {{ $event->location ?? '-' }}</p>
                </td>
                <td style="padding:14px 20px;font-size:13px;color:#374151">{{ $event->user->name ?? '-' }}</td>
                <td style="padding:14px 20px;font-size:12px;color:#9ca3af">
                    {{ $event->event_date ? \Carbon\Carbon::parse($event->event_date)->format('d M Y') : '-' }}
                </td>
                <td style="padding:14px 20px">
                    <span style="font-size:11px;font-weight:700;padding:3px 10px;border-radius:20px;
                        {{ $event->status === 'published' ? 'background:#dcfce7;color:#16a34a' : ($event->status === 'cancelled' ? 'background:#fee2e2;color:#dc2626' : 'background:#fef9c3;color:#ca8a04') }}">
                        {{ ucfirst($event->status ?? 'draft') }}
                    </span>
                </td>
                <td style="padding:14px 20px;text-align:right">
                    <div class="dropdown" style="display:inline-block">
                        <button data-dropdown style="width:32px;height:32px;border-radius:7px;border:1.5px solid #e5e7eb;background:white;cursor:pointer;display:flex;align-items:center;justify-content:center">
                            <svg style="width:15px;height:15px;color:#6b7280" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                            </svg>
                        </button>
                        <div class="dropdown-menu">
                            <a href="{{ route('event.show', $event->slug) }}" target="_blank" class="dropdown-item">👁 Lihat</a>
                            @if($event->status === 'draft')
                            <form method="POST" action="{{ route('admin.events.approve', $event->id) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="dropdown-item w-full text-left">✅ Publikasikan</button>
                            </form>
                            @endif
                            @if($event->status !== 'cancelled')
                            <form method="POST" action="{{ route('admin.events.reject', $event->id) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="dropdown-item danger w-full text-left">🚫 Batalkan</button>
                            </form>
                            @endif
                            <form method="POST" action="{{ route('admin.events.destroy', $event->id) }}" onsubmit="return confirm('Hapus event ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="dropdown-item danger w-full text-left">🗑 Hapus</button>
                            </form>
                        </div>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align:center;padding:48px;color:#9ca3af">
                    <span style="font-size:36px">📅</span>
                    <p style="margin-top:8px;font-size:13px">Tidak ada event ditemukan</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($events->hasPages())
<div style="margin-top:16px">{{ $events->withQueryString()->links() }}</div>
@endif

@endsection
