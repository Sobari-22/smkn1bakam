<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Admin SMKN 1 Bakam</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak] { display: none !important; }</style>
    @stack('styles')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-50 text-slate-900 antialiased">
<div class="flex min-h-screen" x-data="{ sidebarOpen: true }">

    {{-- ============ SIDEBAR ============ --}}
    <aside class="bg-slate-900 text-white flex flex-col shrink-0 transition-all"
           :class="sidebarOpen ? 'w-72' : 'w-20'">
        <div class="p-5 flex items-center gap-3 border-b border-white/10 relative">
            <span class="w-11 h-11 rounded-lg bg-sky-700 flex items-center justify-center font-bold text-lg shrink-0">S</span>
            <div x-show="sidebarOpen" x-cloak>
                <p class="font-bold leading-tight">SMKN 1 Bakam</p>
                <p class="text-xs text-slate-400">Admin Portal</p>
            </div>
            <button @click="sidebarOpen = !sidebarOpen"
                    class="absolute -right-3 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-slate-800 border border-white/10 flex items-center justify-center text-xs">
                <span x-text="sidebarOpen ? '◀' : '▶'"></span>
            </button>
        </div>

        <nav class="flex-1 overflow-y-auto py-5 px-3 space-y-1">
            <p x-show="sidebarOpen" x-cloak class="text-slate-500 text-xs font-bold tracking-wide px-3 mb-2">MENU UTAMA</p>

            @php
                $menu = [
                    ['label' => 'Dashboard', 'icon' => '🏠', 'route' => 'admin.dashboard', 'perm' => null],
                    ['label' => 'Manajemen SPMB', 'icon' => '✅', 'route' => 'admin.ppdb.index', 'perm' => 'ppdb'],
                    ['label' => 'Manajemen Berita', 'icon' => '📰', 'route' => 'admin.news.index', 'perm' => 'berita'],
                    ['label' => 'Moderasi Komentar', 'icon' => '💬', 'route' => 'admin.comments.index', 'perm' => 'berita'],
                    ['label' => 'Pesan Masuk', 'icon' => '✉️', 'route' => 'admin.messages.index', 'perm' => 'pesan'],
                    ['label' => 'Program Keahlian', 'icon' => '🎓', 'route' => 'admin.program.index', 'perm' => 'akademik'],
                    ['label' => 'Guru & Staf', 'icon' => '👥', 'route' => 'admin.teachers.index', 'perm' => 'akademik'],
                    ['label' => 'Profil Sekolah', 'icon' => '🏫', 'route' => 'admin.school-profile.index', 'perm' => 'profil'],
                    ['label' => 'Sarana & Prasarana', 'icon' => '🏢', 'route' => 'admin.facilities.index', 'perm' => 'profil'],
                    ['label' => 'Galeri', 'icon' => '🖼️', 'route' => 'admin.gallery.index', 'perm' => 'galeri'],
                    ['label' => 'Manajemen E-Library', 'icon' => '📖', 'route' => 'admin.library.index', 'perm' => 'elibrary'],
                    ['label' => 'Manajemen BKK', 'icon' => '💼', 'route' => 'admin.bkk.index', 'perm' => 'bkk'],
                    ['label' => 'Rekap Tracer Study', 'icon' => '📈', 'route' => 'admin.tracer-study.index', 'perm' => 'tracer'],
                    ['label' => 'Pengaturan', 'icon' => '⚙️', 'route' => 'admin.settings.index', 'perm' => 'pengaturan'],
                ];
            @endphp

            @foreach ($menu as $item)
                @continue($item['perm'] && ! auth()->user()->hasAccess($item['perm']))
                @php $active = request()->routeIs($item['route'].'*'); @endphp
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition
                          {{ $active ? 'bg-sky-700/30 text-sky-300 ring-1 ring-sky-600/50' : 'text-slate-300 hover:bg-white/5' }}">
                    <span class="text-lg shrink-0">{{ $item['icon'] }}</span>
                    <span x-show="sidebarOpen" x-cloak class="flex-1">{{ $item['label'] }}</span>
                    @if ($item['route'] === 'admin.messages.index')
                        @php $unreadMessages = \App\Models\ContactMessage::where('is_read', false)->count(); @endphp
                        @if ($unreadMessages > 0)
                            <span x-show="sidebarOpen" x-cloak class="text-[10px] font-bold bg-red-500 text-white px-1.5 py-0.5 rounded-full">{{ $unreadMessages }}</span>
                        @endif
                    @endif
                </a>
            @endforeach

            @if (auth()->user()->hasAccess('erapor'))
            <p x-show="sidebarOpen" x-cloak class="text-slate-500 text-xs font-bold tracking-wide px-3 mb-2 mt-6">EXTERNAL</p>
            <a href="{{ route('admin.erapor.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition
                      {{ request()->routeIs('admin.erapor.*') ? 'bg-sky-700/30 text-sky-300 ring-1 ring-sky-600/50' : 'text-slate-300 hover:bg-white/5' }}">
                <span class="text-lg shrink-0">📊</span>
                <span x-show="sidebarOpen" x-cloak class="flex-1">Akses E-Rapor</span>
                <span x-show="sidebarOpen" x-cloak class="text-[10px] bg-white/10 px-1.5 py-0.5 rounded">EXT</span>
            </a>
            @endif
        </nav>

        <div class="p-3 border-t border-white/10">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-red-300 hover:bg-red-500/10 w-full">
                    <span class="text-lg shrink-0">↩️</span>
                    <span x-show="sidebarOpen" x-cloak>Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- ============ MAIN ============ --}}
    <div class="flex-1 flex flex-col min-w-0">
        {{-- Topbar --}}
        <header class="bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between gap-4">
            <p class="text-sm text-slate-400 shrink-0">
                SMKN 1 Bakam / <span class="font-semibold text-slate-800">@yield('title', 'Dashboard')</span>
            </p>

            <div class="flex-1 max-w-sm hidden md:block">
                <input type="text" placeholder="Cari data..."
                       class="w-full px-4 py-2 rounded-lg border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
            </div>

            <div class="flex items-center gap-3 shrink-0">
                <a href="{{ route('home') }}" target="_blank"
                   class="hidden sm:inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-slate-200 text-sm font-medium text-slate-600 hover:bg-slate-50">
                    🌐 Lihat Website
                </a>
                @include('admin.partials.notification-bell')
                @include('admin.partials.user-menu')
            </div>
        </header>

        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>
</div>

@stack('scripts')
</body>
</html>
