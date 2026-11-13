@props(['stats'])

@php
    $colors = [
        'bekerja' => 'bg-blue-500',
        'kuliah' => 'bg-emerald-500',
        'wiraswasta' => 'bg-orange-500',
        'bekerja_kuliah' => 'bg-amber-500',
        'belum' => 'bg-slate-400',
    ];
    $total = max($stats['total'], 1);
@endphp

<div class="bg-white rounded-xl border border-slate-200 p-6">
    <h3 class="font-bold text-slate-900 mb-1">Distribusi Status Alumni</h3>
    <p class="text-slate-400 text-sm mb-5">Berdasarkan survei mandiri alumni</p>

    <div class="space-y-4">
        @foreach ($stats['status_labels'] as $key => $label)
            @php
                $count = $stats['status_counts'][$key] ?? 0;
                $percent = round($count / $total * 100);
            @endphp
            <div class="flex items-center gap-4">
                <span class="w-40 shrink-0 text-sm text-slate-600">{{ $label }}</span>
                <div class="flex-1 h-3 rounded-full bg-slate-100 overflow-hidden">
                    <div class="h-full rounded-full {{ $colors[$key] ?? 'bg-slate-400' }}" style="width: {{ $percent }}%"></div>
                </div>
                <span class="w-16 shrink-0 text-sm font-semibold text-slate-700 text-right">{{ $percent }}% · {{ $count }}</span>
            </div>
        @endforeach
    </div>
</div>
