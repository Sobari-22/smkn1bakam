@extends('layouts.admin')

@section('title', 'Profil Sekolah')

@section('content')

<div x-data="{ del: null }">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900">Profil Sekolah</h1>
        <p class="text-slate-400 text-sm mt-1">
            Kelola Sejarah, Visi &amp; Misi, dan Sambutan Kepala Sekolah yang tampil di halaman
            <a href="{{ route('profil') }}" target="_blank" class="text-sky-700 font-semibold hover:underline">Profil</a>.
            Hanya Superadmin yang dapat mengubah bagian ini.
        </p>
    </div>

    @if (session('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl px-5 py-4 text-sm">{{ session('success') }}</div>
    @endif

    <div class="space-y-8">
        @foreach ($forms as $slug => $form)
            @php
                // old() dipakai hanya untuk form yang baru saja dikirim (field bernama sama dipakai di beberapa form)
                $o = fn ($field, $default) => old('_section') === $slug ? old($field, $default) : $default;
                $page = $form['page'];
                $imageUrl = $page?->image_url;
            @endphp

            <section id="{{ $slug }}" class="bg-white rounded-xl border border-slate-100 shadow-sm p-6 scroll-mt-6">

                <div class="flex flex-wrap items-start justify-between gap-3 mb-5">
                    <div>
                        <h2 class="font-bold text-slate-900 text-lg">{{ $form['label'] }}</h2>
                        @if ($form['exists'])
                            <p class="text-xs text-emerald-600 font-semibold mt-1">● Tampil dari data yang Anda simpan</p>
                        @else
                            <p class="text-xs text-amber-600 font-semibold mt-1">● Belum disimpan, halaman publik memakai teks bawaan</p>
                        @endif
                    </div>
                    @if ($form['exists'])
                        <button type="button" @click="del = { slug: '{{ $slug }}', label: '{{ $form['label'] }}' }"
                                class="px-4 py-2 rounded-lg border-2 border-red-200 text-red-600 text-sm font-semibold hover:bg-red-50">
                            Hapus
                        </button>
                    @endif
                </div>

                <form method="POST" action="{{ route('admin.school-profile.update', $slug) }}" enctype="multipart/form-data" class="space-y-5">
                    @csrf @method('PUT')
                    <input type="hidden" name="_section" value="{{ $slug }}">

                    @if ($slug === 'visi-misi')
                        <div>
                            <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">VISI SEKOLAH</label>
                            <textarea name="excerpt" rows="3" maxlength="255"
                                      class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500">{{ $o('excerpt', $form['excerpt']) }}</textarea>
                            <p class="text-slate-400 text-xs mt-1">Maksimal 255 karakter.</p>
                            @error('excerpt', $slug) <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">MISI SEKOLAH</label>
                            <textarea name="content" rows="8"
                                      class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500">{{ $o('content', $form['content']) }}</textarea>
                            <p class="text-slate-400 text-xs mt-1">Satu misi per baris. Kosongkan untuk memakai misi bawaan.</p>
                            @error('content', $slug) <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    @else
                        <div>
                            <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">
                                {{ $slug === 'sambutan-kepala' ? 'NAMA KEPALA SEKOLAH' : 'JUDUL' }}
                            </label>
                            <input type="text" name="title" value="{{ $o('title', $form['title']) }}"
                                   placeholder="{{ $slug === 'sambutan-kepala' ? 'Nama lengkap beserta gelar' : '' }}"
                                   class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500">
                            @error('title', $slug) <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">
                                {{ $slug === 'sambutan-kepala' ? 'ISI SAMBUTAN' : 'ISI SEJARAH' }}
                            </label>
                            <textarea name="content" rows="12"
                                      class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500">{{ $o('content', $form['content']) }}</textarea>
                            <p class="text-slate-400 text-xs mt-1">Pisahkan paragraf dengan satu baris kosong.</p>
                            @error('content', $slug) <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    @endif

                    {{-- Gambar --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">
                            {{ $slug === 'sambutan-kepala' ? 'FOTO KEPALA SEKOLAH' : 'GAMBAR' }}
                            @if ($slug === 'visi-misi') (OPSIONAL) @endif
                        </label>

                        @if ($imageUrl)
                            <div class="mb-3 flex items-start gap-4">
                                <img src="{{ $imageUrl }}" alt="Gambar saat ini" class="h-32 w-auto rounded-lg border border-slate-200 object-cover">
                                <label class="flex items-center gap-2 text-sm text-slate-600 mt-1">
                                    <input type="checkbox" name="remove_image" value="1" class="rounded border-slate-300 text-red-600">
                                    Hapus gambar ini
                                </label>
                            </div>
                        @endif

                        <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp"
                               class="w-full text-sm text-slate-600 file:mr-4 file:px-4 file:py-2.5 file:rounded-lg file:border-0 file:bg-slate-100 file:text-slate-700 file:font-semibold hover:file:bg-slate-200 border border-slate-200 rounded-lg">
                        <p class="text-slate-400 text-xs mt-1">
                            JPG, PNG, atau WEBP, maksimal 2 MB.
                            @if ($imageUrl) Memilih file baru akan mengganti gambar saat ini. @endif
                        </p>
                        @error('image', $slug) <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-2.5 rounded-lg bg-sky-700 text-white font-semibold text-sm hover:bg-sky-800">Simpan {{ $form['label'] }}</button>
                    </div>
                </form>
            </section>
        @endforeach
    </div>

    {{-- Modal konfirmasi hapus --}}
    <div x-show="del" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4"
         @keydown.escape.window="del = null">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6" @click.outside="del = null">
            <h3 class="text-lg font-bold text-slate-900 mb-2">Hapus <span x-text="del ? del.label : ''"></span>?</h3>
            <p class="text-slate-500 text-sm mb-6">
                Data dan gambarnya akan dihapus permanen. Halaman Profil di website akan kembali menampilkan teks bawaan.
            </p>
            <form method="POST" :action="del ? '{{ url('admin/profil-sekolah') }}/' + del.slug : '#'" class="flex justify-end gap-3">
                @csrf @method('DELETE')
                <button type="button" @click="del = null" class="px-5 py-2.5 rounded-lg border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50">Batal</button>
                <button type="submit" class="px-5 py-2.5 rounded-lg bg-red-600 text-white font-semibold text-sm hover:bg-red-700">Ya, Hapus</button>
            </form>
        </div>
    </div>
</div>

@endsection
