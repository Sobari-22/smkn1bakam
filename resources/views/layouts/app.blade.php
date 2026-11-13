<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SMKN 1 Bakam') — SMKN 1 Bakam</title>
    <meta name="description" content="@yield('meta_description', 'Website resmi SMKN 1 Bakam, Kabupaten Bangka Barat.')">

    {{-- Assumes Tailwind is already wired up via Vite in this Laravel 13 project
         (default when using `laravel new` with the Tailwind starter kit).
         If not, run: php artisan install:api or see resources/css/app.css note. --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    {{-- Alpine.js powers the hero slider + mobile menu toggle below.
         Skip this tag if Alpine is already bundled via app.js / Livewire. --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-white text-slate-900 antialiased">

    @include('partials.navbar')

    <main>
        @yield('content')
    </main>

    @include('partials.footer')

    @stack('scripts')

</body>
</html>
