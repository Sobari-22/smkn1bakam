@extends('layouts.admin')

@section('title', 'Rekap Tracer Study')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-900">Rekap Tracer Study</h1>
    <p class="text-slate-400 text-sm mt-1">Data alumni dan statistik penempatan lulusan</p>
</div>

<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-5 mb-6">
    @php
        $cards = [
            ['border' => 'border-t-sky-500', 'value' => $stats['bekerja'], 'label' => 'Bekerja'],
            ['border' => 'border-t-purple-500', 'value' => $stats['kuliah'], 'label' => 'Kuliah'],
            ['border' => 'border-t-emerald-500', 'value' => $stats['wiraswasta'], 'label' => 'Wiraswasta'],
            ['border' => 'border-t-sky-400', 'value' => $stats['bekerja_kuliah'], 'label' => 'Bekerja sambil Kuliah'],
            ['border' => 'border-t-slate-400', 'value' => $stats['belum'], 'label' => 'Belum Bekerja/Kuliah'],
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
    <div class="px-6 py-5 border-b border-slate-100">
        <h2 class="font-bold text-slate-900">Data Responden ({{ $responses->total() }} alumni)</h2>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-slate-500 text-xs bg-sky-50">
                    <th class="px-6 py-3 font-semibold">NISN</th>
                    <th class="px-6 py-3 font-semibold">Nama</th>
                    <th class="px-6 py-3 font-semibold">Tahun Lulus</th>
                    <th class="px-6 py-3 font-semibold">Konsentrasi</th>
                    <th class="px-6 py-3 font-semibold">Status</th>
                    <th class="px-6 py-3 font-semibold">Keterangan</th>
                    <th class="px-6 py-3 font-semibold">Sesuai Bidang</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($responses as $r)
                    @php
                        $statusLabels = [
                            'bekerja' => ['Bekerja', 'bg-sky-50 text-sky-700'],
                            'kuliah' => ['Kuliah', 'bg-purple-50 text-purple-700'],
                            'wiraswasta' => ['Wiraswasta', 'bg-emerald-50 text-emerald-700'],
                            'bekerja_kuliah' => ['Bekerja sambil Kuliah', 'bg-sky-50 text-sky-600'],
                            'belum' => ['Belum Bekerja/Kuliah', 'bg-slate-100 text-slate-500'],
                        ];
                        [$statusLabel, $statusClass] = $statusLabels[$r->status_kegiatan] ?? [$r->status_kegiatan, 'bg-slate-100 text-slate-500'];

                        $keterangan = match ($r->status_kegiatan) {
                            'bekerja', 'bekerja_kuliah' => trim(($r->nama_instansi ?? '-').($r->posisi_jabatan ? ' — '.$r->posisi_jabatan : '')),
                            'kuliah' => $r->nama_kampus ?? '-',
                            'wiraswasta' => $r->jenis_usaha ?? '-',
                            default => $r->alasan_belum ?? '-',
                        };

                        $sesuai = match ($r->kesesuaian_kerja) {
                            'sangat_sesuai', 'sesuai' => '✓',
                            'kurang_sesuai', 'tidak_sesuai' => '✗',
                            default => '~',
                        };
                        $sesuaiClass = match ($r->kesesuaian_kerja) {
                            'sangat_sesuai', 'sesuai' => 'text-emerald-600',
                            'kurang_sesuai', 'tidak_sesuai' => 'text-red-500',
                            default => 'text-slate-300',
                        };
                    @endphp
                    <tr>
                        <td class="px-6 py-4 text-slate-400 font-mono text-xs">{{ $r->nisn }}</td>
                        <td class="px-6 py-4 font-semibold text-slate-900">{{ $r->alumni_nama }}</td>
                        <td class="px-6 py-4 text-slate-500">{{ $r->tahun_lulus }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-md bg-sky-50 text-sky-700 text-xs font-bold">{{ $r->concentration->name ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">{{ $statusLabel }}</span>
                        </td>
                        <td class="px-6 py-4 text-slate-600">{{ $keterangan }}</td>
                        <td class="px-6 py-4 text-center font-bold {{ $sesuaiClass }}">{{ $sesuai }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-6 py-10 text-center text-slate-400">Belum ada data tracer study.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($responses->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">{{ $responses->links() }}</div>
    @endif
</div>

@endsection
