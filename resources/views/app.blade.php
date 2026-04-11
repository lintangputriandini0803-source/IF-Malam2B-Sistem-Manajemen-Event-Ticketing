<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMETIC - Event & Ticketing</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans text-gray-900">

    <nav class="bg-dark border-b border-gray-200 fixed w-full z-20 top-0 start-0 px-4 py-2.5">
        <div class="flex flex-wrap justify-between items-center mx-auto max-w-screen-xl">
            <a href="/" class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gray-200 border border-gray-400 flex items-center justify-center">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <span class="self-center text-2xl font-bold tracking-tight">SIMETIC</span>
            </a>

            <div class="flex items-center space-x-6">
                <div class="hidden md:flex space-x-6 text-sm font-medium text-gray-600">
                    <a href="#" class="hover:text-blue-600">Event</a>
                    <a href="#" class="hover:text-blue-600">Tentang Kami</a>
                </div>
                
                <button data-modal-target="login-modal" data-modal-toggle="login-modal" class="text-gray-900 bg-white border border-gray-300 hover:bg-gray-100 font-medium rounded-lg text-sm px-5 py-2 flex items-center">
                    Login
                    <svg class="w-4 h-4 ms-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
            </div>
        </div>
    </nav>

    <main class="mt-20 max-w-screen-xl mx-auto p-4">
        <div class="flex gap-2 mb-6">
            <div class="relative flex-grow">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" class="block w-full p-2.5 ps-10 text-sm text-gray-900 border border-gray-300 rounded-full bg-white focus:ring-blue-500 focus:border-blue-500" placeholder="Search">
            </div>
            <button class="px-5 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-100">Search</button>
            <button class="px-5 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 flex items-center">
                <span class="me-1">+</span> Buat Event
            </button>
        </div>

        <div class="w-full h-64 bg-gray-200 border-2 border-dashed border-gray-400 rounded-lg flex items-center justify-center mb-8 relative">
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="w-full h-px bg-gray-400 rotate-12"></div>
                <div class="w-full h-px bg-gray-400 -rotate-12"></div>
            </div>
        </div>

        <h2 class="text-2xl font-bold text-center mb-8">Sistem Manajemen Event & Ticketing</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 mb-10">
            @for ($i = 0; $i < 8; $i++)
            <div class="bg-white border border-gray-200 rounded-lg shadow overflow-hidden">
                <div class="h-40 bg-gray-200 border-b border-gray-300 flex items-center justify-center relative">
                    <div class="absolute inset-0 flex items-center justify-center opacity-20">
                        <div class="w-full h-px bg-black rotate-45"></div>
                        <div class="w-full h-px bg-black -rotate-45"></div>
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="font-bold text-lg mb-2">Judul Event</h3>
                    <p class="text-xs text-gray-600 flex items-center mb-1">
                        <span class="w-3 h-3 bg-black me-2"></span> 00.00 - 00.00
                    </p>
                    <p class="text-xs text-gray-600 flex items-center">
                        <span class="w-3 h-3 bg-black me-2"></span> Mulai Dari Rp.00.000,00
                    </p>
                </div>
            </div>
            @endfor
        </div>

        <div class="flex justify-center mb-10">
            <button class="px-6 py-2.5 text-sm font-medium text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 shadow-sm">
                Lihat Event Lainnya
            </button>
        </div>
    </main>

    <div id="login-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-gray-200 rounded-lg shadow border border-gray-400">
                <div class="p-8">
                    <h3 class="text-3xl font-bold text-gray-900 mb-8 text-center md:text-left">Login</h3>
                    <form class="space-y-6" action="#">
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                            <input type="email" name="email" class="bg-gray-50 border border-gray-400 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required />
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900">Password</label>
                            <input type="password" name="password" class="bg-gray-50 border border-gray-400 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required />
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <div class="flex items-center">
                                <input id="remember" type="checkbox" class="w-4 h-4 border border-gray-400 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300" />
                                <label for="remember" class="ms-2 text-gray-900">Ingat Saya</label>
                            </div>
                            <a href="#" class="text-gray-900 hover:underline">Lupa Password?</a>
                        </div>
                        <div class="text-xs">
                            <span class="text-gray-900">Belum Punya akun?</span>
                        </div>
                        <div class="flex justify-center pt-4">
                            <button type="submit" class="text-gray-900 bg-transparent border border-gray-500 hover:bg-gray-300 focus:ring-4 focus:outline-none focus:ring-gray-400 font-medium rounded-lg text-sm px-10 py-2.5 text-center">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
