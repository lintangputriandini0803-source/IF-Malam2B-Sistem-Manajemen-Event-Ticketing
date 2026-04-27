<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMETIX - Event & Ticketing</title>
    <link rel="icon" href="{{asset('img.logo.png')}}" type="image/x-icon">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        button, a, .cursor-pointer { cursor: pointer; }
        body { background: rgb(216, 216, 216); }
        html {
    overflow-y: scroll;}
    </style>
</head>

<body class="font-sans text-gray-900 relative overflow-x-hidden">

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
            <button onclick="openLoginModal()" class="bg-white rounded px-3 text-[#8A008A] hover:bg-gray-100">Login</button>
        </div>
</nav>

<!-- MAIN -->
<main class="mt-20 mx-auto">

    <!-- NOTIFIKASI SUCCESS / ERROR -->
    @if(session('success'))
    <div class="max-w-2xl mx-auto mt-4 px-4">
        <div class="bg-green-100 text-green-700 px-4 py-3 rounded-xl text-sm">
            {{ session('success') }}
        </div>
    </div>
    @endif

    @if(session('login_error'))
<div class="fixed top-20 left-1/2 -translate-x-1/2 w-full max-w-2xl px-4" style="z-index: 999">
    <div class="bg-red-100 text-red-700 px-4 py-3 rounded-xl text-sm shadow-md">
        {{ session('login_error') }}
    </div>
</div>
@endif



<!-- SEARCH -->
<form method="GET" action="{{ route('home') }}" class="flex max-w-2xl  mx-auto mb-5 shadow rounded-xl overflow-hidden" >
    <input type="text" name="search" value="{{ request('search') }}"
    class="w-full p-2 pl-4 border-none focus:ring-2 focus:ring-purple-500"
    placeholder="Cari event, lokasi, atau kategori...">
    <div style="position:relative;border-right:1px solid #e5e7eb;flex-shrink:0;background:white">
        <select name="category"
                style="appearance:none;padding:0 32px 0 14px;height:100%;font-size:13px;font-weight:600;color:#374151;border:none;background:transparent;cursor:pointer;min-width:130px;outline:none">
            <option value=""><svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
<path d="M10.83 5a3.001 3.001 0 0 0-5.66 0H4a1 1 0 1 0 0 2h1.17a3.001 3.001 0 0 0 5.66 0H20a1 1 0 1 0 0-2h-9.17ZM4 11h9.17a3.001 3.001 0 0 1 5.66 0H20a1 1 0 1 1 0 2h-1.17a3.001 3.001 0 0 1-5.66 0H4a1 1 0 1 1 0-2Zm1.17 6H4a1 1 0 1 0 0 2h1.17a3.001 3.001 0 0 0 5.66 0H20a1 1 0 1 0 0-2h-9.17a3.001 3.001 0 0 0-5.66 0Z"/>
</svg></option>
            <option value="seminar"   {{ request('category')==='seminar'   ?'selected':'' }}>📚 Seminar</option>
            <option value="workshop"  {{ request('category')==='workshop'  ?'selected':'' }}>🛠 Workshop</option>
            <option value="konser"    {{ request('category')==='konser'    ?'selected':'' }}>🎵 Konser</option>
            <option value="kompetisi" {{ request('category')==='kompetisi' ?'selected':'' }}>🏆 Kompetisi</option>
            <option value="pameran"   {{ request('category')==='pameran'   ?'selected':'' }}>🎨 Pameran</option>
            <option value="olahraga"  {{ request('category')==='olahraga'  ?'selected':'' }}>⚽ Olahraga</option>
            <option value="hiburan"   {{ request('category')==='hiburan'   ?'selected':'' }}>🎭 Hiburan</option>
        </select>
        <svg style="position:absolute;right:8px;top:50%;transform:translateY(-50%);width:12px;height:12px;color:#9ca3af;pointer-events:none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </div>
    <button type="submit" class="bg-[#8A008A] text-white px-6 hover:bg-purple-700 transition">Search</button>
</form>
<div class="mx-10 mb-6">
    <h1 class="text-xl font-bold shadow-md mb-1">Discover Events, Activities and Promotions in PoliBatam</h1>
    <p class="max-w-4xl">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quas recusandae quisquam atque distinctio molestiae odio.
         Architecto voluptatem, necessitatibus eligendi obcaecati deserunt culpa, error expedita fugiat veniam aperiam quam nihil nam.</p>
</div>


    @if(request('search'))
    <p class="text-center text-gray-600 mb-6">
        Hasil untuk: <strong>"{{ request('search') }}"</strong> ({{ $events->total() }} event)
        <a href="{{ route('home') }}" class="text-purple-600 ml-2 hover:underline">Reset</a>
    </p>
    @endif

    <!-- GRID EVENT -->
    <div class="max-w-screen-2xl px-5 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-10 mx-auto mb-12">
        @forelse ($events as $event)
        <a href="{{ route('event.show', $event->slug) }}" class="block">
            <div class="w-full bg-white rounded-2xl shadow-md overflow-hidden hover:shadow-xl hover:-translate-y-2 transition duration-300">
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
                    <h3 class="font-bold text-[#8A008A] text-lg mb-2 line-clamp-2">{{ $event->title }}</h3>
                    <p class="text-sm text-gray-500 mb-1">📅 {{ $event->event_date }}</p>
                    <p class="text-sm text-gray-500 mb-2">📍 {{ $event->location }}</p>
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

    @if($events->hasPages())
    <div class="flex justify-center mb-10">{{ $events->withQueryString()->links() }}</div>
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
                <li><a href="#" class="hover:underline">Olahraga</a></li>
                <li><a href="#" class="hover:underline">Seminar</a></li>
                <li><a href="#" class="hover:underline">Musik</a></li>

            </ul>
        </div>
    </div>
    <div class="border-t border-purple-800 mt-5 pt-6 text-center text-xs opacity-60">
        &copy; 2026 SIMETIX - All Rights Reserved.
    </div>
