@extends('layouts.app')

@section('title', 'Program Keahlian')

@section('content')

{{-- ============ HERO ============ --}}
<section class="bg-gradient-to-br from-sky-800 to-sky-600 py-20 relative overflow-hidden">
    <div class="absolute -right-20 -bottom-20 w-96 h-96 rounded-full bg-white/5"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <p class="text-sky-200 font-semibold text-sm tracking-wide mb-3">SMKN 1 BAKAM</p>
        <h1 class="text-4xl sm:text-5xl font-extrabold text-white mb-5">Program Keahlian</h1>
        <p class="text-sky-100 max-w-2xl">
            Dua program keahlian dengan tiga konsentrasi dirancang sesuai kebutuhan industri dan potensi lokal Bangka Belitung.
        </p>
    </div>
</section>

{{-- ============ INTRO ============ --}}
<section class="bg-slate-50 pt-16 pb-4 text-center">
    <div class="max-w-3xl mx-auto px-4">
        <p class="text-sky-600 font-semibold uppercase tracking-wide text-sm mb-2">Program Keahlian</p>
        <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 mb-4">
            {{ $programs->count() }} Program Keahlian, {{ $programs->sum(fn ($p) => $p->concentrations->count()) }} Konsentrasi
        </h2>
        <p class="text-slate-500">
            Pilih program keahlian yang sesuai dengan minat, bakat, dan impian karir Anda.
        </p>
    </div>
</section>

{{-- ============ DAFTAR PROGRAM ============ --}}
<section class="bg-slate-50 pb-20 pt-6">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        @foreach ($programs as $program)
            <div class="bg-white rounded-2xl border-l-4 border-sky-700 shadow-sm p-8">
                <div class="flex items-start gap-4 mb-6">
                    <div class="w-16 h-16 rounded-xl bg-sky-700 flex items-center justify-center text-3xl shrink-0">
                        {{ $loop->first ? '💻' : '🔧' }}
                    </div>
                    <div>
                        <p class="text-xs font-bold tracking-wide text-slate-400 mb-1">
                            {{ $loop->first ? 'TJKT' : 'TO' }}
                        </p>
                        <h3 class="text-2xl font-bold text-sky-900 mb-2">{{ $program->name }}</h3>
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="px-3 py-1 rounded-full bg-sky-50 text-sky-700 text-xs font-semibold">
                                Akreditasi {{ $program->accreditation ?? '-' }}
                            </span>
                            <span class="text-slate-400 text-sm">{{ $program->concentrations->count() }} Konsentrasi</span>
                        </div>
                    </div>
                </div>

                <p class="text-slate-600 mb-6">{{ $program->description }}</p>

                <div class="grid sm:grid-cols-2 gap-4">
                    @foreach ($program->concentrations as $concentration)
                        <div class="border border-slate-200 rounded-xl p-5">
                            <div class="flex items-center justify-between mb-3">
                                <span class="px-3 py-1 rounded-full bg-sky-800 text-white text-xs font-bold">
                                    {{ $concentration->name }}
                                </span>
                                <span class="text-slate-400 text-sm">Kuota {{ $concentration->quota }}</span>
                            </div>
                            <h4 class="font-bold text-slate-900 mb-1.5">{{ $concentration->title ?? $concentration->name }}</h4>
                            <p class="text-slate-500 text-sm mb-4">{{ \Illuminate\Support\Str::limit($concentration->description, 100) }}</p>
                            <a href="{{ route('program.concentration', $concentration) }}"
                               class="block w-full text-center py-2.5 rounded-lg bg-sky-50 text-sky-700 text-sm font-semibold hover:bg-sky-100 transition">
                                Lihat Detail & Kompetensi →
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</section>

{{-- ============ CTA PENUTUP ============ --}}
<section class="bg-sky-50 py-16 text-center">
    <div class="max-w-2xl mx-auto px-4">
        <h2 class="text-2xl sm:text-3xl font-bold text-sky-900 mb-3">Sudah Menentukan Pilihan?</h2>
        <p class="text-slate-500 mb-8">
            Segera daftarkan diri Anda melalui sistem SPMB Online. Kuota terbatas per konsentrasi.
        </p>
        <a href="{{ route('ppdb.create') }}" class="inline-block px-8 py-3.5 rounded-lg bg-sky-700 text-white font-semibold hover:bg-sky-800 transition">
            Daftar SPMB Sekarang
        </a>
    </div>
</section>

@endsection