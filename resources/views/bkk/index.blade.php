@extends('layouts.app')

@section('title', 'Bursa Kerja Khusus (BKK)')

@section('content')
<div x-data="{ typeFilter: '{{ request('type', 'semua') }}' }">

    {{-- ============ HERO ============ --}}
    <section class="bg-gradient-to-br from-sky-800 to-sky-600 py-20 relative overflow-hidden">
        <div class="absolute -right-20 -bottom-20 w-96 h-96 rounded-full bg-white/5"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <p class="text-sky-200 font-semibold text-sm tracking-wide mb-3">SMKN 1 BAKAM</p>
            <h1 class="text-4xl sm:text-5xl font-extrabold text-white mb-5">Bursa Kerja Khusus (BKK)</h1>
            <p class="text-sky-100 max-w-2xl">
                Informasi lowongan kerja dan magang dari mitra industri SMKN 1 Bakam untuk alumni dan siswa kelas XII.
            </p>
        </div>
    </section>

    {{-- ============ QUICK STATS BAR ============ --}}
    <section class="bg-sky-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-2 sm:grid-cols-4 gap-6">
            @php
                $quick = [
                    ['icon' => '🤝', 'title' => 'Mitra Industri', 'desc' => $stats['mitra_industri'].'+ perusahaan aktif'],
                    ['icon' => '💼', 'title' => 'Lowongan Aktif', 'desc' => $stats['lowongan_aktif'].' posisi tersedia'],
                    ['icon' => '🎯', 'title' => 'Penempatan Alumni', 'desc' => $stats['kesesuaian_persen'].'%+ terserap industri'],
                    ['icon' => '📞', 'title' => 'Kontak BKK', 'desc' => \App\Models\Setting::get('school_phone', '0717-XXXXXX').' / Ruang BKK'],
                ];
            @endphp
            @foreach ($quick as $item)
                <div class="flex items-center gap-3">
                    <span class="w-11 h-11 rounded-lg bg-sky-700 text-white flex items-center justify-center text-xl shrink-0">{{ $item['icon'] }}</span>
                    <div>
                        <p class="font-semibold text-slate-900 text-sm">{{ $item['title'] }}</p>
                        <p class="text-slate-500 text-xs">{{ $item['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ============ RINGKASAN STATISTIK TRACER STUDY ============ --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <p class="text-sky-600 font-semibold text-xs tracking-wide mb-2">DATA ALUMNI</p>
        <h2 class="text-3xl font-bold text-slate-900 mb-2">Statistik Penempatan Alumni BKK</h2>
        <p class="text-slate-500 mb-8">Rekap hasil tracer study alumni SMKN 1 Bakam — diperbarui berkala oleh tim BKK sekolah.</p>

        <x-tracer-cta />

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
            <x-tracer-stat-card border="border-sky-500" icon="👥" :value="$tracerSummary['total']" label="Total Alumni Tersurvei" />
            <x-tracer-stat-card border="border-emerald-500" icon="🎯" :value="$tracerSummary['kesesuaian_persen'].'%'" label="Kesesuaian Bidang Kerja" />
            <x-tracer-stat-card border="border-orange-500" icon="⏱️" :value="$tracerSummary['avg_wait_months'].' bulan'" label="Rata-rata Waktu Tunggu" />
            <x-tracer-stat-card border="border-amber-500" icon="🤝" :value="$tracerSummary['via_bkk']" label="Tersalurkan via BKK" />
        </div>

        <div class="grid lg:grid-cols-2 gap-6 mt-6">
            <x-tracer-status-distribution :stats="$tracerSummary" />
            <x-tracer-kesesuaian-donut :stats="$tracerSummary" />
        </div>
    </section>

    {{-- ============ CTA PENUTUP TRACER STUDY ============ --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        <div class="bg-gradient-to-br from-sky-800 to-sky-600 rounded-2xl px-8 py-12 text-center relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-40 h-40 rounded-full bg-white/5"></div>
            <div class="absolute -left-10 -bottom-10 w-32 h-32 rounded-full bg-white/5"></div>
            <p class="text-sky-200 font-semibold text-xs tracking-wide mb-3 relative">TRACER STUDY ALUMNI</p>
            <h2 class="text-2xl sm:text-3xl font-bold text-white mb-3 relative">Sudah Bekerja, Kuliah, atau Berwirausaha?<br>Bagikan Kisahmu!</h2>
            <p class="text-sky-100 max-w-xl mx-auto mb-6 relative">
                Data alumni membantu SMKN 1 Bakam terus meningkatkan kualitas pendidikan dan relevansi program keahlian dengan industri.
            </p>
            <a href="{{ route('bkk.tracer-study.index') }}" class="relative inline-block px-6 py-3 rounded-lg bg-white text-sky-800 font-semibold hover:bg-sky-50 transition">
                Isi Tracer Study Sekarang →
            </a>
            <p class="text-sky-200 text-xs mt-4 relative">Hanya perlu NISN dan ±5 menit · Data dijaga kerahasiaannya</p>
        </div>
    </section>

    {{-- ============ LOWONGAN & MAGANG ============ --}}
    <section class="bg-slate-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
                <div>
                    <p class="text-sky-600 font-semibold text-xs tracking-wide mb-2">BURSA KERJA KHUSUS</p>
                    <h2 class="text-3xl font-bold text-slate-900">Lowongan & Magang Terbaru</h2>
                </div>
                <div class="flex flex-wrap gap-2">
                    @php
                        $filters = ['' => 'Semua', 'kerja_tetap' => 'Kerja Tetap', 'magang' => 'Magang', 'magang_rekrutmen' => 'Magang & Rekrutmen'];
                    @endphp
                    @foreach ($filters as $value => $label)
                        <a href="{{ route('bkk', $value ? ['type' => $value] : []) }}"
                           class="px-4 py-2 rounded-lg text-sm font-semibold transition
                                  {{ request('type', '') === $value ? 'bg-sky-700 text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="space-y-4">
                @forelse ($listings as $listing)
                    <div class="bg-white rounded-xl border-l-4 border-sky-700 shadow-sm p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2 mb-3 flex-wrap">
                                @php
                                    $typeColors = [
                                        'kerja_tetap' => 'bg-emerald-100 text-emerald-700',
                                        'magang' => 'bg-indigo-100 text-indigo-700',
                                        'magang_rekrutmen' => 'bg-purple-100 text-purple-700',
                                    ];
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $typeColors[$listing->type] ?? 'bg-slate-100 text-slate-600' }}">
                                    {{ $listing->typeLabel() }}
                                </span>
                                @if ($listing->isNew())
                                    <span class="px-3 py-1 rounded-full bg-sky-800 text-white text-xs font-bold">TERBARU</span>
                                @endif
                                <span class="text-slate-400 text-sm">{{ $listing->posted_at->translatedFormat('j M Y') }}</span>
                            </div>
                            <h3 class="text-xl font-bold text-sky-900 mb-1">{{ $listing->title }}</h3>
                            <p class="text-slate-600 text-sm flex items-center gap-1.5">🏢 {{ $listing->company_name }}</p>
                            <p class="text-slate-400 text-sm flex items-center gap-1.5">📍 {{ $listing->location }}</p>
                        </div>
                        <a href="#" class="shrink-0 px-5 py-2.5 rounded-lg bg-sky-50 text-sky-700 text-sm font-semibold hover:bg-sky-100 transition text-center">
                            Lihat Detail & Kontak →
                        </a>
                    </div>
                @empty
                    <div class="bg-white rounded-xl border border-slate-200 p-10 text-center text-slate-400">
                        Belum ada lowongan untuk kategori ini.
                    </div>
                @endforelse
            </div>

            <div class="mt-8 bg-sky-50 border border-sky-100 rounded-xl px-6 py-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="font-bold text-sky-900 mb-1">Ingin Mendaftarkan Perusahaan Anda?</h3>
                    <p class="text-slate-500 text-sm">Bagi perusahaan/industri yang ingin bermitra dengan BKK SMKN 1 Bakam, silakan hubungi kami.</p>
                </div>
                <p class="text-sky-800 font-semibold text-sm whitespace-nowrap">
                    📞 {{ \App\Models\Setting::get('school_phone', '0717-XXXXXX') }} | ✉️ bkk@smkn1bakam.sch.id
                </p>
            </div>
        </div>
    </section>

</div>
@endsection
