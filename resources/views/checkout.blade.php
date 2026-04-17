<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMETIX - Ringkasan Pemesanan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#E5D5E5] font-sans text-gray-900">

<nav class="bg-white backdrop-blur-md fixed w-full z-30 top-0 px-4 py-2 shadow-md">
    <div class="max-w-3xl mx-auto py-2 px-4">
        <ol class="flex items-center w-full text-sm font-medium text-center text-gray-500 sm:text-base">
            <li class="flex md:w-full items-center text-[#8A008A] sm:after:content-[''] after:w-full after:h-1 after:border-b after:border-gray-200 after:border-1 after:hidden sm:after:inline-block after:mx-6">
                <span class="flex items-center after:content-['/'] sm:after:hidden after:mx-2 after:text-gray-200">
                    <span class="flex items-center justify-center w-6 h-6 me-2 text-xs border border-purple-700 rounded-full shrink-0">1</span>
                    Detail
                </span>
            </li>
            <li class="flex md:w-full items-center after:content-[''] after:w-full after:h-1 after:border-b after:border-gray-200 after:border-1 after:hidden sm:after:inline-block after:mx-6">
                <span class="flex items-center after:content-['/'] sm:after:hidden after:mx-2 after:text-gray-200">
                    <span class="flex items-center justify-center w-6 h-6 me-2 text-xs border border-gray-500 rounded-full shrink-0">2</span>
                    Pembayaran
                </span>
            </li>
            <li class="flex items-center">
                <span class="flex items-center justify-center w-6 h-6 me-2 text-xs border border-gray-500 rounded-full shrink-0">3</span>
                Ringkasan
            </li>
        </ol>
    </div>
</nav>

    <main class="max-w-4xl mx-auto px-4 pb-20">

        <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
            <h2 class="text-xl font-bold mb-4">Ringkasan Pemesanan</h2>

            <div class="flex items-center space-x-4 border-b pb-4 mb-4">
                <img src="{{ asset('img/event-mini.jpg') }}" class="w-16 h-16 rounded-lg object-cover">
                <div>
                    <h3 class="font-bold text-gray-800">POLIBATAM FAIR 2026</h3>
                    <p class="text-xs text-gray-500 italic">Batam, Kepulauan Riau</p>
                    <p class="text-xs text-gray-500">Jumat, 17 April 2026 | 09:00 - Selesai</p>
                </div>
            </div>

            <div class="space-y-3 text-sm">
                <div class="flex justify-between uppercase">
                    <span>Early Bird - Tiket Festival <span class="text-gray-400">x 1</span></span>
                    <span class="font-semibold">Rp. 35.000</span>
                </div>
                <div class="flex justify-between uppercase">
                    <span>Normal - Tiket Festival <span class="text-gray-400">x 1</span></span>
                    <span class="font-semibold">Rp. 50.000</span>
                </div>
                <div class="flex justify-between uppercase border-b pb-3">
                    <span>VIP - Tiket Festival <span class="text-gray-400">x 1</span></span>
                    <span class="font-semibold">Rp. 75.000</span>
                </div>
                <div class="flex justify-between text-lg font-bold pt-2">
                    <span>Total</span>
                    <span class="text-purple-800">Rp. 160.000</span>
                </div>
            </div>
        </div>

        <div class="mb-8">
            <h2 class="text-lg font-bold mb-1">Ringkasan Pembeli</h2>
            <p class="text-sm text-gray-600 mb-4 italic">E-Tiket akan dikirim ke email anda</p>
            <div class="bg-white rounded-xl shadow-sm p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold mb-2">Nama <span class="text-red-500">*</span></label>
                        <input type="text" class="w-full border-0 border-b-2 border-gray-300 focus:border-purple-600 focus:ring-0 px-0 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-2">NIM <span class="text-red-500">*</span></label>
                        <input type="text" class="w-full border-0 border-b-2 border-gray-300 focus:border-purple-600 focus:ring-0 px-0 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-2">Email <span class="text-red-500">*</span></label>
                        <input type="email" class="w-full border-0 border-b-2 border-gray-300 focus:border-purple-600 focus:ring-0 px-0 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-2">Prodi <span class="text-red-500">*</span></label>
                        <input type="text" class="w-full border-0 border-b-2 border-gray-300 focus:border-purple-600 focus:ring-0 px-0 py-2">
                    </div>
                </div>
            </div>
        </div>

        @php
            $ticketTypes = ['Early Bird - Tiket Festival', 'Normal - Tiket Festival', 'VIP - Tiket Festival'];
        @endphp

        @foreach($ticketTypes as $type)
        <div class="mb-8">
            <div class="flex items-center space-x-3 mb-4">
                <h2 class="text-lg font-bold uppercase">{{ $type }}</h2>
                <span class="bg-purple-200 text-purple-700 text-[10px] px-2 py-0.5 rounded font-bold uppercase">1 Tiket</span>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold mb-2">Nama <span class="text-red-500">*</span></label>
                        <input type="text" class="w-full border-0 border-b-2 border-gray-300 focus:border-purple-600 focus:ring-0 px-0 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-2">NIM <span class="text-red-500">*</span></label>
                        <input type="text" class="w-full border-0 border-b-2 border-gray-300 focus:border-purple-600 focus:ring-0 px-0 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-2">Email <span class="text-red-500">*</span></label>
                        <input type="email" class="w-full border-0 border-b-2 border-gray-300 focus:border-purple-600 focus:ring-0 px-0 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-2">Prodi <span class="text-red-500">*</span></label>
                        <input type="text" class="w-full border-0 border-b-2 border-gray-300 focus:border-purple-600 focus:ring-0 px-0 py-2">
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        <div class="mt-12 flex justify-center">
            <button class="w-full md:w-2/3 bg-[#8A008A] hover:bg-purple-800 text-white font-bold py-4 rounded-xl shadow-lg transition duration-300">
                Lanjutkan Pembayaran
            </button>
        </div>

    </main>

</body>
</html>
