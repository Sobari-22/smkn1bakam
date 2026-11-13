@extends('layouts.admin')

@section('title', 'Manajemen SPMB')

@section('content')

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Manajemen SPMB</h1>
        <p class="text-slate-400 text-sm mt-1">Sistem Penerimaan Murid Baru {{ now()->year }}/{{ now()->year + 1 }}</p>
    </div>
    <div class="flex gap-3">
        @php
            $exportParams = array_filter(request()->only('status'));
            $exportFilter = ['verified' => 'Diterima', 'pending' => 'Dalam Verifikasi', 'rejected' => 'Ditolak'][request('status')] ?? null;
        @endphp
        <div class="relative" x-data="{ open: false }" @click.outside="open = false" @keydown.escape.window="open = false">
            <button type="button" @click="open = !open"
                    class="px-5 py-2.5 rounded-lg border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50">
                ↓ Ekspor Data ▾
            </button>
            <div x-show="open" x-cloak class="absolute right-0 mt-2 w-64 bg-white border border-slate-200 rounded-xl shadow-lg z-20 overflow-hidden">
                <p class="px-4 pt-3 pb-2 text-xs text-slate-400 border-b border-slate-100">
                    {{ $exportFilter ? 'Hanya status: '.$exportFilter : 'Semua pendaftar' }}
                </p>
                <a href="{{ route('admin.ppdb.export.xlsx', $exportParams) }}" class="block px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50">📗 Excel (.xlsx)</a>
                <a href="{{ route('admin.ppdb.export.pdf', $exportParams) }}" class="block px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50">📕 PDF</a>
                <a href="{{ route('admin.ppdb.export', $exportParams) }}" class="block px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50">📄 CSV</a>
            </div>
        </div>
        <a href="{{ route('ppdb.create') }}" target="_blank" class="px-5 py-2.5 rounded-lg bg-sky-700 text-white font-semibold text-sm hover:bg-sky-800">
            + Tambah Manual
        </a>
    </div>
</div>

