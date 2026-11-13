@props(['trend'])

@php
    $max = max($trend->max('total') ?? 1, 1);
@endphp

<div class="bg-white rounded-xl border border-slate-200 p-6">
    <h3 class="font-bold text-slate-900 mb-5">Tren Penempatan per Tahun Lulus</h3>

    <div class="flex items-end justify-around gap-6 h-48 border-b border-slate-100 pb-2">
        @forelse ($trend as $row)
            <div class="flex flex-col items-center gap-2 flex-1">
                <div class="flex items-end gap-1.5 h-40">
                    <div class="flex flex-col items-center justify-end h-full">
                        <span class="text-xs font-semibold text-slate-600 mb-1">{{ $row->total }}</span>
                        <div class="w-8 bg-blue-500 rounded-t" style="height: {{ max($row->total / $max * 100, 4) }}%"></div>
                    </div>
                    <div class="flex flex-col items-center justify-end h-full">
                        <span class="text-xs font-semibold text-slate-600 mb-1">{{ $row->bekerja }}</span>
                        <div class="w-8 bg-emerald-500 rounded-t" style="height: {{ max($row->bekerja / $max * 100, 4) }}%"></div>
                    </div>
                </div>
                <span class="text-xs text-slate-500 whitespace-nowrap">Lulusan {{ $row->tahun_lulus }}</span>
            </div>
        @empty
            <p class="text-slate-400 text-sm self-center">Belum ada data.</p>
        @endforelse
    </div>

    <div class="flex items-center gap-5 mt-4">
        <span class="flex items-center gap-1.5 text-xs text-slate-500"><span class="w-3 h-3 rounded-sm bg-blue-500"></span> Total Responden</span>
        <span class="flex items-center gap-1.5 text-xs text-slate-500"><span class="w-3 h-3 rounded-sm bg-emerald-500"></span> Bekerja</span>
    </div>
</div>
