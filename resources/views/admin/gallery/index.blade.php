@extends('layouts.admin')

@section('title', 'Manajemen Galeri')

@section('content')

<div x-data="{ showForm: {{ $errors->any() ? 'true' : 'false' }} }">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Manajemen Galeri</h1>
            <p class="text-slate-400 text-sm mt-1">Kelola foto dan video dokumentasi kegiatan sekolah</p>
        </div>
        <button @click="showForm = !showForm" class="px-5 py-2.5 rounded-lg bg-sky-700 text-white font-semibold text-sm hover:bg-sky-800">
            <span x-text="showForm ? 'Tutup Form' : '+ Tambah Dokumentasi'"></span>
        </button>
    </div>

    @if (session('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl px-5 py-4 text-sm">{{ session('success') }}</div>
    @endif

    {{-- Form tambah --}}
    <div x-show="showForm" x-cloak x-data="{ type: 'photo' }" class="bg-white rounded-xl border border-slate-100 shadow-sm p-6 mb-6">
        <h2 class="font-bold text-slate-900 flex items-center gap-2 mb-5">📷 Tambah Dokumentasi Baru</h2>

        <form method="POST" action="{{ route('admin.gallery.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="grid sm:grid-cols-2 gap-5 mb-5">
                <div>
                    <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">JUDUL</label>
                    <input type="text" name="title" value="{{ old('title') }}" placeholder="Judul dokumentasi..."
                           class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500">
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">KATEGORI</label>
                    <select name="category" class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500">
                        <option value="">-- Pilih --</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid sm:grid-cols-2 gap-5 mb-5">
                <div>
                    <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">TIPE</label>
                    <select name="type" x-model="type" class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500">
                        <option value="photo">Foto</option>
                        <option value="video">Video</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">TANGGAL KEGIATAN</label>
                    <input type="date" name="event_date" value="{{ old('event_date') }}"
                           class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>
            </div>

            <div x-show="type === 'video'" x-cloak class="mb-5">
                <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">LINK VIDEO (YouTube, dll)</label>
                <input type="url" name="video_url" value="{{ old('video_url') }}" placeholder="https://youtube.com/..."
                       class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500">
                @error('video_url') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">
                <span x-text="type === 'video' ? 'THUMBNAIL VIDEO' : 'FOTO'"></span>
            </label>
            <label class="block border-2 border-dashed border-slate-200 rounded-xl p-8 text-center cursor-pointer hover:border-sky-300 transition mb-5">
                <input type="file" name="file" accept="image/*" class="hidden"
                       x-on:change="$el.nextElementSibling.textContent = $el.files[0]?.name ?? 'Klik atau seret foto ke sini'">
                <div class="text-3xl mb-2">📷</div>
                <p class="text-slate-500 text-sm">Klik atau seret foto ke sini</p>
                <p class="text-slate-400 text-xs mt-1">JPG/PNG/WEBP, maks. 5MB</p>
            </label>
            @error('file') <p class="text-red-500 text-xs -mt-4 mb-4">{{ $message }}</p> @enderror

            <div class="flex gap-3">
                <button type="submit" class="px-6 py-3 rounded-lg bg-sky-700 hover:bg-sky-800 text-white font-semibold text-sm">
                    Simpan Dokumentasi
                </button>
                <button type="button" @click="showForm = false" class="px-6 py-3 rounded-lg text-slate-400 font-semibold text-sm">
                    Batal
                </button>
            </div>
        </form>
    </div>

    {{-- Grid galeri --}}
    <div class="bg-white rounded-xl border border-slate-100 shadow-sm">
        <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100">
            <h2 class="font-bold text-slate-900">Semua Dokumentasi</h2>
            <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-semibold">{{ $galleries->total() }} item</span>
        </div>

        @if ($galleries->isEmpty())
            <p class="px-6 py-10 text-center text-slate-400">Belum ada dokumentasi. Klik "+ Tambah Dokumentasi" untuk mulai menambahkan.</p>
        @else
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5 p-6">
                @foreach ($galleries as $item)
                    <div class="border border-slate-200 rounded-xl overflow-hidden" x-data="{ editing: false }">
                        <div class="relative aspect-video bg-slate-100">
                            @if ($item->file_path)
                                <img src="{{ asset('storage/'.$item->file_path) }}" alt="{{ $item->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-3xl text-slate-300">🖼️</div>
                            @endif
                            @if ($item->type === 'video')
                                <span class="absolute top-2 left-2 px-2 py-0.5 rounded-full bg-slate-900/70 text-white text-[10px] font-bold">▶ VIDEO</span>
                            @endif
                        </div>
                        <div class="p-4">
                            <div class="flex items-center gap-2 mb-1.5">
                                @if ($item->category)
                                    <span class="px-2 py-0.5 rounded-full bg-sky-50 text-sky-700 text-[11px] font-semibold">{{ $item->category }}</span>
                                @endif
                                @if ($item->event_date)
                                    <span class="text-slate-400 text-xs">{{ $item->event_date->translatedFormat('d M Y') }}</span>
                                @endif
                            </div>
                            <h3 class="font-semibold text-slate-900 text-sm mb-3 truncate">{{ $item->title }}</h3>
                            <div class="flex gap-2">
                                <button @click="editing = true" class="flex-1 px-3 py-1.5 rounded-lg bg-sky-50 text-sky-700 text-xs font-semibold hover:bg-sky-100">Edit</button>
                                <form method="POST" action="{{ route('admin.gallery.destroy', $item) }}" onsubmit="return confirm('Hapus dokumentasi ini?')" class="flex-1">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-full px-3 py-1.5 rounded-lg bg-red-50 text-red-600 text-xs font-semibold hover:bg-red-100">Hapus</button>
                                </form>
                            </div>
                        </div>

                        {{-- Edit modal --}}
                        <div x-show="editing" x-cloak @click.self="editing = false" class="fixed inset-0 z-50 bg-slate-900/60 flex items-center justify-center p-4">
                            <div class="bg-white rounded-2xl max-w-md w-full p-6">
                                <div class="flex justify-between items-start mb-4">
                                    <h3 class="text-lg font-bold text-sky-900">Edit Dokumentasi</h3>
                                    <button @click="editing = false" class="text-slate-400">✕</button>
                                </div>
                                <form method="POST" action="{{ route('admin.gallery.update', $item) }}" enctype="multipart/form-data" class="space-y-4">
                                    @csrf @method('PATCH')
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">JUDUL</label>
                                        <input type="text" name="title" value="{{ $item->title }}" class="w-full px-4 py-2.5 rounded-lg border border-slate-200">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">KATEGORI</label>
                                        <select name="category" class="w-full px-4 py-2.5 rounded-lg border border-slate-200">
                                            <option value="">-- Pilih --</option>
                                            @foreach ($categories as $cat)
                                                <option value="{{ $cat }}" {{ $item->category === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">TANGGAL KEGIATAN</label>
                                        <input type="date" name="event_date" value="{{ $item->event_date?->format('Y-m-d') }}" class="w-full px-4 py-2.5 rounded-lg border border-slate-200">
                                    </div>
                                    @if ($item->type === 'video')
                                        <div>
                                            <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">LINK VIDEO</label>
                                            <input type="url" name="video_url" value="{{ $item->video_url }}" class="w-full px-4 py-2.5 rounded-lg border border-slate-200">
                                        </div>
                                    @endif
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">GANTI FOTO (opsional)</label>
                                        <input type="file" name="file" accept="image/*" class="w-full text-sm">
                                    </div>
                                    <div class="flex gap-3 pt-2">
                                        <button type="submit" class="flex-1 py-2.5 rounded-lg bg-sky-700 hover:bg-sky-800 text-white font-semibold text-sm">Simpan Perubahan</button>
                                        <button type="button" @click="editing = false" class="px-5 py-2.5 rounded-lg border border-slate-200 text-slate-500 font-semibold text-sm">Batal</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($galleries->hasPages())
                <div class="px-6 pb-6">{{ $galleries->links() }}</div>
            @endif
        @endif
    </div>
</div>

@endsection
