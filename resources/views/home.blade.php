@extends('layouts.app')

@section('title', 'Beranda')

@section('content')

{{-- ============ HERO SLIDER ============
     Slide 0 is always the static "main" slide (school intro + SPMB CTA).
     Slides 1+ are the latest published news, passed in as $heroNews. --}}
<section
    x-data="{
        active: 0,
        total: {{ 1 + $heroNews->count() }},
        timer: null,
        paused: false,
        start() {
            this.timer = setInterval(() => { if (!this.paused) this.next(); }, 5500);
        },
        next() { this.active = (this.active + 1) % this.total; },
        prev() { this.active = (this.active - 1 + this.total) % this.total; },
    }"
    x-init="start()"
    @mouseenter="paused = true"
    @mouseleave="paused = false"
    class="relative overflow-hidden"
>
    <div class="relative h-[640px] sm:h-[600px]">

        {{-- Slide 0: main hero — background, gradient overlay, and text/CTA
             are all inside this one x-show block now, so the whole slide
             hides together when another slide is active. --}}
        <div
            x-show="active === 0"
            x-transition:enter="transition ease-out duration-700"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            class="absolute inset-0"
        >
            <div
                class="absolute inset-0 bg-cover bg-center"
                style="background-image: url('{{ asset('images/hero-gedung.jpg') }}');"
            ></div>

            {{-- Gradient overlay: transparent at top so the photo reads naturally,
                 darkening toward the bottom where the title/CTA sit. --}}
            <div class="absolute inset-0 bg-gradient-to-b from-transparent via-slate-900/40 to-slate-900/85"></div>

            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex flex-col justify-end pb-16 pt-28">
                <span class="inline-flex items-center gap-2 self-start px-4 py-1.5 rounded-full bg-sky-500/90 text-white text-xs sm:text-sm font-semibold mb-5">
                    <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span>
                    SPMB {{ now()->year }}/{{ now()->year + 1 }}
                    {{ \App\Models\Setting::get('ppdb_status', 'open') === 'open' ? 'Dibuka · Kuota Terbatas' : 'Belum Dibuka' }}
                </span>

                <h1 class="text-4xl sm:text-6xl font-extrabold text-white leading-tight">
                    SMK Negeri 1<br>
                    <span class="text-sky-300 italic">Bakam</span>
                </h1>
                <p class="text-3xl sm:text-4xl font-bold text-white mt-2">Kab. Bangka</p>

                <p class="text-slate-200 mt-5 max-w-xl">
                    Membangun tenaga vokasi terampil, beriman, dan berdaya saing tinggi untuk kemajuan Bangka Belitung dan Indonesia.
                </p>

                <div class="flex flex-wrap gap-3 mt-8">
                    <a href="{{ route('ppdb.create') }}" class="px-6 py-3 rounded-lg bg-white text-sky-800 font-semibold hover:bg-sky-50 transition">
                        Daftar SPMB Sekarang
                    </a>
                    <a href="{{ route('profil') }}" class="px-6 py-3 rounded-lg border-2 border-white text-white font-semibold hover:bg-white/10 transition">
                        Profil Sekolah
                    </a>
                </div>
            </div>
        </div>

        {{-- Slides 1+: latest news --}}
        @foreach ($heroNews as $index => $item)
            <div x-show="active === {{ $index + 1 }}" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="absolute inset-0">
                <div class="absolute inset-0 bg-cover bg-center" style="background-image:url('{{ $item->thumbnail ? asset('storage/'.$item->thumbnail) : '/images/news-placeholder.jpg' }}')"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/50 to-transparent"></div>

                <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex flex-col justify-end pb-20 pt-28">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="px-3 py-1 rounded-full bg-emerald-500 text-white text-xs font-bold uppercase">
                            {{ $item->category ?? 'Berita' }}
                        </span>
                        <span class="text-slate-200 text-sm">{{ $item->published_at->translatedFormat('d F Y') }}</span>
                    </div>
                    <h2 class="text-3xl sm:text-5xl font-extrabold text-white max-w-3xl leading-tight">
                        {{ $item->title }}
                    </h2>
                    <p class="text-slate-200 mt-4 max-w-2xl">{{ $item->excerpt }}</p>
                    <a href="{{ route('berita.show', $item) }}" class="inline-flex items-center gap-2 mt-6 px-5 py-2.5 rounded-lg bg-white text-sky-800 font-semibold w-fit hover:bg-sky-50 transition">
                        Baca Selengkapnya →
                    </a>
                </div>
            </div>
        @endforeach

        {{-- Arrows --}}
        <button @click="prev()" aria-label="Slide sebelumnya"
                class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/20 hover:bg-white/30 text-white flex items-center justify-center backdrop-blur-sm transition">
            ‹
        </button>
        <button @click="next()" aria-label="Slide berikutnya"
                class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/20 hover:bg-white/30 text-white flex items-center justify-center backdrop-blur-sm transition">
            ›
        </button>

        {{-- Dots --}}
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex gap-2 z-10">
            <template x-for="i in total" :key="i">
                <button @click="active = i - 1"
                        :class="active === i - 1 ? 'bg-white w-6' : 'bg-white/40 w-2'"
                        class="h-2 rounded-full transition-all"></button>
            </template>
        </div>
    </div>
</section>

