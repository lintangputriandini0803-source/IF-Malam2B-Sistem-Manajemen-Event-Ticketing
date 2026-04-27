@extends('layouts.panitia')
@section('title', 'Pengaturan')
@section('page-title', 'Pengaturan Akun')

@section('content')

<div style="max-width:680px">

    {{-- TABS --}}
    <div style="display:flex;gap:4px;background:white;padding:6px;border-radius:12px;margin-bottom:20px;width:fit-content">
        <button class="tab-btn active" onclick="switchTab('profil', this)">👤 Profil</button>
        <button class="tab-btn" onclick="switchTab('keamanan', this)">🔒 Keamanan</button>
        <button class="tab-btn" onclick="switchTab('notifikasi', this)">🔔 Notifikasi</button>
    </div>

    {{-- TAB: PROFIL --}}
    <div id="tab-profil">
        <div style="background:white;border-radius:14px;padding:24px;margin-bottom:16px">
            <h2 style="font-size:15px;font-weight:700;color:#111;margin-bottom:18px;padding-bottom:12px;border-bottom:1px solid #f3f4f6">
                Informasi Profil
            </h2>

            {{-- AVATAR --}}
            <div style="display:flex;align-items:center;gap:16px;margin-bottom:24px">
                <div style="width:72px;height:72px;border-radius:50%;background:#6B0080;display:flex;align-items:center;justify-content:center;font-size:28px;font-weight:800;color:white;flex-shrink:0">
                    {{ strtoupper(substr(auth()->user()->name ?? 'P', 0, 1)) }}
                </div>
                <div>
                    <p style="font-size:14px;font-weight:700;color:#111">{{ auth()->user()->name ?? 'Nama Panitia' }}</p>
                    <p style="font-size:12px;color:#9ca3af;margin-top:2px">{{ auth()->user()->email ?? '' }}</p>
                    <p style="font-size:11px;color:#6B0080;margin-top:4px;font-weight:600">Panitia · {{ auth()->user()->organization ?? 'Organisasi' }}</p>
                </div>
            </div>

            <form method="POST" action="{{ route('panitia.settings.update') }}">
                @csrf @method('PUT')

                <div style="margin-bottom:14px">
                    <label style="display:block;font-size:12px;font-weight:700;color:#374151;margin-bottom:6px;text-transform:uppercase;letter-spacing:.05em">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}"
                           style="width:100%;padding:11px 14px;border:1.5px solid #e5e7eb;border-radius:10px;font-size:14px;color:#111;outline:none;box-sizing:border-box"
                           onfocus="this.style.borderColor='#6B0080'" onblur="this.style.borderColor='#e5e7eb'">
                </div>

                <div style="margin-bottom:14px">
                    <label style="display:block;font-size:12px;font-weight:700;color:#374151;margin-bottom:6px;text-transform:uppercase;letter-spacing:.05em">Email</label>
                    <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}"
                           style="width:100%;padding:11px 14px;border:1.5px solid #e5e7eb;border-radius:10px;font-size:14px;color:#111;outline:none;box-sizing:border-box"
                           onfocus="this.style.borderColor='#6B0080'" onblur="this.style.borderColor='#e5e7eb'">
                </div>

                <div style="margin-bottom:14px">
                    <label style="display:block;font-size:12px;font-weight:700;color:#374151;margin-bottom:6px;text-transform:uppercase;letter-spacing:.05em">Nama Organisasi</label>
                    <input type="text" name="organization" value="{{ old('organization', auth()->user()->organization ?? '') }}"
                           placeholder="Contoh: BEM Politeknik Batam"
                           style="width:100%;padding:11px 14px;border:1.5px solid #e5e7eb;border-radius:10px;font-size:14px;color:#111;outline:none;box-sizing:border-box"
                           onfocus="this.style.borderColor='#6B0080'" onblur="this.style.borderColor='#e5e7eb'">
                </div>

                <div style="margin-bottom:20px">
                    <label style="display:block;font-size:12px;font-weight:700;color:#374151;margin-bottom:6px;text-transform:uppercase;letter-spacing:.05em">Nomor HP</label>
                    <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone ?? '') }}"
                           placeholder="+62..."
                           style="width:100%;padding:11px 14px;border:1.5px solid #e5e7eb;border-radius:10px;font-size:14px;color:#111;outline:none;box-sizing:border-box"
                           onfocus="this.style.borderColor='#6B0080'" onblur="this.style.borderColor='#e5e7eb'">
                </div>

                <button type="submit"
                        style="padding:11px 24px;background:#6B0080;color:white;border:none;border-radius:10px;font-size:14px;font-weight:700;cursor:pointer">
                    Simpan Perubahan
                </button>
            </form>
        </div>
    </div>

    {{-- TAB: KEAMANAN --}}
    <div id="tab-keamanan" style="display:none">
        <div style="background:white;border-radius:14px;padding:24px;margin-bottom:16px">
            <h2 style="font-size:15px;font-weight:700;color:#111;margin-bottom:18px;padding-bottom:12px;border-bottom:1px solid #f3f4f6">
                Ubah Password
            </h2>

            <form method="POST" action="{{ route('panitia.settings.password') }}">
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
                <button type="submit"
                        style="padding:11px 24px;background:#6B0080;color:white;border:none;border-radius:10px;font-size:14px;font-weight:700;cursor:pointer">
                    Ubah Password
                </button>
            </form>
        </div>
    </div>

    {{-- TAB: NOTIFIKASI --}}
    <div id="tab-notifikasi" style="display:none">
        <div style="background:white;border-radius:14px;padding:24px">
            <h2 style="font-size:15px;font-weight:700;color:#111;margin-bottom:18px;padding-bottom:12px;border-bottom:1px solid #f3f4f6">
                Preferensi Notifikasi
            </h2>

            <form method="POST" action="{{ route('panitia.settings.notifications') }}">
                @csrf @method('PUT')

                @php
                $notifs = [
                    ['key'=>'notif_ticket',   'label'=>'Tiket Terjual',      'desc'=>'Notifikasi setiap ada tiket terjual'],
                    ['key'=>'notif_cancel',   'label'=>'Pembatalan Tiket',   'desc'=>'Notifikasi jika peserta membatalkan tiket'],
                    ['key'=>'notif_event',    'label'=>'Update Event',        'desc'=>'Notifikasi status perubahan event'],
                    ['key'=>'notif_weekly',   'label'=>'Laporan Mingguan',   'desc'=>'Ringkasan performa event tiap minggu'],
                ];
                @endphp

                @foreach($notifs as $n)
                <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 0;border-bottom:1px solid #f3f4f6">
                    <div>
                        <p style="font-size:14px;font-weight:600;color:#111">{{ $n['label'] }}</p>
                        <p style="font-size:12px;color:#9ca3af;margin-top:2px">{{ $n['desc'] }}</p>
                    </div>
                    <label style="position:relative;display:inline-block;width:44px;height:24px;cursor:pointer">
                        <input type="checkbox" name="{{ $n['key'] }}" value="1" class="hidden"
                               {{ auth()->user()->{$n['key']} ?? true ? 'checked' : '' }}>
                        <span id="toggle-{{ $n['key'] }}" onclick="toggleSwitch(this)"
                              style="position:absolute;inset:0;border-radius:12px;background:#e5e7eb;transition:.2s;cursor:pointer">
                            <span style="position:absolute;top:3px;left:3px;width:18px;height:18px;border-radius:50%;background:white;transition:.2s;box-shadow:0 1px 3px rgba(0,0,0,.2)"></span>
                        </span>
                    </label>
                </div>
                @endforeach

                <button type="submit" style="margin-top:20px;padding:11px 24px;background:#6B0080;color:white;border:none;border-radius:10px;font-size:14px;font-weight:700;cursor:pointer">
                    Simpan Preferensi
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function switchTab(name, btn) {
    ['profil','keamanan','notifikasi'].forEach(t => {
        document.getElementById('tab-'+t).style.display = t === name ? 'block' : 'none';
    });
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
}

function toggleSwitch(el) {
    const on = el.style.background === 'rgb(107, 0, 128)';
    el.style.background = on ? '#e5e7eb' : '#6B0080';
    const knob = el.querySelector('span');
    knob.style.transform = on ? 'translateX(0)' : 'translateX(20px)';
}

// Init toggles
document.querySelectorAll('input[type=checkbox]').forEach(cb => {
    const toggle = document.getElementById('toggle-'+cb.name);
    if (cb.checked && toggle) {
        toggle.style.background = '#6B0080';
        toggle.querySelector('span').style.transform = 'translateX(20px)';
    }
});
</script>
@endpush

@endsection