</footer>

<!-- ═══ MODAL LOGIN ═══ -->
<div id="login-modal"
     class="fixed inset-0 z-50 flex items-center justify-center invisible transition-all duration-300">

    <div id="modal-overlay"
         class="absolute inset-0 bg-black/0 backdrop-blur-none transition-all duration-300"></div>

    <div id="modal-content"
         class="relative bg-white rounded-2xl shadow-xl p-6 w-full max-w-md mx-4 transform scale-95 opacity-0 transition-all duration-300">

        <button onclick="closeLoginModal()"
                class="absolute top-4 right-4 text-gray-400 hover:text-gray-700">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 18 6M6 6l12 12"/>
            </svg>
        </button>

        <h3 class="text-xl font-bold text-gray-800 mb-1">Login</h3>
        <p class="text-sm text-gray-500 mb-5">Masuk sebagai Admin atau Panitia</p>

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                       placeholder="example@email.com">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                       placeholder="••••••••">
            </div>
            <button type="submit"
                    class="w-full bg-[#8A008A] hover:bg-purple-700 text-white font-semibold py-2.5 rounded-lg transition text-sm mb-3">
                Login
            </button>
        </form>
    </div>
</div>
</div>

<script>
    function openLoginModal() {
        document.getElementById('login-modal').classList.remove('hidden');
        document.getElementById('user-dropdown').classList.add('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeLoginModal() {
        document.getElementById('login-modal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    // Tutup modal kalau klik di luar
    document.getElementById('login-modal').addEventListener('click', function(e) {
        if (e.target === this) closeLoginModal();
    });

    // Tutup dropdown kalau klik di luar
    document.addEventListener('click', function(e) {
        const dropdown = document.getElementById('user-dropdown');
        if (dropdown && !e.target.closest('[onclick*="user-dropdown"]') && !dropdown.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });

    // Auto-buka modal jika ada session open_login_modal atau login_error
    @if(session('open_login_modal') || session('login_error') || $errors->has('email'))
        window.addEventListener('DOMContentLoaded', openLoginModal);
    @endif
</script>
<script>
    function openLoginModal() {
    const modal = document.getElementById('login-modal');
    const overlay = document.getElementById('modal-overlay');
    const content = document.getElementById('modal-content');

    // 1. Tampilkan container utama
    modal.classList.remove('invisible');

    // 2. Beri jeda sangat singkat agar browser sempat merender perubahan sebelum animasi
    setTimeout(() => {
        // Overlay jadi hitam transparan
        overlay.classList.replace('bg-black/0', 'bg-black/50');
        overlay.classList.replace('backdrop-blur-none', 'backdrop-blur-sm');

        // Content jadi ukuran normal dan muncul
        content.classList.replace('scale-95', 'scale-100');
        content.classList.replace('opacity-0', 'opacity-100');
    }, 10);

    document.getElementById('user-dropdown').classList.add('hidden');
    document.body.style.overflow = 'hidden';
}

function closeLoginModal() {
    const modal = document.getElementById('login-modal');
    const overlay = document.getElementById('modal-overlay');
    const content = document.getElementById('modal-content');

    // 1. Kembalikan animasi ke kondisi awal
    overlay.classList.replace('bg-black/50', 'bg-black/0');
    overlay.classList.replace('backdrop-blur-sm', 'backdrop-blur-none');

    content.classList.replace('scale-100', 'scale-95');
    content.classList.replace('opacity-100', 'opacity-0');

    // 2. Sembunyikan container setelah animasi selesai (300ms sesuai duration-300)
    setTimeout(() => {
        modal.classList.add('invisible');
        document.body.style.overflow = '';
    }, 300);
}

// Update event listener klik di luar modal
document.getElementById('modal-overlay').addEventListener('click', closeLoginModal);
</script>
<script>
    const slides = document.querySelectorAll('.carousel-slide');
    const slidesContainer = document.getElementById('carousel-slides');
    const dotsContainer = document.getElementById('carousel-dots');
    let current = 0;
    let autoSlide;

    // Buat dots
    slides.forEach((_, i) => {
        const dot = document.createElement('button');
        dot.className = `w-2.5 h-2.5 rounded-full transition-all duration-300 ${i === 0 ? 'bg-white scale-125' : 'bg-white/50'}`;
        dot.onclick = () => goToSlide(i);
        dotsContainer.appendChild(dot);
    });

    function goToSlide(index) {
        current = index;
        slidesContainer.style.transform = `translateX(-${current * 100}%)`;
        document.querySelectorAll('#carousel-dots button').forEach((dot, i) => {
            dot.className = `w-2.5 h-2.5 rounded-full transition-all duration-300 ${i === current ? 'bg-white scale-125' : 'bg-white/50'}`;
        });
    }

    function nextSlide() {
        goToSlide((current + 1) % slides.length);
        resetAuto();
    }

    function prevSlide() {
        goToSlide((current - 1 + slides.length) % slides.length);
        resetAuto();
    }

    function resetAuto() {
        clearInterval(autoSlide);
        autoSlide = setInterval(nextSlide, 4000);
    }

    // Auto-play tiap 4 detik
    autoSlide = setInterval(nextSlide, 4000);

    document.querySelectorAll('*').forEach(el => {
    el.style.outline = '1px solid red';
});
</script>


</body>
</html>

