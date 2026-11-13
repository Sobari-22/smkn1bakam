@extends('layouts.app')

@section('title', 'Kontak & Lokasi')

@section('content')

{{-- ============ HERO ============ --}}
<section class="bg-gradient-to-br from-sky-800 to-sky-600 py-20 relative overflow-hidden">
    <div class="absolute -right-20 -bottom-20 w-96 h-96 rounded-full bg-white/5"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <p class="text-sky-200 font-semibold text-sm tracking-wide mb-3">SMKN 1 BAKAM</p>
        <h1 class="text-4xl sm:text-5xl font-extrabold text-white mb-5">Kontak &amp; Lokasi</h1>
        <p class="text-sky-100 max-w-2xl">
            Ada pertanyaan? Tim kami siap membantu Anda.
        </p>
    </div>
</section>

<section class="bg-slate-50 py-14">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid lg:grid-cols-[1fr_1.3fr] gap-10">

        {{-- ============ INFORMASI KONTAK ============ --}}
        <div>
            <p class="text-sky-600 font-semibold uppercase tracking-wide text-sm mb-2">Informasi Kontak</p>
            <h2 class="text-3xl font-bold text-sky-900 mb-6">Hubungi Kami</h2>

            <div class="space-y-4">
                <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-5 flex gap-4">
                    <span class="w-11 h-11 shrink-0 rounded-lg bg-rose-50 flex items-center justify-center text-lg">📍</span>
                    <div>
                        <p class="text-xs font-bold text-slate-400 tracking-wide mb-1">ALAMAT</p>
                        <p class="text-slate-700 leading-relaxed">
                            {{ \App\Models\Setting::get('school_address', 'Jl. Raya Bakam, Kecamatan Bakam') }}<br>
                            Kabupaten Bangka<br>
                            Kepulauan Bangka Belitung 33315
                        </p>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-5 flex gap-4">
                    <span class="w-11 h-11 shrink-0 rounded-lg bg-rose-50 flex items-center justify-center text-lg">📞</span>
                    <div>
                        <p class="text-xs font-bold text-slate-400 tracking-wide mb-1">TELEPON</p>
                        <p class="text-slate-700 leading-relaxed">
                            {{ \App\Models\Setting::get('school_phone', '(0717) XXX-XXXX') }}<br>
                        </p>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-5 flex gap-4">
                    <span class="w-11 h-11 shrink-0 rounded-lg bg-rose-50 flex items-center justify-center text-lg">✉️</span>
                    <div>
                        <p class="text-xs font-bold text-slate-400 tracking-wide mb-1">EMAIL</p>
                        <p class="text-slate-700 leading-relaxed">
                            {{ \App\Models\Setting::get('school_email', 'info@smkn1bakam.sch.id') }}<br>
                            {{ \App\Models\Setting::get('ppdb_email', 'ppdb@smkn1bakam.sch.id') }}
                        </p>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-5 flex gap-4">
                    <span class="w-11 h-11 shrink-0 rounded-lg bg-rose-50 flex items-center justify-center text-lg">🕒</span>
                    <div>
                        <p class="text-xs font-bold text-slate-400 tracking-wide mb-1">JAM OPERASIONAL</p>
                        <p class="text-slate-700 leading-relaxed">
                            Senin – Kamis: 07.15 – 16.10 WIB<br>
                            Jumat: 07.15 – 11.05 WIB<br>
                        </p>
                    </div>
                </div>
            </div>

            <p class="text-xs font-bold text-slate-400 tracking-wide mt-8 mb-3">MEDIA SOSIAL</p>
            <div class="grid grid-cols-3 gap-3">
                <a href="{{ \App\Models\Setting::get('social_facebook', '#') }}" target="_blank" rel="noopener"
                   class="bg-white rounded-xl border border-slate-100 shadow-sm p-4 text-center hover:shadow-md transition">
                    <span class="w-9 h-9 rounded-lg bg-sky-500 text-white flex items-center justify-center mx-auto mb-2">📘</span>
                    <p class="text-sm font-medium text-slate-700">Facebook</p>
                </a>
                <a href="{{ \App\Models\Setting::get('social_instagram', '#') }}" target="_blank" rel="noopener"
                   class="bg-white rounded-xl border border-slate-100 shadow-sm p-4 text-center hover:shadow-md transition">
                    <span class="w-9 h-9 rounded-lg bg-slate-700 text-white flex items-center justify-center mx-auto mb-2">📷</span>
                    <p class="text-sm font-medium text-slate-700">Instagram</p>
                </a>
                <a href="{{ \App\Models\Setting::get('social_youtube', '#') }}" target="_blank" rel="noopener"
                   class="bg-white rounded-xl border border-slate-100 shadow-sm p-4 text-center hover:shadow-md transition">
                    <span class="w-9 h-9 rounded-lg bg-sky-600 text-white flex items-center justify-center mx-auto mb-2">▶️</span>
                    <p class="text-sm font-medium text-slate-700">YouTube</p>
                </a>
            </div>
        </div>

        {{-- ============ FORM + PETA ============ --}}
        <div class="space-y-8">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 sm:p-8">
                <h3 class="text-xl font-bold text-sky-900 mb-6">Kirim Pesan</h3>

                @if (session('success'))
                    <div class="mb-5 px-4 py-3 rounded-lg bg-emerald-50 text-emerald-700 text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('kontak.store') }}" class="space-y-5">
                    @csrf

                    <div class="grid sm:grid-cols-2 gap-5">
                        <div>
                            <label for="name" class="block text-xs font-bold text-slate-500 tracking-wide mb-2">NAMA LENGKAP</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Nama Anda"
                                   class="w-full px-4 py-3 rounded-lg border border-slate-200 bg-slate-50 focus:bg-white focus:border-sky-500 focus:ring-1 focus:ring-sky-500 outline-none transition">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="email" class="block text-xs font-bold text-slate-500 tracking-wide mb-2">EMAIL</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="email@contoh.com"
                                   class="w-full px-4 py-3 rounded-lg border border-slate-200 bg-slate-50 focus:bg-white focus:border-sky-500 focus:ring-1 focus:ring-sky-500 outline-none transition">
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="subject" class="block text-xs font-bold text-slate-500 tracking-wide mb-2">SUBJEK</label>
                        <select name="subject" id="subject"
                                class="w-full px-4 py-3 rounded-lg border border-slate-200 bg-slate-50 focus:bg-white focus:border-sky-500 focus:ring-1 focus:ring-sky-500 outline-none transition">
                            <option value="" {{ old('subject') ? '' : 'selected' }} disabled>-- Pilih subjek --</option>
                            @foreach (['Pertanyaan Umum', 'Informasi SPMB', 'Kerja Sama / DUDI', 'Kritik & Saran', 'Lainnya'] as $subj)
                                <option value="{{ $subj }}" {{ old('subject') === $subj ? 'selected' : '' }}>{{ $subj }}</option>
                            @endforeach
                        </select>
                        @error('subject')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="message" class="block text-xs font-bold text-slate-500 tracking-wide mb-2">PESAN</label>
                        <textarea name="message" id="message" rows="5" placeholder="Tulis pesan atau pertanyaan Anda..."
                                  class="w-full px-4 py-3 rounded-lg border border-slate-200 bg-slate-50 focus:bg-white focus:border-sky-500 focus:ring-1 focus:ring-sky-500 outline-none transition resize-y">{{ old('message') }}</textarea>
                        @error('message')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full py-3.5 rounded-lg bg-sky-700 text-white font-semibold hover:bg-sky-800 transition">
                        Kirim Pesan
                    </button>
                </form>
            </div>

            {{-- ============ PETA ============
                 Static placeholder card for now — swap for an embedded Google
                 Maps iframe once a Maps embed URL/API key is available for
                 the school's actual coordinates. --}}
            <a href="{{ \App\Models\Setting::get('maps_url', 'https://maps.app.goo.gl/YH3WhRpe4iDWGPndA') }}" target="_blank" rel="noopener"
               class="block bg-slate-100 hover:bg-slate-200 rounded-2xl p-10 text-center transition">
                <p class="text-4xl mb-3">🗺️</p>
                <p class="font-semibold text-slate-700">Peta Lokasi SMKN 1 Bakam</p>
                <p class="text-slate-400 text-sm mt-1">Kec. Bakam, Kab. Bangka, Babel</p>
                <p class="text-sky-700 text-sm font-semibold mt-3">Buka di Google Maps →</p>
            </a>
        </div>

    </div>
</section>

@endsection
