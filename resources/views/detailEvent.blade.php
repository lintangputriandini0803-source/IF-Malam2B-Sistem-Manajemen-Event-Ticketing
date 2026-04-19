<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $event->title }} - SIMETIX</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
    <style>
        button, a, .cursor-pointer { cursor: pointer; }
    </style>
</head>

<body class="relative min-h-screen">

<!-- NAVBAR -->
<nav class="bg-[#8A008A]/90 backdrop-blur-md fixed w-full z-30 top-0 px-4 py-2 shadow-md">
    <div class="flex justify-between items-center max-w-screen-2xl mx-auto">
        <a href="{{ route('home') }}">
            <svg class="w-4 h-4 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5H1m0 0 4 4M1 5l4-4"/>
            </svg>
        </a>
        <div class="hidden md:flex space-x-8 text-white font-medium">
            <a href="{{ route('home') }}" class="flex items-center space-x-2">
                <img src="{{ asset('img/logo.png') }}" class="h-10">
                <span class="text-white text-2xl font-bold">SIMETIX</span>
            </a>
        </div>
    </div>
</nav>

<!-- BACKGROUND BLUR -->
<div class="fixed inset-0 -z-10">
    @if($event->poster)
        <img src="{{ asset('poster/' . $event->poster) }}"
             class="w-full h-full object-cover blur-md scale-110">
    @else
        <div class="w-full h-full bg-purple-900"></div>
    @endif
    <div class="absolute inset-0 bg-black/40"></div>
</div>

<!-- CONTAINER -->
<div class="max-w-6xl mx-auto px-4 py-6 mt-16">

    <!-- POSTER BESAR -->
    <div class="rounded-2xl overflow-hidden shadow-lg mb-6">
        @if($event->poster)
            <img src="{{ asset('poster/' . $event->poster) }}"
                 class="w-full h-[250px] md:h-[350px] object-cover">
        @else
            <div class="w-full h-[250px] md:h-[350px] bg-purple-800 flex items-center justify-center">
                <span class="text-white text-6xl">🎫</span>
            </div>
        @endif
    </div>

    <!-- SECTION UTAMA -->
    <form action="{{ route('checkout.store', $event->slug) }}" method="POST" id="form-checkout">
        @csrf
        <div class="grid md:grid-cols-3 gap-6">

            <!-- KIRI (TIKET + TOMBOL) -->
            <div class="space-y-4">

                @forelse($event->ticketTypes as $ticket)
                <div class="bg-white/80 backdrop-blur rounded-xl p-4 shadow">
                    <p class="font-semibold text-gray-800">{{ $ticket->name }}</p>
                    <p class="text-right font-bold text-[#8A008A] text-lg">
                        @if($ticket->price > 0)
                            Rp {{ number_format($ticket->price, 0, ',', '.') }}
                        @else
                            Gratis
                        @endif
                    </p>
                    <p class="text-xs text-gray-400 mb-3">Sisa: {{ $ticket->getRemainingQuota() }} tiket</p>

                    <!-- TOMBOL +/- -->
                    <div class="flex items-center justify-between">
                        <button type="button"
                                onclick="changeQty({{ $ticket->id }}, -1, {{ $ticket->getRemainingQuota() }})"
                                class="w-8 h-8 rounded-full border-2 border-[#8A008A] text-[#8A008A] font-bold text-lg flex items-center justify-center hover:bg-[#8A008A] hover:text-white transition">
                            −
                        </button>

                        <span id="qty-display-{{ $ticket->id }}" class="text-lg font-bold text-gray-800 w-8 text-center">0</span>

                        <input type="hidden" name="tickets[{{ $ticket->id }}]" id="qty-input-{{ $ticket->id }}" value="0">

                        <button type="button"
                                onclick="changeQty({{ $ticket->id }}, 1, {{ $ticket->getRemainingQuota() }})"
                                class="w-8 h-8 rounded-full border-2 border-[#8A008A] text-[#8A008A] font-bold text-lg flex items-center justify-center hover:bg-[#8A008A] hover:text-white transition">
                            +
                        </button>
                    </div>
                </div>
                @empty
                <div class="bg-white/80 backdrop-blur rounded-xl p-4 shadow text-center text-gray-500">
                    Belum ada jenis tiket tersedia.
                </div>
                @endforelse

                @if($event->ticketTypes->isNotEmpty())
                <!-- TOTAL -->
                <div class="bg-white/80 backdrop-blur rounded-xl p-4 shadow">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Total</span>
                        <span id="total-display" class="font-bold text-[#8A008A]">Rp 0</span>
                    </div>
                </div>

                <button type="submit" id="btn-beli"
                        class="w-full bg-[#8A008A] hover:bg-purple-700 text-white py-3 rounded-xl transition font-semibold disabled:opacity-50 disabled:cursor-not-allowed"
                        disabled>
                    Beli Sekarang
                </button>
                @endif
            </div>

            <!-- KANAN (DESKRIPSI) -->
            <div class="md:col-span-2 space-y-4">
                <div class="bg-white/80 backdrop-blur rounded-xl p-5 shadow">
                    <span class="text-xs bg-purple-100 text-purple-700 px-2 py-1 rounded-full font-medium">
                        {{ optional($event->category)->name ?? 'Umum' }}
                    </span>
                    <h2 class="text-xl font-bold mt-2 mb-2">{{ $event->title }}</h2>
                    <p class="text-sm text-gray-600">📅 {{ $event->event_date }}</p>
                    <p class="text-sm text-gray-600">📍 {{ $event->location }}</p>
                    <p class="text-sm text-gray-500 mt-1">Diselenggarakan oleh: <strong>{{ optional($event->user)->nama_lengkap ?? 'Panitia' }}</strong></p>
                </div>

                <div class="bg-white/80 backdrop-blur rounded-xl h-[280px] p-5 shadow text-sm leading-relaxed overflow-y-auto">
                    <h3 class="font-semibold mb-2">Tentang Event</h3>
                    <p class="text-gray-700 whitespace-pre-line">{{ $event->description }}</p>
                </div>
            </div>
        </div>
    </form>

    <!-- FOTO BAWAH -->
    <div class="grid md:grid-cols-3 gap-6 mt-6">
        <div class="rounded-2xl overflow-hidden shadow-lg">
            <img src="{{ asset('poster/' . $event->poster) }}" class="w-full h-[300px] object-cover">
        </div>
        <div class="md:col-span-2 rounded-2xl overflow-hidden shadow-lg">
            <img src="{{ asset('poster/' . $event->poster) }}" class="w-full h-[300px] object-cover">
        </div>
    </div>

