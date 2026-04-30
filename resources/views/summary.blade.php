<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ringkasan Pemesanan - SIMETIX</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
    <style>
        body { background: #f3eef8; }
        button, a { cursor: pointer; }
    </style>
</head>
<body class="min-h-screen">

<!-- TOPBAR -->
<div class="flex items-center justify-between px-6 py-4 bg-white shadow-sm sticky top-0 z-10">
    <a href="{{ route('home') }}" class="flex items-center gap-2 text-sm text-gray-600 hover:text-[#6B0080]">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 14 10">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5H1m0 0 4 4M1 5l4-4"/>
        </svg>
        Kembali ke Beranda
    </a>

    <!-- STEP INDICATOR (semua done) -->
    <div class="flex items-center gap-2">
        @foreach([1,2,3] as $s)
        <div class="w-7 h-7 rounded-full bg-[#6B0080] text-white flex items-center justify-center text-xs font-bold">{{ $s }}</div>
        @if($s < 3)
        <div class="w-12 h-0.5 bg-[#6B0080]"></div>
        @endif
        @endforeach
    </div>

    <div class="text-sm text-gray-400">Selesai</div>
</div>

<!-- CONTENT -->
<div class="max-w-2xl mx-auto px-4 py-8">

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

        @foreach($registrations as $reg)
        <div class="flex justify-between items-center py-2">
            <span class="text-sm text-gray-700">{{ strtoupper($reg->ticketType->name) }}
                <span class="text-gray-400">x {{ $reg->quantity }}</span>
            </span>
            <span class="text-sm font-semibold">
                @if($reg->total_price > 0)
                    Rp. {{ number_format($reg->total_price, 0, ',', '.') }}
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

    {{-- Detail Pemesan --}}
    <div class="bg-white rounded-2xl shadow p-5 mb-6">
        <h3 class="font-bold text-base mb-4">Detail Pemesan</h3>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-xs text-gray-400">👤 Nama</p>
                <p class="font-medium text-sm mt-0.5">{{ $buyer['name'] }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400">🔖 Referensi Pemesanan</p>
                <p class="font-medium text-sm mt-0.5 font-mono">{{ $orderRef }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400">✉️ Email</p>
                <p class="font-medium text-sm mt-0.5">{{ $buyer['email'] }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400">📅 Tanggal Pemesanan</p>
                <p class="font-medium text-sm mt-0.5">{{ now()->format('d-F-Y') }}</p>
            </div>
        </div>
    </div>

    {{-- Tiket Digital --}}
    @php $ticketNum = 1; @endphp
    @foreach($registrations as $reg)
        @for($i = 0; $i < $reg->quantity; $i++)
        <div class="bg-white rounded-2xl shadow p-5 mb-4 flex items-center gap-4">
            {{-- QR Code placeholder --}}
            <div class="w-20 h-20 bg-gray-900 rounded-xl flex-shrink-0 flex items-center justify-center overflow-hidden">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data={{ urlencode($orderRef . '-' . $reg->id . '-' . $i) }}"
                     alt="QR" class="w-full h-full object-cover"
                     onerror="this.parentElement.innerHTML='<span class=\'text-white text-xs text-center\'>QR</span>'">
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-xs font-semibold text-gray-400 mb-0.5">Ticket {{ $ticketNum }} - {{ $reg->ticketType->name }}</p>
                <p class="font-bold text-sm text-gray-900">{{ strtoupper($event->title) }}</p>
                <p class="text-xs text-gray-500 mt-1">📅 {{ $event->event_date }}</p>
                <p class="text-xs text-gray-500">📍 {{ $event->location }}</p>
                <div class="mt-2 flex items-center gap-1">
                    <span class="text-xs text-gray-400">kode ticket</span>
                    <span class="text-xs font-mono font-semibold text-gray-700">{{ $reg->reg_number }}-{{ str_pad($i+1, 2, '0', STR_PAD_LEFT) }}</span>
                </div>
            </div>
        </div>
        @php $ticketNum++; @endphp
        @endfor
    @endforeach

    <a href="{{ route('home') }}"
       class="block w-full text-center bg-[#6B0080] hover:bg-purple-700 text-white font-bold py-3 rounded-xl transition mt-4">
        Kembali ke Beranda
    </a>

</div>
</body>
</html>
