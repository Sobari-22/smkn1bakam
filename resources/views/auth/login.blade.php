<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login Admin — SMKN 1 Bakam</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="min-h-screen isolate bg-gradient-to-br from-sky-900 via-sky-800 to-sky-600 flex flex-col items-center justify-center px-4 py-12">

        {{-- Latar: satu logo sekolah di tengah, di belakang kartu login --}}
    <div aria-hidden="true" class="fixed inset-0 -z-10 flex items-center justify-center overflow-hidden pointer-events-none select-none">
        <img src="{{ asset('images/logo-smk-watermark.png') }}" alt=""
             style="width:min(45rem, 100vw); max-width:none; opacity: .30;">
    </div>

    {{-- Login Card --}}
    <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl p-8" x-data="{ showPassword: false }">
        <h2 class="text-2xl font-bold text-sky-800 mb-1">Masuk Dashboard</h2>
        <p class="text-slate-400 text-sm mb-6">Khusus Guru & Staf SMKN 1 Bakam</p>

        @if ($errors->any())
            <div class="mb-5 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        @if (session('status'))
            <div class="mb-5 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl px-4 py-3 text-sm">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-xs font-bold text-slate-500 tracking-wide mb-2">USERNAME</label>
                {{-- Authenticated against the `email` column (standard Laravel
                     auth), but labeled "Username" per design — admins/guru
                     log in with their registered email address. --}}
                <input id="email" type="text" name="email" value="{{ old('email') }}" placeholder="Username" autofocus
                       autocomplete="username"
                       class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500 @error('email') border-red-300 @enderror">
                @error('email') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password" class="block text-xs font-bold text-slate-500 tracking-wide mb-2">PASSWORD</label>
                <div class="relative">
                    <input :type="showPassword ? 'text' : 'password'" id="password" name="password" placeholder="••••••••"
                           autocomplete="current-password"
                           class="w-full px-4 py-3 pr-11 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500 @error('password') border-red-300 @enderror">
                           <button type="button" @click="showPassword = !showPassword"
                                    class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                    <path x-show="showPassword" d="M3 3l18 18"/>
                                </svg>
                            </button>                           
                </div>
                @error('password') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>

            <label class="flex items-center gap-2 text-sm text-slate-500 cursor-pointer">
                <input type="checkbox" name="remember" class="w-4 h-4 rounded text-sky-600">
                Ingat saya
            </label>

            <button type="submit" class="w-full py-3.5 rounded-lg bg-sky-700 hover:bg-sky-800 text-white font-bold transition">
                Masuk
            </button>
        </form>
    </div>

    <a href="{{ route('home') }}" class="text-sky-200 text-sm mt-6 hover:text-white transition">
        ← Kembali ke Website
    </a>

</body>
</html>