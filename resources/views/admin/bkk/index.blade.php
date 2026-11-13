@extends('layouts.admin')

@section('title', 'Manajemen BKK')

@section('content')

@php
    $typeLabels = ['kerja_tetap' => 'Kerja Tetap', 'magang' => 'Magang', 'magang_rekrutmen' => 'Magang & Rekrutmen'];
@endphp

<div x-data="{ showAdd: {{ $errors->has('title') && !old('_method') ? 'true' : 'false' }} }">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Manajemen BKK</h1>
            <p class="text-slate-400 text-sm mt-1">Kelola lowongan kerja dan magang dari mitra industri</p>
        </div>
        <button @click="showAdd = true" class="px-5 py-2.5 rounded-lg bg-sky-700 text-white font-semibold text-sm hover:bg-sky-800">
            + Tambah Lowongan
        </button>
    </div>

    @if (session('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl px-5 py-4 text-sm">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-6">
        <h2 class="font-bold text-slate-900 mb-5">Lowongan Aktif ({{ $listings->count() }})</h2>

        <div class="space-y-4">
            @forelse ($listings as $listing)
                <div class="border-l-4 border-sky-500 bg-slate-50 rounded-r-xl p-5 flex items-start justify-between gap-4" x-data="{ editing: false, confirmDelete: false }">
                    <div class="min-w-0">
                        <div class="flex items-center gap-2 mb-1 flex-wrap">
                            <h3 class="font-bold text-slate-900">{{ $listing->title }}</h3>
                            @if ($listing->isNew())
                                <span class="px-2 py-0.5 rounded-full bg-sky-700 text-white text-[10px] font-bold">BARU</span>
                            @endif
                        </div>
                        <p class="text-slate-500 text-sm">
                            🏢 {{ $listing->company_name }} ·
                            📍 {{ $listing->location }} ·
                            {{ $typeLabels[$listing->type] ?? $listing->type }}
                        </p>
                    </div>
                    <div class="flex gap-2 shrink-0">
                        <button @click="editing = true" class="px-4 py-2 rounded-lg bg-sky-50 text-sky-700 text-sm font-semibold hover:bg-sky-100">Edit</button>
                        <button @click="confirmDelete = true" class="px-4 py-2 rounded-lg bg-red-50 text-red-600 text-sm font-semibold hover:bg-red-100">Hapus</button>
                    </div>

                    {{-- Edit modal --}}
                    <div x-show="editing" x-cloak @click.self="editing = false" class="fixed inset-0 z-50 bg-slate-900/60 flex items-center justify-center p-4">
                        <div class="bg-white rounded-2xl max-w-lg w-full p-8 max-h-[90vh] overflow-y-auto">
                            <h3 class="text-xl font-bold text-sky-700 mb-6">Edit Lowongan</h3>
                            <form method="POST" action="{{ route('admin.bkk.update', $listing) }}" class="space-y-5">
                                @csrf @method('PATCH')
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">JUDUL LOWONGAN</label>
                                    <input type="text" name="title" value="{{ $listing->title }}" class="w-full px-4 py-3 rounded-lg border border-slate-200">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">NAMA MITRA INDUSTRI</label>
                                    <input type="text" name="company_name" value="{{ $listing->company_name }}" class="w-full px-4 py-3 rounded-lg border border-slate-200">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">LOKASI</label>
                                    <input type="text" name="location" value="{{ $listing->location }}" class="w-full px-4 py-3 rounded-lg border border-slate-200">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">KONTAK / PIC</label>
                                    <input type="text" name="contact_info" value="{{ $listing->contact_info }}" class="w-full px-4 py-3 rounded-lg border border-slate-200">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">JENIS</label>
                                    <select name="type" class="w-full px-4 py-3 rounded-lg border border-slate-200">
                                        @foreach ($typeLabels as $value => $label)
                                            <option value="{{ $value }}" {{ $listing->type === $value ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">KUALIFIKASI</label>
                                    <textarea name="qualifications" rows="3" class="w-full px-4 py-3 rounded-lg border border-slate-200">{{ $listing->qualifications }}</textarea>
                                </div>
                                <div class="flex justify-end gap-3 pt-2">
                                    <button type="button" @click="editing = false" class="px-6 py-2.5 rounded-lg border border-slate-200 text-slate-500 font-semibold text-sm">Batal</button>
                                    <button type="submit" class="px-6 py-2.5 rounded-lg bg-sky-700 hover:bg-sky-800 text-white font-semibold text-sm">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Delete confirm --}}
                    <div x-show="confirmDelete" x-cloak @click.self="confirmDelete = false" class="fixed inset-0 z-50 bg-slate-900/60 flex items-center justify-center p-4">
                        <div class="bg-white rounded-2xl max-w-sm w-full p-6 text-center">
                            <div class="w-14 h-14 rounded-full bg-red-50 text-red-500 flex items-center justify-center text-2xl mx-auto mb-4">⚠️</div>
                            <h3 class="text-lg font-bold text-slate-900 mb-2">Hapus Lowongan Ini?</h3>
                            <p class="text-slate-500 text-sm mb-6">
                                "<strong>{{ $listing->title }}</strong>" dari {{ $listing->company_name }} akan dihapus permanen. Tindakan ini tidak dapat dibatalkan.
                            </p>
                            <div class="flex gap-3">
                                <button @click="confirmDelete = false" class="flex-1 py-2.5 rounded-lg border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50">Batal</button>
                                <form method="POST" action="{{ route('admin.bkk.destroy', $listing) }}" class="flex-1">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-full py-2.5 rounded-lg bg-red-600 hover:bg-red-700 text-white font-semibold text-sm">Ya, Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center text-slate-400 py-10">Belum ada lowongan. Klik "+ Tambah Lowongan" untuk mulai menambahkan.</p>
            @endforelse
        </div>
    </div>

    {{-- Add modal --}}
    <div x-show="showAdd" x-cloak @click.self="showAdd = false" class="fixed inset-0 z-50 bg-slate-900/60 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full p-8 max-h-[90vh] overflow-y-auto">
            <h3 class="text-xl font-bold text-sky-700 mb-6">Tambah Lowongan</h3>
            <form method="POST" action="{{ route('admin.bkk.store') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">JUDUL LOWONGAN</label>
                    <input type="text" name="title" value="{{ old('title') }}" class="w-full px-4 py-3 rounded-lg border border-slate-200">
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">NAMA MITRA INDUSTRI</label>
                    <input type="text" name="company_name" value="{{ old('company_name') }}" class="w-full px-4 py-3 rounded-lg border border-slate-200">
                    @error('company_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">LOKASI</label>
                    <input type="text" name="location" value="{{ old('location') }}" class="w-full px-4 py-3 rounded-lg border border-slate-200">
                    @error('location') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">KONTAK / PIC</label>
                    <input type="text" name="contact_info" value="{{ old('contact_info') }}" placeholder="mis. Bapak Andi — 0812-3456-7890" class="w-full px-4 py-3 rounded-lg border border-slate-200">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">JENIS</label>
                    <select name="type" class="w-full px-4 py-3 rounded-lg border border-slate-200">
                        @foreach ($typeLabels as $value => $label)
                            <option value="{{ $value }}" {{ old('type') === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">KUALIFIKASI</label>
                    <textarea name="qualifications" rows="3" placeholder="mis. Lulusan TSM, min. IPK 75, berpenampilan rapi, siap kerja shift" class="w-full px-4 py-3 rounded-lg border border-slate-200">{{ old('qualifications') }}</textarea>
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="showAdd = false" class="px-6 py-2.5 rounded-lg border border-slate-200 text-slate-500 font-semibold text-sm">Batal</button>
                    <button type="submit" class="px-6 py-2.5 rounded-lg bg-sky-700 hover:bg-sky-800 text-white font-semibold text-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
