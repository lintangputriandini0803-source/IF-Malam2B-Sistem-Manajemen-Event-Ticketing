<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - SIMETIX</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

    <!-- HEADER -->
    <nav class="bg-purple-800 px-6 py-3 text-white font-bold text-xl shadow">
        SIMETIX
    </nav>

    <!-- CONTENT -->
    <div class="flex flex-1 items-center justify-center px-4 py-8">

        <div class="max-w-6xl w-full grid md:grid-cols-2 gap-6 items-center">

            <!-- LEFT IMAGE -->
            <div class="hidden md:block">
                <img src="/images/poster.jpg"
                     class="rounded-2xl shadow-lg w-full object-cover">
            </div>

            <!-- FORM -->
            <div class="bg-white rounded-2xl shadow-xl p-8 relative overflow-hidden">

                <!-- decorative background -->
                <div class="absolute inset-0 opacity-10 bg-[url('/images/pattern.png')] bg-cover"></div>

                <div class="relative z-10">

                    <h2 class="text-2xl font-bold text-purple-700 mb-6">
                        Registrasi
                    </h2>

                    <form method="POST" action="{{ route('register') }}" class="space-y-4">
                        @csrf

                        <!-- Nama -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Nama Lengkap
                            </label>
                            <input type="text" name="name"
                                class="w-full mt-1 rounded-lg border-gray-300 focus:ring-purple-500 focus:border-purple-500"
                                required>
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Alamat Email
                            </label>
                            <input type="email" name="email"
                                class="w-full mt-1 rounded-lg border-gray-300 focus:ring-purple-500 focus:border-purple-500"
                                required>
                        </div>

                        <!-- NIM -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                NIM
                            </label>
                            <input type="text" name="nim"
                                class="w-full mt-1 rounded-lg border-gray-300 focus:ring-purple-500 focus:border-purple-500"
                                required>
                        </div>

                        <!-- Password -->
                        <div class="relative">
                            <label class="block text-sm font-medium text-gray-700">
                                Password
                            </label>
                            <input type="password" name="password" id="password"
                                class="w-full mt-1 rounded-lg border-gray-300 focus:ring-purple-500 focus:border-purple-500"
                                required>

                            <button type="button" onclick="togglePassword('password')"
                                class="absolute right-3 top-9 text-gray-500">
                                👁
                            </button>
                        </div>

                        <!-- Confirm -->
                        <div class="relative">
                            <label class="block text-sm font-medium text-gray-700">
                                Konfirmasi Password
                            </label>
                            <input type="password" name="password_confirmation" id="confirm"
                                class="w-full mt-1 rounded-lg border-gray-300 focus:ring-purple-500 focus:border-purple-500"
                                required>

                            <button type="button" onclick="togglePassword('confirm')"
                                class="absolute right-3 top-9 text-gray-500">
                                👁
                            </button>
                        </div>

                        <!-- BUTTON -->
                        <div class="flex justify-between pt-4">
                            <a href="/"
                               class="px-4 py-2 rounded-lg bg-yellow-400 hover:bg-yellow-500 text-black shadow">
                                ← Kembali
                            </a>

                            <button type="submit"
                                class="px-6 py-2 rounded-lg bg-purple-700 hover:bg-purple-800 text-white shadow">
                                Daftar
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

    <!-- SCRIPT -->
    <script>
        function togglePassword(id) {
            const input = document.getElementById(id);
            input.type = input.type === "password" ? "text" : "password";
        }
    </script>

</body>
</html>
