<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIMETIX - Event & Ticketing')</title>
    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/x-icon">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        * { font-family: 'Plus Jakarta Sans', sans-serif; }

        button, a, .cursor-pointer { cursor: pointer; }

        body {
            background: #ede9f4;
            background-image:
                radial-gradient(ellipse at 0% 0%, rgba(107,0,128,0.06) 0%, transparent 60%),
                radial-gradient(ellipse at 100% 100%, rgba(107,0,128,0.04) 0%, transparent 55%);
        }

        html { overflow-y: scroll; }

        /* ── NAVBAR ── */
        .navbar {
            background: rgba(74, 0, 90, 0.97);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255,255,255,0.07);
            box-shadow: 0 1px 24px rgba(74,0,90,0.28);
        }
        .nav-link {
            position: relative;
            color: rgba(255,255,255,0.82);
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            transition: color 0.2s;
            padding-bottom: 2px;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -2px; left: 0; right: 0;
            height: 2px;
            background: rgba(255,255,255,0.5);
            border-radius: 2px;
            transform: scaleX(0);
            transition: transform 0.2s;
        }
        .nav-link:hover { color: #fff; }
        .nav-link:hover::after { transform: scaleX(1); }
        .nav-link.active { color: #fff; }
        .nav-link.active::after { transform: scaleX(1); }

        .btn-login {
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.2);
            color: white;
            font-size: 13px;
            font-weight: 600;
            padding: 6px 16px;
            border-radius: 8px;
            transition: all 0.2s;
            letter-spacing: 0.01em;
        }
        .btn-login:hover {
            background: rgba(255,255,255,0.22);
            border-color: rgba(255,255,255,0.35);
        }
        /* ── HAMBURGER / MOBILE MENU ── */
        .hamburger {
            display: none;
            flex-direction: column;
            gap: 5px;
            background: transparent;
            border: none;
            padding: 4px;
            cursor: pointer;
            z-index: 40;
        }
        .hamburger span {
            display: block;
            width: 22px;
            height: 2px;
            background: white;
            border-radius: 2px;
            transition: transform 0.3s, opacity 0.3s;
        }
        .hamburger.open span:nth-child(1) { transform: translateY(7px) rotate(45deg); }
        .hamburger.open span:nth-child(2) { opacity: 0; }
        .hamburger.open span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }

        .mobile-menu {
            display: none;
            position: fixed;
            top: 56px; left: 0; right: 0;
            background: rgba(74, 0, 90, 0.98);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255,255,255,0.08);
            padding: 16px 20px 20px;
            z-index: 29;
            flex-direction: column;
            gap: 4px;
            box-shadow: 0 8px 24px rgba(74,0,90,0.3);
        }
        .mobile-menu.open { display: flex; }
        .mobile-menu a, .mobile-menu button {
            color: rgba(255,255,255,0.85);
            font-size: 15px;
            font-weight: 500;
            text-decoration: none;
            padding: 11px 4px;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            background: none;
            border-left: none;
            border-right: none;
            border-top: none;
            text-align: left;
            transition: color 0.2s;
        }
        .mobile-menu a:last-child, .mobile-menu button:last-child {
            border-bottom: none;
        }
        .mobile-menu a:hover, .mobile-menu button:hover { color: white; }
        .mobile-menu .mobile-login-btn {
            margin-top: 8px;
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.2) !important;
            border-radius: 8px;
            padding: 10px 16px;
            text-align: center;
            font-weight: 600;
            font-size: 14px;
        }
        .mobile-menu .mobile-login-btn:hover {
            background: rgba(255,255,255,0.22);
        }

        @media (max-width: 767px) {
            .hamburger { display: flex; }
        }

        /* ── SEARCH ── */
        .search-wrap {
            background: white;
            border-radius: 14px;
            box-shadow: 0 2px 8px rgba(107,0,128,0.08), 0 1px 2px rgba(0,0,0,0.04);
            overflow: visible;
            display: flex;
            position: relative;
        }
        .search-input {
            border: none;
            outline: none;
            padding: 13px 16px;
            font-size: 14px;
            color: #1a1a2e;
            background: transparent;
            flex: 1;
            border-radius: 14px 0 0 14px;
        }
        .search-input::placeholder { color: #a0a0b0; }
        .search-divider { width: 1px; background: #ede9f4; margin: 10px 0; }
        .btn-filter {
            padding: 0 14px;
            background: transparent;
            border: none;
            color: #6b7280;
            transition: color 0.2s;
        }
        .btn-filter:hover { color: #6B0080; }
        .btn-search {
            background: #6B0080;
            color: white;
            font-size: 13px;
            font-weight: 600;
            padding: 0 22px;
            border-radius: 0 14px 14px 0;
            border: none;
            letter-spacing: 0.02em;
            transition: background 0.2s;
        }
        .btn-search:hover { background: #580068; }

        /* ── SECTION LABEL ── */
        .section-label {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-size: 15px;
            font-weight: 700;
            color: #3a003f;
            letter-spacing: 0.01em;
        }
        .section-label::before, .section-label::after {
            content: '';
            display: block;
            height: 2px;
            width: 32px;
            background: linear-gradient(to right, #6B0080, transparent);
            border-radius: 2px;
        }
        .section-label::after { background: linear-gradient(to left, #6B0080, transparent); }

        /* ── EVENT CARD ── */
        .event-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 4px 12px rgba(107,0,128,0.06);
            transition: transform 0.25s cubic-bezier(0.34,1.56,0.64,1), box-shadow 0.25s ease;
            display: flex;
            flex-direction: column;
            height: 100%;
            border: 1px solid rgba(107,0,128,0.06);
        }
        .event-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 8px 24px rgba(107,0,128,0.14), 0 2px 8px rgba(0,0,0,0.08);
        }
        .event-card-img {
            height: 180px;
            overflow: hidden;
            position: relative;
        }
        .event-card-img img {
            width: 100%; height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }
        .event-card:hover .event-card-img img { transform: scale(1.05); }
        .event-card-body {
            padding: 16px 18px 18px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .event-title {
            font-size: 15px;
            font-weight: 700;
            color: #3a003f;
            line-height: 1.35;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            margin-bottom: 10px;
        }
        .event-meta { margin-top: auto; }
        .event-meta-row {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: #7a7a8a;
            margin-bottom: 4px;
            line-height: 1.4;
        }
        .event-meta-row svg { width: 13px; height: 13px; color: #9B30AF; flex-shrink: 0; }
        .event-price {
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid #f0ebf5;
            font-size: 13px;
            font-weight: 700;
            color: #6B0080;
        }
        .event-price.free { color: #16a34a; }

        /* ── CAROUSEL ── */
        .carousel-wrap {
            position: relative;
            border-radius: 0;
            overflow: hidden;
            width: 100%;
            box-shadow: 0 4px 32px rgba(107,0,128,0.15);

        }
        .carousel-overlay {
            position: absolute; inset: 0;
            background: linear-gradient(to bottom, transparent 40%, rgba(74,0,90,0.55) 100%);
            pointer-events: none;
            z-index: 2;
        }
        #carousel-slides {
            display: flex;
            width: 100%;
            will-change: transform;
        }
        .carousel-slide {
            min-width: 100%;      /* ← pastikan ini ada */
            flex-shrink: 0;
            width: 100%;          /* ← tambahkan ini juga */
        }

        /* ── FOOTER ── */
        .footer-link {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            font-size: 13px;
            transition: color 0.2s;
        }
        .footer-link:hover { color: white; }

        /* ── MODAL ── */
        .modal-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 24px 64px rgba(0,0,0,0.18), 0 4px 16px rgba(107,0,128,0.12);
            border: 1px solid rgba(107,0,128,0.08);
        }
        .modal-input {
            width: 100%;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            padding: 9px 14px;
            font-size: 14px;
            color: #1a1a2e;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }
        .modal-input:focus {
            border-color: #6B0080;
            box-shadow: 0 0 0 3px rgba(107,0,128,0.1);
        }
        .btn-primary {
            width: 100%;
            background: #6B0080;
            color: white;
            font-weight: 600;
            font-size: 14px;
            padding: 11px;
            border-radius: 10px;
            border: none;
            transition: background 0.2s, transform 0.15s;
            letter-spacing: 0.02em;
        }
        .btn-primary:hover { background: #580068; transform: translateY(-1px); }
        .btn-primary:active { transform: translateY(0); }

        /* ── PURPLE PAGINATION ── */
        .pagination-purple nav > div > span,
        .pagination-purple nav > div > a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 38px;
            height: 38px;
            padding: 0 12px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
            border: 1.5px solid transparent;
        }
        .pagination-purple nav > div > a {
            color: #6B0080;
            background: white;
            border-color: rgba(107,0,128,0.15);
            box-shadow: 0 1px 3px rgba(107,0,128,0.06);
        }
        .pagination-purple nav > div > a:hover {
            background: #6B0080;
            color: white;
            border-color: #6B0080;
            box-shadow: 0 4px 12px rgba(107,0,128,0.25);
        }
        .pagination-purple nav > div > span[aria-current="page"] {
            background: #6B0080;
            color: white;
            border-color: #6B0080;
            box-shadow: 0 4px 12px rgba(107,0,128,0.3);
        }
        .pagination-purple nav > div > span:not([aria-current]) {
            color: #c4b5c8;
            background: white;
            border-color: rgba(107,0,128,0.08);
        }
        .pagination-purple nav {
            display: flex;
            justify-content: center;
        }
        .pagination-purple nav > div {
            display: flex;
            gap: 6px;
            align-items: center;
            flex-wrap: wrap;
            justify-content: center;
        }
    </style>
    @stack('styles')
</head>

<body class="font-sans text-gray-900 relative overflow-x-hidden">
    <x-toast />

<!-- NAVBAR -->
<!-- NAVBAR -->
<nav class="navbar fixed w-full z-30 top-0 px-5 py-3">
    <div class="flex justify-between items-center max-w-screen-xl mx-auto">
        <a href="{{ route('home') }}" class="flex items-center gap-2.5">
            <img src="{{ asset('img/logo.png') }}" class="h-9">
            <span class="text-white text-xl font-bold tracking-tight">SIMETIX</span>
        </a>

        {{-- Desktop menu --}}
        <div class="hidden md:flex items-center gap-7">
            <a href="{{ route('homepage') }}" class="nav-link {{ request()->routeIs('homepage') ? 'active' : '' }}">Event</a>
            <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">About Us</a>
            <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">Blog</a>

        </div>
        <div class="hidden md:flex items-center gap-7">
            <button onclick="openLoginModal()" class="btn-login">Login</button>

        </div>
        {{-- Hamburger button (mobile only) --}}
        <button class="hamburger md:hidden" id="hamburger-btn" onclick="toggleMobileMenu()" aria-label="Menu">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>
</nav>

{{-- Mobile dropdown menu --}}
<div class="mobile-menu" id="mobile-menu">
    <a href="{{ route('homepage') }}" onclick="closeMobileMenu()">Event</a>
    <a href="{{ route('about') }}" onclick="closeMobileMenu()">About Us</a>
    <a href="{{ route('about') }}" onclick="closeMobileMenu()">Blog</a>
    <button onclick="openLoginModal(); closeMobileMenu()" class="mobile-login-btn">Login</button>
</div>

<!-- MAIN CONTENT -->
<main class="mt-15">
    @if(session('success'))
    <div class="fixed top-20 left-1/2 -translate-x-1/2 w-full max-w-md px-4" style="z-index:999">
        <div class="bg-white border border-green-200 text-green-600 px-4 py-3 rounded-xl text-sm shadow-lg flex items-center gap-2">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('success') }}
        </div>
    </div>
    @endif

    @yield('content')
</main>

<!-- FOOTER -->
<footer class="bg-[#3d0049] text-white pt-12 pb-4">
    <div class="max-w-screen-xl mx-auto px-6 grid grid-cols-1 md:grid-cols-4 gap-10 mb-8">
        <div class="col-span-2">
            <div class="flex items-center gap-2 mb-2">
                <img src="{{ asset('img/logo.png') }}" class="h-8">
                <span class="font-bold text-3xl">SIMETIX</span>
            </div>
            <p class="text-xs text-white/80 mb-2">Platform manajemen event & ticketing untuk organisasi mahasiswa Polibatam.</p>
            <p class="text-xs text-white/80 mb-2">Polibatam : Jl. Ahmad Yani, Batam Kota, Batam 29461,Kepulauan Riau, Indonesia</p>
            <p class="text-xs text-white/80 mb-2">Contact Polibatam : (0778) 469856</p>
            <p class="text-xs text-white/80 mb-2">Contact Admin SIMETIX : 0878 4599 3443 - 080808080808 - 0080808808080</p>
        </div>

        <div>
            <h4 class="font-semibold mb-4 text-white/90 text-sm uppercase tracking-wider">Events</h4>
            <ul class="space-y-2">
                <li><a href="{{ route('homepage') }}" class="footer-link">Cari Event</a></li>
                <li><a onclick="openLoginModal()" class="footer-link cursor-pointer">Buat Event</a></li>
            </ul>
        </div>
        <div>
            <h4 class="font-semibold mb-4 text-white/90 text-sm uppercase tracking-wider">Kategori</h4>
            <ul class="space-y-2">
                <li><a href="{{ route('homepage') }}?category=4&search=" class="footer-link">Olahraga</a></li>
                <li><a href="{{ route('homepage') }}?category=2&search=" class="footer-link">Seminar</a></li>
                <li><a href="{{ route('homepage') }}?category=3&search=" class="footer-link">Musik</a></li>
            </ul>
        </div>
    </div>
    <div class="border-t border-white/8 pt-4 text-center text-xs text-white/35">
        &copy; 2026 SIMETIX — All Rights Reserved.
    </div>
</footer>

<!-- MODAL LOGIN -->
<div id="login-modal" class="fixed inset-0 z-50 flex items-center justify-center invisible transition-all duration-300">
    <div id="modal-overlay" class="absolute inset-0 bg-black/0 backdrop-blur-none transition-all duration-300"></div>
    <div id="modal-content"
         class="modal-card relative p-7 w-full max-w-sm mx-4 transform scale-95 opacity-0 transition-all duration-300">
        <button onclick="closeLoginModal()" class="absolute top-4 right-4 text-gray-300 hover:text-gray-600 transition-colors">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 18 6M6 6l12 12"/></svg>
        </button>
        <div class="mb-6">
            <h3 class="text-xl font-bold text-gray-900">Selamat Datang</h3>
            <p class="text-sm text-gray-400 mt-1">Login sebagai Admin atau Panitia</p>
        </div>
        <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="modal-input" placeholder="example@email.com">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                <input type="password" name="password" required
                       class="modal-input" placeholder="••••••••">
            </div>
            <button type="submit" class="btn-primary mt-2">Masuk</button>
        </form>
    </div>
</div>

<script>
function openLoginModal() {
    const modal = document.getElementById('login-modal');
    const overlay = document.getElementById('modal-overlay');
    const content = document.getElementById('modal-content');
    modal.classList.remove('invisible');
    setTimeout(() => {
        overlay.classList.replace('bg-black/0', 'bg-black/50');
        overlay.classList.replace('backdrop-blur-none', 'backdrop-blur-sm');
        content.classList.replace('scale-95', 'scale-100');
        content.classList.replace('opacity-0', 'opacity-100');
    }, 10);
    document.body.style.overflow = 'hidden';
}
function closeLoginModal() {
    const modal = document.getElementById('login-modal');
    const overlay = document.getElementById('modal-overlay');
    const content = document.getElementById('modal-content');
    overlay.classList.replace('bg-black/50', 'bg-black/0');
    overlay.classList.replace('backdrop-blur-sm', 'backdrop-blur-none');
    content.classList.replace('scale-100', 'scale-95');
    content.classList.replace('opacity-100', 'opacity-0');
    setTimeout(() => { modal.classList.add('invisible'); document.body.style.overflow = ''; }, 300);
}
document.getElementById('modal-overlay').addEventListener('click', closeLoginModal);

@if(session('open_login_modal') || session('login_error') || $errors->has('email'))
    window.addEventListener('DOMContentLoaded', openLoginModal);
@endif

</script>
<script>
function toggleMobileMenu() {
    const menu = document.getElementById('mobile-menu');
    const btn = document.getElementById('hamburger-btn');
    menu.classList.toggle('open');
    btn.classList.toggle('open');
}
function closeMobileMenu() {
    document.getElementById('mobile-menu').classList.remove('open');
    document.getElementById('hamburger-btn').classList.remove('open');
}
// Tutup mobile menu kalau klik di luar
document.addEventListener('click', function(e) {
    const menu = document.getElementById('mobile-menu');
    const btn = document.getElementById('hamburger-btn');
    if (!menu.contains(e.target) && !btn.contains(e.target)) {
        menu.classList.remove('open');
        btn.classList.remove('open');
    }
});
</script>
@stack('scripts')
</body>
</html>