{{-- ============ PROGRAM KEAHLIAN ============ --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
    <p class="text-sky-600 font-semibold uppercase tracking-wide text-sm mb-2">Program Keahlian</p>
    <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 mb-3">Pilih Jurusan Masa Depanmu</h2>
    <p class="text-slate-500 max-w-2xl mx-auto mb-12">
        Pilih program keahlian yang sesuai minat dan bakat Anda untuk masa depan karir yang cerah.
    </p>

    <div class="grid sm:grid-cols-2 gap-6 text-left">
        @foreach ($programs as $program)
            <div class="bg-white border border-slate-200 rounded-2xl p-8 hover:shadow-lg transition">
                <div class="w-14 h-14 rounded-xl bg-sky-50 flex items-center justify-center text-2xl mb-5">
                    {{ $loop->first ? '💻' : '🔧' }}
                </div>
                <span class="inline-block px-3 py-1 rounded-full bg-sky-50 text-sky-700 text-xs font-semibold mb-3">
                    {{ $program->concentrations->pluck('name')->implode('/') }} · Akreditasi {{ $program->accreditation ?? '-' }}
                </span>
                <h3 class="text-xl font-bold text-slate-900 mb-2">{{ $program->name }}</h3>
                <div class="flex gap-2 mb-3 flex-wrap">
                    @foreach ($program->concentrations as $c)
                        <span class="px-2.5 py-1 rounded-md bg-slate-100 text-slate-600 text-xs font-medium">{{ $c->name }}</span>
                    @endforeach
                </div>
                <p class="text-slate-500 text-sm mb-4">{{ \Illuminate\Support\Str::limit($program->description, 110) }}</p>
                <a href="{{ route('program.index') }}" class="text-sky-700 font-semibold text-sm hover:underline">
                    Lihat Detail Program →
                </a>
            </div>
        @endforeach
    </div>

    <a href="{{ route('program.index') }}" class="inline-block mt-10 px-6 py-3 rounded-lg bg-sky-700 text-white font-semibold hover:bg-sky-800 transition">
        Lihat Detail Semua Jurusan →
    </a>
</section>

{{-- ============ BERITA TERBARU ============ --}}
@if ($latestNews->isNotEmpty())
<section class="bg-slate-50 py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-10">
            <div>
                <p class="text-sky-600 font-semibold uppercase tracking-wide text-sm mb-2">Berita & Pengumuman</p>
                <h2 class="text-3xl sm:text-4xl font-bold text-slate-900">Kabar Terbaru</h2>
            </div>
            <a href="{{ route('berita.index') }}" class="hidden sm:inline-block px-5 py-2.5 rounded-lg border-2 border-sky-700 text-sky-700 font-semibold hover:bg-sky-50 transition">
                Semua Berita →
            </a>
        </div>

        <div class="grid lg:grid-cols-2 gap-8">
            @php $featured = $latestNews->first(); $rest = $latestNews->skip(1); @endphp

            <a href="{{ route('berita.show', $featured) }}" class="block group">
                <div class="rounded-2xl overflow-hidden bg-slate-200 aspect-[4/3] mb-4">
                    <img src="{{ $featured->thumbnail ? asset('storage/'.$featured->thumbnail) : '/images/news-placeholder.jpg' }}"
                         alt="{{ $featured->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                </div>
                <div class="flex items-center gap-3 mb-2">
                    <span class="px-2.5 py-1 rounded-full bg-emerald-500 text-white text-xs font-bold uppercase">{{ $featured->category ?? 'Berita' }}</span>
                    <span class="text-slate-400 text-sm">{{ $featured->published_at->translatedFormat('d F Y') }}</span>
                </div>
                <h3 class="text-xl font-bold text-slate-900 group-hover:text-sky-700 transition">{{ $featured->title }}</h3>
                <p class="text-slate-500 text-sm mt-1">{{ $featured->excerpt }}</p>
            </a>

            <div class="space-y-4">
                @foreach ($rest as $item)
                    <a href="{{ route('berita.show', $item) }}" class="flex gap-4 bg-white rounded-xl p-4 border border-slate-200 hover:shadow-md transition">
                        <img src="{{ $item->thumbnail ? asset('storage/'.$item->thumbnail) : '/images/news-placeholder.jpg' }}"
                             alt="{{ $item->title }}" class="w-24 h-24 rounded-lg object-cover shrink-0">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="px-2 py-0.5 rounded-full bg-sky-700 text-white text-[11px] font-bold uppercase">{{ $item->category ?? 'Berita' }}</span>
                                <span class="text-slate-400 text-xs">{{ $item->published_at->translatedFormat('d F Y') }}</span>
                            </div>
                            <h4 class="font-semibold text-slate-900 text-sm leading-snug">{{ $item->title }}</h4>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

{{-- ============ CTA SPMB PENUTUP ============ --}}
<section class="bg-gradient-to-r from-sky-700 to-sky-500 py-16">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <h2 class="text-2xl sm:text-3xl font-bold text-white mb-6">Siap Bergabung dengan SMKN 1 Bakam?</h2>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ route('ppdb.create') }}" class="px-6 py-3 rounded-lg bg-white text-sky-800 font-semibold hover:bg-sky-50 transition">
                Daftar Sekarang
            </a>
            <a href="{{ route('ppdb.check-status') }}" class="px-6 py-3 rounded-lg border-2 border-white text-white font-semibold hover:bg-white/10 transition">
                Cek Status Pendaftaran
            </a>
        </div>
    </div>
</section>

@endsection