@props(['stats'])

@php
    $max = max($stats['wait_counts']->max() ?? 1, 1);
    $shades = ['kurang_1_bulan' => 'bg-sky-300', '1_3_bulan' => 'bg-sky-500', '3_6_bulan' => 'bg-sky-700', 'lebih_6_bulan' => 'bg-sky-900'];
@endphp

<div class="bg-white rounded-xl border border-slate-200 p-6">
    <h3 class="font-bold text-slate-900 mb-1">Waktu Tunggu Kerja</h3>
    <p class="text-slate-400 text-sm mb-5">Distribusi lama mencari pekerjaan setelah lulus</p>

    <div class="space-y-4">
        @foreach ($stats['wait_labels'] as $key => $label)
            @php $count = $stats['wait_counts'][$key] ?? 0; @endphp
            <div class="flex items-center gap-4">
                <span class="w-20 shrink-0 text-sm text-slate-600">{{ $label }}</span>
                <div class="flex-1 h-3 rounded-full bg-slate-100 overflow-hidden">
                    <div class="h-full rounded-full {{ $shades[$key] }}" style="width: {{ max($count / $max * 100, 4) }}%"></div>
                </div>
                <span class="w-20 shrink-0 text-sm font-semibold text-slate-700 text-right">{{ $count }} orang</span>
            </div>
        @endforeach
    </div>

    <p class="text-slate-400 text-xs mt-4 flex items-center gap-2">
        <span class="w-8 h-2 rounded-full bg-gradient-to-r from-sky-300 to-sky-900"></span>
        Lebih terang = waktu tunggu lebih singkat
    </p>
</div>
