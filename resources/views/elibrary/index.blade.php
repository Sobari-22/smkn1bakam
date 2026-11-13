@extends('layouts.app')

@section('title', 'E-Library')

@push('styles')
<style>
    /* Styles the admin-editable HTML content from the `pages` table
       (see LibrarySeeder) so Visi/Misi render like the Figma design
       without the admin needing to hand-write Tailwind classes. */
    .prose-library blockquote[data-role="visi"] {
        @apply border-l-4 border-sky-600 bg-sky-50 rounded-r-lg px-5 py-4 my-2;
    }
    .prose-library blockquote[data-role="visi"] strong {
        @apply block text-sky-700 text-xs tracking-wide mb-2;
    }
    .prose-library blockquote[data-role="visi"] p {
        @apply italic text-slate-700 m-0;
    }
    .prose-library div[data-role="misi"] > strong {
        @apply block text-sky-700 text-xs tracking-wide mb-3;
    }
    .prose-library ol {
        @apply space-y-3 list-none pl-0 m-0;
    }
    .prose-library ol li {
        @apply flex items-start gap-3;
    }
    .prose-library ol {
        counter-reset: misi;
    }
    .prose-library ol li::before {
        counter-increment: misi;
        content: counter(misi);
        @apply w-6 h-6 rounded-full bg-sky-700 text-white text-xs font-bold flex items-center justify-center shrink-0 mt-0.5;
    }
</style>
@endpush

@section('content')

{{-- ============ HERO ============ --}}
<section class="bg-gradient-to-br from-sky-800 to-sky-600 py-20 relative overflow-hidden">
    <div class="absolute -right-20 -bottom-20 w-96 h-96 rounded-full bg-white/5"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <p class="text-sky-200 font-semibold text-sm tracking-wide mb-3">SMKN 1 BAKAM</p>
        <h1 class="text-4xl sm:text-5xl font-extrabold text-white mb-5">E-Library</h1>
        <p class="text-sky-100 max-w-2xl">
            Katalog koleksi perpustakaan SMKN 1 Bakam — temukan buku referensi sesuai program keahlian Anda.
        </p>
    </div>
</section>

{{-- ============ TENTANG PERPUSTAKAAN ============ --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid lg:grid-cols-2 gap-0 rounded-2xl overflow-hidden border border-slate-200">
        {{-- Foto + caption card --}}
        <div>
            <div class="h-72 lg:h-full bg-cover bg-center" style="background-image:url('/images/perpustakaan.jpg')"></div>
        </div>
        <div class="bg-sky-800 text-white px-6 py-4 lg:hidden flex items-center gap-3">
            <span class="text-2xl">📚</span>
            <div>
                <p class="font-semibold text-sm">Perpustakaan SMKN 1 Bakam</p>
                <p class="text-sky-200 text-xs">Koleksi {{ $stats['titles'] }}+ judul buku</p>
            </div>
        </div>

        {{-- Content --}}
        <div class="p-8 lg:p-10 bg-slate-50">
            <p class="text-sky-600 font-semibold text-xs tracking-wide mb-2">TENTANG PERPUSTAKAAN</p>
            <h2 class="text-3xl font-bold text-slate-900 mb-5">
                {{ $libraryProfile->title ?? 'Pusat Sumber Belajar' }}
            </h2>

            @if ($libraryProfile)
                <div class="prose-library text-slate-600 space-y-5">
                    {!! $libraryProfile->content !!}
                </div>
            @endif
        </div>
    </div>

    {{-- Stat cards --}}
    <div class="grid sm:grid-cols-3 gap-4 mt-8">
        <div class="bg-slate-50 rounded-xl p-6 text-center border border-slate-100">
            <p class="text-3xl font-bold text-sky-800">{{ $stats['titles'] }}+</p>
            <p class="text-slate-500 text-sm mt-1">Judul Buku</p>
        </div>
        <div class="bg-slate-50 rounded-xl p-6 text-center border border-slate-100">
            <p class="text-3xl font-bold text-sky-800">{{ $stats['copies'] }}+</p>
            <p class="text-slate-500 text-sm mt-1">Total Eksemplar</p>
        </div>
        <div class="bg-slate-50 rounded-xl p-6 text-center border border-slate-100">
            <p class="text-3xl font-bold text-sky-800">{{ \App\Models\Setting::get('library_hours', '08.00–14.00') }}</p>
            <p class="text-slate-500 text-sm mt-1">Jam Buka (Sen–Sab)</p>
        </div>
    </div>
</section>

{{-- ============ KATALOG BUKU ============ --}}
<section class="bg-slate-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
            <div>
                <p class="text-sky-600 font-semibold text-xs tracking-wide mb-2">KATALOG FISIK</p>
                <h2 class="text-3xl font-bold text-slate-900">Daftar Koleksi Buku</h2>
            </div>
            <form method="GET" class="w-full sm:w-80">
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">🔍</span>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul atau pengarang..."
                           class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-200 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>
            </form>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-sky-800 text-white text-left">
                            <th class="px-5 py-3.5 font-semibold whitespace-nowrap">No. Induk</th>
                            <th class="px-5 py-3.5 font-semibold whitespace-nowrap">Judul Buku</th>
                            <th class="px-5 py-3.5 font-semibold whitespace-nowrap">Pengarang</th>
                            <th class="px-5 py-3.5 font-semibold whitespace-nowrap">Penerbit</th>
                            <th class="px-5 py-3.5 font-semibold whitespace-nowrap">Tahun</th>
                            <th class="px-5 py-3.5 font-semibold whitespace-nowrap">Klasifikasi</th>
                            <th class="px-5 py-3.5 font-semibold whitespace-nowrap">Stok</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($books as $index => $book)
                            <tr class="{{ $index % 2 === 1 ? 'bg-slate-50/60' : '' }}">
                                <td class="px-5 py-3.5 text-slate-400">{{ $book->no_induk_buku }}</td>
                                <td class="px-5 py-3.5 font-semibold text-slate-900">{{ $book->judul }}</td>
                                <td class="px-5 py-3.5 text-slate-600">{{ $book->pengarang }}</td>
                                <td class="px-5 py-3.5 text-slate-500">{{ $book->penerbit }}</td>
                                <td class="px-5 py-3.5 text-slate-500">{{ $book->tahun_terbit }}</td>
                                <td class="px-5 py-3.5 text-slate-500">{{ $book->klasifikasi }}</td>
                                <td class="px-5 py-3.5">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                                        {{ $book->jumlah >= 4 ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                        {{ $book->jumlah }} eks.
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-10 text-center text-slate-400">
                                    Tidak ada buku yang cocok dengan pencarian "{{ request('q') }}".
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-5 py-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-2 text-sm text-slate-500">
                <span>Menampilkan {{ $books->count() }} dari {{ $books->total() }} judul</span>
                <span>Katalog diperbarui: {{ now()->translatedFormat('F Y') }}</span>
            </div>
        </div>

        @if ($books->hasPages())
            <div class="mt-6">{{ $books->links() }}</div>
        @endif

        <div class="mt-6 bg-sky-50 border border-sky-100 rounded-xl px-5 py-4 flex items-start gap-3 text-sm text-sky-900">
            <span class="text-sky-500 mt-0.5">ℹ️</span>
            <p>
                Katalog ini menampilkan koleksi buku fisik perpustakaan. Untuk peminjaman, kunjungi perpustakaan sekolah pada jam operasional.
                <strong>Petugas: {{ \App\Models\Setting::get('library_officer', 'Petugas Perpustakaan') }}</strong>
            </p>
        </div>
    </div>
</section>

@endsection
