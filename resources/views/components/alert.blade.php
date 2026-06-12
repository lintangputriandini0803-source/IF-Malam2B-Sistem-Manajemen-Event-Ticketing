{{--
    COMPONENT: alert
    Notifikasi inline — tampil di dalam halaman, biasanya di atas form.
    Cocok untuk: error validasi, peringatan, info penting di halaman tertentu.

    CARA PAKAI:
      <x-alert type="error"   message="Email atau password salah." />
      <x-alert type="warning" message="Sesi hampir habis." />
      <x-alert type="info"    message="Kamu sudah login sebelumnya." />
      <x-alert type="success" message="Data berhasil disimpan." />

    Dengan list error Laravel:
      <x-alert type="error" :errors="$errors" />

    PROPS:
      type    : success | error | warning | info
      message : string (opsional jika pakai :errors)
      errors  : $errors dari Laravel (MessageBag), opsional
      dismissible : true | false (default true)
--}}

@php
    $type        = $type        ?? 'info';
    $message     = $message     ?? null;
    $errors      = $errors      ?? null;
    $dismissible = $dismissible ?? true;

    $cfg = [
        'success' => ['bg'=>'#f0fdf4','border'=>'#86efac','icon_color'=>'#16a34a','text'=>'#15803d','label'=>'Berhasil'],
        'error'   => ['bg'=>'#fef2f2','border'=>'#fca5a5','icon_color'=>'#dc2626','text'=>'#b91c1c','label'=>'Terjadi Kesalahan'],
        'warning' => ['bg'=>'#fffbeb','border'=>'#fcd34d','icon_color'=>'#d97706','text'=>'#b45309','label'=>'Perhatian'],
        'info'    => ['bg'=>'#eff6ff','border'=>'#93c5fd','icon_color'=>'#2563eb','text'=>'#1d4ed8','label'=>'Informasi'],
    ];
    $c = $cfg[$type] ?? $cfg['info'];

    $icons = [
        'success' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>',
        'error'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>',
        'warning' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>',
        'info'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
    ];
    $icon     = $icons[$type] ?? $icons['info'];
    $hasError = $errors && $errors->any();
    $uniqueId = 'alert-' . uniqid();
@endphp

@if ($message || $hasError)
<div id="{{ $uniqueId }}"
     style="background:{{ $c['bg'] }};border:1.5px solid {{ $c['border'] }};border-radius:12px;
            padding:14px 16px;display:flex;align-items:flex-start;gap:12px;margin-bottom:16px">

    <svg style="width:18px;height:18px;flex-shrink:0;margin-top:1px" fill="none" viewBox="0 0 24 24" stroke="{{ $c['icon_color'] }}">
        {!! $icon !!}
    </svg>

    <div style="flex:1;min-width:0">
        <p style="font-size:13px;font-weight:700;color:{{ $c['text'] }};margin:0 0 2px">{{ $c['label'] }}</p>

        @if ($message)
            <p style="font-size:13px;color:{{ $c['text'] }};opacity:0.85;margin:0">{{ $message }}</p>
        @endif

        @if ($hasError)
            <ul style="margin:4px 0 0;padding-left:16px;list-style:disc">
                @foreach ($errors->all() as $err)
                    <li style="font-size:13px;color:{{ $c['text'] }};opacity:0.85">{{ $err }}</li>
                @endforeach
            </ul>
        @endif
    </div>

    @if ($dismissible)
    <button onclick="document.getElementById('{{ $uniqueId }}').remove()" aria-label="Tutup"
            style="background:none;border:none;cursor:pointer;padding:2px;color:{{ $c['icon_color'] }};opacity:0.5;flex-shrink:0">
        <svg style="width:15px;height:15px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
    @endif
</div>
@endif