</div>

<!-- FOOTER -->
<footer class="bg-[#8A008A] text-white py-3 mt-10">
    <div class="text-center text-xs opacity-60">
        &copy; 2026 SIMETIX - All Rights Reserved.
    </div>
</footer>

<script>
    const ticketPrices = {
        @foreach($event->ticketTypes as $ticket)
            {{ $ticket->id }}: {{ $ticket->price }},
        @endforeach
    };

    const quantities = {};

    function changeQty(ticketId, delta, maxQuota) {
        if (!quantities[ticketId]) quantities[ticketId] = 0;

        const newQty = quantities[ticketId] + delta;
        if (newQty < 0) return;
        if (newQty > maxQuota) { alert('Kuota tidak mencukupi!'); return; }
        if (newQty > 10) { alert('Maksimal 10 tiket per jenis!'); return; }

        quantities[ticketId] = newQty;
        document.getElementById('qty-display-' + ticketId).textContent = newQty;
        document.getElementById('qty-input-' + ticketId).value = newQty;

        updateTotal();
    }

    function updateTotal() {
        let total = 0;
        let hasItem = false;

        for (const [ticketId, qty] of Object.entries(quantities)) {
            if (qty > 0) {
                total += ticketPrices[ticketId] * qty;
                hasItem = true;
            }
        }

        document.getElementById('total-display').textContent =
            'Rp ' + total.toLocaleString('id-ID');
        document.getElementById('btn-beli').disabled = !hasItem;
    }
</script>

</body>
</html>
