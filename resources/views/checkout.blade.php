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
    <x-toast />

<!-- TOPBAR -->
<div class="flex items-center justify-between px-6 py-4 bg-white shadow-sm sticky top-0 z-10">
    <a href="{{ route('event.show', $event->slug) }}" class="flex items-center gap-2 text-sm text-gray-600 hover:text-[#6B0080]">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 14 10">
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

    {{-- Bug fix (High): sebelumnya halaman ini tidak menampilkan error apa pun
         dari backend (validasi, kuota habis, ATAU error Midtrans), sehingga
         pembeli tidak tahu kenapa pembayaran tidak bisa dilanjutkan. --}}
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-4 mb-6 text-sm">
            <p class="font-semibold mb-1">Terjadi masalah:</p>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ═══ STEP 1: DETAIL ═══ --}}
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
                    <div class="flex items-center gap-1.5 text-xs text-gray-500 mt-1">
                        <svg class="w-3.5 h-3.5 text-[#9B30AF] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>{{ $event->event_date }}</span>
                    </div>
                    <div class="flex items-center gap-1.5 text-xs text-gray-500 mt-0.5">
                        <svg class="w-3.5 h-3.5 text-[#9B30AF] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>{{ $event->location }}</span>
                    </div>
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

        {{-- Data Pembeli --}}
        <div class="bg-white rounded-2xl shadow p-5 mb-6">
            <h3 class="font-bold text-base mb-1">Ringkasan Pembeli</h3>
            <div class="flex items-center gap-1.5 text-xs text-gray-400 mb-5">
                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Tiket akan kami kirim ke email anda
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Nama <span class="text-red-500">*</span>
                        </span>
                    </label>
                    <input type="text" id="buyer-name" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-purple-500 focus:border-purple-500"
                           placeholder="Nama lengkap">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2"/>
                            </svg>
                            NIM / NIK <span class="text-red-500">*</span>
                        </span>
                    </label>
                    <input type="text" id="buyer-nim" required
                           pattern="[A-Za-z0-9]+" inputmode="text"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-purple-500 focus:border-purple-500"
                           placeholder="NIM atau NIK">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Email <span class="text-red-500">*</span>
                        </span>
                    </label>
                    <input type="email" id="buyer-email" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-purple-500 focus:border-purple-500"
                           placeholder="email@contoh.com">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            No HP <span class="text-red-500">*</span>
                        </span>
                    </label>
                    <input type="text" id="buyer-phone" required
                           pattern="\+?[0-9]{8,20}" inputmode="numeric"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-purple-500 focus:border-purple-500"
                           placeholder="08xxxxxxxxxx">
                </div>
            </div>
        </div>

        <button id="lanjutkan-btn" onclick="showKonfirmasiPembeli()"
                class="w-full bg-[#6B0080] hover:bg-purple-700 text-white font-bold py-3 rounded-xl transition mt-2 flex items-center justify-center gap-2">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
            Lanjutkan Pembayaran
        </button>
    </div>

    {{-- ═══ STEP 2: PEMBAYARAN (Midtrans Snap) ═══ --}}
    <div id="page-2" class="hidden">

        {{-- Form hidden untuk submit ke backend --}}
        <form id="process-form" action="{{ route('checkout.process', $event->slug) }}" method="POST">
            @csrf
            <input type="hidden" name="buyer_name"  id="f-name">
            <input type="hidden" name="buyer_nim"   id="f-nim">
            <input type="hidden" name="buyer_email" id="f-email">
            <input type="hidden" name="buyer_phone" id="f-phone">
            @foreach($selectedTickets as $item)
            <input type="hidden" name="tickets[{{ $loop->index }}][id]"  value="{{ $item['ticket']->id }}">
            <input type="hidden" name="tickets[{{ $loop->index }}][qty]" value="{{ $item['qty'] }}">
            @endforeach
        </form>

        {{-- Ringkasan ringkas di step 2 --}}
        <div class="bg-white rounded-2xl shadow p-5 mb-5">
            <div class="flex gap-3 items-center pb-4 border-b border-gray-100 mb-4">
                @if($event->poster)
                <img src="{{ asset('poster/' . $event->poster) }}" class="w-14 h-14 rounded-xl object-cover flex-shrink-0">
                @endif
                <div>
                    <p class="font-bold text-sm">{{ strtoupper($event->title) }}</p>
                    <div class="flex items-center gap-1.5 text-xs text-gray-500 mt-1">
                        <svg class="w-3.5 h-3.5 text-[#9B30AF] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ $event->event_date }}
                    </div>
                </div>
            </div>
            @foreach($selectedTickets as $item)
            <div class="flex justify-between items-center py-1.5">
                <span class="text-sm text-gray-600">{{ $item['ticket']->name }} <span class="text-gray-400">x{{ $item['qty'] }}</span></span>
                <span class="text-sm font-medium">Rp {{ number_format($item['ticket']->price * $item['qty'], 0, ',', '.') }}</span>
            </div>
            @endforeach
            <div class="border-t border-gray-100 mt-3 pt-3 flex justify-between">
                <span class="font-bold">Total</span>
                <span class="font-bold text-[#6B0080]">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- Tombol bayar --}}
        <div class="bg-white rounded-2xl shadow p-6 text-center mb-4">
            <div class="w-14 h-14 bg-[#6B0080]/10 rounded-full flex items-center justify-center mx-auto mb-3">
                <svg class="w-7 h-7 text-[#6B0080] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
            </div>
            <p class="text-sm text-gray-500 mb-1">Pilih metode pembayaran favoritmu</p>
            <p class="text-xs text-gray-400 mb-5">Transfer Bank, QRIS, GoPay, OVO, dan lainnya</p>

            <button id="pay-button" onclick="openMidtrans()"
                    class="w-full bg-[#6B0080] hover:bg-[#580068] text-white font-bold py-3.5 rounded-xl
                           transition shadow-lg shadow-purple-900/20 flex items-center justify-center gap-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
                Bayar Sekarang
            </button>
        </div>

        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-4">
            <div class="flex items-start gap-2">
                <svg class="w-4 h-4 text-amber-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div class="text-sm text-amber-700">
                    <p class="font-semibold mb-0.5">Penting</p>
                    <p>Selesaikan pembayaran sebelum waktu habis. Tiket akan dikirim ke email setelah pembayaran dikonfirmasi.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══ STEP 3: RINGKASAN (fallback, biasanya redirect ke summary) ═══ --}}
    <div id="page-3" class="hidden">
        <div class="bg-white rounded-2xl shadow p-6 text-center">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-green-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h3 class="font-bold text-lg text-gray-900 mb-1">Pembayaran Berhasil!</h3>
            <p class="text-sm text-gray-500 mb-4">Tiket akan dikirimkan ke email kamu.</p>
            <a href="{{ route('home') }}"
               class="inline-flex items-center gap-2 bg-[#6B0080] text-white font-semibold px-6 py-2.5 rounded-xl text-sm hover:bg-[#580068] transition">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Kembali ke Beranda
            </a>
        </div>
    </div>

