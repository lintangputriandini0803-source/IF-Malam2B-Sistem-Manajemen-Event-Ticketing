@extends('layouts.panitia')
@section('title', 'Kelola Event')
@section('page-title', 'Kelola Event')

@section('content')

{{-- HEADER --}}
<div class="flex items-center justify-between mb-5">
    <div>
        <h1 style="font-size:20px;font-weight:800;color:#111">Semua Event</h1>
        <p style="font-size:13px;color:#9ca3af;margin-top:2px">Kelola semua event yang telah kamu buat</p>
    </div>
    <a href="{{ route('panitia.events.create') }}"
       style="display:flex;align-items:center;gap:8px;background:#6B0080;color:white;padding:10px 18px;border-radius:10px;font-size:13px;font-weight:700;text-decoration:none">
        <svg style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Buat Event
    </a>
</div>

{{-- SEARCH + FILTER --}}
<div style="background:white;border-radius:14px;padding:16px 20px;margin-bottom:20px">
    <form method="GET" action="{{ route('panitia.events.index') }}" style="display:flex;gap:10px;flex-wrap:wrap;align-items:center">

        {{-- SEARCH WITH CATEGORY DROPDOWN --}}
        <div style="display:flex;flex:1;min-width:240px;border:1.5px solid #e5e7eb;border-radius:10px;overflow:hidden;background:white">

            {{-- CATEGORY SELECTOR --}}
            <div style="position:relative;border-right:1.5px solid #e5e7eb;flex-shrink:0">
                <select name="category"
                        style="appearance:none;padding:0 36px 0 14px;height:100%;font-size:13px;font-weight:600;color:#374151;border:none;background:transparent;cursor:pointer;min-width:140px">
                    <option value="">Semua Kategori</option>
                    <option value="seminar" {{ request('category')=='seminar'?'selected':'' }}>📚 Seminar</option>
                    <option value="workshop" {{ request('category')=='workshop'?'selected':'' }}>🛠 Workshop</option>
                    <option value="konser" {{ request('category')=='konser'?'selected':'' }}>🎵 Konser</option>
                    <option value="kompetisi" {{ request('category')=='kompetisi'?'selected':'' }}>🏆 Kompetisi</option>
                    <option value="pameran" {{ request('category')=='pameran'?'selected':'' }}>🎨 Pameran</option>
                    <option value="olahraga" {{ request('category')=='olahraga'?'selected':'' }}>⚽ Olahraga</option>
                    <option value="hiburan" {{ request('category')=='hiburan'?'selected':'' }}>🎭 Hiburan</option>
                    <option value="lainnya" {{ request('category')=='lainnya'?'selected':'' }}>📌 Lainnya</option>
                </select>
                <svg style="position:absolute;right:10px;top:50%;transform:translateY(-50%);width:14px;height:14px;color:#9ca3af;pointer-events:none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>

            {{-- SEARCH INPUT --}}
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama event, lokasi..."
                   style="flex:1;padding:10px 14px;border:none;font-size:13px;color:#374151;outline:none">

            {{-- SEARCH BUTTON --}}
            <button type="submit"
                    style="padding:0 18px;background:#6B0080;color:white;border:none;cursor:pointer;font-size:13px;font-weight:700;display:flex;align-items:center;gap:6px">
                <svg style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Cari
            </button>
        </div>

        {{-- STATUS FILTER --}}
        <div style="display:flex;gap:6px">
            @foreach([''=>'Semua','draft'=>'Draft','published'=>'Published','cancelled'=>'Cancelled'] as $val=>$label)
            <a href="{{ route('panitia.events.index', array_merge(request()->query(), ['status'=>$val])) }}"
               style="padding:8px 14px;border-radius:8px;font-size:12px;font-weight:600;text-decoration:none;
                      {{ request('status')===$val ? 'background:#6B0080;color:white' : 'background:#f3f4f6;color:#6b7280' }}">
                {{ $label }}
            </a>
            @endforeach
        </div>

        @if(request('search') || request('category') || request('status'))
        <a href="{{ route('panitia.events.index') }}"
           style="padding:8px 14px;border-radius:8px;font-size:12px;font-weight:600;text-decoration:none;background:#fee2e2;color:#dc2626">
            ✕ Reset
        </a>
        @endif
    </form>
</div>

