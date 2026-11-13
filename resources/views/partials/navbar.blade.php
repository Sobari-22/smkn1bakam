{{-- Top info bar --}}
<div class="bg-sky-800 text-white text-xs sm:text-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2 flex flex-col sm:flex-row items-center justify-between gap-1">
        <span class="flex items-center gap-1.5">
            <span aria-hidden="true">📍</span>
            {{ \App\Models\Setting::get('school_address', 'Jl. Raya Bakam, Kec. Bakam, Kab. Bangka, Kep. Bangka Belitung') }}
        </span>
        <div class="flex items-center gap-4">
            <a href="tel:{{ \App\Models\Setting::get('school_phone', '0717-XXXXXX') }}" class="flex items-center gap-1.5 hover:text-sky-200">
                <span aria-hidden="true">📞</span> {{ \App\Models\Setting::get('school_phone', '+62 812-7393-587') }}
            </a>
            <a href="mailto:{{ \App\Models\Setting::get('school_email', 'info@smkn1bakam.sch.id') }}" class="hidden sm:flex items-center gap-1.5 hover:text-sky-200">
                <span aria-hidden="true">✉️</span> {{ \App\Models\Setting::get('school_email', 'info@smkn1bakam.sch.id') }}
            </a>
            <a href="{{ route('login') }}" class="hidden sm:inline-block px-3 py-1 rounded-md bg-sky-700 hover:bg-sky-600 transition">
                Login Admin
            </a>
        </div>
    </div>
</div>

{{-- Main navbar --}}
<header x-data="{ mobileOpen: false }" class="sticky top-0 z-50 bg-sky-600 shadow-md">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3 shrink-0">
                <img 
                    src="{{ asset('images/logo smk.jpg') }}" 
                    alt="Logo SMKN 1 Bakam"
                    class="w-11 h-11 object-contain rounded-lg"
                >

                <span class="leading-tight">
                    <span class="block font-bold text-white text-base sm:text-lg">
                        SMKN 1 Bakam
                    </span>
                    <span class="block text-sky-100 text-xs">
                        Kab. Bangka · Bangka Belitung
                    </span>
                </span>
            </a>

            {{-- Desktop menu --}}
            <div class="hidden lg:flex items-center gap-1">
                @php
                    $menu = [
                        ['label' => 'Beranda', 'route' => 'home'],
                        ['label' => 'Profil', 'route' => 'profil'],
                        ['label' => 'Program', 'route' => 'program.index'],
                        ['label' => 'Berita', 'route' => 'berita.index'],
                        ['label' => 'Galeri', 'route' => 'galeri'],
                        ['label' => 'E-Library', 'route' => 'elibrary.index'],
                        ['label' => 'BKK', 'route' => 'bkk'],
                        ['label' => 'Kontak', 'route' => 'kontak'],
                    ];
                @endphp

                @foreach ($menu as $item)
                    @php $active = request()->routeIs($item['route'].'*'); @endphp
                    <a href="{{ route($item['route']) }}"
                       class="px-4 py-2 rounded-lg text-sm font-medium transition
                              {{ $active ? 'bg-white/15 text-white ring-1 ring-white/40' : 'text-sky-50 hover:bg-white/10' }}">
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </div>

            {{-- CTA + mobile toggle --}}
            <div class="flex items-center gap-2">
                <a href="{{ route('ppdb.create') }}"
                   class="hidden md:inline-flex items-center px-5 py-2.5 rounded-lg bg-white text-sky-800 font-semibold text-sm shadow-sm hover:bg-sky-50 transition">
                    SPMB Online
                </a>
                <button @click="mobileOpen = !mobileOpen" class="lg:hidden p-2 text-white" aria-label="Buka menu">
                    <svg x-show="!mobileOpen" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg x-show="mobileOpen" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile menu --}}
        <div x-show="mobileOpen" x-cloak x-transition class="lg:hidden pb-4 space-y-1">
            @foreach ($menu as $item)
                <a href="{{ route($item['route']) }}"
                   class="block px-4 py-2.5 rounded-lg text-sm font-medium text-white
                          {{ request()->routeIs($item['route'].'*') ? 'bg-white/15' : 'hover:bg-white/10' }}">
                    {{ $item['label'] }}
                </a>
            @endforeach
            <a href="{{ route('ppdb.create') }}" class="block px-4 py-2.5 rounded-lg text-sm font-semibold bg-white text-sky-800 text-center mt-2">
                SPMB Online
            </a>
        </div>
    </nav>
</header>
