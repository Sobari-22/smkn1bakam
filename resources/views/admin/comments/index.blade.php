@extends('layouts.admin')

@section('title', 'Moderasi Komentar')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-900">Moderasi Komentar</h1>
    <p class="text-slate-400 text-sm mt-1">Tinjau dan setujui komentar publik sebelum ditampilkan</p>
</div>

@if (session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl px-5 py-4 text-sm">{{ session('success') }}</div>
@endif

<div class="grid grid-cols-3 gap-5 mb-6">
    <div class="bg-white rounded-xl border-t-4 border-t-sky-500 border-x border-b border-slate-100 shadow-sm p-6 text-center">
        <p class="text-3xl font-bold text-sky-900">{{ $stats['pending'] }}</p>
        <p class="text-slate-500 text-sm mt-1">Menunggu</p>
    </div>
    <div class="bg-white rounded-xl border-t-4 border-t-emerald-500 border-x border-b border-slate-100 shadow-sm p-6 text-center">
        <p class="text-3xl font-bold text-emerald-600">{{ $stats['approved'] }}</p>
        <p class="text-slate-500 text-sm mt-1">Disetujui</p>
    </div>
    <div class="bg-white rounded-xl border-t-4 border-t-red-500 border-x border-b border-slate-100 shadow-sm p-6 text-center">
        <p class="text-3xl font-bold text-red-500">{{ $stats['rejected'] }}</p>
        <p class="text-slate-500 text-sm mt-1">Ditolak</p>
    </div>
</div>

<h2 class="font-bold text-sky-800 text-sm tracking-wide mb-3">MENUNGGU PERSETUJUAN ({{ $pending->count() }})</h2>

<div class="space-y-4">
    @forelse ($pending as $comment)
        <div class="bg-white rounded-xl border-l-4 border-sky-500 shadow-sm p-5 flex items-start justify-between gap-4">
            <div class="min-w-0">
                <p class="text-slate-400 text-xs mb-2">
                    Berita: <a href="{{ route('berita.show', $comment->news) }}" target="_blank" class="text-sky-700 font-semibold hover:underline">{{ $comment->news->title ?? '-' }}</a>
                    · {{ $comment->created_at->translatedFormat('j M Y, H.i') }}
                </p>
                <p class="font-bold text-slate-900 mb-1">{{ $comment->nama_pengirim }}</p>
                <p class="text-slate-600 text-sm">"{{ $comment->isi_komentar }}"</p>
            </div>
            <div class="flex gap-2 shrink-0">
                <form method="POST" action="{{ route('admin.comments.approve', $comment) }}">
                    @csrf @method('PATCH')
                    <button type="submit" class="px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold">✓ Setujui</button>
                </form>
                <form method="POST" action="{{ route('admin.comments.reject', $comment) }}">
                    @csrf @method('PATCH')
                    <button type="submit" class="px-4 py-2 rounded-lg border-2 border-red-200 text-red-600 text-sm font-semibold hover:bg-red-50">✕ Tolak</button>
                </form>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-xl border border-slate-100 p-10 text-center text-slate-400">
            Tidak ada komentar yang menunggu persetujuan.
        </div>
    @endforelse
</div>

@endsection
