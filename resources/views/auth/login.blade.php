<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    @vite('resources/css/app.css')
    <script src="https://unpkg.com/alpinejs" defer></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-[#3fa9f3] to-[#1b7fc1]">

    <div class="w-full max-w-md mx-auto bg-white rounded-xl shadow-xl p-8 md:p-10">
        <!-- Logo -->
        <div class="text-center mb-6">
            <img src="https://zaintech.co.id/public/icon/logo-h-deks.webp" alt="Logo" class="w-24 h-auto mx-auto mb-3">
            <h2 class="text-2xl font-bold text-gray-800">Selamat Datang</h2>
            <p class="text-gray-500 text-sm mt-1">Masuk ke akun Anda untuk melanjutkan</p>
        </div>

        <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Input Email -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="EMAIL" placeholder="you@example.com"
                    class="mt-1 w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#3fa9f3] focus:outline-none transition" autocomplete="off" required>
            </div>

            <!-- Input Password dengan Show/Hide -->
            <div x-data="{ show: false }">
                <label class="block text-sm font-medium text-gray-700">Password</label>
                <div class="relative">
                    <input :type="show ? 'text' : 'password'" name="PASSWORD" placeholder="••••••••"
                        class="mt-1 w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#3fa9f3] focus:outline-none transition" autocomplete="off" required>
                    <button type="button" @click="show = !show"
                        class="absolute inset-y-0 right-3 flex items-center text-gray-500 focus:outline-none">
                        <!-- Mata terbuka -->
                        <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <!-- Mata tertutup -->
                        <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a10.05 10.05 0 012.28-3.972M9.88 9.88a3 3 0 104.24 4.24M3 3l18 18"/>
                        </svg>
                    </button>
                </div>
            </div>

            @error('EMAIL')
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror

            <!-- Tombol Login -->
            <button type="submit"
                class="w-full py-2 rounded-lg bg-[#3fa9f3] text-white font-semibold shadow hover:bg-[#3498db] active:scale-95 transition">
                Masuk
            </button>
        </form>

        <p class="text-xs text-center text-gray-500 mt-6">
            © {{ date('Y') }} Lead Zaintech. Semua Hak Dilindungi.
        </p>
    </div>
</body>
</html>
