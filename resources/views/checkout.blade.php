<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - SIMETIX</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
    <style>
        body { background: #f3eef8; }
        .step-active { background: #8A008A; color: white; }
        .step-done { background: #8A008A; color: white; }
        .step-inactive { background: #e5e7eb; color: #9ca3af; }
        .step-line-active { background: #8A008A; }
        .step-line-inactive { background: #e5e7eb; }
    </style>
</head>
<body class="min-h-screen">

<!-- TOPBAR -->
<div class="flex items-center justify-between px-6 py-4 bg-white shadow-sm sticky top-0 z-10">
    <a href="{{ route('event.show', $event->slug) }}" class="flex items-center gap-2 text-sm text-gray-600 hover:text-[#8A008A]">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 14 10">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5H1m0 0 4 4M1 5l4-4"/>
        </svg>
        Kembali
    </a>

    <!-- STEP INDICATOR -->
    <div class="flex items-center gap-2">
        <!-- Step 1 -->
        <div id="step-dot-1" class="w-7 h-7 rounded-full step-active flex items-center justify-center text-xs font-bold">1</div>
        <span id="step-label-1" class="text-xs font-medium text-[#8A008A] hidden sm:block">Detail</span>
        <div id="step-line-12" class="w-12 h-0.5 step-line-active"></div>

        <!-- Step 2 -->
        <div id="step-dot-2" class="w-7 h-7 rounded-full step-inactive flex items-center justify-center text-xs font-bold">2</div>
        <span id="step-label-2" class="text-xs font-medium text-gray-400 hidden sm:block">Pembayaran</span>
        <div id="step-line-23" class="w-12 h-0.5 step-line-inactive"></div>

        <!-- Step 3 -->
        <div id="step-dot-3" class="w-7 h-7 rounded-full step-inactive flex items-center justify-center text-xs font-bold">3</div>
        <span id="step-label-3" class="text-xs font-medium text-gray-400 hidden sm:block">Ringkasan</span>
    </div>

    <div class="text-sm text-gray-500">Sisa Waktu : <span id="timer" class="font-bold text-gray-700">15 : 00</span></div>
</div>

<!-- CONTENT -->
<div class="max-w-2xl mx-auto px-4 py-8">

    <!-- ═══ STEP 1: DETAIL ═══ -->
    <div id="page-1">

        <!-- Ringkasan Pemesanan -->
        <div class="bg-white rounded-2xl shadow p-5 mb-6">
            <h3 class="font-bold text-base mb-4">Ringkasan Pemesanan</h3>

            <!-- Info Event -->
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

            <!-- Daftar Tiket yang Dipilih -->
            @foreach($selectedTickets as $item)
            <div class="flex justify-between items-center py-2">
                <span class="text-sm text-gray-700">{{ strtoupper($item['ticket']->name) }} <span class="text-gray-400">x {{ $item['qty'] }}</span></span>
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
                <span class="font-bold text-[#8A008A]">Rp. {{ number_format($totalPrice, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Ringkasan Pembeli -->
        <div class="bg-white rounded-2xl shadow p-5 mb-6">
            <h3 class="font-bold text-base mb-1">Ringkasan Pembeli</h3>
            <p class="text-xs text-gray-400 mb-5">Tiket akan kami kirim ke email anda</p>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama <span class="text-red-500">*</span></label>
                    <input type="text" id="buyer-name" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-purple-500 focus:border-purple-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">NIM / NIK <span class="text-red-500">*</span></label>
                    <input type="text" id="buyer-nim" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-purple-500 focus:border-purple-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                    <input type="email" id="buyer-email" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-purple-500 focus:border-purple-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">No HP <span class="text-red-500">*</span></label>
                    <input type="text" id="buyer-phone" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-purple-500 focus:border-purple-500">
                </div>
            </div>
        </div>

        <!-- Detail per tiket -->
        @foreach($selectedTickets as $item)
        <div class="bg-white rounded-2xl shadow p-5 mb-4">
            <div class="flex justify-between items-center mb-4">
                <h4 class="font-bold text-sm">{{ strtoupper($item['ticket']->name) }}</h4>
                <span class="bg-purple-100 text-purple-700 text-xs px-3 py-1 rounded-full font-medium">{{ $item['qty'] }} Tiket</span>
            </div>

            @for($i = 0; $i < $item['qty']; $i++)
            <div class="grid grid-cols-2 gap-4 {{ $i > 0 ? 'mt-4 pt-4 border-t border-gray-100' : '' }}">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama <span class="text-red-500">*</span></label>
                    <input type="text" name="attendees[{{ $item['ticket']->id }}][{{ $i }}][name]" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-purple-500 focus:border-purple-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">NIM / NIK <span class="text-red-500">*</span></label>
                    <input type="text" name="attendees[{{ $item['ticket']->id }}][{{ $i }}][nim]" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-purple-500 focus:border-purple-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="attendees[{{ $item['ticket']->id }}][{{ $i }}][email]" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-purple-500 focus:border-purple-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">No HP <span class="text-red-500">*</span></label>
                    <input type="text" name="attendees[{{ $item['ticket']->id }}][{{ $i }}][phone]" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-purple-500 focus:border-purple-500">
                </div>
            </div>
            @endfor
        </div>
        @endforeach

        <button onclick="goToStep(2)"
                class="w-full bg-[#8A008A] hover:bg-purple-700 text-white font-bold py-3 rounded-xl transition mt-2">
            Lanjutkan Pembayaran
        </button>
    </div>

    <!-- ═══ STEP 2: PEMBAYARAN ═══ -->
    <div id="page-2" class="hidden">

        <!-- Ringkasan -->
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
                <span class="text-sm text-gray-700">{{ strtoupper($item['ticket']->name) }} <span class="text-gray-400">x {{ $item['qty'] }}</span></span>
                <span class="text-sm font-semibold">Rp. {{ number_format($item['ticket']->price * $item['qty'], 0, ',', '.') }}</span>
            </div>
            @endforeach
            <div class="border-t border-gray-100 mt-3 pt-3 flex justify-between">
                <span class="font-bold">Total</span>
                <span class="font-bold text-[#8A008A]">Rp. {{ number_format($totalPrice, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Metode Pembayaran -->
        <div class="bg-white rounded-2xl shadow p-5 mb-6">
            <div class="flex items-center gap-3 mb-4">
                <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
                <span class="font-semibold">Bank Transfer (Virtual Akun)</span>
            </div>

            @foreach([
                ['name' => 'BCA Virtual Account', 'color' => 'text-blue-700', 'short' => 'BCA'],
                ['name' => 'Mandiri Virtual Account', 'color' => 'text-yellow-600', 'short' => 'MDR'],
                ['name' => 'BNI Virtual Account', 'color' => 'text-orange-600', 'short' => 'BNI'],
                ['name' => 'BRI Virtual Account', 'color' => 'text-blue-800', 'short' => 'BRI'],
                ['name' => 'Permata Virtual Account', 'color' => 'text-green-600', 'short' => 'PMT'],
            ] as $bank)
            <label class="flex items-center gap-3 py-3 cursor-pointer border-b border-gray-50 last:border-0">
                <input type="radio" name="payment_method" value="{{ $bank['name'] }}"
                       class="w-4 h-4 accent-purple-700">
                <span class="text-xs font-bold {{ $bank['color'] }} bg-gray-100 px-2 py-1 rounded w-10 text-center">{{ $bank['short'] }}</span>
                <span class="text-sm text-gray-700">{{ $bank['name'] }}</span>
            </label>
            @endforeach
        </div>

        <button onclick="goToStep(3)"
                class="w-full bg-[#8A008A] hover:bg-purple-700 text-white font-bold py-3 rounded-xl transition">
            Bayar Sekarang
        </button>
    </div>

    <!-- ═══ STEP 3: RINGKASAN ═══ -->
    <div id="page-3" class="hidden">

        <!-- Ringkasan Pemesanan -->
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
                <span class="text-sm text-gray-700">{{ strtoupper($item['ticket']->name) }} <span class="text-gray-400">x {{ $item['qty'] }}</span></span>
                <span class="text-sm font-semibold">Rp. {{ number_format($item['ticket']->price * $item['qty'], 0, ',', '.') }}</span>
            </div>
            @endforeach
            <div class="border-t border-gray-100 mt-3 pt-3 flex justify-between">
                <span class="font-bold">Total</span>
                <span class="font-bold text-[#8A008A]">Rp. {{ number_format($totalPrice, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Detail Pemesan -->
        <div class="bg-white rounded-2xl shadow p-5 mb-6">
            <h3 class="font-bold text-base mb-4">Detail Pemesan</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-gray-400 flex items-center gap-1">👤 Nama</p>
                    <p class="font-medium text-sm mt-1" id="summary-name">-</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 flex items-center gap-1">🔖 Referensi Pemesanan</p>
                    <p class="font-medium text-sm mt-1">{{ 'XX-' . rand(10000, 99999) }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 flex items-center gap-1">✉️ Email</p>
                    <p class="font-medium text-sm mt-1" id="summary-email">-</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 flex items-center gap-1">📅 Tanggal Pemesanan</p>
                    <p class="font-medium text-sm mt-1">{{ now()->format('d M Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Tiket Digital -->
        <div class="bg-white rounded-2xl shadow p-5 text-center mb-6">
            <p class="font-bold text-base mb-1">{{ strtoupper($event->title) }}</p>
            <p class="text-xs text-gray-500">📅 {{ $event->event_date }}</p>
            <p class="text-xs text-gray-500">📍 {{ $event->location }}</p>
        </div>

        <a href="{{ route('home') }}"
           class="block w-full text-center bg-[#8A008A] hover:bg-purple-700 text-white font-bold py-3 rounded-xl transition">
            Kembali ke Beranda
        </a>
    </div>

</div>

<script>
    // ─── STEP NAVIGATION ─────────────────────────────────────────────────────
    function goToStep(step) {
        [1, 2, 3].forEach(s => {
            document.getElementById('page-' + s).classList.add('hidden');
            document.getElementById('step-dot-' + s).className =
                'w-7 h-7 rounded-full step-inactive flex items-center justify-center text-xs font-bold';
            document.getElementById('step-label-' + s).className =
                'text-xs font-medium text-gray-400 hidden sm:block';
        });

        // Aktifkan step yang dipilih
        document.getElementById('page-' + step).classList.remove('hidden');
        for (let s = 1; s <= step; s++) {
            document.getElementById('step-dot-' + s).className =
                'w-7 h-7 rounded-full step-done flex items-center justify-center text-xs font-bold';
            document.getElementById('step-label-' + s).className =
                'text-xs font-medium text-[#8A008A] hidden sm:block';
        }

        // Garis step
        ['12', '23'].forEach(pair => {
            const [a, b] = pair.split('').map(Number);
            document.getElementById('step-line-' + pair).className =
                'w-12 h-0.5 ' + (step > a ? 'step-line-active' : 'step-line-inactive');
        });

        // Isi ringkasan nama & email di step 3
        if (step === 3) {
            document.getElementById('summary-name').textContent =
                document.getElementById('buyer-name').value || '-';
            document.getElementById('summary-email').textContent =
                document.getElementById('buyer-email').value || '-';
        }

        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // ─── TIMER ───────────────────────────────────────────────────────────────
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
