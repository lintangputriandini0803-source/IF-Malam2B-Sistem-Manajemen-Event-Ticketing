@extends('layouts.admin')
@section('title', 'Manajemen Pengguna')
@section('page-title', 'Manajemen Pengguna')

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px">
    <div>
        <h1 style="font-size:20px;font-weight:800;color:#111">Semua Pengguna</h1>
        <p style="font-size:13px;color:#9ca3af;margin-top:2px">Kelola akun pengguna dan hak akses</p>
    </div>
    <span style="font-size:13px;color:#9ca3af;font-weight:600">{{ $users->total() }} pengguna</span>
</div>

{{-- SEARCH + FILTER --}}
<div style="background:white;border-radius:14px;padding:16px 20px;margin-bottom:16px">
    <form method="GET" action="{{ route('admin.users.index') }}" style="display:flex;gap:10px;flex-wrap:wrap;align-items:center">

        {{-- SEARCH WITH ROLE CATEGORY --}}
        <div style="display:flex;flex:1;min-width:240px;border:1.5px solid #e5e7eb;border-radius:10px;overflow:hidden;background:white">
            <div style="position:relative;border-right:1.5px solid #e5e7eb;flex-shrink:0">
                <select name="role"
                        style="appearance:none;padding:0 36px 0 14px;height:100%;font-size:13px;font-weight:600;color:#374151;border:none;background:transparent;cursor:pointer;min-width:130px">
                    <option value="">Semua Role</option>
                    <option value="admin"   {{ request('role')==='admin'   ? 'selected' : '' }}>👑 Admin</option>
                    <option value="panitia" {{ request('role')==='panitia' ? 'selected' : '' }}>📋 Panitia</option>
                    <option value="user"    {{ request('role')==='user'    ? 'selected' : '' }}>👤 User</option>
                </select>
                <svg style="position:absolute;right:10px;top:50%;transform:translateY(-50%);width:14px;height:14px;color:#9ca3af;pointer-events:none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama atau email..."
                   style="flex:1;padding:10px 14px;border:none;font-size:13px;color:#374151;outline:none">
            <button type="submit"
                    style="padding:0 18px;background:#6B0080;color:white;border:none;cursor:pointer;font-size:13px;font-weight:700;display:flex;align-items:center;gap:6px">
                <svg style="width:15px;height:15px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Cari
            </button>
        </div>

        @if(request('search') || request('role'))
        <a href="{{ route('admin.users.index') }}"
           style="padding:8px 14px;border-radius:8px;font-size:12px;font-weight:600;text-decoration:none;background:#fee2e2;color:#dc2626">
            ✕ Reset
        </a>
        @endif
    </form>
</div>

{{-- TABLE --}}
<div style="background:white;border-radius:14px;overflow:hidden">
    <table style="width:100%;border-collapse:collapse">
        <thead style="background:#f8fafc">
            <tr>
                <th style="text-align:left;padding:14px 20px;font-size:11px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.05em">Pengguna</th>
                <th style="text-align:left;padding:14px 20px;font-size:11px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.05em">Role</th>
                <th style="text-align:left;padding:14px 20px;font-size:11px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.05em">Organisasi</th>
                <th style="text-align:left;padding:14px 20px;font-size:11px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.05em">Status</th>
                <th style="text-align:left;padding:14px 20px;font-size:11px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.05em">Bergabung</th>
                <th style="text-align:right;padding:14px 20px;font-size:11px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.05em">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users ?? [] as $user)
            <tr style="border-top:1px solid #f3f4f6;transition:background .1s" onmouseover="this.style.background='#fafafa'" onmouseout="this.style.background='white'">
                <td style="padding:14px 20px">
                    <div style="display:flex;align-items:center;gap:12px">
                        <div style="width:38px;height:38px;border-radius:50%;background:#6B0080;display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:700;color:white;flex-shrink:0">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p style="font-size:13px;font-weight:700;color:#111">{{ $user->name }}</p>
                            <p style="font-size:12px;color:#9ca3af;margin-top:1px">{{ $user->email }}</p>
                        </div>
                    </div>
                </td>
                <td style="padding:14px 20px">
                    <span style="font-size:11px;font-weight:700;padding:3px 10px;border-radius:20px;
                        {{ $user->role === 'admin' ? 'background:#f5eeff;color:#6B0080' : ($user->role === 'panitia' ? 'background:#eff6ff;color:#2563eb' : 'background:#f3f4f6;color:#6b7280') }}">
                        {{ ucfirst($user->role ?? 'user') }}
                    </span>
                </td>
                <td style="padding:14px 20px;font-size:13px;color:#374151">{{ $user->organization ?? '-' }}</td>
                <td style="padding:14px 20px">
                    @if($user->role === 'panitia')
                    <span style="font-size:11px;font-weight:700;padding:3px 10px;border-radius:20px;
                        {{ $user->status === 'approved' ? 'background:#dcfce7;color:#16a34a' : ($user->status === 'rejected' ? 'background:#fee2e2;color:#dc2626' : 'background:#fef9c3;color:#ca8a04') }}">
                        {{ $user->status === 'approved' ? '✓ Disetujui' : ($user->status === 'rejected' ? '✗ Ditolak' : '⏳ Pending') }}
                    </span>
                    @else
                    <span style="font-size:11px;color:#9ca3af">-</span>
                    @endif
                </td>
                <td style="padding:14px 20px;font-size:12px;color:#9ca3af">{{ $user->created_at->format('d M Y') }}</td>
                <td style="padding:14px 20px;text-align:right">
                    <div class="dropdown" style="display:inline-block">
                        <button data-dropdown
                                style="width:32px;height:32px;border-radius:7px;border:1.5px solid #e5e7eb;background:white;cursor:pointer;display:flex;align-items:center;justify-content:center">
                            <svg style="width:15px;height:15px;color:#6b7280" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                            </svg>
                        </button>
                        <div class="dropdown-menu">
                            @if($user->role === 'panitia' && $user->status === 'pending')
                            <form method="POST" action="{{ route('admin.users.approve', $user->id) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="dropdown-item w-full text-left">✅ Setujui</button>
                            </form>
                            <form method="POST" action="{{ route('admin.users.reject', $user->id) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="dropdown-item danger w-full text-left">❌ Tolak</button>
                            </form>
                            @elseif($user->role === 'panitia' && $user->status === 'approved')
                            <form method="POST" action="{{ route('admin.users.reject', $user->id) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="dropdown-item danger w-full text-left">❌ Cabut Akses</button>
                            </form>
                            @else
                            <span class="dropdown-item" style="color:#9ca3af;cursor:default">Tidak ada aksi</span>
                            @endif
                        </div>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center;padding:48px;color:#9ca3af">
                    <span style="font-size:36px">👥</span>
                    <p style="margin-top:8px;font-size:13px">Tidak ada pengguna ditemukan</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if(isset($users) && $users->hasPages())
<div style="margin-top:16px">{{ $users->withQueryString()->links() }}</div>
@endif

@endsection
