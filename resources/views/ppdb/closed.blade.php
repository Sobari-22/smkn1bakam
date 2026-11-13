@extends('layouts.app')

@section('title', 'SPMB Online')

@section('content')

<section class="bg-gradient-to-br from-sky-800 to-sky-600 py-20 relative overflow-hidden">
    <div class="absolute -right-20 -bottom-20 w-96 h-96 rounded-full bg-white/5"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <p class="text-sky-200 font-semibold text-sm tracking-wide mb-3">SMKN 1 BAKAM</p>
        <h1 class="text-4xl sm:text-5xl font-extrabold text-white mb-5">SPMB Online</h1>
        <p class="text-sky-100 max-w-2xl">Sistem Penerimaan Murid Baru SMKN 1 Bakam.</p>
    </div>
</section>

<section class="bg-slate-50 py-16">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-10 text-center">
            <p class="text-5xl mb-5">🔒</p>
            <h2 class="text-2xl font-bold text-slate-900 mb-3">Pendaftaran Belum Dibuka</h2>
            <p class="text-slate-500 leading-relaxed mb-8">{{ $message }}</p>

            <div class="flex flex-wrap justify-center gap-3">
                <a href="{{ route('ppdb.create') }}" class="px-6 py-3 rounded-lg bg-sky-700 text-white font-semibold hover:bg-sky-800 transition">
                    Cek Status Pendaftaran Lama
                </a>
                <a href="{{ route('home') }}" class="px-6 py-3 rounded-lg border-2 border-sky-700 text-sky-700 font-semibold hover:bg-sky-50 transition">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</section>

@endsection