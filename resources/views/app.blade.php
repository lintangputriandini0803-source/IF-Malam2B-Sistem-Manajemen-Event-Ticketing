<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMETIX - Event & Ticketing</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans text-gray-900">

    <nav class="bg-[#8A008A] fixed w-full z-30 top-0 start-0 px-4 py-3 shadow-md">
        <div class="flex flex-wrap justify-between items-center mx-auto max-w-screen-xl">
        <a href="/" class="flex items-center space-x-2">
            <div class="w-12 h-13 flex items-center justify-center">
                <img src="{{ asset('img/logo.png') }}" alt="Logo Simetix" class="h-full w-auto object-contain">
            </div>
            <span class="text-white text-2xl font-bold tracking-wider">SIMETIX</span>
        </a>

            <div class="flex items-center space-x-8">
                <div class="hidden md:flex space-x-8 text-sm font-medium">
                    <a href="#" class="text-white hover:text-gray-200">Event</a>
                    <a href="#" class="text-white hover:text-gray-200">Tentang Kami</a>
                </div>
                
                <button class="bg-white text-[#8A008A] font-bold rounded-full text-sm px-8 py-2 flex items-center shadow-lg hover:bg-gray-100">
                    Login
                    <svg class="w-4 h-4 ms-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                </button>
            </div>
        </div>
    </nav>

    <main class="mt-24 max-w-screen-xl mx-auto p-4">
        
        <div class="flex items-center gap-0 mb-8 max-w-4xl mx-auto">
            <div class="relative flex-grow">
                <div class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" class="block w-full p-3 ps-12 text-sm text-gray-900 border border-gray-300 rounded-s-xl bg-gray-100 focus:ring-purple-500 focus:border-purple-500" placeholder="Cari event...">
            </div>
            <button class="p-3 bg-gray-200 border-y border-gray-300 hover:bg-gray-300">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
            </button>
            <button class="bg-[#8A008A] text-white px-8 py-3 text-sm font-bold rounded-e-xl hover:bg-purple-800 transition">
                Search
            </button>
        </div>

        <div id="default-carousel" class="relative w-full mb-12 shadow-xl rounded-2xl overflow-hidden" data-carousel="slide">
            <div class="relative h-64 md:h-[400px] bg-gray-300 flex items-center justify-center">
                <div class="absolute bottom-5 flex space-x-2">
                    <div class="w-2 h-2 rounded-full bg-gray-800"></div>
                    <div class="w-2 h-2 rounded-full bg-gray-500"></div>
                    <div class="w-2 h-2 rounded-full bg-gray-500"></div>
                    <div class="w-2 h-2 rounded-full bg-gray-500"></div>
                </div>
                <span class="text-gray-400 font-bold text-xl uppercase tracking-widest">Banner Promo Event</span>
            </div>
        </div>

        <h2 class="text-3xl font-extrabold text-center text-[#8A008A] mb-12">Sistem Manajemen Event & Ticketing</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8 mb-12">
            @for ($i = 0; $i < 8; $i++)
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden transform hover:scale-105 transition duration-300">
                <div class="h-48 bg-[#F3F4F6] relative border-b border-gray-200 flex items-center justify-center">
                    <svg class="w-full h-full text-gray-300 p-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="1" d="M0 0 L24 24 M24 0 L0 24"></path>
                    </svg>
                </div>
                <div class="p-5">
                    <h3 class="font-bold text-[#8A008A] text-lg mb-3">Judul Event</h3>
                    <div class="space-y-2">
                        <div class="flex items-center text-[10px] font-bold text-gray-700 uppercase">
                            <span class="w-4 h-4 bg-[#8A008A] rounded-sm me-3"></span>
                            00.00 - 00.00
                        </div>
                        <div class="flex items-center text-[10px] font-bold text-gray-700 uppercase">
                            <span class="w-4 h-4 bg-[#8A008A] rounded-sm me-3"></span>
                            Mulai Dari Rp. 00.000,00
                        </div>
                    </div>
                </div>
            </div>
            @endfor
        </div>

        <div class="flex justify-center mb-20">
            <button class="px-10 py-3 text-sm font-bold text-white bg-[#8A008A] rounded-full hover:bg-purple-800 shadow-lg transition flex items-center">
                Lihat Event Lainnya <span class="ms-2">></span>
            </button>
        </div>
    </main>

    <footer class="bg-[#8A008A] text-white py-12">
        <div class="max-w-screen-xl mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-12 text-center md:text-left">
            <div>
                <h4 class="font-bold mb-4 uppercase tracking-tighter">Events</h4>
                <ul class="text-xs space-y-2 opacity-90 font-light">
                    <li><a href="#" class="hover:underline">Cari Event</a></li>
                    <li><a href="#" class="hover:underline">Buat Event</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold mb-4 uppercase tracking-tighter">Tentang Website</h4>
                <ul class="text-xs space-y-2 opacity-90 font-light">
                    <li><a href="#" class="hover:underline">Tentang Kami</a></li>
                    <li><a href="#" class="hover:underline">Tutorial Memesan Tiket</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold mb-4 uppercase tracking-tighter">Kategori Event</h4>
                <ul class="text-xs space-y-2 opacity-90 font-light">
                    <li><a href="#" class="hover:underline">Olahraga</a></li>
                    <li><a href="#" class="hover:underline">Entertainment</a></li>
                    <li><a href="#" class="hover:underline">Seminar</a></li>
                    <li><a href="#" class="hover:underline">Bisnis</a></li>
                    <li><a href="#" class="hover:underline">Other</a></li>
                </ul>
            </div>
        </div>
    </footer>
</body>
</html>