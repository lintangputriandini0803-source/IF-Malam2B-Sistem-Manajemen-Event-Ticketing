<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - SIMETIX</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
    <style>
        button, a, .cursor-pointer { cursor: pointer; }
        body { background: #f3eef8; }
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
        <div id="step-dot-1" class="w-7 h-7 rounded-full step-active flex items-center justify-center text-xs font-bold">1</div>
        <span id="step-label-1" class="text-xs font-medium text-[#6B0080] hidden sm:block">Detail</span>
        <div id="step-line-12" class="w-12 h-0.5 step-line-inactive"></div>
        <div id="step-dot-2" class="w-7 h-7 rounded-full step-inactive flex items-center justify-center text-xs font-bold">2</div>
        <span id="step-label-2" class="text-xs font-medium text-gray-400 hidden sm:block">Pembayaran</span>
        <div id="step-line-23" class="w-12 h-0.5 step-line-inactive"></div>
        <div id="step-dot-3" class="w-7 h-7 rounded-full step-inactive flex items-center justify-center text-xs font-bold">3</div>
        <span id="step-label-3" class="text-xs font-medium text-gray-400 hidden sm:block">Ringkasan</span>
    </div>

    <div class="text-sm text-gray-500">Sisa Waktu : <span id="timer" class="font-bold text-gray-700">15 : 00</span></div>
</div>

<!-- CONTENT -->
<div class="max-w-2xl mx-auto px-4 py-8">

    {{-- ═══════════════════════════════════════════════════════
         STEP 1: DETAIL (Ringkasan + Data Pembeli + Metode)
    ═══════════════════════════════════════════════════════ --}}
    <div id="page-1">

        {{-- Ringkasan Pemesanan --}}
        <div class="bg-white rounded-2xl shadow p-5 mb-6">
            <h3 class="font-bold text-base mb-4">Ringkasan Pemesanan</h3>
            <div class="flex gap-3 mb-4 pb-4 border-b border-gray-100">
                @if($event->poster)
                <img src="{{ asset('poster/' . $event->poster) }}" class="w-16 h-16 rounded-xl object-cover flex-shrink-0">
                @endif
                <div>
                    <p class="font-bold text-sm">{{ strtoupper($event->title) }}</p>
                    <p class="text-xs text-gray-500 mt-1">📅 {{ $event->event_date }}</p>
                    <p class="text-xs text-gray-500">📍 {{ $event->location }}</p>
                </div>
            </div>
            @foreach($selectedTickets as $item)
            <div class="flex justify-between items-center py-2">
                <span class="text-sm text-gray-700">{{ strtoupper($item['ticket']->name) }}
                    <span class="text-gray-400">x {{ $item['qty'] }}</span>
                </span>
                <span class="text-sm font-semibold">
                    @if($item['ticket']->price > 0)
                        Rp. {{ number_format($item['ticket']->price * $item['qty'], 0, ',', '.') }}
                    @else
                        Gratis
                    @endif
                </span>
            </div>
            @endforeach
            <div class="border-t border-gray-100 mt-3 pt-3 flex justify-between">
                <span class="font-bold">Total</span>
                <span class="font-bold text-[#6B0080]">Rp. {{ number_format($totalPrice, 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- Ringkasan Pembeli (Penanggung Jawab) --}}
        <div class="bg-white rounded-2xl shadow p-5 mb-6">
            <h3 class="font-bold text-base mb-1">Ringkasan Pembeli</h3>
            <p class="text-xs text-gray-400 mb-5">Tiket akan kami kirim ke email anda</p>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama <span class="text-red-500">*</span></label>
                    <input type="text" id="buyer-name" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-purple-500 focus:border-purple-500"
                           placeholder="Nama lengkap">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">NIM / NIK <span class="text-red-500">*</span></label>
                    <input type="text" id="buyer-nim" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-purple-500 focus:border-purple-500"
                           placeholder="NIM atau NIK">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                    <input type="email" id="buyer-email" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-purple-500 focus:border-purple-500"
                           placeholder="email@contoh.com">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">No HP <span class="text-red-500">*</span></label>
                    <input type="text" id="buyer-phone" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-purple-500 focus:border-purple-500"
                           placeholder="08xxxxxxxxxx">
                </div>
            </div>
        </div>

        {{-- Metode Pembayaran --}}
        <div class="bg-white rounded-2xl shadow p-5 mb-6">
            <div class="flex items-center gap-3 mb-4">
                <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
                <span class="font-semibold">Bank Transfer</span>
            </div>
            @foreach([
                ['value' => 'bca',     'label' => 'BCA Virtual Account',     'badge' => 'BCA',   'color' => 'text-blue-700'],
                ['value' => 'mandiri', 'label' => 'Mandiri Virtual Account', 'badge' => 'MDR',   'color' => 'text-yellow-600'],
                ['value' => 'bni',     'label' => 'BNI Virtual Account',     'badge' => 'BNI',   'color' => 'text-orange-600'],
                ['value' => 'bri',     'label' => 'BRI Virtual Account',     'badge' => 'BRI',   'color' => 'text-blue-800'],
                ['value' => 'permata', 'label' => 'Permata Virtual Account', 'badge' => 'PMT',   'color' => 'text-red-600'],
            ] as $bank)
            <label class="flex items-center gap-3 py-3 cursor-pointer border-b border-gray-50 last:border-0">
                <input type="radio" name="payment_opt" value="{{ $bank['value'] }}"
                       class="w-4 h-4 accent-purple-700">
                <span class="text-xs font-bold {{ $bank['color'] }} bg-gray-100 px-2 py-1 rounded w-12 text-center">{{ $bank['badge'] }}</span>
                <span class="text-sm text-gray-700">{{ $bank['label'] }}</span>
            </label>
            @endforeach
        </div>

        <button onclick="validateAndNext()"
                class="w-full bg-[#6B0080] hover:bg-purple-700 text-white font-bold py-3 rounded-xl transition mt-2">
            Lanjutkan Pembayaran
        </button>
    </div>

    {{-- ═══════════════════════════════════════════════════════
         STEP 2: PEMBAYARAN (Virtual Account)
    ═══════════════════════════════════════════════════════ --}}
    <div id="page-2" class="hidden">

        {{-- Form tersembunyi untuk submit ke backend --}}
        <form id="process-form" action="{{ route('checkout.process', $event->slug) }}" method="POST">
            @csrf
            <input type="hidden" name="buyer_name"     id="f-name">
            <input type="hidden" name="buyer_nim"      id="f-nim">
            <input type="hidden" name="buyer_email"    id="f-email">
            <input type="hidden" name="buyer_phone"    id="f-phone">
            <input type="hidden" name="payment_method" id="f-payment">
            @foreach($selectedTickets as $item)
            <input type="hidden" name="tickets[{{ $loop->index }}][id]"  value="{{ $item['ticket']->id }}">
            <input type="hidden" name="tickets[{{ $loop->index }}][qty]" value="{{ $item['qty'] }}">
            @endforeach
        </form>

        <div class="bg-white rounded-2xl shadow p-8 mb-6 text-center">
            {{-- Logo --}}
            <div class="w-20 h-20 bg-[#6B0080] rounded-full flex items-center justify-center mx-auto mb-4">
                <img src="{{ asset('img/logo.png') }}" class="h-12 w-12 object-contain filter brightness-0 invert">
            </div>

            <h3 class="text-[#6B0080] font-bold text-xl mb-2">Menunggu Pembayaran</h3>
            <p class="text-sm text-gray-600 mb-1">Hampir Selesai!!</p>
            <p class="text-sm text-gray-600 mb-6">Yuk Selesaikan Pembayaran Ticketmu<br>menggunakan Virtual Account di bawah ini</p>

            {{-- Total --}}
            <div class="bg-purple-50 rounded-xl p-4 mb-6">
                <p class="text-sm font-semibold text-gray-700 mb-1">Total Pembayaran:</p>
                <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalPrice, 0, ',', '.') }}</p>
            </div>

            {{-- VA Number --}}
            <p class="text-[#6B0080] font-semibold text-sm mb-3 text-left">Transfer Ke Virtual Account</p>
            <div class="flex items-center justify-between border-2 border-gray-200 rounded-xl px-4 py-3 mb-3">
                <div class="flex items-center gap-3">
                    <span id="va-bank-label" class="text-sm font-bold text-blue-700 bg-blue-50 px-3 py-1 rounded-lg">BCA</span>
                    <span id="va-number-display" class="font-mono font-semibold text-gray-800 text-base tracking-wide">-</span>
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
            <p id="copy-success" class="text-xs text-green-600 hidden">✓ Nomor disalin!</p>
        </div>

        <button type="button" onclick="submitAndFinish()"
                class="w-full bg-[#6B0080] hover:bg-purple-700 text-white font-bold py-3 rounded-xl transition">
            Ringkasan Pemesanan
        </button>
    </div>

