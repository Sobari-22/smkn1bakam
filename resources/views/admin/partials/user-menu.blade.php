{{-- Menu pengguna di pojok kanan atas navbar admin: profil, ubah password, keluar.
     Dipakai di layouts/admin.blade.php lewat @include('admin.partials.user-menu'). --}}
@php $menuUser = auth()->user(); @endphp

<div class="relative pl-3 border-l border-slate-200"
     x-data="{ open: false }"
     @keydown.escape.window="open = false">
    <button type="button" @click="open = !open" :aria-expanded="open" aria-haspopup="menu"
            class="flex items-center gap-2.5 rounded-lg px-1.5 py-1 hover:bg-slate-50">
        <span class="w-9 h-9 rounded-lg bg-sky-700 text-white flex items-center justify-center font-bold text-sm">
            {{ strtoupper(substr($menuUser->name ?? 'A', 0, 1)) }}
        </span>
        <div class="hidden sm:block text-left">
            <p class="text-sm font-semibold leading-tight">{{ $menuUser->name ?? 'Administrator' }}</p>
            <p class="text-xs text-slate-400">{{ $menuUser->roleLabel() }}</p>
        </div>
        <svg class="w-4 h-4 text-slate-400 hidden sm:block" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>
    </button>

    <div x-show="open" x-cloak x-transition.opacity @click.outside="open = false" role="menu"
         class="absolute right-0 mt-2 w-64 bg-white rounded-xl border border-slate-200 shadow-lg z-50 overflow-hidden">
        <div class="px-4 py-3 border-b border-slate-100">
            <p class="text-sm font-semibold text-slate-900">{{ $menuUser->name }}</p>
            <p class="text-xs text-slate-400 truncate">{{ $menuUser->email }}</p>
        </div>

        <a href="{{ route('admin.profile.edit') }}" role="menuitem"
           class="block px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50">Profil &amp; Ganti Password</a>

        <form method="POST" action="{{ route('logout') }}" class="border-t border-slate-100">
            @csrf
            <button type="submit" role="menuitem"
                    class="w-full text-left px-4 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50">Keluar</button>
        </form>
    </div>
</div>
