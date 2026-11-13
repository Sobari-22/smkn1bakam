@extends('layouts.admin')

@section('title', 'Profil Saya')

@section('content')

@php
    $input = 'w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-sky-600 focus:ring-1 focus:ring-sky-600';
@endphp

<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-900">Profil Saya</h1>
    <p class="text-slate-400 text-sm mt-1">Kelola nama, email, dan password akun Anda</p>
</div>

<div class="grid lg:grid-cols-2 gap-6 max-w-5xl items-start">

    {{-- ============ DATA AKUN ============ --}}
    <form method="POST" action="{{ route('admin.profile.update') }}"
          class="bg-white rounded-xl border border-slate-100 shadow-sm p-6 space-y-5">
        @csrf
        @method('PUT')

        <h2 class="font-bold text-slate-900">Data Akun</h2>

        @if (session('success_profile'))
            <div class="rounded-lg bg-green-50 border border-green-200 text-green-800 px-4 py-3 text-sm">{{ session('success_profile') }}</div>
        @endif

        @if ($errors->any())
            <div class="rounded-lg bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div>
            <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">Nama</label>
            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" class="{{ $input }}" required>
        </div>

        <div>
            <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" class="{{ $input }}" required>
            <p class="text-xs text-slate-400 mt-1">Email ini dipakai untuk masuk ke panel admin.</p>
        </div>

        <div>
            <p class="block text-sm font-semibold text-slate-700 mb-1.5">Peran</p>
            <p class="text-sm text-slate-600">{{ $user->roleLabel() }}</p>
        </div>

        <button type="submit" class="px-6 py-2.5 rounded-lg bg-sky-700 text-white font-semibold text-sm hover:bg-sky-800">Simpan Perubahan</button>
    </form>

    {{-- ============ GANTI PASSWORD ============ --}}
    <form method="POST" action="{{ route('admin.profile.password') }}"
          class="bg-white rounded-xl border border-slate-100 shadow-sm p-6 space-y-5">
        @csrf
        @method('PUT')

        <h2 class="font-bold text-slate-900">Ganti Password</h2>

        @if (session('success_password'))
            <div class="rounded-lg bg-green-50 border border-green-200 text-green-800 px-4 py-3 text-sm">{{ session('success_password') }}</div>
        @endif

        @if ($errors->password->any())
            <div class="rounded-lg bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm">
                @foreach ($errors->password->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div>
            <label for="current_password" class="block text-sm font-semibold text-slate-700 mb-1.5">Password saat ini</label>
            <input id="current_password" name="current_password" type="password" autocomplete="current-password" class="{{ $input }}" required>
        </div>

        <div>
            <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">Password baru</label>
            <input id="password" name="password" type="password" autocomplete="new-password" class="{{ $input }}" required>
            <p class="text-xs text-slate-400 mt-1">Minimal 8 karakter.</p>
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-1.5">Ulangi password baru</label>
            <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" class="{{ $input }}" required>
        </div>

        <button type="submit" class="px-6 py-2.5 rounded-lg bg-sky-700 text-white font-semibold text-sm hover:bg-sky-800">Ganti Password</button>
    </form>
</div>

@endsection
