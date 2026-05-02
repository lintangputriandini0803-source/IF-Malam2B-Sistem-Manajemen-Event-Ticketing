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

{{-- ============================================================ --}}
{{-- MODAL TAMBAH PENGGUNA                                        --}}
{{-- ============================================================ --}}
<div id="modalTambahPengguna"
     style="display:none;position:fixed;inset:0;z-index:1000;align-items:center;justify-content:center;background:rgba(0,0,0,.45);backdrop-filter:blur(3px)"
     onclick="if(event.target===this)this.style.display='none'">
 
    <div style="background:white;border-radius:16px;width:100%;max-width:480px;margin:16px;box-shadow:0 20px 60px rgba(0,0,0,.2);animation:slideUp .2s ease">
 
        {{-- Header Modal --}}
        <div style="display:flex;align-items:center;justify-content:space-between;padding:20px 24px;border-bottom:1px solid #f3f4f6">
            <div style="display:flex;align-items:center;gap:10px">
                <div style="width:36px;height:36px;border-radius:10px;background:#f5eeff;display:flex;align-items:center;justify-content:center">
                    <svg style="width:18px;height:18px;color:#6B0080" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                </div>
                <div>
                    <h2 style="font-size:15px;font-weight:800;color:#111;margin:0">Tambah Pengguna</h2>
                    <p style="font-size:12px;color:#9ca3af;margin:0">Buat akun pengguna baru</p>
                </div>
            </div>
            <button onclick="document.getElementById('modalTambahPengguna').style.display='none'"
                    style="width:32px;height:32px;border-radius:8px;border:1.5px solid #e5e7eb;background:white;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#6b7280;font-size:16px">
                ✕
            </button>
        </div>
 
        {{-- Form --}}
        <form method="POST" action="{{ route('admin.users.store') }}" style="padding:24px">
            @csrf
 
            {{-- Nama --}}
            <div style="margin-bottom:16px">
                <label style="display:block;font-size:12px;font-weight:700;color:#374151;margin-bottom:6px">
                    Nama Lengkap <span style="color:#dc2626">*</span>
                </label>
                <input type="text" name="name" required placeholder="Masukkan nama lengkap"
                       style="width:100%;padding:10px 14px;border:1.5px solid #e5e7eb;border-radius:9px;font-size:13px;color:#374151;outline:none;box-sizing:border-box;transition:border .2s"
                       onfocus="this.style.borderColor='#6B0080'" onblur="this.style.borderColor='#e5e7eb'">
            </div>
 
            {{-- Email --}}
            <div style="margin-bottom:16px">
                <label style="display:block;font-size:12px;font-weight:700;color:#374151;margin-bottom:6px">
                    Email <span style="color:#dc2626">*</span>
                </label>
                <input type="email" name="email" required placeholder="contoh@email.com"
                       style="width:100%;padding:10px 14px;border:1.5px solid #e5e7eb;border-radius:9px;font-size:13px;color:#374151;outline:none;box-sizing:border-box;transition:border .2s"
                       onfocus="this.style.borderColor='#6B0080'" onblur="this.style.borderColor='#e5e7eb'">
            </div>
 
            {{-- Role --}}
            <div style="margin-bottom:16px">
                <label style="display:block;font-size:12px;font-weight:700;color:#374151;margin-bottom:6px">
                    Role <span style="color:#dc2626">*</span>
                </label>
                <div style="position:relative">
                    <select name="role" required id="selectRoleModal"
                            onchange="toggleOrganisasiField(this.value)"
                            style="appearance:none;width:100%;padding:10px 36px 10px 14px;border:1.5px solid #e5e7eb;border-radius:9px;font-size:13px;color:#374151;background:white;outline:none;cursor:pointer;box-sizing:border-box;transition:border .2s"
                            onfocus="this.style.borderColor='#6B0080'" onblur="this.style.borderColor='#e5e7eb'">
                        <option value="">Pilih role...</option>
                        <option value="admin">👑 Admin</option>
                        <option value="panitia">📋 Panitia</option>
                        <option value="user">👤 User</option>
                    </select>
                    <svg style="position:absolute;right:12px;top:50%;transform:translateY(-50%);width:14px;height:14px;color:#9ca3af;pointer-events:none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>
 
            {{-- Organisasi (muncul hanya jika role = panitia) --}}
            <div id="fieldOrganisasi" style="margin-bottom:16px;display:none">
                <label style="display:block;font-size:12px;font-weight:700;color:#374151;margin-bottom:6px">
                    Organisasi
                </label>
                <input type="text" name="organization" placeholder="Nama organisasi / UKM"
                       style="width:100%;padding:10px 14px;border:1.5px solid #e5e7eb;border-radius:9px;font-size:13px;color:#374151;outline:none;box-sizing:border-box;transition:border .2s"
                       onfocus="this.style.borderColor='#6B0080'" onblur="this.style.borderColor='#e5e7eb'">
            </div>
 
            {{-- Password --}}
            <div style="margin-bottom:16px">
                <label style="display:block;font-size:12px;font-weight:700;color:#374151;margin-bottom:6px">
                    Password <span style="color:#dc2626">*</span>
                </label>
                <div style="position:relative">
                    <input type="password" name="password" id="inputPassword" required placeholder="Minimal 8 karakter"
                           style="width:100%;padding:10px 40px 10px 14px;border:1.5px solid #e5e7eb;border-radius:9px;font-size:13px;color:#374151;outline:none;box-sizing:border-box;transition:border .2s"
                           onfocus="this.style.borderColor='#6B0080'" onblur="this.style.borderColor='#e5e7eb'">
                    <button type="button" onclick="togglePassword()"
                            style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#9ca3af;padding:0">
                        <svg id="eyeIcon" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
            </div>
 
            {{-- Konfirmasi Password --}}
            <div style="margin-bottom:24px">
                <label style="display:block;font-size:12px;font-weight:700;color:#374151;margin-bottom:6px">
                    Konfirmasi Password <span style="color:#dc2626">*</span>
                </label>
                <input type="password" name="password_confirmation" required placeholder="Ulangi password"
                       style="width:100%;padding:10px 14px;border:1.5px solid #e5e7eb;border-radius:9px;font-size:13px;color:#374151;outline:none;box-sizing:border-box;transition:border .2s"
                       onfocus="this.style.borderColor='#6B0080'" onblur="this.style.borderColor='#e5e7eb'">
            </div>
 
            {{-- Action Buttons --}}
            <div style="display:flex;gap:10px">
                <button type="button"
                        onclick="document.getElementById('modalTambahPengguna').style.display='none'"
                        style="flex:1;padding:11px;border-radius:9px;border:1.5px solid #e5e7eb;background:white;font-size:13px;font-weight:700;color:#374151;cursor:pointer;transition:background .2s"
                        onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='white'">
                    Batal
                </button>
                <button type="submit"
                        style="flex:2;padding:11px;border-radius:9px;border:none;background:#6B0080;color:white;font-size:13px;font-weight:700;cursor:pointer;transition:background .2s;display:flex;align-items:center;justify-content:center;gap:7px"
                        onmouseover="this.style.background='#550066'" onmouseout="this.style.background='#6B0080'">
                    <svg style="width:15px;height:15px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Pengguna
                </button>
            </div>
        </form>
    </div>
</div>
 
<style>
@keyframes slideUp {
    from { opacity:0; transform:translateY(16px) scale(.98); }
    to   { opacity:1; transform:translateY(0)    scale(1);   }
}
</style>
 
<script>
function toggleOrganisasiField(role) {
    document.getElementById('fieldOrganisasi').style.display = role === 'panitia' ? 'block' : 'none';
}
function togglePassword() {
    const input = document.getElementById('inputPassword');
    input.type = input.type === 'password' ? 'text' : 'password';
}
</script>
 
@endsection
