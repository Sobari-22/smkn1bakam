@extends('layouts.admin')

@section('title', 'Pesan Masuk')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-900">Pesan Masuk</h1>
    <p class="text-slate-400 text-sm mt-1">Pesan yang dikirim pengunjung lewat formulir halaman Kontak</p>
</div>

@if (session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl px-5 py-4 text-sm">{{ session('success') }}</div>
@endif

<div class="grid grid-cols-2 gap-5 mb-6 max-w-xl">
    <div class="bg-white rounded-xl border-t-4 border-t-sky-500 border-x border-b border-slate-100 shadow-sm p-6 text-center">
        <p class="text-3xl font-bold text-sky-900">{{ $stats['total'] }}</p>
        <p class="text-slate-500 text-sm mt-1">Total Pesan</p>
    </div>
    <div class="bg-white rounded-xl border-t-4 border-t-red-500 border-x border-b border-slate-100 shadow-sm p-6 text-center">
        <p class="text-3xl font-bold text-red-500">{{ $stats['unread'] }}</p>
        <p class="text-slate-500 text-sm mt-1">Belum Dibaca</p>
    </div>
</div>

<div class="flex gap-2 mb-5">
    <a href="{{ route('admin.messages.index') }}"
       class="px-4 py-2 rounded-lg text-sm font-semibold {{ $filter === 'all' ? 'bg-sky-700 text-white' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">Semua</a>
    <a href="{{ route('admin.messages.index', ['filter' => 'unread']) }}"
       class="px-4 py-2 rounded-lg text-sm font-semibold {{ $filter === 'unread' ? 'bg-sky-700 text-white' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">Belum Dibaca</a>
</div>

<div class="space-y-4">
    @forelse ($messages as $item)
        <div x-data="{ open: false, read: {{ $item->is_read ? 'true' : 'false' }} }"
             class="bg-white rounded-xl shadow-sm border-l-4 transition"
             :class="read ? 'border-slate-200' : 'border-sky-500'">

            {{-- Ringkasan (klik untuk buka) --}}
            <button type="button" class="w-full text-left p-5 flex items-start justify-between gap-4"
                    @click="
                        open = !open;
                        if (open && !read) {
                            fetch('{{ route('admin.messages.read', $item) }}', {
                                method: 'PATCH',
                                headers: {
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                },
                            }).then(r => { if (r.ok) read = true; });
                        }
                    ">
                <div class="min-w-0">
                    <p class="text-slate-400 text-xs mb-1">
                        {{ $item->created_at->translatedFormat('j M Y, H.i') }}
                    </p>
                    <p class="text-slate-900" :class="read ? 'font-semibold' : 'font-bold'">{{ $item->subject }}</p>
                    <p class="text-slate-500 text-sm mt-0.5">
                        {{ $item->name }} · {{ $item->email }}
                    </p>
                    <p x-show="!open" class="text-slate-400 text-sm mt-2 truncate">{{ \Illuminate\Support\Str::limit($item->message, 120) }}</p>
                </div>
                <div class="shrink-0 flex items-center gap-2">
                    <span x-show="!read" x-cloak class="text-[11px] font-bold bg-sky-100 text-sky-700 px-2 py-1 rounded-full">BARU</span>
                    <span class="text-slate-400 text-xs" x-text="open ? '▲' : '▼'"></span>
                </div>
            </button>

            {{-- Isi pesan --}}
            <div x-show="open" x-cloak class="px-5 pb-5 border-t border-slate-100 pt-4">
                <p class="text-slate-700 text-sm whitespace-pre-line mb-5">{{ $item->message }}</p>

                <div class="flex flex-wrap gap-2">
                    <a href="mailto:{{ $item->email }}?subject={{ rawurlencode('Re: '.$item->subject) }}"
                       class="px-4 py-2 rounded-lg bg-sky-700 hover:bg-sky-800 text-white text-sm font-semibold">✉️ Balas via Email</a>

                    <form method="POST" action="{{ route('admin.messages.unread', $item) }}">
                        @csrf @method('PATCH')
                        <button type="submit" class="px-4 py-2 rounded-lg border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50">Tandai Belum Dibaca</button>
                    </form>

                    <form method="POST" action="{{ route('admin.messages.destroy', $item) }}"
                          onsubmit="return confirm('Hapus pesan ini? Tindakan ini tidak dapat dibatalkan.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="px-4 py-2 rounded-lg border-2 border-red-200 text-red-600 text-sm font-semibold hover:bg-red-50">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-xl border border-slate-100 p-10 text-center text-slate-400">
            {{ $filter === 'unread' ? 'Tidak ada pesan yang belum dibaca.' : 'Belum ada pesan masuk.' }}
        </div>
    @endforelse
</div>

<div class="mt-6">{{ $messages->links() }}</div>

@endsection
