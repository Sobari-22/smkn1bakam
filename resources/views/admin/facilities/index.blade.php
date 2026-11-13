@extends('layouts.admin')

@section('title', 'Sarana & Prasarana')

@section('content')

<div x-data="{ showAdd: {{ $errors->getBag('new')->any() ? 'true' : 'false' }}, del: null }">

    <div class="flex flex-wrap items-start justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Sarana &amp; Prasarana</h1>
            <p class="text-slate-400 text-sm mt-1">
                Kelola fasilitas sekolah yang tampil di tab Sarana &amp; Prasarana pada halaman
                <a href="{{ route('profil') }}#sarana" target="_blank" class="text-sky-700 font-semibold hover:underline">Profil</a>.
            </p>
        </div>
        <button type="button" @click="showAdd = !showAdd"
                class="px-5 py-2.5 rounded-lg bg-sky-700 text-white font-semibold text-sm hover:bg-sky-800">
            <span x-text="showAdd ? 'Tutup Form' : '+ Tambah Fasilitas'"></span>
        </button>
    </div>

    @if (session('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl px-5 py-4 text-sm">{{ session('success') }}</div>
    @endif

    {{-- Form tambah --}}
    <div x-show="showAdd" x-cloak class="bg-white rounded-xl border border-slate-100 shadow-sm p-6 mb-8">
        <h2 class="font-bold text-slate-900 mb-5">Tambah Fasilitas Baru</h2>
        @php $bag = $errors->getBag('new'); $isNew = old('_form') === 'new'; @endphp
        <form method="POST" action="{{ route('admin.facilities.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <input type="hidden" name="_form" value="new">
            <div class="grid md:grid-cols-[120px_1fr_200px] gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">IKON</label>
                    <input type="text" name="icon" maxlength="16" value="{{ $isNew ? old('icon') : '' }}" placeholder="🏫"
                           class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500 text-center text-xl">
                    @if ($bag->has('icon')) <p class="text-red-500 text-xs mt-1">{{ $bag->first('icon') }}</p> @endif
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">NAMA FASILITAS</label>
                    <input type="text" name="name" value="{{ $isNew ? old('name') : '' }}" placeholder="Contoh: Laboratorium Komputer"
                           class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500">
                    @if ($bag->has('name')) <p class="text-red-500 text-xs mt-1">{{ $bag->first('name') }}</p> @endif
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">JUMLAH</label>
                    <input type="text" name="qty" value="{{ $isNew ? old('qty') : '' }}" placeholder="2 Lab"
                           class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500">
                    @if ($bag->has('qty')) <p class="text-red-500 text-xs mt-1">{{ $bag->first('qty') }}</p> @endif
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">DESKRIPSI</label>
                <textarea name="description" rows="3" maxlength="1000"
                          class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500">{{ $isNew ? old('description') : '' }}</textarea>
                @if ($bag->has('description')) <p class="text-red-500 text-xs mt-1">{{ $bag->first('description') }}</p> @endif
            </div>
            <div class="grid md:grid-cols-[1fr_160px] gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">FOTO (OPSIONAL)</label>
                    <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp"
                           class="w-full text-sm text-slate-600 file:mr-4 file:px-4 file:py-2.5 file:rounded-lg file:border-0 file:bg-slate-100 file:text-slate-700 file:font-semibold hover:file:bg-slate-200 border border-slate-200 rounded-lg">
                    <p class="text-slate-400 text-xs mt-1">JPG, PNG, atau WEBP, maksimal 2 MB.</p>
                    @if ($bag->has('image')) <p class="text-red-500 text-xs mt-1">{{ $bag->first('image') }}</p> @endif
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">URUTAN</label>
                    <input type="number" name="order" min="0" max="9999" value="{{ $isNew ? old('order') : '' }}" placeholder="Otomatis"
                           class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500">
                    @if ($bag->has('order')) <p class="text-red-500 text-xs mt-1">{{ $bag->first('order') }}</p> @endif
                </div>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2.5 rounded-lg bg-sky-700 text-white font-semibold text-sm hover:bg-sky-800">Simpan Fasilitas</button>
            </div>
        </form>
    </div>

    {{-- Daftar fasilitas --}}
    @if ($facilities->isEmpty())
        <div class="bg-white rounded-xl border border-slate-100 p-10 text-center text-slate-400">
            Belum ada fasilitas. Klik "+ Tambah Fasilitas" untuk menambahkan.
        </div>
    @else
        <div class="grid lg:grid-cols-2 gap-5">
            @foreach ($facilities as $item)
                @php
                    $formKey = 'facility-'.$item->id;
                    $fbag = $errors->getBag($formKey);
                    $mine = old('_form') === $formKey;
                    $o = fn ($field, $default) => $mine ? old($field, $default) : $default;
                @endphp

                <div id="fasilitas-{{ $item->id }}" x-data="{ editing: {{ $fbag->any() ? 'true' : 'false' }} }"
                     class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">

                    {{-- Tampilan biasa --}}
                    <div x-show="!editing">
                        @if ($item->image_url)
                            <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="w-full h-40 object-cover" loading="lazy">
                        @endif
                        <div class="p-5">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="font-semibold text-slate-900">
                                        @if ($item->icon) <span class="mr-1">{{ $item->icon }}</span> @endif{{ $item->name }}
                                    </p>
                                    @if ($item->qty) <p class="text-xs text-sky-700 font-medium mt-0.5">{{ $item->qty }}</p> @endif
                                </div>
                                <span class="shrink-0 text-[11px] text-slate-400 bg-slate-100 rounded-full px-2 py-1">Urutan {{ $item->order }}</span>
                            </div>
                            @if ($item->description)
                                <p class="text-sm text-slate-500 mt-3">{{ $item->description }}</p>
                            @endif
                            <div class="flex gap-2 mt-5">
                                <button type="button" @click="editing = true"
                                        class="px-4 py-2 rounded-lg border border-slate-200 text-slate-700 text-sm font-semibold hover:bg-slate-50">Edit</button>
                                <button type="button" @click="del = { id: {{ $item->id }}, name: @js($item->name) }"
                                        class="px-4 py-2 rounded-lg border-2 border-red-200 text-red-600 text-sm font-semibold hover:bg-red-50">Hapus</button>
                            </div>
                        </div>
                    </div>

                    {{-- Form edit --}}
                    <form x-show="editing" x-cloak method="POST" action="{{ route('admin.facilities.update', $item) }}" enctype="multipart/form-data" class="p-5 space-y-4">
                        @csrf @method('PUT')
                        <input type="hidden" name="_form" value="{{ $formKey }}">

                        <div class="grid grid-cols-[90px_1fr] gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">IKON</label>
                                <input type="text" name="icon" maxlength="16" value="{{ $o('icon', $item->icon) }}"
                                       class="w-full px-3 py-2.5 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500 text-center text-xl">
                                @if ($fbag->has('icon')) <p class="text-red-500 text-xs mt-1">{{ $fbag->first('icon') }}</p> @endif
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">NAMA FASILITAS</label>
                                <input type="text" name="name" value="{{ $o('name', $item->name) }}"
                                       class="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500">
                                @if ($fbag->has('name')) <p class="text-red-500 text-xs mt-1">{{ $fbag->first('name') }}</p> @endif
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">JUMLAH</label>
                                <input type="text" name="qty" value="{{ $o('qty', $item->qty) }}"
                                       class="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500">
                                @if ($fbag->has('qty')) <p class="text-red-500 text-xs mt-1">{{ $fbag->first('qty') }}</p> @endif
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">URUTAN</label>
                                <input type="number" name="order" min="0" max="9999" value="{{ $o('order', $item->order) }}"
                                       class="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500">
                                @if ($fbag->has('order')) <p class="text-red-500 text-xs mt-1">{{ $fbag->first('order') }}</p> @endif
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">DESKRIPSI</label>
                            <textarea name="description" rows="3" maxlength="1000"
                                      class="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500">{{ $o('description', $item->description) }}</textarea>
                            @if ($fbag->has('description')) <p class="text-red-500 text-xs mt-1">{{ $fbag->first('description') }}</p> @endif
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">FOTO</label>
                            @if ($item->image_url)
                                <div class="mb-3 flex items-start gap-4">
                                    <img src="{{ $item->image_url }}" alt="Foto saat ini" class="h-24 w-auto rounded-lg border border-slate-200 object-cover">
                                    <label class="flex items-center gap-2 text-sm text-slate-600 mt-1">
                                        <input type="checkbox" name="remove_image" value="1" class="rounded border-slate-300 text-red-600">
                                        Hapus foto ini
                                    </label>
                                </div>
                            @endif
                            <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp"
                                   class="w-full text-sm text-slate-600 file:mr-4 file:px-4 file:py-2 file:rounded-lg file:border-0 file:bg-slate-100 file:text-slate-700 file:font-semibold hover:file:bg-slate-200 border border-slate-200 rounded-lg">
                            <p class="text-slate-400 text-xs mt-1">JPG, PNG, atau WEBP, maksimal 2 MB.@if ($item->image_url) Memilih file baru akan mengganti foto saat ini.@endif</p>
                            @if ($fbag->has('image')) <p class="text-red-500 text-xs mt-1">{{ $fbag->first('image') }}</p> @endif
                        </div>

                        <div class="flex justify-end gap-3">
                            <button type="button" @click="editing = false"
                                    class="px-5 py-2.5 rounded-lg border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50">Batal</button>
                            <button type="submit" class="px-5 py-2.5 rounded-lg bg-sky-700 text-white text-sm font-semibold hover:bg-sky-800">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Modal konfirmasi hapus --}}
    <div x-show="del" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4"
         @click.self="del = null" @keydown.escape.window="del = null">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6">
            <h3 class="text-lg font-bold text-slate-900 mb-2">Hapus fasilitas?</h3>
            <p class="text-slate-500 text-sm mb-6">
                "<span class="font-semibold text-slate-700" x-text="del ? del.name : ''"></span>" beserta fotonya akan dihapus permanen
                dan tidak tampil lagi di halaman Profil.
            </p>
            <form method="POST" :action="del ? '{{ url('admin/sarana') }}/' + del.id : '#'" class="flex justify-end gap-3">
                @csrf @method('DELETE')
                <button type="button" @click="del = null" class="px-5 py-2.5 rounded-lg border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50">Batal</button>
                <button type="submit" class="px-5 py-2.5 rounded-lg bg-red-600 text-white font-semibold text-sm hover:bg-red-700">Ya, Hapus</button>
            </form>
        </div>
    </div>
</div>

@endsection
