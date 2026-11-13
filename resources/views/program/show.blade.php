@extends('layouts.app')

@section('title', ($concentration->title ?? $concentration->name) . ' - Program Keahlian')

@section('content')

{{-- ============ HERO ============ --}}
<section class="bg-sky-800 py-14">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="text-sm text-sky-200 mb-3" aria-label="Breadcrumb">
            <a href="{{ route('program.index') }}" class="hover:text-white">Program</a>
            <span class="mx-1">/</span>
            <span>{{ $program->name }}</span>
        </nav>
        <h1 class="text-3xl sm:text-5xl font-extrabold text-white mb-5">
            {{ $concentration->title ?? $concentration->name }} ({{ $concentration->name }})
        </h1>
        <div class="flex flex-wrap gap-2">
            <span class="px-4 py-1.5 rounded-full bg-white/15 text-white text-sm font-semibold">
                Akreditasi {{ $program->accreditation ?? '-' }}
            </span>
            <span class="px-4 py-1.5 rounded-full bg-white/15 text-white text-sm font-semibold">
                Kuota {{ $concentration->quota }} Siswa
            </span>
        </div>
    </div>
</section>

{{-- ============ ISI ============ --}}
<section class="bg-white py-14">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid lg:grid-cols-3 gap-10 items-start">

        {{-- Kolom utama --}}
        <div class="lg:col-span-2">

            {{-- Pengantar: foto + deskripsi --}}
            <div class="flex flex-col md:flex-row gap-8 mb-12">
                @if ($concentration->image)
                    <img src="{{ asset('storage/' . $concentration->image) }}"
                         alt="Kegiatan siswa {{ $concentration->name }}"
                         class="w-full md:w-80 h-64 object-cover rounded-2xl shrink-0">
                @else
                    <div class="w-full md:w-80 h-64 rounded-2xl bg-sky-100 text-sky-700 font-semibold flex items-center justify-center text-center p-6 shrink-0">
                        Foto kegiatan belum ditambahkan
                    </div>
                @endif
                <div class="text-slate-600 text-lg leading-relaxed">
                    <p>{{ $concentration->description }}</p>
                    @if ($concentration->overview)
                        <p class="mt-4 whitespace-pre-line">{{ $concentration->overview }}</p>
                    @endif
                </div>
            </div>

            @if (!empty($concentration->competencies))
                <div class="border-t border-slate-200 pt-8 mb-12">
                    <h2 class="text-2xl font-bold text-slate-900 mb-5">Materi Pembelajaran</h2>
                    <ul class="grid sm:grid-cols-2 gap-4">
                        @foreach ($concentration->competencies as $skill)
                            <li class="flex items-start gap-3 rounded-xl bg-sky-50 p-4">
                                <span class="w-8 h-8 shrink-0 rounded-full bg-sky-700 text-white text-sm font-bold flex items-center justify-center">
                                    {{ $loop->iteration }}
                                </span>
                                <span class="font-semibold text-slate-800 pt-1">{{ $skill }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (!empty($concentration->advantages))
                <div class="border-t border-slate-200 pt-8 mb-12">
                    <h2 class="text-2xl font-bold text-slate-900 mb-5">Keunggulan Jurusan</h2>
                    <ul class="divide-y divide-slate-100">
                        @foreach ($concentration->advantages as $item)
                            <li class="flex items-start gap-3 py-3 text-slate-700 text-lg">
                                <svg class="w-6 h-6 text-sky-700 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12l5 5 9-10"/></svg>
                                <span>{{ $item }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (!empty($concentration->career_prospects))
                <div class="border-t border-slate-200 pt-8 mb-12">
                    <h2 class="text-2xl font-bold text-slate-900 mb-5">Prospek Lulusan</h2>
                    <div class="flex flex-wrap gap-2.5">
                        @foreach ($concentration->career_prospects as $career)
                            <span class="px-4 py-2 rounded-full bg-sky-800 text-white text-sm font-semibold">{{ $career }}</span>
                        @endforeach
                    </div>
                </div>
            @endif

            @if (!empty($concentration->further_studies))
                <div class="border-t border-slate-200 pt-8">
                    <h2 class="text-2xl font-bold text-slate-900 mb-5">Peluang Lanjutan</h2>
                    <div class="flex flex-wrap gap-2.5">
                        @foreach ($concentration->further_studies as $study)
                            <span class="px-4 py-2 rounded-full bg-sky-50 text-sky-700 text-sm font-semibold">{{ $study }}</span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <aside class="lg:sticky lg:top-24 space-y-5">
            <div class="rounded-2xl border border-slate-200 shadow-sm p-6">
                <h2 class="font-bold text-slate-900 mb-3">Informasi Ringkas</h2>
                <dl class="text-sm divide-y divide-slate-100">
                    <div class="flex justify-between py-3"><dt class="text-slate-500">Program</dt><dd class="font-semibold text-slate-800 text-right">{{ $program->name }}</dd></div>
                    <div class="flex justify-between py-3"><dt class="text-slate-500">Akreditasi</dt><dd class="font-semibold text-slate-800">{{ $program->accreditation ?? '-' }}</dd></div>
                    <div class="flex justify-between py-3"><dt class="text-slate-500">Kuota</dt><dd class="font-semibold text-slate-800">{{ $concentration->quota }} siswa</dd></div>
                </dl>
                <a href="{{ route('ppdb.create', ['program' => $program->id, 'concentration' => $concentration->id]) }}"
                   class="mt-4 block text-center py-3 rounded-lg bg-sky-700 text-white font-semibold hover:bg-sky-800 transition">
                    Daftar SPMB Online
                </a>
            </div>

            @if ($others->isNotEmpty())
                <div class="rounded-2xl bg-sky-50 p-6">
                    <h2 class="font-bold text-slate-900 mb-2">Konsentrasi Lainnya</h2>
                    <ul>
                        @foreach ($others as $other)
                            <li>
                                <a href="{{ route('program.concentration', $other) }}"
                                   class="block py-2.5 font-semibold text-sky-700 hover:text-sky-900">
                                    {{ $other->title ?? $other->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </aside>
    </div>
</section>

{{-- ============ KEMBALI ============ --}}
<section class="bg-sky-50 py-10 text-center">
    <a href="{{ route('program.index') }}" class="font-semibold text-sky-700 hover:text-sky-900">Kembali ke semua program keahlian</a>
</section>

@endsection