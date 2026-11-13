@extends('layouts.admin')

@section('title', 'Guru & Staf')

@section('content')

<div x-data="{ showForm: {{ $errors->getBag('new')->any() ? 'true' : 'false' }}, showImport: {{ $errors->has('file') ? 'true' : 'false' }} }">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Data Guru & Staf</h1>
            <p class="text-slate-400 text-sm mt-1">Kelola profil {{ $teachers->total() }} tenaga pendidik SMKN 1 Bakam</p>
        </div>
        <div class="flex gap-3">
            <button type="button" @click="showImport = !showImport; if (showImport) showForm = false"
                    class="px-5 py-2.5 rounded-lg border border-slate-200 text-slate-700 font-semibold text-sm hover:bg-slate-50">
                <span x-text="showImport ? 'Tutup Import' : 'Import Excel'"></span>
            </button>
            <button @click="showForm = !showForm; if (showForm) showImport = false" class="px-5 py-2.5 rounded-lg bg-sky-700 text-white font-semibold text-sm hover:bg-sky-800">
                <span x-text="showForm ? 'Tutup Form' : '+ Tambah Guru'"></span>
            </button>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl px-5 py-4 text-sm">{{ session('success') }}</div>
    @endif

    @if (session('import_issues'))
        <div class="mb-6 bg-amber-50 border border-amber-200 text-amber-800 rounded-xl px-5 py-4 text-sm">
            <p class="font-semibold mb-2">Catatan import:</p>
            <ul class="list-disc pl-5 space-y-1">
                @foreach (session('import_issues') as $issue)
                    <li>{{ $issue }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Panel import Excel --}}
    <div x-show="showImport" x-cloak class="bg-white rounded-xl border border-slate-100 shadow-sm p-6 mb-6">
        <h2 class="font-bold text-slate-900 flex items-center gap-2 mb-2">📥 Import Data Guru/Staf dari Excel</h2>
        <p class="text-slate-500 text-sm mb-2">
            Gunakan <a href="{{ route('admin.teachers.template') }}" class="text-sky-700 font-semibold hover:underline">template Excel</a>
            agar kolomnya sesuai. Baris dengan NIP yang sudah terdaftar akan memperbarui data lama; baris lain ditambahkan sebagai data baru.
        </p>
        <ul class="text-slate-400 text-xs mb-5 list-disc pl-5 space-y-1">
            <li><strong>Jabatan</strong>: tulis seperti di form (mis. <em>Wakasek kesiswaan; Pembina harian OSIS</em>), pisahkan dengan titik koma. Wali kelas: <em>Wali kelas TKJ (XI TKJ 1)</em>.</li>
            <li><strong>Mata Pelajaran</strong>: pisahkan dengan titik koma. <strong>Mengajar di</strong>: <em>TKJ; TKR</em> atau <em>Semua program</em>.</li>
            <li>Foto diunggah terpisah lewat tombol edit.</li>
        </ul>

        <form method="POST" action="{{ route('admin.teachers.import') }}" enctype="multipart/form-data" class="flex flex-col sm:flex-row sm:items-start gap-4">
            @csrf
            <div class="flex-1">
                <input type="file" name="file" accept=".xlsx,.csv"
                       class="w-full text-sm text-slate-600 file:mr-4 file:px-4 file:py-2.5 file:rounded-lg file:border-0 file:bg-slate-100 file:text-slate-700 file:font-semibold hover:file:bg-slate-200 border border-slate-200 rounded-lg">
                @error('file') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                <p class="text-slate-400 text-xs mt-2">Format .xlsx atau .csv, maksimal 2 MB / 1000 baris.</p>
            </div>
            <button type="submit" class="px-6 py-2.5 rounded-lg bg-sky-700 text-white font-semibold text-sm hover:bg-sky-800">Import Sekarang</button>
        </form>
    </div>

    {{-- Form tambah --}}
    <div x-show="showForm" x-cloak class="bg-white rounded-xl border border-slate-100 shadow-sm p-6 mb-6">
        <h2 class="font-bold text-slate-900 flex items-center gap-2 mb-5">👤 Tambah Guru/Staf Baru</h2>

        <form method="POST" action="{{ route('admin.teachers.store') }}" enctype="multipart/form-data">
            @csrf
            @include('admin.teachers._fields', ['teacher' => null, 'bagName' => 'new'])

            <div class="flex gap-3 mt-6">
                <button type="submit" class="px-6 py-3 rounded-lg bg-sky-700 hover:bg-sky-800 text-white font-semibold text-sm">
                    Simpan Data
                </button>
                <button type="button" @click="showForm = false" class="px-6 py-3 rounded-lg text-slate-400 font-semibold text-sm">
                    Batal
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl border border-slate-100 shadow-sm">
        <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100">
            <h2 class="font-bold text-slate-900">Daftar Guru & Staf</h2>
            <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-semibold">{{ $teachers->total() }} orang</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-slate-400 text-xs">
                        <th class="px-6 py-3 font-semibold">NAMA & JABATAN</th>
                        <th class="px-6 py-3 font-semibold">MATA PELAJARAN</th>
                        <th class="px-6 py-3 font-semibold">MENGAJAR DI</th>
                        <th class="px-6 py-3 font-semibold">NIP</th>
                        <th class="px-6 py-3 font-semibold">TMT</th>
                        <th class="px-6 py-3 font-semibold">STATUS</th>
                        <th class="px-6 py-3 font-semibold">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($teachers as $t)
                        @php $editBag = 'teacher-'.$t->id; @endphp
                        <tr x-data="{ editing: {{ $errors->getBag($editBag)->any() ? 'true' : 'false' }} }">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if ($t->photo)
                                        <img src="{{ asset('storage/'.$t->photo) }}" class="w-10 h-10 rounded-full object-cover" alt="">
                                    @else
                                        <span class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400">👤</span>
                                    @endif
                                    <div>
                                        <p class="font-bold text-slate-900">{{ $t->name }}</p>
                                        @if ($t->positions->isNotEmpty())
                                            <div class="flex flex-wrap gap-1 mt-1">
                                                @foreach ($t->positions as $pos)
                                                    <span class="px-2 py-0.5 rounded-full bg-sky-50 text-sky-700 text-[11px] font-semibold">{{ $pos->label }}</span>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="text-slate-400 text-xs">Guru</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-600">
                                @if (! $t->teaches)
                                    <span class="text-slate-400 text-xs">Tidak mengajar</span>
                                @else
                                    {{ $t->subject_list ? implode(', ', $t->subject_list) : '—' }}
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-600">{{ $t->teaching_label ?? '—' }}</td>
                            <td class="px-6 py-4 text-slate-500 font-mono text-xs">{{ $t->nip ?? '—' }}</td>
                            <td class="px-6 py-4 text-slate-500 text-xs">{{ $t->tmt?->locale('id')->translatedFormat('d M Y') ?? '—' }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $t->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                    {{ $t->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <button @click="editing = true" class="px-3 py-1.5 rounded-lg bg-sky-50 text-sky-700 text-xs font-semibold hover:bg-sky-100">Edit</button>
                                    <form method="POST" action="{{ route('admin.teachers.destroy', $t) }}" onsubmit="return confirm('Hapus data {{ addslashes($t->name) }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 rounded-lg bg-red-50 text-red-600 text-xs font-semibold hover:bg-red-100">Hapus</button>
                                    </form>
                                </div>

                                {{-- Edit modal --}}
                                <div x-show="editing" x-cloak @click.self="editing = false" class="fixed inset-0 z-50 bg-slate-900/60 flex items-center justify-center p-4">
                                    <div class="bg-white rounded-2xl max-w-3xl w-full p-6 max-h-[90vh] overflow-y-auto">
                                        <div class="flex justify-between items-start mb-5">
                                            <h3 class="text-lg font-bold text-sky-900">Edit Data Guru/Staf</h3>
                                            <button type="button" @click="editing = false" class="text-slate-400">✕</button>
                                        </div>
                                        <form method="POST" action="{{ route('admin.teachers.update', $t) }}" enctype="multipart/form-data">
                                            @csrf @method('PATCH')
                                            @include('admin.teachers._fields', ['teacher' => $t, 'bagName' => $editBag])

                                            <div class="flex gap-3 pt-5">
                                                <button type="submit" class="flex-1 py-2.5 rounded-lg bg-sky-700 hover:bg-sky-800 text-white font-semibold text-sm">Simpan Perubahan</button>
                                                <button type="button" @click="editing = false" class="px-5 py-2.5 rounded-lg border border-slate-200 text-slate-500 font-semibold text-sm">Batal</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-6 py-10 text-center text-slate-400">Belum ada data guru & staf. Klik "+ Tambah Guru" untuk mulai menambahkan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($teachers->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">{{ $teachers->links() }}</div>
        @endif
    </div>
</div>

@endsection
