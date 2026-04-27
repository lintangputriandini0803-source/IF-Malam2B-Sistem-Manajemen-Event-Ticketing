@extends('layouts.admin')
@section('title', 'Pengaturan Sistem')
@section('page-title', 'Pengaturan Sistem')

@section('content')

<div style="max-width:720px">

    {{-- TABS --}}
    <div style="display:flex;gap:4px;background:white;padding:6px;border-radius:12px;margin-bottom:20px;width:fit-content">
        <button class="tab-btn active" onclick="switchTab('platform', this)">🌐 Platform</button>
        <button class="tab-btn" onclick="switchTab('profil', this)">👤 Profil Admin</button>
        <button class="tab-btn" onclick="switchTab('keamanan', this)">🔒 Keamanan</button>
    </div>

    {{-- TAB: PLATFORM --}}
    <div id="tab-platform">

        {{-- GENERAL --}}
        <div style="background:white;border-radius:14px;padding:24px;margin-bottom:16px">
            <h2 style="font-size:15px;font-weight:700;color:#111;margin-bottom:18px;padding-bottom:12px;border-bottom:1px solid #f3f4f6">
                ⚙️ Pengaturan Umum
            </h2>
            <form method="POST" action="{{ route('admin.settings.update') }}">
                @csrf @method('PUT')

                <div style="margin-bottom:14px">
                    <label style="display:block;font-size:12px;font-weight:700;color:#374151;margin-bottom:6px;text-transform:uppercase;letter-spacing:.05em">Nama Platform</label>
                    <input type="text" name="app_name" value="{{ config('app.name', 'SIMETIX') }}"
                           style="width:100%;padding:11px 14px;border:1.5px solid #e5e7eb;border-radius:10px;font-size:14px;outline:none;box-sizing:border-box"
                           onfocus="this.style.borderColor='#6B0080'" onblur="this.style.borderColor='#e5e7eb'">
                </div>

                <div style="margin-bottom:14px">
                    <label style="display:block;font-size:12px;font-weight:700;color:#374151;margin-bottom:6px;text-transform:uppercase;letter-spacing:.05em">Email Kontak</label>
                    <input type="email" name="contact_email" value="{{ $settings['contact_email'] ?? '' }}"
                           placeholder="admin@simetix.id"
                           style="width:100%;padding:11px 14px;border:1.5px solid #e5e7eb;border-radius:10px;font-size:14px;outline:none;box-sizing:border-box"
                           onfocus="this.style.borderColor='#6B0080'" onblur="this.style.borderColor='#e5e7eb'">
                </div>

                <div style="margin-bottom:20px">
                    <label style="display:block;font-size:12px;font-weight:700;color:#374151;margin-bottom:6px;text-transform:uppercase;letter-spacing:.05em">Deskripsi Platform</label>
                    <textarea name="app_description" rows="3"
                              style="width:100%;padding:11px 14px;border:1.5px solid #e5e7eb;border-radius:10px;font-size:14px;outline:none;resize:vertical;box-sizing:border-box;font-family:inherit"
                              onfocus="this.style.borderColor='#6B0080'" onblur="this.style.borderColor='#e5e7eb'">{{ $settings['app_description'] ?? 'Platform event & ticketing Politeknik Batam' }}</textarea>
                </div>

                <button type="submit" style="padding:11px 24px;background:#6B0080;color:white;border:none;border-radius:10px;font-size:14px;font-weight:700;cursor:pointer">
                    Simpan Pengaturan
                </button>
            </form>
        </div>

        {{-- FITUR TOGGLE --}}
        <div style="background:white;border-radius:14px;padding:24px">
            <h2 style="font-size:15px;font-weight:700;color:#111;margin-bottom:18px;padding-bottom:12px;border-bottom:1px solid #f3f4f6">
                🔧 Fitur Platform
            </h2>
            @php
            $features = [
                ['key'=>'feature_registration', 'label'=>'Pendaftaran Publik',    'desc'=>'Izinkan pengguna baru untuk mendaftar'],
                ['key'=>'feature_payment',       'label'=>'Pembayaran Online',     'desc'=>'Aktifkan fitur pembelian tiket online'],
                ['key'=>'feature_panitia_reg',   'label'=>'Daftar sebagai Panitia','desc'=>'Izinkan pengguna mendaftar sebagai panitia'],
                ['key'=>'feature_maintenance',   'label'=>'Mode Maintenance',      'desc'=>'Tampilkan halaman maintenance untuk pengunjung'],
            ];
            @endphp
            @foreach($features as $f)
            <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 0;border-bottom:1px solid #f3f4f6">
                <div>
                    <p style="font-size:14px;font-weight:600;color:#111">{{ $f['label'] }}</p>
                    <p style="font-size:12px;color:#9ca3af;margin-top:2px">{{ $f['desc'] }}</p>
                </div>
                <label style="position:relative;display:inline-block;width:44px;height:24px;cursor:pointer;flex-shrink:0">
                    <input type="checkbox" name="{{ $f['key'] }}" class="hidden toggle-cb" {{ $settings[$f['key']] ?? true ? 'checked' : '' }}>
                    <span class="toggle-ui" data-key="{{ $f['key'] }}" onclick="toggleFeature(this)"
                          style="position:absolute;inset:0;border-radius:12px;background:{{ $settings[$f['key']] ?? true ? '#6B0080' : '#e5e7eb' }};transition:.2s;cursor:pointer">
                        <span style="position:absolute;top:3px;left:{{ $settings[$f['key']] ?? true ? '23px' : '3px' }};width:18px;height:18px;border-radius:50%;background:white;transition:.2s;box-shadow:0 1px 3px rgba(0,0,0,.2)"></span>
                    </span>
                </label>
            </div>
            @endforeach
        </div>
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
