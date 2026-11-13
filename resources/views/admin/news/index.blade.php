@extends('layouts.admin')

@section('title', 'Manajemen Berita')

@section('content')

<div x-data="{ showForm: {{ $errors->any() ? 'true' : 'false' }} }">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Manajemen Berita</h1>
            <p class="text-slate-400 text-sm mt-1">Kelola konten berita dan pengumuman sekolah</p>
        </div>
        <button @click="showForm = !showForm" class="px-5 py-2.5 rounded-lg bg-sky-700 text-white font-semibold text-sm hover:bg-sky-800">
            <span x-text="showForm ? 'Tutup Form' : '+ Tulis Berita Baru'"></span>
        </button>
    </div>

    @if (session('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl px-5 py-4 text-sm">{{ session('success') }}</div>
    @endif

    {{-- Form tulis berita --}}
    <div x-show="showForm" x-cloak class="bg-white rounded-xl border border-slate-100 shadow-sm p-6 mb-6">
        <h2 class="font-bold text-slate-900 flex items-center gap-2 mb-5">✏️ Tulis Berita Baru</h2>

        <form method="POST" action="{{ route('admin.news.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="grid sm:grid-cols-2 gap-5 mb-5">
                <div>
                    <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">JUDUL BERITA</label>
                    <input type="text" name="title" value="{{ old('title') }}" placeholder="Judul berita yang menarik..."
                           class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500">
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">KATEGORI</label>
                    <select name="category" class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500">
                        <option value="">-- Pilih --</option>
                        @foreach (['Prestasi', 'PPDB', 'Kegiatan', 'Pengumuman'] as $cat)
                            <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                    @error('category') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">ISI BERITA</label>
            <textarea name="content" rows="10" placeholder="Tulis konten berita di sini. Pisahkan paragraf dengan satu baris kosong."
                      class="w-full px-4 py-3 rounded-lg border border-slate-200 mb-5 focus:outline-none focus:ring-2 focus:ring-sky-500">{{ old('content') }}</textarea>
            @error('content') <p class="text-red-500 text-xs -mt-4 mb-4">{{ $message }}</p> @enderror

            <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">FOTO UTAMA</label>
            <label class="block border-2 border-dashed border-slate-200 rounded-xl p-10 text-center cursor-pointer hover:border-sky-300 transition mb-5">
                <input type="file" name="thumbnail" accept="image/*" class="hidden"
                       x-on:change="$el.nextElementSibling.textContent = $el.files[0]?.name ?? 'Klik atau seret foto ke sini'">
                <div class="text-3xl mb-2">📷</div>
                <p class="text-slate-500 text-sm">Klik atau seret foto ke sini</p>
                <p class="text-slate-400 text-xs mt-1">JPG/PNG/WEBP, maks. 5MB</p>
            </label>

            <div class="flex gap-3">
                <button type="submit" name="action" value="publish" class="px-6 py-3 rounded-lg bg-sky-700 hover:bg-sky-800 text-white font-semibold text-sm">
                    Terbitkan Berita
                </button>
                <button type="submit" name="action" value="draft" class="px-6 py-3 rounded-lg border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50">
                    Simpan Draft
                </button>
                <button type="button" @click="showForm = false" class="px-6 py-3 rounded-lg text-slate-400 font-semibold text-sm">
                    Batal
                </button>
            </div>
        </form>
    </div>

    {{-- Daftar berita --}}
    <div class="bg-white rounded-xl border border-slate-100 shadow-sm">
        <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100">
            <h2 class="font-bold text-slate-900">Semua Berita</h2>
            <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-semibold">{{ $news->total() }} artikel</span>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse ($news as $item)
                <div class="flex items-center gap-4 px-6 py-4">
                    <img src="{{ $item->thumbnail ? asset('storage/'.$item->thumbnail) : '/images/news-placeholder.jpg' }}"
                         alt="{{ $item->title }}" class="w-16 h-16 rounded-lg object-cover shrink-0">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="px-2 py-0.5 rounded-full bg-sky-50 text-sky-700 text-xs font-semibold">{{ $item->category ?? '-' }}</span>
                            <span class="text-slate-400 text-xs">
                                {{ $item->published_at ? $item->published_at->translatedFormat('d F Y') : 'Draft' }}
                            </span>
                        </div>
                        <h3 class="font-bold text-slate-900 truncate">{{ $item->title }}</h3>
                        <p class="text-slate-400 text-sm">{{ $item->author->name ?? 'Admin' }}</p>
                    </div>
                    <div class="flex gap-2 shrink-0">
                        @if ($item->published_at && $item->published_at->lte(now()))
                            <a href="{{ route('berita.show', $item) }}" target="_blank"
                               class="px-4 py-2 rounded-lg bg-slate-100 text-slate-600 text-xs font-semibold hover:bg-slate-200">Lihat</a>
                        @endif
                        <a href="{{ route('admin.news.edit', $item) }}" class="px-4 py-2 rounded-lg bg-sky-50 text-sky-700 text-xs font-semibold hover:bg-sky-100">Edit</a>
                        <form method="POST" action="{{ route('admin.news.destroy', $item) }}" onsubmit="return confirm('Hapus berita ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="px-4 py-2 rounded-lg bg-red-50 text-red-600 text-xs font-semibold hover:bg-red-100">Hapus</button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="px-6 py-10 text-center text-slate-400">Belum ada berita.</p>
            @endforelse
        </div>
        @if ($news->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">{{ $news->links() }}</div>
        @endif
    </div>
</div>

@endsection
