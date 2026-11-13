@extends('layouts.admin')

@section('title', 'Akses E-Rapor')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-900">Akses E-Rapor</h1>
    <p class="text-slate-400 text-sm mt-1">Sistem rapor digital terintegrasi Kemendikbud</p>
</div>

<div class="bg-white rounded-xl border border-slate-100 shadow-sm p-10 text-center max-w-2xl">
    <span class="inline-flex w-20 h-20 rounded-2xl bg-gradient-to-br from-sky-600 to-sky-400 items-center justify-center text-4xl mb-6">
        📊
    </span>
    <h2 class="text-xl font-bold text-slate-900 mb-3">Sistem E-Rapor</h2>
    <p class="text-slate-500 mb-6">
        Sistem E-Rapor dikelola secara terpisah oleh Kemendikbud. Klik tombol di bawah untuk membuka sistem di tab baru.
    </p>
    <a href="{{ $erapotUrl }}" target="_blank" rel="noopener"
       class="inline-flex items-center gap-2 px-6 py-3.5 rounded-lg bg-gradient-to-r from-sky-700 to-sky-500 text-white font-semibold hover:from-sky-800 hover:to-sky-600 transition">
        Buka E-Rapor ↗
    </a>
</div>

<div class="mt-6 max-w-2xl bg-sky-50 border border-sky-100 rounded-xl px-5 py-4 text-sm text-sky-900">
    ℹ️ <strong>Integrasi SSO (Fase 2):</strong> Di masa depan, Single Sign-On akan memungkinkan guru mengakses E-Rapor tanpa login ulang menggunakan akun yang sama.
</div>

@endsection
