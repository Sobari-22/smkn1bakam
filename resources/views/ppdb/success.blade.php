@extends('layouts.app')

@section('title', 'Pendaftaran Berhasil')

@section('content')

<section class="bg-slate-50 py-20 min-h-[70vh]">
    <div class="max-w-xl mx-auto px-4 text-center">
        <div class="w-16 h-16 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-3xl mx-auto mb-6">✓</div>
        <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 mb-3">Pendaftaran Berhasil Dikirim!</h1>
        <p class="text-slate-500 mb-8">Simpan nomor registrasi Anda untuk mengecek status pendaftaran di kemudian hari.</p>

        <div class="bg-white rounded-2xl shadow-sm p-8">
            <p class="text-xs font-bold text-slate-400 tracking-wide mb-2">NOMOR REGISTRASI</p>
            <p class="text-3xl font-bold text-sky-800 mb-6">{{ $registrant->registration_number }}</p>

            <div class="text-left space-y-2 text-sm border-t border-slate-100 pt-5">
                <div class="flex justify-between"><span class="text-slate-400">Nama</span><span class="font-semibold text-slate-700">{{ $registrant->full_name }}</span></div>
                <div class="flex justify-between"><span class="text-slate-400">Program</span><span class="font-semibold text-slate-700">{{ $registrant->program?->name }}</span></div>
                <div class="flex justify-between"><span class="text-slate-400">Konsentrasi</span><span class="font-semibold text-slate-700">{{ $registrant->concentration?->name ?? '—' }}</span></div>
                <div class="flex justify-between"><span class="text-slate-400">Status</span><span class="px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-700 text-xs font-bold">Menunggu Verifikasi</span></div>
            </div>
        </div>

        <div class="flex flex-wrap justify-center gap-3 mt-8">
            <a href="{{ route('ppdb.check-status') }}" class="px-6 py-3 rounded-lg bg-sky-700 text-white font-semibold hover:bg-sky-800 transition">
                Cek Status Pendaftaran
            </a>
            <a href="{{ route('home') }}" class="px-6 py-3 rounded-lg border border-slate-200 text-slate-600 font-semibold hover:bg-slate-50 transition">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</section>

@endsection
