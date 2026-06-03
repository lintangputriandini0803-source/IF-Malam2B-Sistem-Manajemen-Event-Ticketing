<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - SIMETIX</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        button, a { cursor: pointer; }
        body { background: #f3eef8; }
        .step-done     { background: #6B0080; color: white; }
        .step-active   { background: #6B0080; color: white; }
        .step-inactive { background: #e5e7eb; color: #9ca3af; }
        .step-line-active   { background: #6B0080; }
        .step-line-inactive { background: #e5e7eb; }
    </style>
</head>
<body class="min-h-screen">

<!-- TOPBAR -->
<div class="flex items-center justify-between px-6 py-4 bg-white shadow-sm sticky top-0 z-10">
    <a href="{{ route('event.show', $event->slug) }}"
       class="flex items-center gap-2 text-sm text-gray-600 hover:text-[#6B0080]">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 14 10">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5H1m0 0 4 4M1 5l4-4"/>
        </svg>
        Kembali
    </a>

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

    <div class="text-sm text-gray-500">
        Sisa Waktu : <span id="timer" class="font-bold text-gray-700">15 : 00</span>
    </div>
</div>

<!-- CONTENT -->
<div class="max-w-2xl mx-auto px-4 py-8">

    <!-- RINGKASAN PEMESANAN -->
    <div class="bg-white rounded-2xl shadow p-5 mb-5">
        <h3 class="font-bold text-base mb-4">Ringkasan Pemesanan</h3>
        <div class="flex gap-3 mb-4 pb-4 border-b border-gray-100">
            @if($event->poster)
            <img src="{{ asset('poster/' . $event->poster) }}"
                 class="w-16 h-16 rounded-xl object-cover flex-shrink-0">
            @endif
            <div>
                <p class="font-bold text-sm">{{ strtoupper($event->title) }}</p>
                <p class="text-xs text-gray-500 mt-1">📅 {{ $event->event_date }}</p>
                <p class="text-xs text-gray-500">📍 {{ $event->location }}</p>
            </div>
        </div>

        @foreach($registrations as $reg)
        <div class="flex justify-between items-center py-2">
            <span class="text-sm text-gray-700">
                {{ strtoupper($reg->ticketType->name ?? '-') }}
                <span class="text-gray-400">x {{ $reg->quantity }}</span>
            </span>
            <span class="text-sm font-semibold">
                Rp {{ number_format($reg->total_price, 0, ',', '.') }}
            </span>
        </div>
        @endforeach

        <div class="border-t border-gray-100 mt-3 pt-3 flex justify-between">
            <span class="font-bold">Total</span>
            <span class="font-bold text-[#6B0080]">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
        </div>
    </div>

    <!-- TOMBOL BAYAR MIDTRANS -->
    @if($snapToken)
    <div class="bg-white rounded-2xl shadow p-6 mb-5 text-center">
        <div class="w-16 h-16 bg-[#6B0080]/10 rounded-full flex items-center justify-center mx-auto mb-3">
            <svg class="w-7 h-7 text-[#6B0080]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
            </svg>
        </div>
        <h3 class="font-bold text-gray-900 mb-1">Selesaikan Pembayaran</h3>
        <p class="text-sm text-gray-500 mb-5">
            Klik tombol di bawah untuk memilih metode pembayaran<br>
            (Transfer Bank, QRIS, GoPay, OVO, dll.)
        </p>

        <button id="pay-button"
                class="w-full bg-[#6B0080] hover:bg-[#580068] text-white font-bold py-3.5 rounded-xl
                       transition shadow-lg shadow-purple-900/25 flex items-center justify-center gap-2"
                onclick="openMidtrans()">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
            </svg>
            Bayar Sekarang
        </button>

        <p class="text-xs text-gray-400 mt-3">
            Pembayaran diproses secara aman oleh
            <img src="https://www.midtrans.com/assets/image/logo.png"
                 class="inline h-3 mx-1 opacity-70" onerror="this.remove()">
            Midtrans
        </p>
    </div>

    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-sm text-amber-700">
        <p class="font-semibold mb-1">⚠️ Penting</p>
        <p>Order ID: <strong class="font-mono">{{ $orderRef }}</strong></p>
        <p class="mt-1">Selesaikan pembayaran sebelum waktu habis. Tiket akan dikirim ke email setelah pembayaran dikonfirmasi.</p>
    </div>

    @else
    {{-- Fallback jika snap token gagal --}}
    <div class="bg-white rounded-2xl shadow p-6 text-center">
        <p class="text-gray-500 mb-2">Terjadi kendala menghubungi payment gateway.</p>
        <p class="text-sm text-gray-400 mb-4">Order ID: <strong>{{ $orderRef }}</strong></p>
        <a href="{{ route('home') }}"
           class="inline-block bg-[#6B0080] text-white px-6 py-2 rounded-lg text-sm font-semibold">
            Kembali ke Beranda
        </a>
    </div>
    @endif

</div>

<!-- Load Midtrans Snap JS -->
<script src="{{ $snapUrl }}" data-client-key="{{ $clientKey }}"></script>

<script>
    function openMidtrans() {
        const btn = document.getElementById('pay-button');
        btn.disabled = true;
        btn.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Memuat...';

        snap.pay('{{ $snapToken }}', {
            onSuccess: function(result) {
                // Pembayaran berhasil → ke halaman summary
                window.location.href = '{{ route("checkout.summary", $event->slug) }}?transaction_status=' + result.transaction_status + '&order_id=' + result.order_id;
            },
            onPending: function(result) {
                // Menunggu pembayaran (transfer bank, dll)
                window.location.href = '{{ route("checkout.summary", $event->slug) }}?transaction_status=pending&order_id=' + result.order_id;
            },
            onError: function(result) {
                alert('Pembayaran gagal: ' + (result.status_message || 'Silakan coba lagi.'));
                btn.disabled = false;
                btn.innerHTML = '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg> Bayar Sekarang';
            },
            onClose: function() {
                // User tutup popup tanpa bayar
                btn.disabled = false;
                btn.innerHTML = '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg> Bayar Sekarang';
            }
        });
    }

    // Timer countdown
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
