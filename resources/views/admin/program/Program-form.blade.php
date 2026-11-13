@extends('layouts.admin')

@php
    $isEdit = $program->exists;
    $input = 'w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-sky-600 focus:ring-1 focus:ring-sky-600';
@endphp

@section('title', $isEdit ? 'Edit Program Keahlian' : 'Tambah Program Keahlian')

@section('content')

<div class="mb-6">
    <a href="{{ route('admin.program.index') }}" class="text-sm font-semibold text-sky-700 hover:text-sky-900">Kembali ke Program Keahlian</a>
    <h1 class="text-2xl font-bold text-slate-900 mt-2">{{ $isEdit ? 'Edit Program Keahlian' : 'Tambah Program Keahlian' }}</h1>
</div>

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
      action="{{ $isEdit ? route('admin.program.update', $program) : route('admin.program.store') }}"
      class="bg-white rounded-xl border border-slate-100 shadow-sm p-6 space-y-6 max-w-3xl">
    @csrf
    @if ($isEdit) @method('PUT') @endif

    <div>
        <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">Nama program keahlian</label>
        <input id="name" name="name" type="text" value="{{ old('name', $program->name) }}" class="{{ $input }}" required>
    </div>

    <div class="grid sm:grid-cols-2 gap-4">
        <div>
            <label for="accreditation" class="block text-sm font-semibold text-slate-700 mb-1.5">Akreditasi</label>
            <input id="accreditation" name="accreditation" type="text" maxlength="10" placeholder="A" value="{{ old('accreditation', $program->accreditation) }}" class="{{ $input }}">
        </div>
        <div>
            <label for="slug" class="block text-sm font-semibold text-slate-700 mb-1.5">Slug</label>
            <input id="slug" name="slug" type="text" value="{{ old('slug', $program->slug) }}" class="{{ $input }}">
            <p class="text-xs text-slate-400 mt-1">Kosongkan untuk dibuat otomatis dari nama.</p>
        </div>
    </div>

    <div>
        <label for="description" class="block text-sm font-semibold text-slate-700 mb-1.5">Deskripsi</label>
        <textarea id="description" name="description" rows="4" class="{{ $input }}">{{ old('description', $program->description) }}</textarea>
    </div>

    <div class="flex items-center gap-3 pt-2">
        <button type="submit" class="px-6 py-2.5 rounded-lg bg-sky-700 text-white font-semibold text-sm hover:bg-sky-800">Simpan</button>
        <a href="{{ route('admin.program.index') }}" class="px-6 py-2.5 rounded-lg border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50">Batal</a>
    </div>
</form>

@if ($isEdit)
    <p class="text-sm text-slate-400 mt-4 max-w-3xl">Konsentrasi program ini diatur dari halaman daftar program: klik nama konsentrasi untuk mengedit, atau "Tambah Konsentrasi" untuk menambah.</p>
@endif

@endsection