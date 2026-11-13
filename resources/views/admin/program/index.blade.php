@extends('layouts.admin')

@section('title', 'Program Keahlian')

@section('content')

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Program Keahlian</h1>
        <p class="text-slate-400 text-sm mt-1">Kelola data jurusan dan kompetensi keahlian</p>
    </div>
    <a href="{{ route('admin.program.create') }}" class="px-5 py-2.5 rounded-lg bg-sky-700 text-white font-semibold text-sm hover:bg-sky-800">
        + Tambah Jurusan
    </a>
</div>

@if (session('success'))
    <div class="mb-6 rounded-lg bg-green-50 border border-green-200 text-green-800 px-4 py-3 text-sm">{{ session('success') }}</div>
@endif

@if ($errors->any())
    <div class="mb-6 rounded-lg bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

<div class="grid sm:grid-cols-2 gap-6">
    @foreach ($programs as $program)
        <div class="bg-white rounded-xl border-t-4 border-t-sky-700 border-x border-b border-slate-100 shadow-sm p-6">
            <div class="flex items-start justify-between mb-4">
                <span class="text-3xl">{{ $loop->first ? '💻' : '🔧' }}</span>
                <span class="px-3 py-1 rounded-full bg-sky-50 text-sky-700 text-xs font-semibold">
                    Akreditasi {{ $program->accreditation ?? '-' }}
                </span>
            </div>
            <p class="text-xs font-bold text-sky-600 tracking-wide mb-1">{{ $loop->first ? 'TJKT' : 'TO' }}</p>
            <h3 class="font-bold text-slate-900 text-lg mb-2">{{ $program->name }}</h3>
            <p class="text-slate-500 text-sm mb-4">{{ \Illuminate\Support\Str::limit($program->description, 90) }}</p>

            {{-- Konsentrasi: nama (link ke form lengkap) + kuota yang bisa diubah langsung --}}
            <div class="mb-4">
                <p class="text-slate-400 text-xs mb-2">Klik nama konsentrasi untuk mengedit isi halaman detailnya</p>
                <ul class="divide-y divide-slate-100 rounded-lg border border-slate-100">
                    @forelse ($program->concentrations as $c)
                        <li class="flex items-center justify-between gap-3 px-3 py-2.5">
                            <a href="{{ route('admin.program.concentrations.edit', $c) }}"
                               title="Edit {{ $c->title ?? $c->name }}"
                               class="text-sm font-semibold text-slate-700 hover:text-sky-700">{{ $c->name }}</a>
                            <form method="POST" action="{{ route('admin.program.concentrations.quota', $c) }}" class="flex items-center gap-2">
                                @csrf
                                @method('PATCH')
                                <label for="quota-{{ $c->id }}" class="text-xs text-slate-400">Kuota</label>
                                <input id="quota-{{ $c->id }}" name="quota" type="number" min="0" max="9999" value="{{ $c->quota }}"
                                       class="w-20 rounded-md border border-slate-300 px-2 py-1.5 text-sm focus:border-sky-600 focus:ring-1 focus:ring-sky-600">
                                <button type="submit" class="px-3 py-1.5 rounded-md bg-sky-50 text-sky-700 text-xs font-semibold hover:bg-sky-100">Simpan</button>
                            </form>
                        </li>
                    @empty
                        <li class="px-3 py-2.5 text-sm text-slate-400">Belum ada konsentrasi.</li>
                    @endforelse
                </ul>
                <a href="{{ route('admin.program.concentrations.create', $program) }}"
                   class="inline-block mt-3 px-2.5 py-1 rounded-md border border-dashed border-sky-300 text-sky-700 text-xs font-semibold hover:bg-sky-50">+ Tambah Konsentrasi</a>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                <div>
                    <p class="text-slate-400 text-xs">Total Kuota</p>
                    <p class="font-bold text-slate-900 text-xl">{{ $program->concentrations->sum('quota') }}</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.program.edit', $program) }}" class="px-4 py-2 rounded-lg bg-sky-50 text-sky-700 text-sm font-semibold hover:bg-sky-100">Edit</a>
                    <a href="{{ route('program.index') }}" target="_blank" class="px-4 py-2 rounded-lg border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50">Detail</a>
                </div>
            </div>
        </div>
    @endforeach
</div>

@endsection