@extends('layouts.app')

@section('title', 'Berita & Pengumuman')

@section('content')

{{-- ============ HERO ============ --}}
<section class="bg-gradient-to-br from-sky-800 to-sky-600 py-20 relative overflow-hidden">
    <div class="absolute -right-20 -bottom-20 w-96 h-96 rounded-full bg-white/5"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <p class="text-sky-200 font-semibold text-sm tracking-wide mb-3">SMKN 1 BAKAM</p>
        <h1 class="text-4xl sm:text-5xl font-extrabold text-white mb-5">Berita &amp; Pengumuman</h1>
        <p class="text-sky-100 max-w-2xl">
            Kabar terkini seputar prestasi, kegiatan, dan informasi penting dari SMKN 1 Bakam.
        </p>
    </div>
</section>

{{-- ============ SEARCH + FILTER ============ --}}
<section class="bg-white border-b border-slate-200 py-6 sticky top-20 z-30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <form method="GET" action="{{ route('berita.index') }}" class="flex flex-col lg:flex-row items-stretch lg:items-center gap-3">
            {{-- Search --}}
            <div class="relative flex-1">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">🔍</span>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari berita..."
                       class="w-full pl-11 pr-4 py-3 rounded-lg border border-slate-200 bg-slate-50 focus:bg-white focus:border-sky-500 focus:ring-1 focus:ring-sky-500 outline-none transition">
            </div>

            {{-- Category pills — each is its own submit-on-click link so the
                 search box value is preserved via the query string. --}}
            <div class="flex items-center gap-2 overflow-x-auto no-scrollbar">
                @php $active = request('kategori'); @endphp
                <a href="{{ route('berita.index', array_filter(['q' => request('q')])) }}"
                   class="whitespace-nowrap px-4 py-2.5 rounded-lg text-sm font-semibold transition
                          {{ !$active ? 'bg-sky-700 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Semua
                </a>
                @foreach ($categories as $cat)
                    <a href="{{ route('berita.index', array_filter(['q' => request('q'), 'kategori' => $cat])) }}"
                       class="whitespace-nowrap px-4 py-2.5 rounded-lg text-sm font-semibold transition
                              {{ $active === $cat ? 'bg-sky-700 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        {{ $cat }}
                    </a>
                @endforeach
            </div>
        </form>
    </div>
</section>

{{-- ============ GRID BERITA ============ --}}
<section class="bg-slate-50 py-14">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        @if ($news->isEmpty())
            <div class="text-center py-20">
                <p class="text-5xl mb-4">📭</p>
                <p class="text-slate-500">
                    @if (request('q') || request('kategori'))
                        Tidak ada berita yang cocok dengan pencarian Anda.
                    @else
                        Belum ada berita yang dipublikasikan.
                    @endif
                </p>
            </div>
        @else
            @php
                // Category badge colors — kept out of the DB since it's purely
                // presentational. Falls back to sky if a new category shows up.
                $badgeColors = [
                    'Prestasi' => 'bg-emerald-500',
                    'Kegiatan' => 'bg-sky-500',
                    'PPDB' => 'bg-sky-800',
                    'Pengumuman' => 'bg-purple-600',
                ];
            @endphp

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($news as $item)
                    <a href="{{ route('berita.show', $item) }}" class="block group bg-white rounded-2xl overflow-hidden border border-slate-100 shadow-sm hover:shadow-md transition">
                        <div class="aspect-[4/3] bg-gradient-to-br from-sky-100 to-sky-200 overflow-hidden relative">
                            @if ($item->thumbnail)
                                <img src="{{ asset('storage/'.$item->thumbnail) }}"
                                     alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-sky-400 text-4xl">📰</div>
                            @endif
                        </div>
                        <div class="p-5">
                            <div class="flex items-center gap-3 mb-3">
                                <span class="px-2.5 py-1 rounded-full {{ $badgeColors[$item->category] ?? 'bg-sky-600' }} text-white text-xs font-bold uppercase">
                                    {{ $item->category ?? 'Berita' }}
                                </span>
                                <span class="text-slate-400 text-sm">{{ $item->published_at->translatedFormat('d F Y') }}</span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 group-hover:text-sky-700 transition leading-snug mb-2">
                                {{ $item->title }}
                            </h3>
                            <p class="text-slate-500 text-sm line-clamp-2 mb-3">{{ $item->excerpt }}</p>
                            <span class="text-sky-700 text-sm font-semibold">Baca selengkapnya →</span>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-12">
                {{ $news->links() }}
            </div>
        @endif

    </div>
</section>

@endsection