</div>

<script>
// ─── DATA TIKET (untuk form hidden) ──────────────────────────────────────────
const selectedTickets = @json(array_map(fn($i) => ['id' => $i['ticket']->id, 'qty' => $i['qty']], $selectedTickets));

// ─── GENERATE VA DI SISI CLIENT (preview) ────────────────────────────────────
const vaMap = {
    bca:     { label: 'BCA',   prefix: '126' },
    mandiri: { label: 'MDR',   prefix: '888' },
    bni:     { label: 'BNI',   prefix: '988' },
    bri:     { label: 'BRI',   prefix: '002' },
    permata: { label: 'PMT',   prefix: '013' },
};

function generateVANumber(bank) {
    const p = vaMap[bank]?.prefix || '000';
    const r1 = String(Math.floor(Math.random()*9000)+1000);
    const r2 = String(Math.floor(Math.random()*900000)+100000);
    return p + ' ' + r1 + ' ' + r2;
}

// ─── VALIDASI STEP 1 → STEP 2 ────────────────────────────────────────────────
function validateAndNext() {
    const name  = document.getElementById('buyer-name').value.trim();
    const nim   = document.getElementById('buyer-nim').value.trim();
    const email = document.getElementById('buyer-email').value.trim();
    const phone = document.getElementById('buyer-phone').value.trim();
    const payEl = document.querySelector('input[name="payment_opt"]:checked');

    if (!name || !nim || !email || !phone) {
        alert('Lengkapi semua data pembeli terlebih dahulu.');
        return;
    }
    if (!payEl) {
        alert('Pilih metode pembayaran terlebih dahulu.');
        return;
    }

    // Isi form hidden
    document.getElementById('f-name').value    = name;
    document.getElementById('f-nim').value     = nim;
    document.getElementById('f-email').value   = email;
    document.getElementById('f-phone').value   = phone;
    document.getElementById('f-payment').value = payEl.value;

    // Tampilkan VA preview
    const vaNum = generateVANumber(payEl.value);
    document.getElementById('va-bank-label').textContent   = vaMap[payEl.value]?.label || payEl.value.toUpperCase();
    document.getElementById('va-number-display').textContent = vaNum;

    goToStep(2);
}

