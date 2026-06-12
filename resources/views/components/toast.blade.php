{{--
    COMPONENT: toast
    Tampil otomatis di pojok kanan atas, auto-hilang setelah 4 detik.

    CARA PAKAI (dari controller):
      return redirect()->back()->with('success', 'Tiket berhasil dibeli!');
      return redirect()->back()->with('error', 'Email atau password salah.');
      return redirect()->back()->with('warning', 'Akun belum diverifikasi.');
      return redirect()->back()->with('info', 'Silakan cek email kamu.');

    CARA PAKAI (manual di blade):
      <x-toast type="success" message="Berhasil disimpan!" />

    PROPS:
      type    : success | error | warning | info  (default: info)
      message : string
--}}

@php
    $type    = $type    ?? null;
    $message = $message ?? null;

    // Ambil dari session jika tidak di-pass manual
    if (!$type && !$message) {
        if (session('success'))      { $type = 'success'; $message = session('success'); }
        elseif (session('error'))    { $type = 'error';   $message = session('error'); }
        elseif (session('login_error')) { $type = 'error'; $message = session('login_error'); }
        elseif (session('warning'))  { $type = 'warning'; $message = session('warning'); }
        elseif (session('info'))     { $type = 'info';    $message = session('info'); }
    }

    $styles = [
        'success' => ['bg' => '#f0fdf4', 'border' => '#86efac', 'icon_bg' => '#dcfce7', 'icon_color' => '#16a34a', 'text' => '#15803d'],
        'error'   => ['bg' => '#fef2f2', 'border' => '#fca5a5', 'icon_bg' => '#fee2e2', 'icon_color' => '#dc2626', 'text' => '#b91c1c'],
        'warning' => ['bg' => '#fffbeb', 'border' => '#fcd34d', 'icon_bg' => '#fef3c7', 'icon_color' => '#d97706', 'text' => '#b45309'],
        'info'    => ['bg' => '#eff6ff', 'border' => '#93c5fd', 'icon_bg' => '#dbeafe', 'icon_color' => '#2563eb', 'text' => '#1d4ed8'],
    ];
    $s = $styles[$type] ?? $styles['info'];

    $icons = [
        'success' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>',
        'error'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>',
        'warning' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>',
        'info'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
    ];
    $icon = $icons[$type] ?? $icons['info'];
@endphp

@if ($message)
<div id="simetix-toast"
     style="position:fixed;top:20px;right:20px;z-index:9999;max-width:360px;width:calc(100% - 40px);
            background:{{ $s['bg'] }};border:1.5px solid {{ $s['border'] }};border-radius:14px;
            padding:14px 16px;display:flex;align-items:flex-start;gap:12px;
            box-shadow:0 4px 24px rgba(0,0,0,0.10);
            animation:toastIn .3s cubic-bezier(.21,1.02,.73,1) forwards;">

    {{-- Icon --}}
    <div style="width:32px;height:32px;border-radius:50%;background:{{ $s['icon_bg'] }};
                display:flex;align-items:center;justify-content:center;flex-shrink:0">
        <svg style="width:16px;height:16px;color:{{ $s['icon_color'] }}" fill="none" viewBox="0 0 24 24" stroke="{{ $s['icon_color'] }}">
            {!! $icon !!}
        </svg>
    </div>

    {{-- Message --}}
    <div style="flex:1;min-width:0">
        <p style="font-size:13px;font-weight:700;color:{{ $s['text'] }};margin:0 0 2px">
            {{ ucfirst($type) === 'Login_error' ? 'Error' : ucfirst($type ?? 'Info') }}
        </p>
        <p style="font-size:13px;color:{{ $s['text'] }};opacity:0.85;margin:0;line-height:1.45">
            {{ $message }}
        </p>
    </div>

    {{-- Close button --}}
    <button onclick="closeToast()" aria-label="Tutup"
            style="background:none;border:none;cursor:pointer;padding:2px;color:{{ $s['icon_color'] }};opacity:0.6;flex-shrink:0">
        <svg style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>

    {{-- Progress bar --}}
    <div style="position:absolute;bottom:0;left:0;height:3px;border-radius:0 0 14px 14px;
                background:{{ $s['icon_color'] }};width:100%;
                animation:toastProgress 4s linear forwards;opacity:0.4"></div>
</div>

<style>
@keyframes toastIn {
    from { opacity:0; transform:translateX(40px) scale(.96); }
    to   { opacity:1; transform:translateX(0) scale(1); }
}
@keyframes toastOut {
    from { opacity:1; transform:translateX(0) scale(1); }
    to   { opacity:0; transform:translateX(40px) scale(.96); }
}
@keyframes toastProgress {
    from { width:100%; }
    to   { width:0%; }
}
</style>

<script>
function closeToast() {
    const t = document.getElementById('simetix-toast');
    if (t) { t.style.animation = 'toastOut .25s ease forwards'; setTimeout(() => t.remove(), 260); }
}
setTimeout(closeToast, 4000);
</script>
@endif
