@extends('layouts.admin')
@section('title', 'Admin Dashboard')
@section('page-title', 'Admin Dashboard')
@section('page-sub', 'Pantau seluruh aktivitas platform')

@section('content')

{{-- STAT CARDS --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div style="background:white;border-radius:12px;padding:20px;display:flex;align-items:flex-start;justify-content:space-between">
        <div>
            <p style="font-size:11px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.06em">Total Pengguna</p>
            <p style="font-size:26px;font-weight:800;color:#111;margin-top:4px">{{ $totalUsers ?? 0 }}</p>
            <p style="font-size:12px;color:#22c55e;margin-top:2px;font-weight:600">↑ +12 bulan ini</p>
        </div>
        <div style="width:40px;height:40px;border-radius:10px;background:#eff6ff;display:flex;align-items:center;justify-content:center">
            <svg style="width:20px;height:20px;color:#2563eb" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
        </div>
    </div>
    <div style="background:white;border-radius:12px;padding:20px;display:flex;align-items:flex-start;justify-content:space-between">
        <div>
            <p style="font-size:11px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.06em">Total Event</p>
            <p style="font-size:26px;font-weight:800;color:#111;margin-top:4px">{{ $totalEvents ?? 0 }}</p>
            <p style="font-size:12px;color:#22c55e;margin-top:2px;font-weight:600">↑ +3 minggu ini</p>
        </div>
        <div style="width:40px;height:40px;border-radius:10px;background:#f5eeff;display:flex;align-items:center;justify-content:center">
            <svg style="width:20px;height:20px;color:#6B0080" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
    </div>
    <div style="background:white;border-radius:12px;padding:20px;display:flex;align-items:flex-start;justify-content:space-between">
        <div>
            <p style="font-size:11px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.06em">Tiket Terjual</p>
            <p style="font-size:26px;font-weight:800;color:#111;margin-top:4px">{{ $totalTickets ?? 0 }}</p>
            <p style="font-size:12px;color:#22c55e;margin-top:2px;font-weight:600">↑ +89 hari ini</p>
        </div>
        <div style="width:40px;height:40px;border-radius:10px;background:#f0fdf4;display:flex;align-items:center;justify-content:center">
            <svg style="width:20px;height:20px;color:#16a34a" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
            </svg>
        </div>
    </div>
    <div style="background:white;border-radius:12px;padding:20px;display:flex;align-items:flex-start;justify-content:space-between">
        <div>
            <p style="font-size:11px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.06em">Total Revenue</p>
            <p style="font-size:22px;font-weight:800;color:#111;margin-top:4px">Rp{{ number_format($totalRevenue ?? 0,0,',','.') }}</p>
            <p style="font-size:12px;color:#22c55e;margin-top:2px;font-weight:600">↑ Semester ini</p>
        </div>
        <div style="width:40px;height:40px;border-radius:10px;background:#fff7ed;display:flex;align-items:center;justify-content:center">
            <svg style="width:20px;height:20px;color:#ea580c" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
    </div>
</div>

{{-- MAIN GRID --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- RECENT USERS --}}
    <div style="grid-column:span 2;background:white;border-radius:14px;padding:20px">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
            <h2 style="font-size:15px;font-weight:700;color:#111">Pengguna Terbaru</h2>
            <a href="{{ route('admin.users.index') }}" style="font-size:12px;color:#6B0080;font-weight:600;text-decoration:none">Lihat semua →</a>
        </div>
        <table style="width:100%;border-collapse:collapse">
            <thead>
                <tr style="border-bottom:2px solid #f3f4f6">
                    <th style="text-align:left;padding:8px 0;font-size:11px;font-weight:700;color:#9ca3af;text-transform:uppercase">Pengguna</th>
                    <th style="text-align:left;padding:8px 0;font-size:11px;font-weight:700;color:#9ca3af;text-transform:uppercase">Role</th>
                    <th style="text-align:left;padding:8px 0;font-size:11px;font-weight:700;color:#9ca3af;text-transform:uppercase">Bergabung</th>
                    <th style="text-align:right;padding:8px 0;font-size:11px;font-weight:700;color:#9ca3af;text-transform:uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentUsers ?? [] as $user)
                <tr style="border-bottom:1px solid #f9fafb">
                    <td style="padding:12px 0">
                        <div style="display:flex;align-items:center;gap:10px">
                            <div style="width:34px;height:34px;border-radius:50%;background:#6B0080;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;color:white">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p style="font-size:13px;font-weight:600;color:#111">{{ $user->name }}</p>
                                <p style="font-size:11px;color:#9ca3af">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td style="padding:12px 0">
                        <span style="font-size:11px;font-weight:700;padding:3px 10px;border-radius:20px;
                            {{ $user->role === 'admin' ? 'background:#f5eeff;color:#6B0080' : ($user->role === 'panitia' ? 'background:#eff6ff;color:#2563eb' : 'background:#f3f4f6;color:#6b7280') }}">
                            {{ ucfirst($user->role ?? 'user') }}
                        </span>
                    </td>
                    <td style="padding:12px 0;font-size:12px;color:#9ca3af">{{ $user->created_at->format('d M Y') }}</td>
                    <td style="padding:12px 0;text-align:right">
                        <a href="{{ route('admin.users.edit', $user->id) }}" style="font-size:12px;color:#6B0080;font-weight:600;text-decoration:none">Edit</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" style="text-align:center;padding:32px;color:#9ca3af;font-size:13px">Belum ada pengguna</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- RIGHT PANEL --}}
    <div style="display:flex;flex-direction:column;gap:14px">
        <div style="background:white;border-radius:14px;padding:20px">
            <h2 style="font-size:15px;font-weight:700;color:#111;margin-bottom:14px">Role Pengguna</h2>
            @foreach([['Admin',$adminCount??0,'#6B0080','#f5eeff'],['Panitia',$panitiaCount??0,'#2563eb','#eff6ff'],['User',$userCount??0,'#16a34a','#f0fdf4']] as [$label,$count,$color,$bg])
            <div style="display:flex;justify-content:space-between;align-items:center;padding:9px 0;border-bottom:1px solid #f9fafb">
                <div style="display:flex;align-items:center;gap:8px">
                    <div style="width:8px;height:8px;border-radius:50%;background:{{ $color }}"></div>
                    <span style="font-size:13px;color:#374151;font-weight:500">{{ $label }}</span>
                </div>
                <span style="font-size:12px;font-weight:700;padding:2px 10px;border-radius:20px;background:{{ $bg }};color:{{ $color }}">{{ $count }}</span>
            </div>
            @endforeach
        </div>
        <div style="background:white;border-radius:14px;padding:20px">
            <h2 style="font-size:15px;font-weight:700;color:#111;margin-bottom:14px">Quick Action</h2>
            <a href="{{ route('admin.users.create') }}" style="display:flex;align-items:center;padding:10px 14px;background:#6B0080;color:white;border-radius:10px;text-decoration:none;font-size:13px;font-weight:600;margin-bottom:8px">+ Tambah Pengguna</a>
            <a href="{{ route('admin.events.index') }}" style="display:flex;align-items:center;padding:10px 14px;background:#eff6ff;color:#2563eb;border-radius:10px;text-decoration:none;font-size:13px;font-weight:600;margin-bottom:8px">🗓 Kelola Event</a>
            <a href="{{ route('admin.settings') }}" style="display:flex;align-items:center;padding:10px 14px;background:#f3f4f6;color:#374151;border-radius:10px;text-decoration:none;font-size:13px;font-weight:600">⚙️ Pengaturan</a>
        </div>
    </div>
</div>

@endsection
