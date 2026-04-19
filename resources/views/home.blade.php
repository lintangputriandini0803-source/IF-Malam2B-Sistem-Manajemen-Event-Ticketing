<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMETIX - Event & Ticketing</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        button, a, .cursor-pointer { cursor: pointer; }
        body { background: rgb(216, 216, 216); }
    </style>
</head>

<body class="font-sans text-gray-900 relative overflow-x-hidden">

<!-- BACKGROUND ORNAMEN -->
<div class="absolute inset-0 opacity-10 pointer-events-none">
    <div class="absolute top-10 left-10 w-20 h-20 bg-purple-500 rounded-full blur-2xl"></div>
    <div class="absolute bottom-20 right-20 w-32 h-32 bg-pink-400 rounded-full blur-2xl"></div>
</div>

<!-- NAVBAR -->
<nav class="bg-[#8A008A]/90 backdrop-blur-md fixed w-full z-30 top-0 px-4 py-3 shadow-md">
    <div class="flex justify-between items-center max-w-screen-2xl mx-auto">

        <a href="{{ route('home') }}" class="flex items-center space-x-2">
            <img src="{{ asset('img/logo.png') }}" class="h-10">
            <span class="text-white text-2xl font-bold">SIMETIX</span>
        </a>

        <div class="hidden md:flex items-center space-x-8 text-white font-medium">
            <a href="{{ route('home') }}" class="hover:text-gray-200">Event</a>
            <a href="#" class="hover:text-gray-200">Tentang Kami</a>

            <div class="relative">
                <button class="bg-white text-[#8A008A] font-bold px-3 py-1 rounded-full shadow hover:bg-gray-100"
                        id="dropdownNavbarLink" data-dropdown-toggle="user-dropdown">
                    <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                    </svg>
                </button>
                <div id="user-dropdown" class="z-10 hidden font-normal bg-white divide-y divide-gray-100 rounded-lg shadow w-44 absolute right-0 mt-2">
                    <div class="px-4 py-3 text-black text-sm border-b">
                        <span class="block font-medium">Anda Belum Login</span>
                        <span class="block text-gray-500 truncate">-</span>
                    </div>
                    <ul class="py-2 text-sm text-gray-700">
                        <li>
                            <a data-modal-target="authentication-modal"
                               data-modal-toggle="authentication-modal"
                               class="block px-4 py-2 hover:bg-gray-100 cursor-pointer">Login</a>
                        </li>
                        <li>
                            <a href="{{ route('register') }}" class="block px-4 py-2 hover:bg-gray-100">Daftar sebagai Panitia</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <button data-collapse-toggle="navbar-mobile" type="button"
                class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-white rounded-lg md:hidden hover:bg-purple-700">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
            </svg>
        </button>
    </div>
</nav>