</div>

{{-- Midtrans Snap JS --}}
<script src="{{ config('midtrans.snap_url') }}" data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>
function validateAndGoToPayment() {
    const name  = document.getElementById('buyer-name').value.trim();
    const nim   = document.getElementById('buyer-nim').value.trim();
    const email = document.getElementById('buyer-email').value.trim();
    const phone = document.getElementById('buyer-phone').value.trim();

    if (!name || !nim || !email || !phone) {
        alert('Lengkapi semua data pembeli terlebih dahulu.');
        return;
    }

    // Bug fix (Medium): NIM/NIK hanya boleh huruf & angka, No HP hanya boleh angka.
    if (!/^[A-Za-z0-9]+$/.test(nim)) {
        alert('NIM/NIK hanya boleh berisi huruf dan angka, tanpa spasi atau simbol.');
        return;
    }
    if (!/^\+?[0-9]{8,20}$/.test(phone)) {
        alert('Nomor HP hanya boleh berisi angka (8-20 digit, boleh diawali +).');
        return;
    }

    document.getElementById('f-name').value  = name;
    document.getElementById('f-nim').value   = nim;
    document.getElementById('f-email').value = email;
    document.getElementById('f-phone').value = phone;

    // Bug fix (High): cegah double-click / submit berkali-kali. Begitu form
    // ini disubmit, tombol langsung dikunci sampai halaman pindah/reload,
    // jadi klik berulang tidak akan mengirim request process() berkali-kali.
    const form = document.getElementById('process-form');
    if (form.dataset.submitted === '1') return;
    form.dataset.submitted = '1';

    const submitBtn = document.getElementById('simetix-confirm-ok');
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.style.opacity = '0.6';
        submitBtn.textContent = 'Memproses...';
    }

    form.submit();
}

