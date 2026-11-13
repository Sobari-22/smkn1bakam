@props(['stats'])

@php
    $sesuai = $stats['kesesuaian_persen'];
    $tidak = 100 - $sesuai;
@endphp

<div class="bg-white rounded-xl border border-slate-200 p-6">
    <h3 class="font-bold text-slate-900 mb-1">Kesesuaian Bidang Kerja</h3>
    <p class="text-slate-400 text-sm mb-5">Alumni yang bekerja sesuai kompetensinya</p>

    <div class="flex items-center gap-8">
        <div class="relative w-36 h-36 rounded-full shrink-0"
             style="background: conic-gradient(#0369a1 0% {{ $sesuai }}%, #e2e8f0 {{ $sesuai }}% 100%);">
            <div class="absolute inset-3 rounded-full bg-white flex flex-col items-center justify-center">
                <span class="text-2xl font-bold text-slate-900">{{ $sesuai }}%</span>
                <span class="text-slate-400 text-xs">sesuai keahlian</span>
            </div>
        </div>
        <div class="space-y-3">
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-sky-700"></span>
                <div>
                    <p class="text-slate-500 text-xs">Sesuai bidang keahlian</p>
                    <p class="font-bold text-slate-900">{{ $sesuai }}%</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-slate-200"></span>
                <div>
                    <p class="text-slate-500 text-xs">Tidak sesuai bidang</p>
                    <p class="font-bold text-slate-900">{{ $tidak }}%</p>
                </div>
            </div>
        </div>
    </div>
</div>