{{-- ACTIVE FILTER TAGS --}}
@if(request('category') || request('search'))
<div style="display:flex;gap:8px;margin-bottom:12px;flex-wrap:wrap">
    @if(request('search'))
    <span style="padding:4px 12px;background:#f5eeff;color:#6B0080;border-radius:20px;font-size:12px;font-weight:600">
        🔍 "{{ request('search') }}"
    </span>
    @endif
    @if(request('category'))
    <span style="padding:4px 12px;background:#f5eeff;color:#6B0080;border-radius:20px;font-size:12px;font-weight:600">
        📁 {{ ucfirst(request('category')) }}
    </span>
    @endif
    <span style="font-size:12px;color:#9ca3af;align-self:center">
        {{ $events->total() ?? 0 }} event ditemukan
    </span>
</div>
@endif

{{-- EVENT LIST --}}
<div>
    @forelse($events ?? [] as $event)
    <div class="event-row" style="margin-bottom:10px">

        {{-- THUMB --}}
        <div class="event-thumb" style="width:100px;height:70px;border-radius:10px;overflow:hidden;background:#f5eeff;flex-shrink:0;position:relative">
            @if($event->poster)
                <img src="{{ asset('poster/'.$event->poster) }}" style="width:100%;height:100%;object-fit:cover">
            @else
                <div style="display:flex;align-items:center;justify-content:center;height:100%;font-size:28px">🎫</div>
            @endif
            <span class="badge-status badge-{{ $event->status ?? 'draft' }}" style="position:absolute;top:5px;left:5px;font-size:9px;padding:2px 6px;border-radius:4px;font-weight:700;text-transform:uppercase">
                {{ $event->status ?? 'draft' }}
            </span>
        </div>

        {{-- INFO --}}
        <div style="flex:1;min-width:0">
            <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap">
                <p style="font-size:14px;font-weight:700;color:#111">{{ $event->title }}</p>
                @if($event->category)
                <span style="font-size:10px;padding:2px 8px;background:#f0f0f5;color:#6b7280;border-radius:4px;font-weight:600">
                    {{ $event->category }}
                </span>
                @endif
            </div>
            <p style="font-size:12px;color:#9ca3af;margin-top:3px">
                📅 {{ isset($event->date) ? \Carbon\Carbon::parse($event->date)->format('d M Y') : '-' }}
                &nbsp;·&nbsp;
                📍 {{ $event->location ?? '-' }}
                &nbsp;·&nbsp;
                🎫 {{ $event->tickets_sold ?? 0 }}/{{ $event->quota ?? 0 }} tiket
            </p>
        </div>

        {{-- HARGA --}}
        <div style="text-align:right;flex-shrink:0">
            <p style="font-size:14px;font-weight:800;color:#6B0080">
                {{ $event->price > 0 ? 'Rp'.number_format($event->price,0,',','.') : 'GRATIS' }}
            </p>
        </div>

        {{-- ACTIONS --}}
        <div class="dropdown" style="flex-shrink:0">
            <button data-dropdown
                    style="width:34px;height:34px;border-radius:8px;border:1.5px solid #e5e7eb;background:white;cursor:pointer;display:flex;align-items:center;justify-content:center">
                <svg style="width:16px;height:16px;color:#6b7280" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                </svg>
            </button>
            <div class="dropdown-menu">
                <a href="{{ route('panitia.events.edit', $event->id) }}" class="dropdown-item">
                    ✏️ Edit
                </a>
                <a href="{{ route('event.show', $event->slug) }}" target="_blank" class="dropdown-item">
                    👁 Lihat
                </a>
                <form method="POST" action="{{ route('panitia.events.destroy', $event->id) }}"
                      onsubmit="return confirm('Hapus event ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="dropdown-item danger w-full text-left">🗑 Hapus</button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div style="text-align:center;padding:60px 20px;background:white;border-radius:14px">
        <span style="font-size:48px">📭</span>
        <p style="font-size:16px;font-weight:700;color:#374151;margin-top:12px">Belum ada event</p>
        <p style="font-size:13px;color:#9ca3af;margin-top:4px">
            @if(request('search') || request('category'))
                Tidak ada event yang sesuai filter. Coba kata kunci lain.
            @else
                Mulai dengan membuat event pertamamu!
            @endif
        </p>
        @if(!request('search') && !request('category'))
        <a href="{{ route('panitia.events.create') }}"
           style="display:inline-flex;align-items:center;gap:8px;margin-top:16px;background:#6B0080;color:white;padding:10px 20px;border-radius:10px;font-size:13px;font-weight:700;text-decoration:none">
            + Buat Event
        </a>
        @endif
    </div>
    @endforelse
</div>

{{-- PAGINATION --}}
@if(isset($events) && $events->hasPages())
<div style="margin-top:20px">
    {{ $events->withQueryString()->links() }}
</div>
@endif

@endsection
