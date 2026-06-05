<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $event->title }} - SIMETIX</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        button, a, .cursor-pointer { cursor: pointer; }

        /* ── TICKET CARD ── */
        .ticket-card {
            background: rgba(255,255,255,0.88);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-radius: 14px;
            padding: 16px;
            border: 1px solid rgba(255,255,255,0.6);
            box-shadow:
                0 2px 8px rgba(0,0,0,0.08),
                0 1px 2px rgba(0,0,0,0.04);
            transition: box-shadow 0.2s;
        }
        .ticket-card:hover {
            box-shadow:
                0 4px 16px rgba(107,0,128,0.12),
                0 1px 4px rgba(0,0,0,0.06);
        }
        .ticket-card.unavailable { opacity: 0.65; }

        /* ── INFO CARD ── */
        .info-card {
            background: rgba(255,255,255,0.88);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-radius: 14px;
            padding: 20px;
            border: 1px solid rgba(255,255,255,0.6);
            box-shadow:
                0 2px 8px rgba(0,0,0,0.08),
                0 1px 2px rgba(0,0,0,0.04);
        }

        /* ── QTY BUTTON ── */
        .qty-btn {
            width: 32px; height: 32px;
            border-radius: 50%;
            border: 1.5px solid #6B0080;
            color: #6B0080;
            font-size: 18px;
            display: flex; align-items: center; justify-content: center;
            transition: all 0.15s;
            background: transparent;
            line-height: 1;
        }
        .qty-btn:hover {
            background: #6B0080;
            color: white;
        }

        /* ── BUY BUTTON ── */
        .btn-buy {
            width: 100%;
            background: #6B0080;
            color: white;
            font-weight: 700;
            font-size: 14px;
            padding: 13px;
            border-radius: 12px;
            border: none;
            letter-spacing: 0.02em;
            box-shadow: 0 4px 14px rgba(107,0,128,0.35);
            transition: all 0.2s;
        }
        .btn-buy:hover:not(:disabled) {
            background: #580068;
            box-shadow: 0 6px 20px rgba(107,0,128,0.45);
            transform: translateY(-1px);
        }
        .btn-buy:disabled {
            background: rgba(107,0,128,0.35);
            box-shadow: none;
            cursor: not-allowed;
        }

        /* ── CATEGORY BADGE ── */
        .cat-badge {
            display: inline-flex;
            align-items: center;
            background: rgba(107,0,128,0.1);
            color: #6B0080;
            font-size: 11px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 20px;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        /* ── FOOTER ── */
        .mini-footer {
            background: rgba(74,0,90,0.95);
            backdrop-filter: blur(8px);
        }
    </style>
</head>

<body class="relative min-h-screen">

<!-- NAVBAR -->
<nav class="bg-[#6B0080]/90 backdrop-blur-md fixed w-full z-30 top-0 px-5 py-3 shadow-sm border-b border-white/10">
    <div class="flex justify-between items-center max-w-screen-xl mx-auto">
        <a href="{{ route('home') }}" class="flex items-center gap-2 text-white hover:opacity-80 transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 14 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5H1m0 0 4 4M1 5l4-4"/>
            </svg>
            <span class="text-sm font-medium">Kembali</span>
        </a>
        <a href="{{ route('home') }}" class="flex items-center gap-2">
            <img src="{{ asset('img/logo.png') }}" class="h-9">
            <span class="text-white text-xl font-bold tracking-tight">SIMETIX</span>
        </a>
        <div class="w-20"></div>
    </div>
</nav>

<!-- BACKGROUND BLUR -->
<div class="fixed inset-0 -z-10">
    @if($event->poster)
        <img src="{{ asset('poster/' . $event->poster) }}"
             class="w-full h-full object-cover blur-xl scale-110 opacity-80">
    @else
        <div class="w-full h-full bg-[#3d0049]"></div>
    @endif
    <div class="absolute inset-0 bg-gradient-to-b from-black/50 via-black/35 to-black/55"></div>
</div>

<!-- CONTAINER -->
<div class="max-w-5xl mx-auto px-4 py-6 mt-16">

    <!-- POSTER BESAR -->
    <div class="rounded-2xl overflow-hidden mb-6" style="box-shadow: 0 8px 40px rgba(0,0,0,0.4);">
        @if($event->poster)
            <img src="{{ asset('poster/' . $event->poster) }}"
                 class="w-full h-[220px] md:h-[340px] object-cover"
                 alt="{{ $event->title }}">
        @else
            <div class="w-full h-[220px] md:h-[340px] bg-purple-900 flex items-center justify-center">
                <svg class="w-16 h-16 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                </svg>
            </div>
        @endif
    </div>

    <!-- SECTION UTAMA -->
    <form action="{{ route('checkout.store', $event->slug) }}" method="POST" id="form-checkout">
        @csrf
        <div class="grid md:grid-cols-3 gap-5">

            <!-- KIRI: TIKET -->
            <div class="space-y-3">
                @forelse($event->ticketTypes as $ticket)
                @php $ticketStatus = $ticket->getStatus(); @endphp
                <div class="ticket-card {{ $ticketStatus !== 'available' ? 'unavailable' : '' }}">
                    <div class="flex justify-between items-start mb-1">
                        <p class="font-bold text-gray-900 text-sm leading-tight">{{ $ticket->name }}</p>
                        <p class="font-bold text-[#6B0080] text-sm ml-2 whitespace-nowrap">
                            @if($ticket->price > 0) Rp {{ number_format($ticket->price, 0, ',', '.') }}
                            @else <span class="text-green-600">Gratis</span>
                            @endif
                        </p>
                    </div>

                    @if($ticket->description)
                    <ul class="mb-2 space-y-0.5">
                        @foreach(explode("\n", $ticket->description) as $line)
                            @if(trim($line))
                            <li class="flex items-start gap-1.5 text-xs text-gray-500">
                                <span class="text-[#6B0080] mt-0.5 flex-shrink-0">•</span>
                                <span>{{ trim($line) }}</span>
                            </li>
                            @endif
                        @endforeach
                    </ul>
                    @endif

                    <div class="flex items-center justify-between mb-3">
                        @if($ticketStatus === 'available')
                            <span class="text-xs text-gray-400">Sisa {{ $ticket->getRemainingQuota() }} tiket</span>
                            @if($ticket->closes_at)
                            <span class="text-xs bg-orange-50 text-orange-500 border border-orange-100 px-2 py-0.5 rounded-full font-medium">
                                ⏱ {{ $ticket->getClosesAtHuman() }}
                            </span>
                            @endif
                        @elseif($ticketStatus === 'sold_out')
                            <span class="text-xs bg-red-50 text-red-500 border border-red-100 px-2 py-0.5 rounded-full font-medium">Habis Terjual</span>
                        @else
                            <span class="text-xs bg-gray-100 text-gray-400 px-2 py-0.5 rounded-full font-medium">Tidak Tersedia</span>
                        @endif
                    </div>

                    @if($ticketStatus === 'available')
                    <div class="flex items-center justify-between">
                        <button type="button" onclick="changeQty({{ $ticket->id }}, -1, {{ $ticket->getRemainingQuota() }})" class="qty-btn">−</button>
                        <span id="qty-display-{{ $ticket->id }}" class="text-base font-bold text-gray-900 w-8 text-center">0</span>
                        <input type="hidden" name="tickets[{{ $ticket->id }}]" id="qty-input-{{ $ticket->id }}" value="0">
                        <button type="button" onclick="changeQty({{ $ticket->id }}, 1, {{ $ticket->getRemainingQuota() }})" class="qty-btn">+</button>
                    </div>
                    @else
                    <p class="text-center text-xs text-gray-400 italic py-1">Tiket tidak tersedia</p>
                    @endif
                </div>
                @empty
                <div class="ticket-card text-center text-gray-500 text-sm">Belum ada jenis tiket tersedia.</div>
                @endforelse

                @if($event->ticketTypes->isNotEmpty())
                <!-- TOTAL -->
                <div class="ticket-card">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-600">Total Pembayaran</span>
                        <span id="total-display" class="font-bold text-[#6B0080] text-base">Rp 0</span>
                    </div>
                </div>

                <button type="submit" id="btn-beli" class="btn-buy" disabled>
                    Beli Sekarang
                </button>
                @endif
            </div>

            <!-- KANAN: INFO EVENT -->
            <div class="md:col-span-2 space-y-4">
                <div class="info-card">
                    <span class="cat-badge mb-3 inline-block">{{ optional($event->category)->name ?? 'Umum' }}</span>
                    <h2 class="text-xl font-bold text-gray-900 mb-3 leading-snug">{{ $event->title }}</h2>

                    <div class="space-y-2 mb-3">
                        <div class="flex items-center gap-2.5 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-[#6B0080] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>{{ $event->event_date }}</span>
                        </div>
                        <div class="flex items-center gap-2.5 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-[#6B0080] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>{{ $event->location }}</span>
                        </div>
                        <div class="flex items-center gap-2.5 text-sm text-gray-500">
                            <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span>Diselenggarakan oleh <strong class="text-gray-700">{{ optional($event->user)->nama_lengkap ?? 'Panitia' }}</strong></span>
                        </div>
                    </div>
                </div>

                <div class="info-card overflow-y-auto" style="max-height: 280px;">
                    <h3 class="font-bold text-gray-900 mb-3">Tentang Event</h3>
                    <p class="text-gray-600 whitespace-pre-line text-sm leading-relaxed">{{ $event->description }}</p>
                </div>
            </div>
        </div>
    </form>

    <!-- FOTO BAWAH -->
    @if($event->poster)
    <div class="grid md:grid-cols-3 gap-4 mt-6">
        <div class="rounded-2xl overflow-hidden" style="box-shadow:0 4px 20px rgba(0,0,0,0.25)">
            <img src="{{ asset('poster/' . $event->poster) }}" class="w-full h-[260px] object-cover">
        </div>
        <div class="md:col-span-2 rounded-2xl overflow-hidden" style="box-shadow:0 4px 20px rgba(0,0,0,0.25)">
            <img src="{{ asset('poster/' . $event->poster) }}" class="w-full h-[260px] object-cover">
        </div>
    </div>
    @endif

</div>

<!-- FOOTER -->
<footer class="mini-footer text-white py-4 mt-10">
    <div class="text-center text-xs text-white/40">
        &copy; 2026 SIMETIX — All Rights Reserved.
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
    if (newQty > 5) { alert('Maksimal 5 tiket per jenis!'); return; }
    quantities[ticketId] = newQty;
    document.getElementById('qty-display-' + ticketId).textContent = newQty;
    document.getElementById('qty-input-' + ticketId).value = newQty;
    updateTotal();
}

function updateTotal() {
    let total = 0, hasItem = false;
    for (const [id, qty] of Object.entries(quantities)) {
        if (qty > 0) { total += ticketPrices[id] * qty; hasItem = true; }
    }
    document.getElementById('total-display').textContent = 'Rp ' + total.toLocaleString('id-ID');
    document.getElementById('btn-beli').disabled = !hasItem;
}
</script>
</body>
</html>