<!-- MAIN -->
<main class="mt-16 mx-auto">

    <!-- BANNER -->
    <div class="relative h-64 md:h-[300px] mb-8 overflow-hidden shadow-lg">
        <img src="{{ asset('img/banner.jpg') }}" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
            <h1 class="text-white text-4xl font-extrabold drop-shadow-lg">Temukan Event Terbaik</h1>
        </div>
    </div>

    <!-- TITLE -->
    <h2 class="text-3xl font-extrabold text-center text-[#8A008A] mb-8">
        Sistem Manajemen Event & Ticketing
    </h2>

    <!-- SEARCH (terhubung ke controller) -->
    <form method="GET" action="{{ route('home') }}" class="flex max-w-2xl mx-auto mb-10 shadow rounded-xl overflow-hidden">
        <input type="text"
               name="search"
               value="{{ request('search') }}"
               class="w-full p-2 pl-4 border-none focus:ring-2 focus:ring-purple-500"
               placeholder="Cari event, lokasi, atau kategori...">
        <button type="submit" class="bg-[#8A008A] text-white px-6 hover:bg-purple-700 transition">
            Cari
        </button>
    </form>

    <!-- INFO HASIL SEARCH -->
    @if(request('search'))
    <p class="text-center text-gray-600 mb-6">
        Hasil pencarian untuk: <strong>"{{ request('search') }}"</strong>
        ({{ $events->total() }} event ditemukan)
        <a href="{{ route('home') }}" class="text-purple-600 ml-2 hover:underline">Reset</a>
    </p>
    @endif

    <!-- GRID EVENT -->
    <div class="max-w-screen-2xl px-5 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-10 mx-auto mb-12">

        @forelse ($events as $event)
        {{-- Seluruh card bisa diklik ke halaman detail --}}
        <a href="{{ route('event.show', $event->slug) }}" class="block">
            <div class="w-full bg-white rounded-2xl shadow-md overflow-hidden
                        hover:shadow-xl hover:-translate-y-2
                        transition duration-300 cursor-pointer">

                <div class="h-48 overflow-hidden">
                    @if($event->poster)
                        <img src="{{ asset('poster/' . $event->poster) }}"
                             class="w-full h-full object-cover hover:scale-110 transition duration-300"
                             alt="{{ $event->title }}">
                    @else
                        <div class="w-full h-full bg-purple-100 flex items-center justify-center">
                            <span class="text-purple-400 text-4xl">🎫</span>
                        </div>
                    @endif
                </div>

                <div class="p-5">
                    <h3 class="font-bold text-[#8A008A] text-lg mb-2 line-clamp-2">
                        {{ $event->title }}
                    </h3>

                    <p class="text-sm text-gray-500 mb-1">
                        📅 {{ $event->event_date }}
                    </p>

                    <p class="text-sm text-gray-500 mb-2">
                        📍 {{ $event->location }}
                    </p>

                    @if($event->ticketTypes->isNotEmpty())
                        <p class="text-sm font-semibold text-purple-700">
                            Mulai Rp {{ number_format($event->ticketTypes->min('price'), 0, ',', '.') }}
                        </p>
                    @else
                        <p class="text-sm font-semibold text-green-600">Gratis</p>
                    @endif
                </div>
            </div>
        </a>
        @empty
        <div class="col-span-4 text-center py-20 text-gray-500">
            <p class="text-5xl mb-4">🔍</p>
            <p class="text-lg font-semibold">Tidak ada event ditemukan.</p>
            @if(request('search'))
                <a href="{{ route('home') }}" class="text-purple-600 hover:underline mt-2 inline-block">Lihat semua event</a>
            @endif
        </div>
        @endforelse

    </div>

    <!-- PAGINATION -->
    @if($events->hasPages())
    <div class="flex justify-center mb-10">
        {{ $events->withQueryString()->links() }}
    </div>
    @endif

    <!-- BUTTON LIHAT LAINNYA (hanya muncul jika tidak sedang search) -->
    @if(!request('search'))
    <div class="flex justify-center mb-20">
        <a href="{{ route('home') }}"
           class="px-10 py-3 text-sm font-bold text-white
                  bg-gradient-to-r from-purple-600 to-pink-500
                  rounded-full shadow-lg hover:scale-105 hover:shadow-xl transition">
            Lihat Event Lainnya →
        </a>
    </div>
    @endif

</main>

<!-- FOOTER -->
<footer class="bg-[#8A008A] text-white py-12">
    <div class="max-w-screen-xl mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-12 text-center">
        <div>
            <h4 class="font-bold mb-4 uppercase text-lg">Events</h4>
            <ul class="text-sm space-y-2 opacity-80 font-medium">
                <li><a href="{{ route('home') }}" class="hover:underline">Cari Event</a></li>
                <li><a href="{{ route('register') }}" class="hover:underline">Buat Event</a></li>
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
            <h4 class="font-bold mb-4 uppercase text-lg">Kategori</h4>
            <ul class="text-sm space-y-2 opacity-80 font-medium">
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

<!-- MODAL LOGIN -->
<div id="authentication-modal" tabindex="-1" aria-hidden="true"
     class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-2xl shadow-sm p-4 md:p-6">
            <div class="flex items-center justify-between border-b pb-4 mb-4">
                <h3 class="text-lg font-medium">Login</h3>
                <button type="button" data-modal-hide="authentication-modal"
                        class="text-gray-400 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="email" class="block mb-2 text-sm font-medium">Email</label>
                    <input type="email" name="email" id="email"
                           class="bg-gray-50 border border-gray-300 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5"
                           placeholder="example@email.com" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="block mb-2 text-sm font-medium">Password</label>
                    <input type="password" name="password" id="password"
                           class="bg-gray-50 border border-gray-300 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5"
                           placeholder="••••••••" required>
                </div>
                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" name="remember" class="rounded"> Remember me
                    </label>
                    <a href="#" class="text-sm text-purple-700 hover:underline">Lupa password?</a>
                </div>
                <button type="submit"
                        class="w-full text-white bg-[#8A008A] hover:bg-purple-700 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-3">
                    Login
                </button>
                <p class="text-sm text-gray-500 text-center">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="text-purple-700 hover:underline">Daftar sebagai Panitia</a>
                </p>
            </form>
        </div>
    </div>
</div>

</body>
</html>
