@extends('layouts.app')

@section('title', 'Profil Sekolah')

@section('content')

{{-- Root x-data holds the active tab, same interaction pattern as the
     concentration modal in program/index.blade.php. Tab param syncs to the
     URL hash so a direct link like /profil#struktur opens that tab. --}}
<div x-data="{
        person: null,
        tab: (window.location.hash || '#sejarah').replace('#', ''),
        tabs: ['sejarah', 'visi-misi', 'sambutan', 'struktur', 'sarana'],
        setTab(t) { this.tab = t; window.location.hash = t; }
     }">

    {{-- ============ HERO ============ --}}
    <section class="bg-gradient-to-br from-sky-800 to-sky-600 py-20 relative overflow-hidden">
        <div class="absolute -right-20 -bottom-20 w-96 h-96 rounded-full bg-white/5"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <p class="text-sky-200 font-semibold text-sm tracking-wide mb-3">SMKN 1 BAKAM</p>
            <h1 class="text-4xl sm:text-5xl font-extrabold text-white mb-5">Profil Sekolah</h1>
            <p class="text-sky-100 max-w-2xl">
                Mengenal SMKN 1 Bakam lebih dekat — sejarah, visi misi, dan kiprah kami dalam dunia pendidikan vokasi.
            </p>
        </div>
    </section>

    {{-- ============ TAB NAV ============ --}}
    <div class="bg-white border-b border-slate-200 sticky top-20 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center gap-6 sm:gap-8 text-sm text-slate-500 overflow-x-auto no-scrollbar">
            @php
                $tabLabels = [
                    'sejarah' => 'Sejarah',
                    'visi-misi' => 'Visi & Misi',
                    'sambutan' => 'Sambutan Kepala',
                    'struktur' => 'Struktur Organisasi',
                    'sarana' => 'Sarana & Prasarana',
                ];
            @endphp
            @foreach ($tabLabels as $key => $label)
                <button @click="setTab('{{ $key }}')"
                        :class="tab === '{{ $key }}' ? 'text-sky-700 font-semibold border-sky-700' : 'border-transparent hover:text-sky-600'"
                        class="whitespace-nowrap py-4 border-b-2 transition">
                    {{ $label }}
                </button>
            @endforeach
        </div>
    </div>

    <div class="bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">

            {{-- ============ SEJARAH ============ --}}
            <section x-show="tab === 'sejarah'" x-cloak x-transition.opacity>
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <div>
                        <!-- <p class="text-sky-600 font-semibold uppercase tracking-wide text-sm mb-2">Sejarah Singkat</p> -->
                        <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 mb-5 leading-tight">
                            {{ $pages['sejarah']->title ?? 'Perjalanan Panjang Sejak 2012' }}
                        </h2>
                        <div class="text-slate-600 leading-relaxed space-y-4">
                            @if (!empty($pages['sejarah']->content))
                                {!! $pages['sejarah']->content !!}
                            @else
                                <p>SMKN 1 Bakam didirikan pada tahun 2012 sebagai respons atas kebutuhan pendidikan vokasi di Kecamatan Bakam, Kabupaten Bangka Barat, Kepulauan Bangka Belitung. Sekolah ini hadir untuk memberikan akses pendidikan kejuruan berkualitas bagi putra-putri daerah tanpa harus merantau jauh.</p>
                                <p>Dimulai dengan dua Program Keahlian dan 80 siswa perdana, SMKN 1 Bakam terus berkembang hingga kini memiliki 2 Program Keahlian dengan 3 konsentrasi, 450+ siswa aktif, dan puluhan tenaga pendidik berpengalaman.</p>
                                <p>Sekolah ini berkomitmen menjadi pusat keunggulan vokasi di Bangka Barat, menghubungkan dunia pendidikan dengan dunia industri demi menciptakan lulusan yang langsung siap kerja.</p>
                            @endif
                        </div>
                    </div>
                    <div>
                        <div class="rounded-2xl overflow-hidden shadow-sm h-72 bg-slate-200">
                            <img src="{{ $pages['sejarah']->image_url ?? asset('images/placeholder-gedung.jpg') }}"
                                 alt="Gedung SMKN 1 Bakam" class="w-full h-full object-cover"
                                 onerror="this.style.display='none'">
                        </div>
                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <div class="bg-sky-50 rounded-xl p-5 text-center">
                                <p class="text-3xl font-extrabold text-sky-900">2012</p>
                                <p class="text-xs text-slate-500 mt-1">Tahun Berdiri</p>
                            </div>
                            <div class="bg-sky-50 rounded-xl p-5 text-center">
                                <p class="text-3xl font-extrabold text-sky-900">{{ now()->year - 2012 }}</p>
                                <p class="text-xs text-slate-500 mt-1">Tahun Pengabdian</p>
                            </div>
                            <div class="bg-sky-50 rounded-xl p-5 text-center">
                                <p class="text-3xl font-extrabold text-sky-900">2000+</p>
                                <p class="text-xs text-slate-500 mt-1">Alumni</p>
                            </div>
                            <div class="bg-sky-50 rounded-xl p-5 text-center">
                                <p class="text-3xl font-extrabold text-sky-900">32</p>
                                <p class="text-xs text-slate-500 mt-1">Mitra Industri</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- ============ VISI & MISI ============ --}}
            <section x-show="tab === 'visi-misi'" x-cloak x-transition.opacity>
                <div class="bg-gradient-to-br from-sky-800 to-sky-600 rounded-3xl p-10 md:p-14 text-center text-white max-w-4xl mx-auto mb-14">
                    <p class="uppercase tracking-widest text-xs text-sky-200 font-bold mb-4">Visi Sekolah</p>
                    <p class="italic text-xl md:text-2xl leading-relaxed">
                        "{{ $pages['visi-misi']->excerpt ?? 'Menjadi Sekolah Menengah Kejuruan unggulan yang menghasilkan lulusan kompeten, beriman, berakhlak mulia, berwawasan lingkungan, dan mampu bersaing di dunia kerja maupun berwirausaha.' }}"
                    </p>
                </div>

                @if (!empty($pages['visi-misi']->image_url))
                    <div class="max-w-4xl mx-auto mb-14 rounded-2xl overflow-hidden shadow-sm bg-slate-200">
                        <img src="{{ $pages['visi-misi']->image_url }}" alt="Visi dan Misi SMKN 1 Bakam" class="w-full max-h-96 object-cover"
                             onerror="this.parentElement.style.display='none'">
                    </div>
                @endif

                <div class="max-w-4xl mx-auto">
                    <p class="text-sky-600 font-semibold uppercase tracking-wide text-sm mb-6 text-center">Misi Sekolah</p>
                    <div class="space-y-4">
                        @php
                            // Falls back to this default list if the admin hasn't filled in
                            // `pages.content` (expected as a newline-separated list) yet.
                            $misiItems = !empty($pages['visi-misi']->content)
                                ? preg_split('/\r?\n/', trim($pages['visi-misi']->content))
                                : [
                                    'Menyelenggarakan pembelajaran vokasi yang berkualitas, inovatif, dan relevan dengan kebutuhan industri.',
                                    'Membentuk karakter siswa yang beriman, disiplin, jujur, dan bertanggung jawab.',
                                    'Membangun kemitraan strategis dengan dunia usaha dan dunia industri (DUDI) untuk peningkatan kompetensi.',
                                    'Menyiapkan lulusan yang kompeten, berdaya saing, dan siap memasuki pasar kerja nasional maupun internasional.',
                                    'Mengembangkan jiwa wirausaha berbasis potensi lokal Bangka Belitung.',
                                    'Mewujudkan sekolah yang berwawasan lingkungan dan berbudaya mutu.',
                                ];
                        @endphp
                        @foreach ($misiItems as $i => $misi)
                            <div class="flex gap-4 items-start bg-white rounded-xl p-5 shadow-sm border border-slate-100">
                                <span class="w-8 h-8 shrink-0 rounded-full bg-sky-700 text-white flex items-center justify-center font-bold text-sm">{{ $i + 1 }}</span>
                                <p class="text-slate-700 pt-1">{{ $misi }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            {{-- ============ SAMBUTAN KEPALA ============ --}}
            <section x-show="tab === 'sambutan'" x-cloak x-transition.opacity>
                <div class="grid md:grid-cols-[280px_1fr] gap-12">
                    <div>
                        <div class="rounded-2xl overflow-hidden shadow-sm h-72 bg-slate-200">
                            <img src="{{ $pages['sambutan-kepala']->image_url ?? asset('images/placeholder-kepsek.jpg') }}"
                                 alt="Kepala SMKN 1 Bakam" class="w-full h-full object-cover"
                                 onerror="this.style.display='none'">
                        </div>
                        <p class="font-bold text-sky-900 mt-4 text-lg">{{ $pages['sambutan-kepala']->title ?? 'Drs. Ahmad Fauzie, M.Pd.' }}</p>
                        <p class="text-slate-500 text-sm">Kepala SMKN 1 Bakam</p>
                    </div>
                    <div>
                        <p class="text-sky-600 font-semibold uppercase tracking-wide text-sm mb-2">Sambutan Kepala Sekolah</p>
                        <h2 class="text-3xl font-bold text-slate-900 mb-5">Assalamu'alaikum Wr. Wb.</h2>
                        <div class="text-slate-600 leading-relaxed space-y-4">
                            @if (!empty($pages['sambutan-kepala']->content))
                                {!! $pages['sambutan-kepala']->content !!}
                            @else
                                <p>Puji syukur kehadirat Allah SWT atas segala limpahan rahmat dan hidayah-Nya sehingga SMKN 1 Bakam terus tumbuh dan berkembang menjadi lembaga pendidikan vokasi yang semakin dipercaya oleh masyarakat Bangka Barat.</p>
                                <p>Pendidikan vokasi adalah tulang punggung kemajuan industri bangsa. Di SMKN 1 Bakam, kami tidak hanya mengajarkan keterampilan teknis, tetapi juga membentuk karakter — kejujuran, kedisiplinan, dan semangat pantang menyerah — yang merupakan bekal utama dalam kehidupan nyata.</p>
                                <p>Kami mengundang putra-putri terbaik Bangka Barat untuk bergabung bersama kami. Bersama, kita wujudkan generasi vokasi yang unggul, berkarakter, dan siap membangun daerah serta bangsa tercinta.</p>
                                <p class="italic">Wassalamu'alaikum Wr. Wb.</p>
                            @endif
                        </div>
                        <div class="mt-6 bg-sky-50 border-l-4 border-sky-700 rounded-r-lg px-5 py-4">
                            <p class="font-semibold text-sky-900">{{ $pages['sambutan-kepala']->title ?? 'Drs. Ahmad Fauzie, M.Pd.' }}</p>
                            <p class="text-sm text-slate-500">Kepala SMKN 1 Bakam</p>
                        </div>
                    </div>
                </div>
            </section>

            <section x-show="tab === 'struktur'" x-cloak x-transition.opacity>
                 @include('partials.struktur-organisasi')
            </section>

            {{-- ============ SARANA & PRASARANA ============
                 Dikelola superadmin di Dashboard Admin > Sarana & Prasarana. --}}
            <section x-show="tab === 'sarana'" x-cloak x-transition.opacity class="text-center">
                <h2 class="text-3xl font-bold text-slate-900 mb-2">Sarana & Prasarana</h2>
                <p class="text-slate-500 mb-10">Fasilitas pendukung pembelajaran yang terus ditingkatkan</p>

                @if (empty($facilities) || $facilities->isEmpty())
                    <p class="text-slate-400">Data sarana dan prasarana belum tersedia.</p>
                @else
                    <div class="grid sm:grid-cols-2 md:grid-cols-4 gap-5 text-left">
                        @foreach ($facilities as $item)
                            <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
                                @if ($item->image_url)
                                    <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="w-full h-36 object-cover" loading="lazy"
                                         onerror="this.style.display='none'">
                                @endif
                                <div class="p-5">
                                    @if ($item->icon && !$item->image_url)
                                        <p class="text-2xl mb-2">{{ $item->icon }}</p>
                                    @endif
                                    <p class="font-semibold text-slate-900">
                                        @if ($item->icon && $item->image_url) <span class="mr-1">{{ $item->icon }}</span> @endif{{ $item->name }}
                                    </p>
                                    @if ($item->qty)
                                        <p class="text-xs text-sky-700 font-medium mb-2">{{ $item->qty }}</p>
                                    @endif
                                    @if ($item->description)
                                        <p class="text-sm text-slate-500">{{ $item->description }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>

        </div>
    </div>
</div>
@endsection