function copyVA() {
    const va = document.getElementById('va-number-display').textContent;
    navigator.clipboard.writeText(va).then(() => {
        const el = document.getElementById('copy-success');
        el.classList.remove('hidden');
        setTimeout(() => el.classList.add('hidden'), 2000);
    });
}

function submitAndFinish() {
    document.getElementById('process-form').submit();
}

// ─── STEP NAVIGATION ─────────────────────────────────────────────────────────
function goToStep(step) {
    [1, 2].forEach(s => {
        document.getElementById('page-' + s).classList.add('hidden');
        document.getElementById('step-dot-' + s).className =
            'w-7 h-7 rounded-full step-inactive flex items-center justify-center text-xs font-bold';
        document.getElementById('step-label-' + s).className =
            'text-xs font-medium text-gray-400 hidden sm:block';
    });
    document.getElementById('page-' + step).classList.remove('hidden');
    for (let s = 1; s <= step; s++) {
        document.getElementById('step-dot-' + s).className =
            'w-7 h-7 rounded-full step-done flex items-center justify-center text-xs font-bold';
        document.getElementById('step-label-' + s).className =
            'text-xs font-medium text-[#6B0080] hidden sm:block';
    }
    document.getElementById('step-line-12').className =
        'w-12 h-0.5 ' + (step >= 2 ? 'step-line-active' : 'step-line-inactive');
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// ─── TIMER ────────────────────────────────────────────────────────────────────
let seconds = 15 * 60;
const timerEl = document.getElementById('timer');
setInterval(() => {
    if (seconds <= 0) {
        timerEl.textContent = '00 : 00';
        alert('Waktu pemesanan habis!');
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
