@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 flex items-center gap-2">Selamat Datang <span>👋</span></h1>
        <p class="text-slate-400 text-sm mt-1">{{ now()->translatedFormat('l, d F Y') }} · SMKN 1 Bakam</p>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.news.index') }}" class="px-5 py-2.5 rounded-lg border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50">
            + Tambah Berita
        </a>
        <a href="{{ route('admin.ppdb.index') }}" class="px-5 py-2.5 rounded-lg bg-sky-700 text-white font-semibold text-sm hover:bg-sky-800">
            Unduh Laporan SPMB
        </a>
    </div>
</div>

{{-- Stat cards --}}
<div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
    @php
        $cards = [
            ['icon' => '📝', 'iconBg' => 'bg-sky-50', 'label' => 'TOTAL PENDAFTAR', 'value' => $stats['total'], 'note' => '↑ Data terbaru', 'noteColor' => 'text-sky-600'],
            ['icon' => '✅', 'iconBg' => 'bg-emerald-50', 'label' => 'DITERIMA', 'value' => $stats['verified'], 'note' => $stats['total'] > 0 ? round($stats['verified'] / $stats['total'] * 100).'% dari total' : '—', 'noteColor' => 'text-emerald-600'],
            ['icon' => '⏳', 'iconBg' => 'bg-sky-50', 'label' => 'DALAM VERIFIKASI', 'value' => $stats['pending'], 'note' => '→ Perlu ditinjau', 'noteColor' => 'text-sky-600'],
            ['icon' => '❌', 'iconBg' => 'bg-red-50', 'label' => 'DITOLAK', 'value' => $stats['rejected'], 'note' => '→ Tidak lolos seleksi', 'noteColor' => 'text-red-500'],
        ];
    @endphp
    @foreach ($cards as $card)
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-6 relative overflow-hidden">
            <span class="w-11 h-11 rounded-lg {{ $card['iconBg'] }} flex items-center justify-center text-xl mb-4">{{ $card['icon'] }}</span>
            <p class="text-slate-400 text-xs font-bold tracking-wide mb-1">{{ $card['label'] }}</p>
            <p class="text-3xl font-bold text-slate-900 mb-1">{{ $card['value'] }}</p>
            <p class="text-xs font-semibold {{ $card['noteColor'] }}">{{ $card['note'] }}</p>
        </div>
    @endforeach
</div>

<div class="grid sm:grid-cols-2 gap-5 mb-6">
    <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-6">
        <span class="w-11 h-11 rounded-lg bg-slate-100 flex items-center justify-center text-xl mb-4">📋</span>
        <p class="text-slate-400 text-xs font-bold tracking-wide mb-1">BERITA AKTIF</p>
        <p class="text-3xl font-bold text-slate-900 mb-1">{{ $stats['news_active'] }}</p>
        <p class="text-xs font-semibold text-slate-500">→ {{ $stats['news_draft'] }} draft</p>
    </div>
</div>

<div class="grid lg:grid-cols-3 gap-6 mb-6">
    {{-- Pendaftar terbaru --}}
    <div class="lg:col-span-2 bg-white rounded-xl border border-slate-100 shadow-sm">
        <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100">
            <h2 class="font-bold text-slate-900">Pendaftar SPMB Terbaru</h2>
            <span class="px-3 py-1 rounded-full bg-sky-50 text-sky-700 text-xs font-semibold">{{ $stats['total'] }} pendaftar</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-slate-400 text-xs">
                        <th class="px-6 py-3 font-semibold">NO. REG</th>
                        <th class="px-6 py-3 font-semibold">NAMA</th>
                        <th class="px-6 py-3 font-semibold">JURUSAN</th>
                        <th class="px-6 py-3 font-semibold">STATUS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($latestRegistrants as $r)
                        <tr>
                            <td class="px-6 py-3.5 text-slate-400">{{ $r->registration_number }}</td>
                            <td class="px-6 py-3.5">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-8 h-8 rounded-full bg-sky-700 text-white flex items-center justify-center text-xs font-bold">
                                        {{ strtoupper(substr($r->full_name, 0, 1)) }}{{ strtoupper(substr(strrchr($r->full_name, ' ') ?: '', 1, 1)) }}
                                    </span>
                                    <span class="font-semibold text-slate-800">{{ $r->full_name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-3.5">
                                <span class="px-2 py-1 rounded-md bg-sky-50 text-sky-700 text-xs font-bold">{{ $r->concentration->name ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-3.5">
                                @include('admin.partials.status-badge', ['status' => $r->status])
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-6 py-8 text-center text-slate-400">Belum ada pendaftar.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Aktivitas terbaru --}}
    <div class="bg-white rounded-xl border border-slate-100 shadow-sm">
        <div class="px-6 py-5 border-b border-slate-100">
            <h2 class="font-bold text-slate-900">Aktivitas Terbaru</h2>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse ($activity as $item)
                <div class="flex gap-3 px-6 py-4">
                    <span class="w-9 h-9 rounded-full bg-slate-100 flex items-center justify-center text-base shrink-0">{{ $item['icon'] }}</span>
                    <div>
                        <p class="text-sm text-slate-700">{{ $item['text'] }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">{{ $item['time']->diffForHumans() }}</p>
                    </div>
                </div>
            @empty
                <p class="px-6 py-8 text-center text-slate-400 text-sm">Belum ada aktivitas.</p>
            @endforelse
        </div>
    </div>
</div>

{{-- Kuota per program --}}
<div class="bg-white rounded-xl border border-slate-100 shadow-sm p-6">
    <div class="flex items-center justify-between mb-5">
        <h2 class="font-bold text-slate-900">Kuota per Program Keahlian</h2>
        <span class="text-slate-400 text-sm">TA {{ now()->year }}/{{ now()->year + 1 }}</span>
    </div>
    <div class="grid sm:grid-cols-2 gap-5">
        @foreach ($quotaByProgram as $p)
            <div class="bg-slate-50 rounded-xl p-5">
                <div class="flex items-center justify-between mb-3">
                    <span class="flex items-center gap-2 font-semibold text-slate-800">
                        {{ $loop->first ? '💻' : '🔧' }} {{ $p['code'] }}
                    </span>
                    <span class="font-bold text-sky-700">{{ $p['percent'] }}%</span>
                </div>
                <div class="h-2 rounded-full bg-slate-200 overflow-hidden mb-2">
                    <div class="h-full rounded-full bg-sky-700" style="width: {{ $p['percent'] }}%"></div>
                </div>
                <div class="flex justify-between text-xs text-slate-400">
                    <span>Terisi: {{ $p['filled'] }}</span>
                    <span>Kuota: {{ $p['quota'] }}</span>
                </div>
            </div>
        @endforeach
    </div>
</div>

@endsection
