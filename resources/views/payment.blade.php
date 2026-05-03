<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - SIMETIX</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
    <style>
        body { background: #f3eef8; }
        button, a { cursor: pointer; }
        .step-active   { background: #6B0080; color: white; }
        .step-done     { background: #6B0080; color: white; }
        .step-inactive { background: #e5e7eb; color: #9ca3af; }
        .step-line-active   { background: #6B0080; }
        .step-line-inactive { background: #e5e7eb; }
    </style>
</head>
<body class="min-h-screen">

<!-- TOPBAR -->
<div class="flex items-center justify-between px-6 py-4 bg-white shadow-sm sticky top-0 z-10">
    <a href="{{ route('event.show', $event->slug) }}" class="flex items-center gap-2 text-sm text-gray-600 hover:text-[#6B0080]">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 14 10">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5H1m0 0 4 4M1 5l4-4"/>
        </svg>
        Kembali
    </a>

    <!-- STEP INDICATOR -->
    <div class="flex items-center gap-2">
        <div class="w-7 h-7 rounded-full step-done flex items-center justify-center text-xs font-bold">1</div>
        <span class="text-xs font-medium text-[#6B0080] hidden sm:block">Detail</span>
        <div class="w-12 h-0.5 step-line-active"></div>
        <div class="w-7 h-7 rounded-full step-active flex items-center justify-center text-xs font-bold">2</div>
        <span class="text-xs font-medium text-[#6B0080] hidden sm:block">Pembayaran</span>
        <div class="w-12 h-0.5 step-line-inactive"></div>
        <div class="w-7 h-7 rounded-full step-inactive flex items-center justify-center text-xs font-bold">3</div>
        <span class="text-xs font-medium text-gray-400 hidden sm:block">Ringkasan</span>
    </div>

    <div class="text-sm text-gray-500">Sisa Waktu : <span id="timer" class="font-bold text-gray-700">15 : 00</span></div>
</div>

<!-- CONTENT -->
<div class="max-w-2xl mx-auto px-4 py-8">

    <div class="bg-white rounded-2xl shadow p-8 mb-6 text-center">
        {{-- Logo --}}
        <div class="w-20 h-20 bg-[#6B0080] rounded-full flex items-center justify-center mx-auto mb-4">
            <img src="{{ asset('img/logo.png') }}" class="h-12 w-12 object-contain filter brightness-0 invert"
                 onerror="this.parentElement.innerHTML='<span class=\'text-white text-2xl font-bold\'>S</span>'">
        </div>

        <h3 class="text-[#6B0080] font-bold text-xl mb-2">Menunggu Pembayaran</h3>
        <p class="text-sm text-gray-600 mb-1">Hampir Selesai!!</p>
        <p class="text-sm text-gray-600 mb-6">
            Yuk Selesaikan Pembayaran Ticketmu<br>
            menggunakan Virtual Account di bawah ini
        </p>

        {{-- Total --}}
        <div class="bg-purple-50 rounded-xl p-4 mb-6">
            <p class="text-sm font-semibold text-gray-700 mb-1">Total Pembayaran:</p>
            <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalPrice, 0, ',', '.') }}</p>
        </div>

        {{-- VA Number --}}
        <p class="text-[#6B0080] font-semibold text-sm mb-3 text-left">Transfer Ke Virtual Account</p>

        @php
            $bankLabels = [
                'bca'     => ['label' => 'BCA',  'color' => 'text-blue-700 bg-blue-50'],
                'mandiri' => ['label' => 'MDR',  'color' => 'text-yellow-700 bg-yellow-50'],
                'bni'     => ['label' => 'BNI',  'color' => 'text-orange-700 bg-orange-50'],
                'bri'     => ['label' => 'BRI',  'color' => 'text-blue-800 bg-blue-100'],
                'permata' => ['label' => 'PMT',  'color' => 'text-red-700 bg-red-50'],
            ];
            $bank = $bankLabels[strtolower($paymentMethod)] ?? ['label' => strtoupper($paymentMethod), 'color' => 'text-gray-700 bg-gray-100'];
        @endphp

        <div class="flex items-center justify-between border-2 border-gray-200 rounded-xl px-4 py-3 mb-3">
            <div class="flex items-center gap-3">
                <span class="text-sm font-bold px-3 py-1 rounded-lg {{ $bank['color'] }}">{{ $bank['label'] }}</span>
                <span class="font-mono font-semibold text-gray-800 text-base tracking-wide" id="va-number">{{ $vaNumber }}</span>
            </div>
            <button type="button" onclick="copyVA()" class="text-gray-400 hover:text-purple-600 transition" title="Salin">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
            </button>
        </div>

        <button type="button" onclick="copyVA()"
                class="w-full py-2.5 bg-purple-50 hover:bg-purple-100 text-[#6B0080] text-sm font-semibold rounded-xl transition mb-2">
            Salin Nomor Virtual Account
        </button>
        <p id="copy-success" class="text-xs text-green-600 hidden mb-2">✓ Nomor berhasil disalin!</p>
    </div>

    <a href="{{ route('checkout.summary', $event->slug) }}"
       class="block w-full text-center bg-[#6B0080] hover:bg-purple-700 text-white font-bold py-3 rounded-xl transition">
        Ringkasan Pemesanan
    </a>

</div>

<script>
function copyVA() {
    const va = document.getElementById('va-number').textContent.trim();
    navigator.clipboard.writeText(va).then(() => {
        const el = document.getElementById('copy-success');
        el.classList.remove('hidden');
        setTimeout(() => el.classList.add('hidden'), 2000);
    });
}

// ─── TIMER ────────────────────────────────────────────────────────────────────
let seconds = 15 * 60;
const timerEl = document.getElementById('timer');
setInterval(() => {
    if (seconds <= 0) {
        timerEl.textContent = '00 : 00';
        alert('Waktu pembayaran habis!');
        window.location.href = '{{ route("home") }}';
        return;
    }
    seconds--;
    const m = String(Math.floor(seconds / 60)).padStart(2, '0');
    const s = String(seconds % 60).padStart(2, '0');
    timerEl.textContent = m + ' : ' + s;
}, 1000);
</script>

</body>
</html>