@if (session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl px-5 py-4 text-sm">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 rounded-xl px-5 py-4 text-sm">{{ session('error') }}</div>
@endif

{{-- ============ STATUS PENDAFTARAN (buka / tutup) ============ --}}
<div class="bg-white rounded-xl border border-slate-100 shadow-sm p-6 mb-6"
     x-data="{ status: '{{ old('ppdb_status', $ppdbOpen ? 'open' : 'closed') }}' }">
    <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
        <div>
            <h2 class="font-bold text-slate-900">Status Pendaftaran</h2>
            <p class="text-slate-400 text-sm mt-1">Saat ditutup, calon siswa tidak bisa mengisi formulir dan akan melihat tanggal pembukaan.</p>
        </div>
        @if ($ppdbOpen)
            <span class="px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold">● SEDANG DIBUKA</span>
        @else
            <span class="px-3 py-1.5 rounded-full bg-red-50 text-red-600 text-xs font-bold">● SEDANG DITUTUP</span>
        @endif
    </div>

    <form method="POST" action="{{ route('admin.ppdb.registration') }}" class="flex flex-col md:flex-row md:items-end gap-4">
        @csrf @method('PATCH')

        <div>
            <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">STATUS</label>
            <div class="inline-flex rounded-lg border border-slate-200 overflow-hidden">
                <label class="cursor-pointer px-5 py-2.5 text-sm font-semibold transition"
                       :class="status === 'open' ? 'bg-emerald-600 text-white' : 'bg-white text-slate-500 hover:bg-slate-50'">
                    <input type="radio" name="ppdb_status" value="open" x-model="status" class="sr-only"> Dibuka
                </label>
                <label class="cursor-pointer px-5 py-2.5 text-sm font-semibold transition border-l border-slate-200"
                       :class="status === 'closed' ? 'bg-red-600 text-white' : 'bg-white text-slate-500 hover:bg-slate-50'">
                    <input type="radio" name="ppdb_status" value="closed" x-model="status" class="sr-only"> Ditutup
                </label>
            </div>
        </div>

        <div x-show="status === 'closed'" class="flex-1 max-w-xs">
            <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">TANGGAL PENDAFTARAN DIBUKA</label>
            <input type="date" name="ppdb_open_date" value="{{ old('ppdb_open_date', $ppdbOpenDate) }}"
                   :required="status === 'closed'"
                   class="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500">
        </div>

        <button type="submit" class="px-6 py-2.5 rounded-lg bg-sky-700 hover:bg-sky-800 text-white font-semibold text-sm">
            Simpan Status
        </button>
    </form>
    @error('ppdb_status') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
    @error('ppdb_open_date') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
</div>

{{-- ============ JADWAL SPMB ============ --}}
<div class="bg-white rounded-xl border border-slate-100 shadow-sm p-6 mb-6"
     x-data="{ rows: @js(array_values(old('schedule', $schedule))) }">
    <div class="mb-5">
        <h2 class="font-bold text-slate-900">Jadwal SPMB</h2>
        <p class="text-slate-400 text-sm mt-1">Tampil di halaman SPMB untuk calon siswa. Tanggal boleh berupa teks bebas, misalnya "1–25 Agustus 2026".</p>
    </div>

    <form method="POST" action="{{ route('admin.ppdb.schedule') }}">
        @csrf @method('PATCH')

        <div class="space-y-3">
            <template x-for="(row, i) in rows" :key="i">
                <div class="flex flex-col sm:flex-row gap-3">
                    <input type="text" :name="`schedule[${i}][label]`" x-model="row.label" required maxlength="60"
                           placeholder="Kegiatan, mis. Pendaftaran"
                           class="sm:w-1/3 px-4 py-2.5 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500">
                    <input type="text" :name="`schedule[${i}][value]`" x-model="row.value" required maxlength="80"
                           placeholder="Tanggal, mis. 1–25 Agustus 2026"
                           class="flex-1 px-4 py-2.5 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500">
                    <button type="button" @click="rows.splice(i, 1)" :disabled="rows.length <= 1"
                            class="px-4 py-2.5 rounded-lg border border-slate-200 text-red-500 text-sm font-semibold hover:bg-red-50 disabled:opacity-40 disabled:cursor-not-allowed">
                        Hapus
                    </button>
                </div>
            </template>
        </div>

        <div class="flex flex-wrap gap-3 mt-5">
            <button type="button" @click="rows.length < 12 && rows.push({ label: '', value: '' })"
                    class="px-5 py-2.5 rounded-lg border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50">
                + Tambah Jadwal
            </button>
            <button type="submit" class="px-6 py-2.5 rounded-lg bg-sky-700 hover:bg-sky-800 text-white font-semibold text-sm">
                Simpan Jadwal
            </button>
        </div>

        @php
            $scheduleErrors = collect($errors->getMessages())
                ->filter(fn ($v, $k) => str_starts_with($k, 'schedule'))
                ->flatten()->unique();
        @endphp
        @foreach ($scheduleErrors as $msg)
            <p class="text-red-500 text-xs mt-2">{{ $msg }}</p>
        @endforeach
    </form>
</div>

<div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
    @php
        $cards = [
            ['border' => 'border-t-sky-500', 'value' => $stats['total'], 'label' => 'Total Pendaftar'],
            ['border' => 'border-t-emerald-500', 'value' => $stats['verified'], 'label' => 'Diterima'],
            ['border' => 'border-t-sky-500', 'value' => $stats['pending'], 'label' => 'Dalam Verifikasi'],
            ['border' => 'border-t-red-500', 'value' => $stats['rejected'], 'label' => 'Ditolak'],
        ];
    @endphp
    @foreach ($cards as $c)
        <div class="bg-white rounded-xl border-t-4 {{ $c['border'] }} border-x border-b border-slate-100 shadow-sm p-6 text-center">
            <p class="text-3xl font-bold text-sky-900">{{ $c['value'] }}</p>
            <p class="text-slate-500 text-sm mt-1">{{ $c['label'] }}</p>
        </div>
    @endforeach
</div>

<div class="bg-white rounded-xl border border-slate-100 shadow-sm">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 px-6 py-5 border-b border-slate-100">
        <h2 class="font-bold text-slate-900">Data Pendaftar</h2>
        <div class="flex flex-wrap gap-2">
            @php $filters = ['' => 'Semua', 'verified' => 'Diterima', 'pending' => 'Dalam Verifikasi', 'rejected' => 'Ditolak']; @endphp
            @foreach ($filters as $value => $label)
                <a href="{{ route('admin.ppdb.index', $value ? ['status' => $value] : []) }}"
                   class="px-4 py-2 rounded-lg text-sm font-semibold transition
                          {{ request('status', '') === $value ? 'bg-sky-700 text-white' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-slate-400 text-xs">
                    <th class="px-6 py-3 font-semibold">NO. REG</th>
                    <th class="px-6 py-3 font-semibold">NAMA</th>
                    <th class="px-6 py-3 font-semibold">ASAL SEKOLAH</th>
                    <th class="px-6 py-3 font-semibold">JURUSAN</th>
                    <th class="px-6 py-3 font-semibold">TGL. DAFTAR</th>
                    <th class="px-6 py-3 font-semibold">STATUS</th>
                    <th class="px-6 py-3 font-semibold">AKSI</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($registrants as $r)
                    <tr>
                        <td class="px-6 py-4 text-slate-400">{{ $r->registration_number }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2.5">
                                <span class="w-8 h-8 rounded-full bg-sky-700 text-white flex items-center justify-center text-xs font-bold">
                                    {{ strtoupper(substr($r->full_name, 0, 1)) }}
                                </span>
                                <span class="font-semibold text-slate-800">{{ $r->full_name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-500">—</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-md bg-sky-50 text-sky-700 text-xs font-bold">{{ $r->concentration->name ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4 text-slate-500">{{ $r->created_at->translatedFormat('d M Y') }}</td>
                        <td class="px-6 py-4">@include('admin.partials.status-badge', ['status' => $r->status])</td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2 flex-wrap" x-data="{ open: false }">
                                <button @click="open = true" class="px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50">Detail</button>
                                @if ($r->status !== 'verified')
                                    <form method="POST" action="{{ route('admin.ppdb.status', $r) }}">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="verified">
                                        <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 text-xs font-semibold hover:bg-emerald-100">Terima ✓</button>
                                    </form>
                                @endif
                                @if ($r->status !== 'rejected')
                                    <form method="POST" action="{{ route('admin.ppdb.status', $r) }}">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" class="px-3 py-1.5 rounded-lg bg-red-50 text-red-600 text-xs font-semibold hover:bg-red-100">Tolak ✕</button>
                                    </form>
                                @endif

                                {{-- Detail modal --}}
                                <div x-show="open" x-cloak @click.self="open = false" class="fixed inset-0 z-50 bg-slate-900/60 flex items-center justify-center p-4">
                                    <div class="bg-white rounded-2xl max-w-lg w-full p-6 max-h-[85vh] overflow-y-auto">
                                        <div class="flex justify-between items-start mb-4">
                                            <h3 class="text-lg font-bold text-sky-900">Detail Pendaftar</h3>
                                            <button @click="open = false" class="text-slate-400">✕</button>
                                        </div>
                                        <div class="space-y-2 text-sm">
                                            <div class="flex justify-between"><span class="text-slate-400">No. Registrasi</span><span class="font-semibold">{{ $r->registration_number }}</span></div>
                                            <div class="flex justify-between"><span class="text-slate-400">Nama</span><span class="font-semibold">{{ $r->full_name }}</span></div>
                                            <div class="flex justify-between"><span class="text-slate-400">Jenis Kelamin</span><span class="font-semibold">{{ $r->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</span></div>
                                            <div class="flex justify-between"><span class="text-slate-400">NIK</span><span class="font-semibold">{{ $r->nik }}</span></div>
                                            <div class="flex justify-between"><span class="text-slate-400">NISN</span><span class="font-semibold">{{ $r->nisn }}</span></div>
                                            <div class="flex justify-between"><span class="text-slate-400">No. KK</span><span class="font-semibold">{{ $r->no_kk }}</span></div>
                                            <div class="flex justify-between"><span class="text-slate-400">Domisili</span><span class="font-semibold text-right">{{ $r->address }}</span></div>
                                            <div class="flex justify-between"><span class="text-slate-400">Program</span><span class="font-semibold">{{ $r->program->name ?? '-' }}</span></div>
                                            <div class="flex justify-between"><span class="text-slate-400">Konsentrasi</span><span class="font-semibold">{{ $r->concentration->name ?? '-' }}</span></div>
                                            <div class="flex justify-between"><span class="text-slate-400">Kartu Bansos</span><span class="font-semibold">
                                                {{ collect(['KIP' => $r->has_kip, 'KIS' => $r->has_kis, 'PKH' => $r->has_pkh])->filter()->keys()->implode(', ') ?: 'Tidak ada' }}
                                            </span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-6 py-10 text-center text-slate-400">Belum ada data pendaftar.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($registrants->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">{{ $registrants->links() }}</div>
    @endif
</div>

@endsection