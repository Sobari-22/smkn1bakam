@php
    $map = [
        'pending' => ['label' => 'Dalam Verifikasi', 'class' => 'bg-sky-50 text-sky-700'],
        'verified' => ['label' => 'Diterima', 'class' => 'bg-emerald-50 text-emerald-700'],
        'rejected' => ['label' => 'Ditolak', 'class' => 'bg-red-50 text-red-700'],
    ];
    $s = $map[$status] ?? ['label' => $status, 'class' => 'bg-slate-100 text-slate-600'];
@endphp
<span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $s['class'] }}">{{ $s['label'] }}</span>
