@extends('layouts.admin')

@section('title', 'Edit Berita')

@section('content')

@php
    $isPublished = $news->published_at && $news->published_at->lte(now());
    $isScheduled = $news->published_at && $news->published_at->isFuture();
    $currentCategory = old('category', $news->category);
@endphp

<div class="max-w-4xl">

    <a href="{{ route('admin.news.index') }}" class="inline-flex items-center gap-1.5 text-sky-700 text-sm font-semibold mb-4 hover:underline">← Kembali ke Manajemen Berita</a>

    <div class="flex flex-wrap items-start justify-between gap-3 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Edit Berita</h1>
            <p class="text-slate-400 text-sm mt-1">Perubahan langsung tampil di website setelah disimpan (bila berita sudah terbit).</p>
        </div>
        <div class="flex items-center gap-2">
            @if ($isPublished)
                <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold">TERBIT · {{ $news->published_at->translatedFormat('d F Y') }}</span>
                <a href="{{ route('berita.show', $news) }}" target="_blank" class="px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-bold hover:bg-slate-200">Lihat di website ↗</a>
            @elseif ($isScheduled)
                <span class="px-3 py-1 rounded-full bg-amber-50 text-amber-700 text-xs font-bold">TERJADWAL · {{ $news->published_at->translatedFormat('d F Y H.i') }}</span>
            @else
                <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-500 text-xs font-bold">DRAFT</span>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-6">
        <form method="POST" action="{{ route('admin.news.update', $news) }}" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="grid sm:grid-cols-2 gap-5 mb-5">
                <div>
                    <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">JUDUL BERITA</label>
                    <input type="text" name="title" value="{{ old('title', $news->title) }}"
                           class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500">
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">KATEGORI</label>
                    <select name="category" class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500">
                        <option value="">-- Pilih --</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat }}" {{ $currentCategory === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                    @error('category') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">ISI BERITA</label>
            <textarea name="content" rows="16"
                      class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500">{{ old('content', $content) }}</textarea>
            <p class="text-slate-400 text-xs mt-1 mb-5">Pisahkan paragraf dengan satu baris kosong. Format khusus (tabel, daftar, tebal) pada berita lama akan menjadi teks biasa saat disimpan.</p>
            @error('content') <p class="text-red-500 text-xs -mt-4 mb-4">{{ $message }}</p> @enderror

            <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">FOTO UTAMA</label>
            @if ($news->thumbnail)
                <div class="flex items-start gap-4 mb-3">
                    <img src="{{ asset('storage/'.$news->thumbnail) }}" alt="Foto saat ini" class="h-28 w-auto rounded-lg border border-slate-200 object-cover">
                    <label class="flex items-center gap-2 text-sm text-slate-600 mt-1">
                        <input type="checkbox" name="remove_thumbnail" value="1" class="rounded border-slate-300 text-red-600">
                        Hapus foto ini
                    </label>
                </div>
            @else
                <p class="text-slate-400 text-sm mb-3">Belum ada foto utama.</p>
            @endif
            <input type="file" name="thumbnail" accept="image/*"
                   class="w-full text-sm text-slate-600 file:mr-4 file:px-4 file:py-2.5 file:rounded-lg file:border-0 file:bg-slate-100 file:text-slate-700 file:font-semibold hover:file:bg-slate-200 border border-slate-200 rounded-lg">
            <p class="text-slate-400 text-xs mt-1 mb-6">JPG/PNG/WEBP, maks. 5MB.@if ($news->thumbnail) Memilih file baru akan mengganti foto saat ini.@endif</p>
            @error('thumbnail') <p class="text-red-500 text-xs -mt-4 mb-4">{{ $message }}</p> @enderror

            <div class="flex flex-wrap gap-3 pt-2 border-t border-slate-100">
                @if ($isPublished)
                    <button type="submit" name="action" value="save" class="mt-5 px-6 py-3 rounded-lg bg-sky-700 hover:bg-sky-800 text-white font-semibold text-sm">Simpan Perubahan</button>
                    <button type="submit" name="action" value="draft" class="mt-5 px-6 py-3 rounded-lg border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50"
                            onclick="return confirm('Berita akan ditarik dari website dan menjadi draft. Lanjutkan?')">Tarik ke Draft</button>
                @else
                    <button type="submit" name="action" value="publish" class="mt-5 px-6 py-3 rounded-lg bg-sky-700 hover:bg-sky-800 text-white font-semibold text-sm">Simpan &amp; Terbitkan</button>
                    <button type="submit" name="action" value="save" class="mt-5 px-6 py-3 rounded-lg border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50">Simpan {{ $isScheduled ? 'Perubahan' : 'sebagai Draft' }}</button>
                @endif
                <a href="{{ route('admin.news.index') }}" class="mt-5 px-6 py-3 rounded-lg text-slate-400 font-semibold text-sm hover:text-slate-600">Batal</a>
            </div>
        </form>
    </div>
</div>

@endsection
