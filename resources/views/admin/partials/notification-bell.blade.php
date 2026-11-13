{{-- Lonceng notifikasi navbar admin: komentar menunggu moderasi + pesan belum dibaca.
     Dipakai di layouts/admin.blade.php lewat @include('admin.partials.notification-bell'). --}}
@php
    $bellUser = auth()->user();
    $pendingComments = 0;
    $unreadMessages = 0;
    $pendingRegistrants = 0;
    $registrants = collect();
    $comments = collect();
    $messages = collect();

    if ($bellUser && $bellUser->hasAccess('berita')) {
        $pendingQuery = \Illuminate\Support\Facades\DB::table('news_comments')->where('status', 'pending');
        $pendingComments = (clone $pendingQuery)->count();
        $comments = (clone $pendingQuery)->latest('created_at')->limit(3)->get();
    }

    if ($bellUser && $bellUser->hasAccess('pesan')) {
        $unreadQuery = \App\Models\ContactMessage::where('is_read', false);
        $unreadMessages = (clone $unreadQuery)->count();
        $messages = (clone $unreadQuery)->latest()->limit(3)->get();
    }

    // Pendaftar SPMB yang masuk dan belum diverifikasi. Nilai 'pending' harus sama
    // dengan status "Dalam Verifikasi" di tabel ppdb_registrants.
    if ($bellUser && $bellUser->hasAccess('ppdb')) {
        $registrantQuery = \App\Models\PpdbRegistrant::where('status', 'pending');
        $pendingRegistrants = (clone $registrantQuery)->count();
        $registrants = (clone $registrantQuery)->with('concentration')->latest()->limit(3)->get();
    }

    $total = $pendingRegistrants + $pendingComments + $unreadMessages;
@endphp

<details class="relative" id="notification-bell">
    <summary class="list-none cursor-pointer relative flex items-center justify-center w-9 h-9 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 [&::-webkit-details-marker]:hidden"
             aria-label="Notifikasi{{ $total > 0 ? ' (' . $total . ' baru)' : '' }}">
        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M6 8a6 6 0 1 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/>
            <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
        </svg>
        @if ($total > 0)
            <span class="absolute -top-1.5 -right-1.5 min-w-[1.25rem] h-5 px-1 rounded-full bg-red-600 text-white text-xs font-bold flex items-center justify-center">
                {{ $total > 99 ? '99+' : $total }}
            </span>
        @endif
    </summary>

    <div class="absolute right-0 mt-2 w-80 max-w-[calc(100vw-2rem)] bg-white rounded-xl border border-slate-200 shadow-lg z-50 overflow-hidden">
        <div class="px-4 py-3 border-b border-slate-100 font-bold text-slate-900">Notifikasi</div>

        @if ($total === 0)
            <p class="px-4 py-8 text-center text-sm text-slate-400">Tidak ada notifikasi baru.</p>
        @endif

        @if ($pendingRegistrants > 0)
            <div class="border-b border-slate-100">
                <p class="px-4 pt-3 pb-1 text-xs font-bold tracking-wide text-slate-400">
                    PENDAFTAR SPMB MENUNGGU VERIFIKASI ({{ $pendingRegistrants }})
                </p>
                <ul>
                    @foreach ($registrants as $r)
                        <li>
                            <a href="{{ route('admin.ppdb.index') }}" class="block px-4 py-2.5 hover:bg-slate-50">
                                <p class="text-sm font-semibold text-slate-800">{{ $r->full_name }}</p>
                                <p class="text-sm text-slate-500">{{ $r->registration_number }} · {{ $r->concentration->name ?? '-' }}</p>
                                @if ($r->created_at)
                                    <p class="text-xs text-slate-400 mt-0.5">{{ $r->created_at->diffForHumans() }}</p>
                                @endif
                            </a>
                        </li>
                    @endforeach
                </ul>
                <a href="{{ route('admin.ppdb.index') }}" class="block px-4 py-2.5 text-sm font-semibold text-sky-700 hover:bg-sky-50">Lihat semua pendaftar</a>
            </div>
        @endif

        @if ($pendingComments > 0)
            <div class="border-b border-slate-100">
                <p class="px-4 pt-3 pb-1 text-xs font-bold tracking-wide text-slate-400">
                    KOMENTAR MENUNGGU MODERASI ({{ $pendingComments }})
                </p>
                <ul>
                    @foreach ($comments as $c)
                        <li>
                            <a href="{{ route('admin.comments.index') }}" class="block px-4 py-2.5 hover:bg-slate-50">
                                <p class="text-sm font-semibold text-slate-800">{{ $c->nama_pengirim }}</p>
                                <p class="text-sm text-slate-500">{{ \Illuminate\Support\Str::limit($c->isi_komentar, 60) }}</p>
                                @if ($c->created_at)
                                    <p class="text-xs text-slate-400 mt-0.5">{{ \Illuminate\Support\Carbon::parse($c->created_at)->diffForHumans() }}</p>
                                @endif
                            </a>
                        </li>
                    @endforeach
                </ul>
                <a href="{{ route('admin.comments.index') }}" class="block px-4 py-2.5 text-sm font-semibold text-sky-700 hover:bg-sky-50">Lihat semua komentar</a>
            </div>
        @endif

        @if ($unreadMessages > 0)
            <div>
                <p class="px-4 pt-3 pb-1 text-xs font-bold tracking-wide text-slate-400">
                    PESAN BELUM DIBACA ({{ $unreadMessages }})
                </p>
                <ul>
                    @foreach ($messages as $m)
                        <li>
                            <a href="{{ route('admin.messages.index') }}" class="block px-4 py-2.5 hover:bg-slate-50">
                                <p class="text-sm font-semibold text-slate-800">{{ $m->name }}</p>
                                <p class="text-sm text-slate-500">{{ \Illuminate\Support\Str::limit($m->subject, 60) }}</p>
                                @if ($m->created_at)
                                    <p class="text-xs text-slate-400 mt-0.5">{{ \Illuminate\Support\Carbon::parse($m->created_at)->diffForHumans() }}</p>
                                @endif
                            </a>
                        </li>
                    @endforeach
                </ul>
                <a href="{{ route('admin.messages.index') }}" class="block px-4 py-2.5 text-sm font-semibold text-sky-700 hover:bg-sky-50">Lihat semua pesan</a>
            </div>
        @endif
    </div>
</details>

<script>
    // Tutup dropdown saat klik di luar lonceng atau menekan Escape.
    (function () {
        var el = document.getElementById('notification-bell');
        if (!el) return;
        document.addEventListener('click', function (e) {
            if (!el.contains(e.target)) el.removeAttribute('open');
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') el.removeAttribute('open');
        });
    })();
</script>