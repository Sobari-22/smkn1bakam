<footer class="bg-slate-900 text-slate-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">

        <div>
            <div class="flex items-center gap-3 mb-3">
                <span class="w-11 h-11 rounded-lg bg-sky-700 text-white flex items-center justify-center font-bold text-lg">S</span>
                <div>
                    <span class="block font-bold text-white">SMKN 1 Bakam</span>
                    <span class="block text-xs text-slate-400">Terakreditasi B</span>
                </div>
            </div>
            <p class="text-sm text-slate-400 leading-relaxed">
                Mendidik generasi vokasi unggul, siap kerja, dan berkarakter Pancasila untuk kemajuan Bangka Belitung.
            </p>
        </div>

        <div>
            <h3 class="text-white font-semibold mb-3">Sekolah</h3>
            <ul class="space-y-2 text-sm">
                <li><a href="{{ route('profil') }}" class="hover:text-white">Profil Sekolah</a></li>
                <li><a href="{{ route('program.index') }}" class="hover:text-white">Program Keahlian</a></li>
                <li><a href="{{ route('berita.index') }}" class="hover:text-white">Berita</a></li>
                <li><a href="{{ route('galeri') }}" class="hover:text-white">Galeri</a></li>
            </ul>
        </div>

        <div>
            <h3 class="text-white font-semibold mb-3">Layanan</h3>
            <ul class="space-y-2 text-sm">
                <li><a href="{{ route('ppdb.create') }}" class="hover:text-white">SPMB Online</a></li>
                <li><a href="{{ route('ppdb.check-status') }}" class="hover:text-white">Cek Status SPMB</a></li>
                <li><a href="{{ route('elibrary.index') }}" class="hover:text-white">E-Library</a></li>
                <li><a href="{{ route('bkk') }}" class="hover:text-white">BKK</a></li>
                <li><a href="{{ route('kontak') }}" class="hover:text-white">Kontak & Lokasi</a></li>
            </ul>
        </div>

        <div>
            <h3 class="text-white font-semibold mb-3">Kontak</h3>
            <ul class="space-y-3 text-sm">
                <li class="flex gap-2"><span>📍</span> <span>{{ \App\Models\Setting::get('school_address', 'Jl. Raya Bakam, Kec. Bakam, Kab. Bangka, Kep. Bangka Belitung') }}</span></li>
                <li class="flex gap-2"><span>📞</span> <span>{{ \App\Models\Setting::get('school_phone', '(+62 812-7393-587') }}</span></li>
                <li class="flex gap-2"><span>✉️</span> <span>{{ \App\Models\Setting::get('school_email', 'info@smkn1bakam.sch.id') }}</span></li>
                <li class="flex gap-2"><span>🕒</span> <span>{{ \App\Models\Setting::get('school_hours', 'Senin–Jumat, 07.15–16.10 WIB') }}</span></li>
            </ul>
        </div>
    </div>

    <div class="border-t border-slate-800 py-5 text-center text-xs text-slate-500 px-4">
        &copy; {{ now()->year }} SMKN 1 Bakam — Kementerian Pendidikan, Kebudayaan, Riset, dan Teknologi RI. Website dikembangkan untuk layanan publik.
    </div>
</footer>
