@extends('layouts.app')

@section('title', $news->title)
@section('meta_description', $news->excerpt)

@section('content')

@include('berita._koran-style')

@php
    $kickerColors = [
        'Prestasi' => 'text-emerald-700',
        'Kegiatan' => 'text-sky-700',
        'PPDB' => 'text-sky-900',
        'Pengumuman' => 'text-purple-700',
    ];
    $published = $news->published_at->locale('id');
@endphp

<div class="koran pb-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- ============ KEPALA KORAN (RINGKAS) ============ --}}
        <header class="pt-6 text-center">
            <div class="kicker flex items-center justify-between gap-4 text-stone-600 pb-2 border-b border-stone-400">
                <span>{{ now()->locale('id')->translatedFormat('l, j F Y') }}</span>
                <a href="{{ route('berita.index') }}" class="text-red-700 hover:text-red-900">← Semua Berita</a>
            </div>
            <a href="{{ route('berita.index') }}" class="block headline text-3xl sm:text-4xl lg:text-5xl leading-none py-4 text-stone-900 uppercase">Warta SMKN 1 Bakam</a>
            <div class="rule-double"></div>
        </header>

        <div class="grid lg:grid-cols-12 gap-10 lg:gap-0 mt-10">

            {{-- ============ ARTIKEL ============ --}}
            <div class="lg:col-span-8 lg:pr-10 lg:border-r border-stone-300">
                <article>
                    <p class="kicker {{ $kickerColors[$news->category] ?? 'text-red-700' }}">{{ $news->category ?? 'Berita' }}</p>

                    <h1 class="headline text-3xl sm:text-4xl lg:text-5xl leading-[1.12] mt-3 mb-5">{{ $news->title }}</h1>

                    <div class="sans flex flex-wrap items-center gap-x-4 gap-y-1 text-sm text-stone-600 py-3 border-y border-stone-300">
                        <span>Oleh <strong class="text-stone-900">Humas SMKN 1 Bakam</strong></span>
                        <span class="text-stone-300">|</span>
                        <span>{{ $published->translatedFormat('l, j F Y') }}</span>
                        <span class="text-stone-300">|</span>
                        <span>⏱ {{ $news->reading_time }} menit baca</span>
                    </div>

                    <p class="kicker text-stone-900 mt-8 mb-3">Bakam, {{ $published->translatedFormat('j F Y') }} —</p>

                    {{-- flow-root: agar foto yang mengambang (float) tidak keluar dari kotak isi --}}
                    <div class="koran-body flow-root">
                        @if ($news->thumbnail)
                            <figure class="koran-photo">
                                <img src="{{ asset('storage/'.$news->thumbnail) }}" alt="{{ $news->title }}" loading="lazy">
                                <figcaption>Foto: Dokumentasi SMKN 1 Bakam</figcaption>
                            </figure>
                        @endif

                        {!! $news->content !!}
                    </div>

                    <p class="text-center text-stone-400 tracking-[0.5em] mt-10">❖ ❖ ❖</p>
                </article>

            {{-- ============ KOMENTAR ============ --}}
            <div class="mt-12 pt-8 border-t-4 border-double border-stone-900 sans">
                <h2 class="text-xl font-bold text-slate-900 mb-5">
                    💬 Komentar ({{ $news->approvedComments->count() }})
                </h2>

                @if ($news->approvedComments->isEmpty())
                    <div class="bg-white rounded-xl border border-slate-100 p-6 text-slate-400 text-sm mb-8">
                        Belum ada komentar. Jadilah yang pertama berkomentar!
                    </div>
                @else
                    <div class="space-y-4 mb-8">
                        @foreach ($news->approvedComments as $comment)
                            <div class="bg-white rounded-xl border border-slate-100 p-5">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="w-8 h-8 rounded-full bg-sky-100 text-sky-700 flex items-center justify-center font-bold text-sm">
                                        {{ strtoupper(substr($comment->nama_pengirim ?: 'A', 0, 1)) }}
                                    </span>
                                    <div>
                                        <p class="font-semibold text-slate-900 text-sm">{{ $comment->nama_pengirim ?: 'Anonim' }}</p>
                                        <p class="text-slate-400 text-xs">{{ $comment->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <p class="text-slate-700 text-sm">{{ $comment->isi_komentar }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- ============ FORM KOMENTAR ============
                     Submits go to storeComment() in NewsController, which always
                     saves as status=pending — nothing here appears publicly until
                     an admin approves it via Dashboard Admin (see perencanaan
                     doc §4.B "Moderasi Komentar Berita"). --}}
                <div class="bg-white rounded-2xl border border-slate-100 p-6 sm:p-8"
                     x-data="{ count: 0 }">
                    <p class="font-bold text-sky-900 mb-5">✍️ Tulis Komentar</p>

                    @if (session('success'))
                        <div class="mb-5 px-4 py-3 rounded-lg bg-emerald-50 text-emerald-700 text-sm">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('berita.comment', $news) }}" class="space-y-5">
                        @csrf

                        <div>
                            <label for="nama_pengirim" class="block text-xs font-bold text-slate-500 tracking-wide mb-2">
                                NAMA <span class="font-normal normal-case text-slate-400">(opsional — kosongkan untuk anonim)</span>
                            </label>
                            <input type="text" name="nama_pengirim" id="nama_pengirim" value="{{ old('nama_pengirim') }}"
                                   placeholder="Nama Anda atau biarkan kosong"
                                   class="w-full px-4 py-3 rounded-lg border border-slate-200 bg-slate-50 focus:bg-white focus:border-sky-500 focus:ring-1 focus:ring-sky-500 outline-none transition">
                            @error('nama_pengirim')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="isi_komentar" class="block text-xs font-bold text-slate-500 tracking-wide mb-2">
                                KOMENTAR <span class="text-red-500">*</span>
                            </label>
                            <textarea name="isi_komentar" id="isi_komentar" rows="4" maxlength="500" required
                                      @input="count = $event.target.value.length"
                                      placeholder="Tulis komentar atau pertanyaan Anda di sini..."
                                      class="w-full px-4 py-3 rounded-lg border border-slate-200 bg-slate-50 focus:bg-white focus:border-sky-500 focus:ring-1 focus:ring-sky-500 outline-none transition resize-y">{{ old('isi_komentar') }}</textarea>
                            <div class="flex items-center justify-between mt-1">
                                @error('isi_komentar')
                                    <p class="text-red-500 text-xs">{{ $message }}</p>
                                @else
                                    <span></span>
                                @enderror
                                <span class="text-slate-400 text-xs" x-text="count + '/500'">0/500</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between flex-wrap gap-3">
                            <p class="text-slate-400 text-xs flex items-center gap-1.5">
                                🔒 Komentar bersifat publik. Jaga kesopanan dan etika.
                            </p>
                            <button type="submit" class="px-6 py-3 rounded-lg bg-sky-700 text-white font-semibold hover:bg-sky-800 transition">
                                Kirim Komentar →
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            </div>

            {{-- ============ BACA JUGA ============ --}}
            <aside class="lg:col-span-4 lg:pl-10">
                <div class="lg:sticky lg:top-28">
                    <h2 class="kicker text-stone-900 border-t-4 border-double border-stone-900 pt-2 pb-1">Baca Juga</h2>
                    @forelse ($related as $item)
                        @include('berita._compact', ['item' => $item])
                    @empty
                        <p class="text-stone-500 text-sm py-4">Belum ada berita lain.</p>
                    @endforelse

                    <a href="{{ route('berita.index') }}"
                       class="kicker block text-center mt-6 border-2 border-stone-900 py-2.5 hover:bg-stone-900 hover:text-stone-50 transition">
                        Lihat Semua Berita »
                    </a>
                </div>
            </aside>

        </div>
    </div>
</div>

@endsection
