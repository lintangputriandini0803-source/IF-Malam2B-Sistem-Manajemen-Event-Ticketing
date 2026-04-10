<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SIMETIC</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="bg-red-500 text-white p-4">
    TEST TAILWIND
</div>

    <!-- Navbar -->
    <nav class="bg-white shadow">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <h1 class="text-xl font-bold">SIMETIC</h1>
            <div class="flex gap-4 items-center">
                <a href="#">Event</a>
                <a href="#">Tentang Kami</a>
                <button class="bg-gray-200 px-4 py-1 rounded">Login</button>
            </div>
        </div>
    </nav>

    <!-- Hero / Banner -->
    <div class="bg-gray-300 h-64 flex items-center justify-center">
        <h2 class="text-2xl font-semibold">Banner Event</h2>
    </div>

    <!-- Content -->
    <div class="container mx-auto px-4 py-10">
        <h2 class="text-center text-xl font-bold mb-6">Sistem Manajemen Event & Ticketing</h2>

        <!-- Grid Event -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

            @for ($i = 0; $i < 8; $i++)
            <div class="bg-white rounded shadow">
                <div class="bg-gray-200 h-40"></div>
                <div class="p-4">
                    <h3 class="font-semibold">Judul Event</h3>
                    <p class="text-sm text-gray-500">00:00 - 00:00</p>
                    <p class="text-sm text-gray-500">Mulai dari Rp. 50.000</p>
                </div>
            </div>
            @endfor

        </div>

        <!-- Button -->
        <div class="text-center mt-8">
            <button class="bg-gray-200 px-6 py-2 rounded">Lihat Event Lainnya</button>
        </div>
    </div>

</body>
</html>