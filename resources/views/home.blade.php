<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMETIX - Event & Ticketing</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
    /* Menampilkan garis di semua elemen agar terlihat strukturnya */
    * {
        outline: 1px solid rgba(255, 0, 0, 0.2);
    }
        body{
            background: rgb(216, 216, 216) ;
        }
        button, a, .cursor-pointer {
            cursor: pointer;
        }
    </style>
</head>

<body class="bg-gray=-50 font-sans text-gray-900 relative overflow-x-hidden">

<!-- BACKGROUND ORNAMEN -->
<div class="absolute inset-0 opacity-10 pointer-events-none">
    <div class="absolute top-10 left-10 w-20 h-20 bg-purple-500 rounded-full blur-2xl"></div>
    <div class="absolute bottom-20 right-20 w-32 h-32 bg-pink-400 rounded-full blur-2xl"></div>
</div>

<!-- NAVBAR -->
<nav class="bg-[#8A008A]/90 backdrop-blur-md fixed w-full z-30 top-0 px-4 py-3 shadow-md">
    <div class="flex justify-between items-center max-w-screen-2x1 mx-auto">

        <a href="/" class="flex items-center space-x-2">
            <img src="{{ asset('img/logo.png') }}" class="h-10">
            <span class="text-white text-2xl font-bold">SIMETIX</span>
        </a>

        <div class="hidden md:flex space-x-8 text-white font-medium" id="navbar-sticky">
            <a href="#" class="hover:text-gray-200">Event</a>
            <a href="#" class="hover:text-gray-200">Tentang Kami</a>

            <button class="bg-white text-[#8A008A] font-bold px-3  rounded-full shadow hover:bg-gray-100"  id="dropdownNavbarLink" data-dropdown-toggle="user-dropdown">
                <svg class="w-2.5 h-2.5 " fill="none" viewBox="0 0 10 6"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/></svg>
            </button>
            <div id="user-dropdown" class="z-10 hidden font-normal bg-white divide-y divide-gray-100 rounded-lg shadow w-44">
                <div class="px-4 py-3 text-black text-sm border-b border-default">
                  <span class="block text-heading font-medium">Joseph McFall</span>
                  <span class="block text-body truncate">name@flowbite.com</span>
                </div>
                    <ul class="py-2 text-sm text-gray-700">
                        <li><a href="#" class="block px-4 py-2 hover:bg-gray-100">Event Saya</a></li>
                        <li><a href="#" class="block px-4 py-2 hover:bg-gray-100">Buat Event</a></li>
                        <li><a href="#" class="block px-4 py-2 hover:bg-gray-100">Pengaturan</a></li>
                    </ul>
                    <div class="py-1">
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 font-bold text-red-600">Sign Out</a>
                    </div>
                </div>
            </div>

            <button data-collapse-toggle="navbar-sticky" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-white rounded-lg md:hidden hover:bg-purple-700 focus:outline-none" aria-controls="navbar-sticky" aria-expanded="false">
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/></svg>
        </button>
        </div>
</nav>

<!-- MAIN -->
<main class="mt-16 mx-auto ">


    <!-- BANNER -->
    <div class="relative h-64 md:h-[300px] mb-8  overflow-hidden shadow-lg">
        <img src="{{ asset('img/banner.jpg') }}" class="w-full h-full object-cover">

        <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
        </div>
    </div>


    <!-- TITLE -->
    <h2 class="text-3xl font-extrabold text-center text-[#8A008A] mb-8">
        Sistem Manajemen Event & Ticketing
    </h2>

    <!-- SEARCH -->
    <div class="flex max-w-2xl mx-auto mb-10 shadow rounded-xl overflow-hidden">
        <input type="text"
            class="w-full p-2 pl-4 border-none focus:ring-2 focus:ring-purple-500"
            placeholder="Cari event...">

        <button class="bg-[#8A008A] text-white px-6">
            Search
        </button>
    </div>

    <!-- GRID EVENT -->
    <div class="max-w-screen-2xl px-5 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-10 mx-auto mb-12">

        @foreach ($events as $event)
        <div class="w-[280px] bg-white rounded-2xl shadow-md overflow-hidden
                    hover:shadow-xl hover:-translate-y-2
                    transition duration-300 cursor-pointer">

            <div class="h-48 overflow-hidden">
                <img src="{{ asset('poster/' . $event['Poster']) }}"
     class="w-full h-full object-cover hover:scale-110 transition duration-300">
            </div>

            <div class="p-5">
                <h3 class="font-bold text-[#8A008A] text-lg mb-2">
                    {{ $event['nama'] }}
                </h3>

                <p class="text-sm text-gray-500 mb-2">
                    {{ $event['waktu'] }}
                </p>

                <p class="text-sm font-semibold text-purple-700">
                    Rp {{ number_format($event['harga'], 0, ',', '.') }}
                </p>
            </div>
        </div>
        @endforeach

    </div>

    <!-- BUTTON -->
    <div class="flex justify-center mb-20">
        <button class="px-10 py-3 text-sm font-bold text-white
                       bg-gradient-to-r from-purple-600 to-pink-500
                       rounded-full shadow-lg
                       hover:scale-105 hover:shadow-xl
                       transition">
            Lihat Event Lainnya →
        </button>
    </div>

</main>

<!-- FOOTER -->
<footer class="bg-[#8A008A] text-white py-12">
        <div class="max-w-screen-xl mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-12 text-center ">
            <div>
                <h4 class="font-bold mb-4 uppercase text-lg">Events</h4>
                <ul class="text-sm space-y-2 opacity-80 font-medium">
                    <li><a href="#" class="hover:underline">Cari Event</a></li>
                    <li><a href="#" class="hover:underline">Buat Event</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold mb-4 uppercase text-lg">Tentang Website</h4>
                <ul class="text-sm space-y-2 opacity-80 font-medium">
                    <li><a href="#" class="hover:underline">Tentang Kami</a></li>
                    <li><a href="#" class="hover:underline">Tutorial Pesan</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold mb-4 uppercase text-lg">Hubungi Kami</h4>
             <ul class="text-sm space-y-2">
                <li>Olahraga</li>
                <li>Musik</li>
                <li>Seminar</li>
            </ul>
            </div>
        </div>
        <div class="border-t border-purple-800 mt-5 pt-6 text-center text-xs opacity-60">
            &copy; 2026 SIMETIX - All Rights Reserved.
        </div>
    </footer>

</body>
</html>
