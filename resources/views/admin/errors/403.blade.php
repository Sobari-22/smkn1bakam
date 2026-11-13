<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 — Akses Ditolak</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-slate-50 text-slate-900 antialiased min-h-screen flex items-center justify-center p-6">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm max-w-md w-full p-8 text-center">
        <p class="text-5xl mb-4">🔒</p>
        <h1 class="text-xl font-bold mb-2">Akses Ditolak</h1>
        <p class="text-slate-500 text-sm mb-6">
            {{ $exception->getMessage() ?: 'Anda tidak memiliki akses ke halaman ini.' }}
        </p>
        @auth
            <a href="{{ route('admin.dashboard') }}" class="inline-block px-5 py-2.5 rounded-lg bg-sky-700 hover:bg-sky-800 text-white font-semibold text-sm">
                Kembali ke Dashboard
            </a>
        @else
            <a href="{{ route('login') }}" class="inline-block px-5 py-2.5 rounded-lg bg-sky-700 hover:bg-sky-800 text-white font-semibold text-sm">
                Ke Halaman Login
            </a>
        @endauth
    </div>
</body>
</html>
