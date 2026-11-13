@extends('layouts.admin')

@php
    $isEdit = $concentration->exists;
    $listFields = [
        'competencies'     => ['Materi Pembelajaran (kompetensi keahlian)', 'Satu item per baris. Tampil sebagai kartu bernomor.'],
        'advantages'       => ['Keunggulan Jurusan', 'Satu item per baris.'],
        'career_prospects' => ['Prospek Lulusan', 'Satu item per baris. Contoh: Network Engineer.'],
        'further_studies'  => ['Peluang Lanjutan', 'Satu item per baris. Contoh: Teknik Informatika.'],
    ];
    $input = 'w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-sky-600 focus:ring-1 focus:ring-sky-600';
@endphp

@section('title', $isEdit ? 'Edit Konsentrasi' : 'Tambah Konsentrasi')

@section('content')

<div class="mb-6">
    <a href="{{ route('admin.program.index') }}" class="text-sm font-semibold text-sky-700 hover:text-sky-900">Kembali ke Program Keahlian</a>
    <h1 class="text-2xl font-bold text-slate-900 mt-2">
        {{ $isEdit ? 'Edit Konsentrasi ' . $concentration->name : 'Tambah Konsentrasi' }}
    </h1>
    <p class="text-slate-400 text-sm mt-1">{{ $program->name }}</p>
</div>

@if (session('success'))
    <div class="mb-6 rounded-lg bg-green-50 border border-green-200 text-green-800 px-4 py-3 text-sm">{{ session('success') }}</div>
@endif

@if ($errors->any())
    <div class="mb-6 rounded-lg bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm">
        <p class="font-semibold mb-1">Data belum bisa disimpan:</p>
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST"
      action="{{ $isEdit ? route('admin.program.concentrations.update', $concentration) : route('admin.program.concentrations.store', $program) }}"
      enctype="multipart/form-data"
      class="bg-white rounded-xl border border-slate-100 shadow-sm p-6 space-y-6 max-w-3xl">
    @csrf
    @if ($isEdit) @method('PUT') @endif

    <div class="grid sm:grid-cols-2 gap-4">
        <div>
            <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">Singkatan</label>
            <input id="name" name="name" type="text" placeholder="TKJ" value="{{ old('name', $concentration->name) }}" class="{{ $input }}" required>
        </div>
        <div>
            <label for="quota" class="block text-sm font-semibold text-slate-700 mb-1.5">Kuota</label>
            <input id="quota" name="quota" type="number" min="0" value="{{ old('quota', $concentration->quota ?? 0) }}" class="{{ $input }}" required>
        </div>
    </div>

    <div>
        <label for="title" class="block text-sm font-semibold text-slate-700 mb-1.5">Nama lengkap</label>
        <input id="title" name="title" type="text" placeholder="Teknik Komputer Jaringan" value="{{ old('title', $concentration->title) }}" class="{{ $input }}">
    </div>

    <div>
        <label for="slug" class="block text-sm font-semibold text-slate-700 mb-1.5">Slug</label>
        <input id="slug" name="slug" type="text" value="{{ old('slug', $concentration->slug) }}" class="{{ $input }}">
        <p class="text-xs text-slate-400 mt-1">Kosongkan untuk dibuat otomatis dari singkatan.</p>
    </div>

    <div>
        <label for="description" class="block text-sm font-semibold text-slate-700 mb-1.5">Deskripsi singkat</label>
        <textarea id="description" name="description" rows="3" class="{{ $input }}">{{ old('description', $concentration->description) }}</textarea>
        <p class="text-xs text-slate-400 mt-1">Tampil di kartu daftar program dan paragraf pertama halaman detail.</p>
    </div>

    <div>
        <label for="overview" class="block text-sm font-semibold text-slate-700 mb-1.5">Penjelasan lengkap</label>
        <textarea id="overview" name="overview" rows="6" class="{{ $input }}">{{ old('overview', $concentration->overview) }}</textarea>
        <p class="text-xs text-slate-400 mt-1">Tampil di halaman detail. Pisahkan paragraf dengan baris kosong.</p>
    </div>

    <div>
        <label for="image" class="block text-sm font-semibold text-slate-700 mb-1.5">Foto kegiatan</label>
        @if ($concentration->image)
            <img src="{{ asset('storage/' . $concentration->image) }}" alt="Foto {{ $concentration->name }}" class="w-48 h-32 object-cover rounded-lg mb-3">
            <label class="flex items-center gap-2 text-sm text-slate-600 mb-3">
                <input type="checkbox" name="remove_image" value="1" class="rounded border-slate-300"> Hapus foto ini
            </label>
        @endif
        <input id="image" name="image" type="file" accept="image/*" class="block w-full text-sm text-slate-600 file:mr-4 file:rounded-lg file:border-0 file:bg-sky-50 file:px-4 file:py-2 file:font-semibold file:text-sky-700 hover:file:bg-sky-100">
        <p class="text-xs text-slate-400 mt-1">JPG atau PNG, maksimal 2 MB.</p>
    </div>

    @foreach ($listFields as $field => [$label, $hint])
        <div>
            <label for="{{ $field }}" class="block text-sm font-semibold text-slate-700 mb-1.5">{{ $label }}</label>
            <textarea id="{{ $field }}" name="{{ $field }}" rows="5" class="{{ $input }}">{{ old($field, implode("\n", $concentration->$field ?? [])) }}</textarea>
            <p class="text-xs text-slate-400 mt-1">{{ $hint }}</p>
        </div>
    @endforeach

    <div class="flex items-center gap-3 pt-2">
        <button type="submit" class="px-6 py-2.5 rounded-lg bg-sky-700 text-white font-semibold text-sm hover:bg-sky-800">Simpan</button>
        @if ($isEdit)
            <a href="{{ route('program.concentration', $concentration) }}" target="_blank" class="px-6 py-2.5 rounded-lg border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50">Lihat halaman publik</a>
        @else
            <a href="{{ route('admin.program.index') }}" class="px-6 py-2.5 rounded-lg border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50">Batal</a>
        @endif
    </div>
</form>

@endsection