@extends('layouts.admin')

@section('title', 'Pengaturan')

@section('content')

<div x-data="{ showAddAdmin: false }">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900">Pengaturan Website</h1>
        <p class="text-slate-400 text-sm mt-1">Konfigurasi umum portal SMKN 1 Bakam</p>
    </div>

    @if (session('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl px-5 py-4 text-sm">{{ session('success') }}</div>
    @endif

    <div class="grid lg:grid-cols-2 gap-6 mb-6">
        {{-- Identitas Sekolah --}}
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-6">
            <h2 class="font-bold text-slate-900 mb-5">Identitas Sekolah</h2>
            <form method="POST" action="{{ route('admin.settings.general') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">NAMA SEKOLAH</label>
                    <input type="text" name="school_name" value="{{ old('school_name', $settings['school_name']) }}"
                           class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500">
                    @error('school_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">ALAMAT</label>
                    <input type="text" name="school_address" value="{{ old('school_address', $settings['school_address']) }}"
                           class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">NOMOR TELEPON</label>
                    <input type="text" name="school_phone" value="{{ old('school_phone', $settings['school_phone']) }}"
                           class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">EMAIL</label>
                    <input type="email" name="school_email" value="{{ old('school_email', $settings['school_email']) }}"
                           class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500">
                    @error('school_email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <button type="submit" class="w-full py-3 rounded-lg bg-sky-700 hover:bg-sky-800 text-white font-semibold text-sm">
                    Simpan Perubahan
                </button>
            </form>
        </div>

        {{-- Media Sosial --}}
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-6">
            <h2 class="font-bold text-slate-900 mb-5">Media Sosial</h2>
            <form method="POST" action="{{ route('admin.settings.social') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">FACEBOOK URL</label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2">📘</span>
                        <input type="url" name="social_facebook" value="{{ old('social_facebook', $settings['social_facebook']) }}" placeholder="https://..."
                               class="w-full pl-10 pr-4 py-3 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500">
                    </div>
                    @error('social_facebook') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">INSTAGRAM URL</label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2">📷</span>
                        <input type="url" name="social_instagram" value="{{ old('social_instagram', $settings['social_instagram']) }}" placeholder="https://..."
                               class="w-full pl-10 pr-4 py-3 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500">
                    </div>
                    @error('social_instagram') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">YOUTUBE URL</label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2">▶️</span>
                        <input type="url" name="social_youtube" value="{{ old('social_youtube', $settings['social_youtube']) }}" placeholder="https://..."
                               class="w-full pl-10 pr-4 py-3 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500">
                    </div>
                    @error('social_youtube') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <button type="submit" class="w-full py-3 rounded-lg bg-sky-700 hover:bg-sky-800 text-white font-semibold text-sm">
                    Simpan Media Sosial
                </button>
            </form>
        </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-6">
        {{-- Manajemen Admin --}}
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-6">
            <h2 class="font-bold text-slate-900 mb-5">Manajemen Admin</h2>
            <div class="overflow-x-auto mb-4">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-slate-400 text-xs">
                            <th class="py-2 font-semibold">USERNAME</th>
                            <th class="py-2 font-semibold">ROLE</th>
                            <th class="py-2 font-semibold">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($admins as $admin)
                            <tr x-data="{ editingPassword: false }">
                                <td class="py-3 font-semibold text-slate-800">{{ $admin->username ?? '—' }}</td>
                                <td class="py-3">
                                    <span class="px-2.5 py-1 rounded-full bg-sky-50 text-sky-700 text-xs font-semibold">
                                        {{ $roleLabels[$admin->role] ?? $admin->role }}
                                    </span>
                                </td>
                                <td class="py-3">
                                    <button @click="editingPassword = true" class="px-3 py-1.5 rounded-lg bg-sky-50 text-sky-700 text-xs font-semibold hover:bg-sky-100">
                                        Ubah Password
                                    </button>

                                    <div x-show="editingPassword" x-cloak @click.self="editingPassword = false" class="fixed inset-0 z-50 bg-slate-900/60 flex items-center justify-center p-4">
                                        <div class="bg-white rounded-2xl max-w-sm w-full p-6">
                                            <h3 class="text-lg font-bold text-sky-900 mb-4">Ubah Password — {{ $admin->username }}</h3>
                                            <form method="POST" action="{{ route('admin.settings.admins.password', $admin) }}" class="space-y-4">
                                                @csrf @method('PATCH')
                                                <div>
                                                    <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">PASSWORD BARU</label>
                                                    <input type="password" name="password" class="w-full px-4 py-2.5 rounded-lg border border-slate-200" required minlength="6">
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">KONFIRMASI PASSWORD</label>
                                                    <input type="password" name="password_confirmation" class="w-full px-4 py-2.5 rounded-lg border border-slate-200" required minlength="6">
                                                </div>
                                                <div class="flex gap-3 pt-2">
                                                    <button type="button" @click="editingPassword = false" class="px-5 py-2.5 rounded-lg border border-slate-200 text-slate-500 font-semibold text-sm">Batal</button>
                                                    <button type="submit" class="flex-1 py-2.5 rounded-lg bg-sky-700 hover:bg-sky-800 text-white font-semibold text-sm">Simpan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <button @click="showAddAdmin = true" class="px-5 py-2.5 rounded-lg border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50">
                + Tambah Admin
            </button>
        </div>

        {{-- Backup & Keamanan --}}
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-6">
            <h2 class="font-bold text-slate-900 mb-5">Backup & Keamanan</h2>
            <div class="space-y-3">
                <div class="flex items-center justify-between border border-slate-100 rounded-xl px-4 py-3.5">
                    <div>
                        <p class="font-semibold text-slate-800 text-sm">🗄️ Backup Database Sekarang</p>
                        <p class="text-slate-400 text-xs mt-0.5">Fitur ini belum tersedia</p>
                    </div>
                    <button class="px-4 py-2 rounded-lg bg-slate-100 text-slate-400 text-xs font-semibold cursor-not-allowed" disabled>Backup Sekarang</button>
                </div>
                <div class="flex items-center justify-between border border-slate-100 rounded-xl px-4 py-3.5">
                    <div>
                        <p class="font-semibold text-slate-800 text-sm">📥 Ekspor Data SPMB (Excel/CSV)</p>
                        <p class="text-slate-400 text-xs mt-0.5">Semua data pendaftar {{ now()->year }}/{{ now()->year + 1 }}</p>
                    </div>
                    <a href="{{ route('admin.ppdb.export') }}" class="px-4 py-2 rounded-lg bg-sky-50 text-sky-700 text-xs font-semibold hover:bg-sky-100">Ekspor</a>
                </div>
                <div class="flex items-center justify-between border border-slate-100 rounded-xl px-4 py-3.5">
                    <div>
                        <p class="font-semibold text-slate-800 text-sm">🔒 SSL Certificate</p>
                        <p class="text-slate-400 text-xs mt-0.5">Dikelola di sisi hosting/server</p>
                    </div>
                    <button class="px-4 py-2 rounded-lg bg-slate-100 text-slate-400 text-xs font-semibold cursor-not-allowed" disabled>Perbarui</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Tambah Admin --}}
    <div x-show="showAddAdmin" x-cloak @click.self="showAddAdmin = false" class="fixed inset-0 z-50 bg-slate-900/60 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6">
            <h3 class="text-lg font-bold text-sky-900 mb-4">Tambah Admin Baru</h3>
            <form method="POST" action="{{ route('admin.settings.admins.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">NAMA LENGKAP</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full px-4 py-2.5 rounded-lg border border-slate-200">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">USERNAME</label>
                    <input type="text" name="username" value="{{ old('username') }}" class="w-full px-4 py-2.5 rounded-lg border border-slate-200">
                    @error('username') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">EMAIL</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-2.5 rounded-lg border border-slate-200">
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">ROLE</label>
                    <select name="role" class="w-full px-4 py-2.5 rounded-lg border border-slate-200">
                        @foreach ($roleLabels as $value => $label)
                            <option value="{{ $value }}" @selected(old('role') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('role') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">PASSWORD</label>
                    <input type="password" name="password" minlength="6" class="w-full px-4 py-2.5 rounded-lg border border-slate-200">
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" @click="showAddAdmin = false" class="px-5 py-2.5 rounded-lg border border-slate-200 text-slate-500 font-semibold text-sm">Batal</button>
                    <button type="submit" class="flex-1 py-2.5 rounded-lg bg-sky-700 hover:bg-sky-800 text-white font-semibold text-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
