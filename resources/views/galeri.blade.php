@extends('layouts.app')

@section('title', 'Galeri Kegiatan')

@section('content')

{{-- ============ HERO ============ --}}
<section class="bg-gradient-to-br from-sky-800 to-sky-600 py-20 relative overflow-hidden">
    <div class="absolute -right-20 -bottom-20 w-96 h-96 rounded-full bg-white/5"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <p class="text-sky-200 font-semibold text-sm tracking-wide mb-3">SMKN 1 BAKAM</p>
        <h1 class="text-4xl sm:text-5xl font-extrabold text-white mb-5">Galeri Kegiatan</h1>
        <p class="text-sky-100 max-w-2xl">
            Dokumentasi kegiatan, praktikum, dan momen berharga di SMKN 1 Bakam.
        </p>
    </div>
</section>

{{-- ============ FILTER KATEGORI ============ --}}
@if ($categories->isNotEmpty())
<section class="bg-white border-b border-slate-100 py-5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-wrap gap-2">
        <a href="{{ route('galeri') }}"
           class="px-4 py-2 rounded-lg text-sm font-semibold transition
                  {{ !request('category') ? 'bg-sky-700 text-white' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}">
            Semua
        </a>
        @foreach ($categories as $category)
            <a href="{{ route('galeri', ['category' => $category]) }}"
               class="px-4 py-2 rounded-lg text-sm font-semibold transition
                      {{ request('category') === $category ? 'bg-sky-700 text-white' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}">
                {{ $category }}
            </a>
        @endforeach
    </div>
</section>
@endif

{{-- ============ GRID GALERI ============ --}}
<section class="bg-slate-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        @if ($galleries->isEmpty())
            <div class="text-center py-20">
                <div class="text-5xl mb-4">🖼️</div>
                <p class="text-slate-400">Belum ada dokumentasi yang ditambahkan.</p>
            </div>
        @else
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($galleries as $item)
                    <div class="bg-white rounded-xl overflow-hidden border border-slate-200 group">
                        <div class="relative aspect-video bg-slate-200 overflow-hidden">
                            <img src="{{ asset('storage/'.$item->file_path) }}" alt="{{ $item->title }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            @if ($item->type === 'video')
                                <div class="absolute inset-0 flex items-center justify-center bg-black/20">
                                    <span class="w-12 h-12 rounded-full bg-white/90 flex items-center justify-center text-sky-800 text-xl">▶</span>
                                </div>
                            @endif
                            @if ($item->category)
                                <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full bg-sky-800/90 text-white text-xs font-semibold">
                                    {{ $item->category }}
                                </span>
                            @endif
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-slate-900 text-sm mb-1">{{ $item->title }}</h3>
                            @if ($item->event_date)
                                <p class="text-slate-400 text-xs">{{ $item->event_date->translatedFormat('d F Y') }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($galleries->hasPages())
                <div class="mt-10">{{ $galleries->links() }}</div>
            @endif
        @endif
    </div>
</section>

@endsection
