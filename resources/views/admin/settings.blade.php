@extends('layouts.admin')
@section('title', 'Pengaturan Sistem')
@section('page-title', 'Pengaturan Sistem')

@section('content')

<div style="max-width:720px">

    {{-- TABS --}}
    <div style="display:flex;gap:4px;background:white;padding:6px;border-radius:12px;margin-bottom:20px;width:fit-content">

        <button class="tab-btn" onclick="switchTab('profil', this)">👤 Profil Admin</button>
        <button class="tab-btn" onclick="switchTab('keamanan', this)">🔒 Keamanan</button>
    </div>

    {{-- TAB: PLATFORM --}}
    <div id="tab-platform">

        {{-- GENERAL --}}


        {{-- FITUR TOGGLE --}}

    </div>

    {{-- TAB: PROFIL --}}
    <div id="tab-profil" style="display:none">
        <div style="background:white;border-radius:14px;padding:24px">
            <h2 style="font-size:15px;font-weight:700;color:#111;margin-bottom:20px;padding-bottom:12px;border-bottom:1px solid #f3f4f6">Profil Admin</h2>

            <div style="display:flex;align-items:center;gap:16px;margin-bottom:24px">
                <div style="width:72px;height:72px;border-radius:50%;background:#6B0080;display:flex;align-items:center;justify-content:center;font-size:28px;font-weight:800;color:white">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>
                <div>
                    <p style="font-size:15px;font-weight:700;color:#111">{{ auth()->user()->name }}</p>
                    <p style="font-size:12px;color:#9ca3af">{{ auth()->user()->email }}</p>
                    <span style="font-size:11px;font-weight:700;padding:2px 8px;background:#f5eeff;color:#6B0080;border-radius:4px;margin-top:4px;display:inline-block">Super Admin</span>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.settings.profile') }}">
                @csrf @method('PUT')
                <div style="margin-bottom:14px">
                    <label style="display:block;font-size:12px;font-weight:700;color:#374151;margin-bottom:6px;text-transform:uppercase;letter-spacing:.05em">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ auth()->user()->name }}"
                           style="width:100%;padding:11px 14px;border:1.5px solid #e5e7eb;border-radius:10px;font-size:14px;outline:none;box-sizing:border-box"
                           onfocus="this.style.borderColor='#6B0080'" onblur="this.style.borderColor='#e5e7eb'">
                </div>
                <div style="margin-bottom:20px">
                    <label style="display:block;font-size:12px;font-weight:700;color:#374151;margin-bottom:6px;text-transform:uppercase;letter-spacing:.05em">Email</label>
                    <input type="email" name="email" value="{{ auth()->user()->email }}"
                           style="width:100%;padding:11px 14px;border:1.5px solid #e5e7eb;border-radius:10px;font-size:14px;outline:none;box-sizing:border-box"
                           onfocus="this.style.borderColor='#6B0080'" onblur="this.style.borderColor='#e5e7eb'">
                </div>
                <button type="submit" style="padding:11px 24px;background:#6B0080;color:white;border:none;border-radius:10px;font-size:14px;font-weight:700;cursor:pointer">
                    Simpan Profil
                </button>
            </form>
        </div>
    </div>

    {{-- TAB: KEAMANAN --}}
    <div id="tab-keamanan" style="display:none">
        <div style="background:white;border-radius:14px;padding:24px">
            <h2 style="font-size:15px;font-weight:700;color:#111;margin-bottom:20px;padding-bottom:12px;border-bottom:1px solid #f3f4f6">Ubah Password</h2>
            <form method="POST" action="{{ route('admin.settings.password') }}">
                @csrf @method('PUT')
                <div style="margin-bottom:14px">
                    <label style="display:block;font-size:12px;font-weight:700;color:#374151;margin-bottom:6px;text-transform:uppercase;letter-spacing:.05em">Password Saat Ini</label>
                    <input type="password" name="current_password"
                           style="width:100%;padding:11px 14px;border:1.5px solid #e5e7eb;border-radius:10px;font-size:14px;outline:none;box-sizing:border-box"
                           onfocus="this.style.borderColor='#6B0080'" onblur="this.style.borderColor='#e5e7eb'">
                </div>
                <div style="margin-bottom:14px">
                    <label style="display:block;font-size:12px;font-weight:700;color:#374151;margin-bottom:6px;text-transform:uppercase;letter-spacing:.05em">Password Baru</label>
                    <input type="password" name="password"
                           style="width:100%;padding:11px 14px;border:1.5px solid #e5e7eb;border-radius:10px;font-size:14px;outline:none;box-sizing:border-box"
                           onfocus="this.style.borderColor='#6B0080'" onblur="this.style.borderColor='#e5e7eb'">
                </div>
                <div style="margin-bottom:20px">
                    <label style="display:block;font-size:12px;font-weight:700;color:#374151;margin-bottom:6px;text-transform:uppercase;letter-spacing:.05em">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation"
                           style="width:100%;padding:11px 14px;border:1.5px solid #e5e7eb;border-radius:10px;font-size:14px;outline:none;box-sizing:border-box"
                           onfocus="this.style.borderColor='#6B0080'" onblur="this.style.borderColor='#e5e7eb'">
                </div>
                <button type="submit" style="padding:11px 24px;background:#6B0080;color:white;border:none;border-radius:10px;font-size:14px;font-weight:700;cursor:pointer">
                    Ubah Password
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function switchTab(name, btn) {
    ['platform','profil','keamanan'].forEach(t => {
        document.getElementById('tab-'+t).style.display = t === name ? 'block' : 'none';
    });
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
}

function toggleFeature(el) {
    const on = el.style.background === 'rgb(107, 0, 128)';
    el.style.background = on ? '#e5e7eb' : '#6B0080';
    const knob = el.querySelector('span');
    knob.style.left = on ? '3px' : '23px';
}
</script>
@endpush

@endsection
