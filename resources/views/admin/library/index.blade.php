@extends('layouts.admin')

@section('title', 'Manajemen E-Library')

@section('content')

<div x-data="{ showAdd: {{ $errors->has('no_induk_buku') && !request()->routeIs('*.update') ? 'true' : 'false' }} }">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Manajemen E-Library</h1>
            <p class="text-slate-400 text-sm mt-1">CRUD koleksi buku perpustakaan SMKN 1 Bakam</p>
        </div>
        <button @click="showAdd = true" class="px-5 py-2.5 rounded-lg bg-sky-700 text-white font-semibold text-sm hover:bg-sky-800">
            + Tambah Buku
        </button>
    </div>

    @if (session('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl px-5 py-4 text-sm">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-xl border border-slate-100 shadow-sm">
        <div class="px-6 py-5 border-b border-slate-100">
            <h2 class="font-bold text-slate-900">Koleksi Buku ({{ $books->total() }} judul)</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-slate-500 text-xs bg-sky-50">
                        <th class="px-6 py-3 font-semibold">No. Induk</th>
                        <th class="px-6 py-3 font-semibold">Judul</th>
                        <th class="px-6 py-3 font-semibold">Pengarang</th>
                        <th class="px-6 py-3 font-semibold">Penerbit</th>
                        <th class="px-6 py-3 font-semibold">Tahun</th>
                        <th class="px-6 py-3 font-semibold">Stok</th>
                        <th class="px-6 py-3 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($books as $book)
                        <tr x-data="{ editing: false, confirmDelete: false }">
                            <td class="px-6 py-4 text-slate-400">{{ $book->no_induk_buku }}</td>
                            <td class="px-6 py-4 font-semibold text-slate-900">{{ $book->judul }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $book->pengarang }}</td>
                            <td class="px-6 py-4 text-slate-500">{{ $book->penerbit }}</td>
                            <td class="px-6 py-4 text-slate-500">{{ $book->tahun_terbit }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $book->jumlah >= 4 ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                                    {{ $book->jumlah }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <button @click="editing = true" class="px-3 py-1.5 rounded-lg bg-sky-50 text-sky-700 text-xs font-semibold hover:bg-sky-100">Edit</button>
                                    <button @click="confirmDelete = true" class="px-3 py-1.5 rounded-lg bg-red-50 text-red-600 text-xs font-semibold hover:bg-red-100">Hapus</button>
                                </div>

                                {{-- Edit modal --}}
                                <div x-show="editing" x-cloak @click.self="editing = false" class="fixed inset-0 z-50 bg-slate-900/60 flex items-center justify-center p-4">
                                    <div class="bg-white rounded-2xl max-w-2xl w-full p-8 max-h-[90vh] overflow-y-auto">
                                        <h3 class="text-xl font-bold text-sky-700 mb-6">Edit Buku</h3>
                                        <form method="POST" action="{{ route('admin.library.update', $book) }}">
                                            @csrf @method('PATCH')
                                            <div class="grid sm:grid-cols-2 gap-5 mb-5">
                                                <div>
                                                    <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">NO. INDUK BUKU</label>
                                                    <input type="text" name="no_induk_buku" value="{{ $book->no_induk_buku }}" class="w-full px-4 py-3 rounded-lg border border-slate-200">
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">NO. INVENTARIS</label>
                                                    <input type="text" name="no_inventaris_buku" value="{{ $book->no_inventaris_buku }}" class="w-full px-4 py-3 rounded-lg border border-slate-200">
                                                </div>
                                            </div>
                                            <div class="mb-5">
                                                <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">JUDUL BUKU</label>
                                                <input type="text" name="judul" value="{{ $book->judul }}" class="w-full px-4 py-3 rounded-lg border border-slate-200">
                                            </div>
                                            <div class="mb-5">
                                                <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">PENGARANG</label>
                                                <input type="text" name="pengarang" value="{{ $book->pengarang }}" class="w-full px-4 py-3 rounded-lg border border-slate-200">
                                            </div>
                                            <div class="grid sm:grid-cols-2 gap-5 mb-5">
                                                <div>
                                                    <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">PENERBIT</label>
                                                    <input type="text" name="penerbit" value="{{ $book->penerbit }}" class="w-full px-4 py-3 rounded-lg border border-slate-200">
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">TAHUN TERBIT</label>
                                                    <input type="number" name="tahun_terbit" value="{{ $book->tahun_terbit }}" class="w-full px-4 py-3 rounded-lg border border-slate-200">
                                                </div>
                                            </div>
                                            <div class="grid sm:grid-cols-2 gap-5 mb-6">
                                                <div>
                                                    <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">KLASIFIKASI DDC</label>
                                                    <input type="text" name="klasifikasi" value="{{ $book->klasifikasi }}" class="w-full px-4 py-3 rounded-lg border border-slate-200">
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">JUMLAH (STOK)</label>
                                                    <input type="number" name="jumlah" value="{{ $book->jumlah }}" min="0" class="w-full px-4 py-3 rounded-lg border border-slate-200">
                                                </div>
                                            </div>
                                            <div class="flex justify-end gap-3">
                                                <button type="button" @click="editing = false" class="px-6 py-2.5 rounded-lg border border-slate-200 text-slate-500 font-semibold text-sm">Batal</button>
                                                <button type="submit" class="px-6 py-2.5 rounded-lg bg-sky-700 hover:bg-sky-800 text-white font-semibold text-sm">Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                {{-- Delete confirmation alert --}}
                                <div x-show="confirmDelete" x-cloak @click.self="confirmDelete = false" class="fixed inset-0 z-50 bg-slate-900/60 flex items-center justify-center p-4">
                                    <div class="bg-white rounded-2xl max-w-sm w-full p-6 text-center">
                                        <div class="w-14 h-14 rounded-full bg-red-50 text-red-500 flex items-center justify-center text-2xl mx-auto mb-4">⚠️</div>
                                        <h3 class="text-lg font-bold text-slate-900 mb-2">Hapus Buku Ini?</h3>
                                        <p class="text-slate-500 text-sm mb-6">
                                            "<strong>{{ $book->judul }}</strong>" akan dihapus secara permanen dari koleksi. Tindakan ini tidak dapat dibatalkan.
                                        </p>
                                        <div class="flex gap-3">
                                            <button @click="confirmDelete = false" class="flex-1 py-2.5 rounded-lg border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50">Batal</button>
                                            <form method="POST" action="{{ route('admin.library.destroy', $book) }}" class="flex-1">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="w-full py-2.5 rounded-lg bg-red-600 hover:bg-red-700 text-white font-semibold text-sm">Ya, Hapus</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-6 py-10 text-center text-slate-400">Belum ada koleksi buku.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($books->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">{{ $books->links() }}</div>
        @endif
    </div>

    {{-- Add modal --}}
    <div x-show="showAdd" x-cloak @click.self="showAdd = false" class="fixed inset-0 z-50 bg-slate-900/60 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-2xl w-full p-8 max-h-[90vh] overflow-y-auto">
            <h3 class="text-xl font-bold text-sky-700 mb-6">Tambah Buku Baru</h3>
            <form method="POST" action="{{ route('admin.library.store') }}">
                @csrf
                <div class="grid sm:grid-cols-2 gap-5 mb-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">NO. INDUK BUKU</label>
                        <input type="text" name="no_induk_buku" value="{{ old('no_induk_buku') }}" class="w-full px-4 py-3 rounded-lg border border-slate-200">
                        @error('no_induk_buku') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">NO. INVENTARIS</label>
                        <input type="text" name="no_inventaris_buku" value="{{ old('no_inventaris_buku') }}" class="w-full px-4 py-3 rounded-lg border border-slate-200">
                    </div>
                </div>
                <div class="mb-5">
                    <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">JUDUL BUKU</label>
                    <input type="text" name="judul" value="{{ old('judul') }}" class="w-full px-4 py-3 rounded-lg border border-slate-200">
                    @error('judul') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="mb-5">
                    <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">PENGARANG</label>
                    <input type="text" name="pengarang" value="{{ old('pengarang') }}" class="w-full px-4 py-3 rounded-lg border border-slate-200">
                </div>
                <div class="grid sm:grid-cols-2 gap-5 mb-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">PENERBIT</label>
                        <input type="text" name="penerbit" value="{{ old('penerbit') }}" class="w-full px-4 py-3 rounded-lg border border-slate-200">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">TAHUN TERBIT</label>
                        <input type="number" name="tahun_terbit" value="{{ old('tahun_terbit', now()->year) }}" class="w-full px-4 py-3 rounded-lg border border-slate-200">
                    </div>
                </div>
                <div class="grid sm:grid-cols-2 gap-5 mb-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">KLASIFIKASI DDC</label>
                        <input type="text" name="klasifikasi" value="{{ old('klasifikasi') }}" class="w-full px-4 py-3 rounded-lg border border-slate-200">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">JUMLAH (STOK)</label>
                        <input type="number" name="jumlah" value="{{ old('jumlah', 1) }}" min="0" class="w-full px-4 py-3 rounded-lg border border-slate-200">
                        @error('jumlah') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" @click="showAdd = false" class="px-6 py-2.5 rounded-lg border border-slate-200 text-slate-500 font-semibold text-sm">Batal</button>
                    <button type="submit" class="px-6 py-2.5 rounded-lg bg-sky-700 hover:bg-sky-800 text-white font-semibold text-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