function showKonfirmasiPembeli() {
    const name  = document.getElementById('buyer-name').value.trim();
    const nim   = document.getElementById('buyer-nim').value.trim();
    const email = document.getElementById('buyer-email').value.trim();
    const phone = document.getElementById('buyer-phone').value.trim();

    if (!name || !nim || !email || !phone) {
        alert('Lengkapi semua data pembeli terlebih dahulu.');
        return;
    }

    SimetixConfirm.show({
        title  : 'Konfirmasi Pembelian',
        message: 'Tiket akan dikirim ke email berikut. Pastikan data sudah benar.',
        detail : `Nama : ${name}\nEmail: ${email}\nPhone: ${phone}`,
        confirm: 'Ya, Lanjutkan Bayar',
        cancel : 'Periksa Lagi',
        type   : 'info',
        onConfirm: () => { validateAndGoToPayment(); }
    });
}

function openMidtrans() {
    const token = '{{ session("snap_token") }}';

    if (!token) {
        alert('Token pembayaran tidak tersedia. Silakan coba lagi.');
        return;
    }

    const btn = document.getElementById('pay-button');
    btn.disabled = true;
    btn.innerHTML = `
        <svg class="w-4 h-4 flex-shrink-0 animate-spin" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
        </svg>
        Memuat...`;

    snap.pay(token, {
        onSuccess: function(result) {
            window.location.href = '{{ route("checkout.summary", $event->slug) }}?transaction_status=' + result.transaction_status + '&order_id=' + result.order_id;
        },
        onPending: function(result) {
            window.location.href = '{{ route("checkout.summary", $event->slug) }}?transaction_status=pending&order_id=' + result.order_id;
        },
        onError: function(result) {
            alert('Pembayaran gagal: ' + (result.status_message || 'Silakan coba lagi.'));
            btn.disabled = false;
            btn.innerHTML = `
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
                Bayar Sekarang`;
        },
        onClose: function() {
            btn.disabled = false;
            btn.innerHTML = `
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
                Bayar Sekarang`;
        }
    });
}

function goToStep(step) {
    [1, 2, 3].forEach(s => {
        document.getElementById('page-' + s)?.classList.add('hidden');
    });
    document.getElementById('page-' + step)?.classList.remove('hidden');

    [1, 2, 3].forEach(s => {
        const dot   = document.getElementById('step-dot-' + s);
        const label = document.getElementById('step-label-' + s);
        if (!dot) return;
        if (s <= step) {
            dot.className   = 'w-7 h-7 rounded-full step-done flex items-center justify-center text-xs font-bold';
            label.className = 'text-xs font-medium text-[#6B0080] hidden sm:block';
        } else {
            dot.className   = 'w-7 h-7 rounded-full step-inactive flex items-center justify-center text-xs font-bold';
            label.className = 'text-xs font-medium text-gray-400 hidden sm:block';
        }
    });

    ['12', '23'].forEach(pair => {
        const line = document.getElementById('step-line-' + pair);
        if (!line) return;
        line.className = 'w-12 h-0.5 ' + (step > parseInt(pair[0]) ? 'step-line-active' : 'step-line-inactive');
    });

    window.scrollTo({ top: 0, behavior: 'smooth' });
}

@if(session('snap_token') && session('on_step') == 2)
    document.addEventListener('DOMContentLoaded', function() {
        goToStep(2);
        setTimeout(openMidtrans, 500);
    });
@endif

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

<x-confirm-dialog />

</body>
</html>
