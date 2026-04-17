<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SIMETIX</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
 <style>
        button, a, .cursor-pointer {
            cursor: pointer;
        }
    </style>
<body class="relative min-h-screen">
<nav class="bg-[#8A008A]/90 backdrop-blur-md fixed w-full z-30 top-0 px-4 py-2 shadow-md">

    <div class="flex justify-between items-center max-w-screen-2x1 mx-auto">
<a href="/">
        <svg class="w-4 h-4 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14 10">
    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5H1m0 0 4 4M1 5l4-4"/>
</svg>
</a>


        <div class="hidden md:flex space-x-8 text-white font-medium" id="navbar-sticky">
        <a href="/" class="flex items-center space-x-2">
            <img src="{{ asset('img/logo.png') }}" class="h-10">
            <span class="text-white text-2xl font-bold">SIMETIX</span>
        </a>
        </div>
</nav>
    <!-- BACKGROUND BLUR -->
    <div class="fixed inset-0 -z-10">
        <img src="/poster/image.png"
             class="w-full h-full object-cover blur-md scale-110" />
        <div class="absolute inset-0 bg-black/40"></div>
    </div>

    <!-- CONTAINER -->
    <div class="max-w-6xl mx-auto px-4 py-6 mt-17">

        <!-- HEADER / IMAGE BESAR -->
        <div class="rounded-2xl overflow-hidden shadow-lg mb-6">
            <img src="/poster/image.png"
                 class="w-full h-[250px] md:h-[300px] object-cover" />
        </div>

        <!-- SECTION UTAMA -->
        <div class="grid md:grid-cols-3 gap-6">

            <!-- KIRI (TIKET) -->
            <div class="space-y-4">
                <div class="bg-white/80 backdrop-blur rounded-xl p-4 shadow">
                    <p class="font-semibold">EARLY BIRD</p>
                    <p class="text-right">Rp. 20.000</p>
                </div>

                <div class="bg-white/80 backdrop-blur rounded-xl p-4 shadow">
                    <p class="font-semibold">NORMAL</p>
                    <p class="text-right">Rp. 50.000</p>
                </div>

                <div class="bg-white/80 backdrop-blur rounded-xl p-4 shadow">
                    <p class="font-semibold">VIP</p>
                    <p class="text-right">Rp. 70.000</p>
                </div>

                <button class="w-full bg-[#8A008A] hover:bg-purple-600 text-white py-2 rounded-xl transition">
                    Beli Sekarang
                </button>
            </div>

            <!-- TENGAH (DESKRIPSI) -->
            <div class="md:col-span-2 space-y-4">
                <div class="bg-white/80 backdrop-blur rounded-xl p-5 shadow">
                    <h2 class="text-xl font-bold mb-2">Pagelaran Teknovasi</h2>
                    <p class="text-sm text-gray-600">Batam, 19–21 Agustus 2025</p>
                    <p class="text-sm text-gray-600">Politeknik Negeri Batam</p>
                </div>

                <div class="bg-white/80 backdrop-blur rounded-xl p-5 shadow text-sm leading-relaxed">
                    <p>
                        Ajang kolaboratif terbesar tahun ini! Dari kompetisi internasional hingga bazaar kreatif.
                    </p>
                    <ul class="list-disc ml-5 mt-2">
                        <li>SEASIC 2025</li>
                        <li>Roboboat Competition</li>
                        <li>CDIO Meeting</li>
                        <li>PBL Expo</li>
                        <li>Job Fair</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- BAGIAN BAWAH (2 GAMBAR) -->
        <div class="grid md:grid-cols-3 gap-6 mt-6">

            <div class="rounded-2xl overflow-hidden shadow-lg">
                <img src="/poster/image.png" class="w-full h-[300px] object-cover" />
            </div>

            <div class="md:col-span-2 rounded-2xl overflow-hidden shadow-lg">
                <img src="/poster/image.png" class="w-full h-[300px] object-cover" />
            </div>

        </div>

    </div>
<footer class="bg-[#8A008A] text-white py-3">
        <div class="text-center text-xs opacity-60">
            &copy; 2026 SIMETIX - All Rights Reserved.
        </div>
    </footer>
</body>
</html>
