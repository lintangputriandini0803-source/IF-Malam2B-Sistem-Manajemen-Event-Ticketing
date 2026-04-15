<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - SIMETIX</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#8A008A] min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl overflow-hidden shadow-2xl max-w-5xl w-full flex flex-col md:flex-row min-h-[600px] relative">
        
        <div class="absolute inset-0 pointer-events-none opacity-20">
             </div>

        <div class="md:w-1/2 bg-gray-100 p-8 grid grid-cols-2 gap-4 content-center">
            <img src="{{ asset('img/poster1.jpg') }}" class="rounded-lg shadow-md transform -rotate-2 hover:rotate-0 transition">
            <img src="{{ asset('img/poster2.jpg') }}" class="rounded-lg shadow-md transform rotate-3 hover:rotate-0 transition">
            <img src="{{ asset('img/poster3.jpg') }}" class="rounded-lg shadow-md transform rotate-2 hover:rotate-0 transition">
            <img src="{{ asset('img/poster4.jpg') }}" class="rounded-lg shadow-md transform -rotate-3 hover:rotate-0 transition">
        </div>

        <div class="md:w-1/2 p-10 flex flex-col justify-center">
            <div class="flex items-center gap-2 mb-8">
                <img src="{{ asset('img/logo.png') }}" alt="Logo" class="h-10">
                <span class="text-[#8A008A] text-2xl font-bold">SIMETIX</span>
            </div>

            <h2 class="text-3xl font-extrabold text-[#8A008A] mb-6">Registrasi</h2>

            <form action="{{ route('register.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-bold text-[#8A008A] mb-1">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" class="w-full p-3 bg-gray-200 border-none rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>

                <div>
                    <label class="block text-sm font-bold text-[#8A008A] mb-1">Alamat Email</label>
                    <input type="email" name="email" class="w-full p-3 bg-gray-200 border-none rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>

                <div>
                    <label class="block text-sm font-bold text-[#8A008A] mb-1">No Hp</label>
                    <input type="text" name="no_hp" class="w-full p-3 bg-gray-200 border-none rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>

                <div class="relative">
                    <label class="block text-sm font-bold text-[#8A008A] mb-1">Password</label>
                    <input type="password" name="password" class="w-full p-3 bg-gray-200 border-none rounded-lg focus:ring-2 focus:ring-purple-500">
                    <button type="button" class="absolute right-3 top-9 text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>

                <div class="relative">
                    <label class="block text-sm font-bold text-[#8A008A] mb-1">Konfirmasi password</label>
                    <input type="password" name="password_confirmation" class="w-full p-3 bg-gray-200 border-none rounded-lg focus:ring-2 focus:ring-purple-500">
                    <button type="button" class="absolute right-3 top-9 text-gray-500">
                        </button>
                </div>

                <div class="flex gap-4 pt-4">
                    <a href="/" class="flex-1 text-center py-3 bg-[#FFDE59] text-gray-700 font-bold rounded-lg hover:bg-yellow-400 transition shadow-md">
                        < Kembali
                    </a>
                    <button type="submit" class="flex-1 py-3 bg-[#8A008A] text-white font-bold rounded-lg hover:bg-purple-800 transition shadow-md">
                        Daftar
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>