{{--
    ============ STRUKTUR ORGANISASI (bagan) ============
    Susunan bagan mengikuti struktur organisasi sekolah dan ditetapkan di config/struktur.php
    (atasan, kolom, jenis garis). Data pemegang jabatan diinput di Dashboard Admin > Guru & Staf
    (tabel teacher_positions); orang dengan beberapa jabatan tampil di beberapa kotak.
--}}
@php
    $defs = config('struktur.positions');
    $static = config('struktur.static');

    $holders = \App\Models\TeacherPosition::with(['teacher.concentrations', 'teacher.positions'])
        ->whereHas('teacher', fn ($q) => $q->where('is_active', true))
        ->get()
        ->sortBy(fn ($h) => mb_strtolower($h->teacher->name))
        ->groupBy('position_key');

    $of = fn (string $key) => $holders->get($key, collect());

    // Data untuk jendela detail
    $payloadOf = function ($holder) {
        $t = $holder->teacher;

        return [
            'name' => $t->name,
            'positions' => $t->positions->map(fn ($p) => $p->label)->values()->all(),
            'nip' => $t->nip,
            'tmt' => $t->tmt ? $t->tmt->locale('id')->translatedFormat('j F Y') : null,
            'subjects' => $t->teaches ? $t->subject_list : [],
            'teaching' => $t->teaches ? $t->teaching_label : null,
            'photo' => $t->photo ? asset('storage/'.$t->photo) : null,
        ];
    };

    $keysWhere = fn (callable $filter) => collect($defs)->filter($filter)->keys();

    $wakasekKeys = collect($defs)->filter(fn ($d) => ($d['area'] ?? null) === 'wakasek')->sortBy('col')->keys();
    $unitKeysOf = fn (string $parent) => $keysWhere(fn ($d) => ($d['area'] ?? null) === 'unit' && ($d['parent'] ?? null) === $parent);
    $kkKeysOf = fn (string $col) => $keysWhere(fn ($d) => ($d['area'] ?? null) === 'kk' && ($d['col'] ?? null) === $col);

    $year = now()->year;
    $academicYear = now()->month >= 7 ? $year.'/'.($year + 1) : ($year - 1).'/'.$year;
@endphp

<div x-data="{ person: null }">

    <style>
        .org-static { background: #f1f5f9; border: 1px solid #cbd5e1; border-radius: .5rem; padding: .65rem .75rem; text-align: center; font-size: .8rem; font-weight: 700; color: #334155; }
        .org-top { display: flex; flex-direction: column; align-items: center; gap: .75rem; }
        .org-side, .org-center { width: 100%; max-width: 16rem; }
        .org-link { display: none; }
        .org-body { display: flex; flex-direction: column; gap: 1.25rem; margin-top: 1.25rem; }
        .org-tu { max-width: 16rem; margin: 0 auto; width: 100%; }
        .org-wk, .org-kk { display: grid; grid-template-columns: 1fr; gap: 1.5rem; }
        .org-unit { margin-top: .5rem; }
        .org-unit .org-gap { display: none; }
        .org-stack > * + * { margin-top: .5rem; }
        .org-dewan { max-width: 20rem; width: 100%; margin: 1.25rem auto 0; }

        /* Garis penghubung hanya di layar lebar; di layar kecil kotak disusun menurun */
        @media (min-width: 1024px) {
            .org-top { flex-direction: row; justify-content: center; gap: 0; }
            .org-side { width: 12rem; flex: none; }
            .org-center { width: 16rem; flex: none; }
            .org-link { display: block; flex: 1; max-width: 5rem; border-top: 1px dashed #94a3b8; }

            .org-body { position: relative; margin-top: 0; padding-top: 1.5rem; gap: 0; }
            .org-body::before { content: ''; position: absolute; left: 50%; top: 0; bottom: 0; width: 1px; background: #94a3b8; }

            .org-tu { display: grid; grid-template-columns: 1fr 1fr; max-width: none; margin: 0 0 2rem; }
            .org-tu-cell { position: relative; padding-left: 2rem; }
            .org-tu-cell::before { content: ''; position: absolute; left: 0; top: 1.1rem; width: 2rem; height: 1px; background: #94a3b8; }

            .org-wk { grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 0; }
            .org-col { position: relative; padding: 2rem .375rem 0; }
            .org-col::before { content: ''; position: absolute; top: 0; left: 50%; width: 1px; height: 2rem; background: #94a3b8; }
            .org-col::after { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 1px; background: #94a3b8; }
            .org-col:first-child::after { left: 50%; }
            .org-col:last-child::after { right: 50%; }
            .org-unit { margin-top: 0; }
            .org-unit .org-gap { display: block; width: 1px; height: .5rem; background: #94a3b8; margin: 0 auto; }

            .org-kk { grid-template-columns: 1fr 1fr; gap: 0; margin-top: 2.5rem; }
            .org-kkcol { position: relative; padding: 2rem 1.5rem 0; }
            .org-kkcol::before { content: ''; position: absolute; top: 0; left: 50%; height: 2rem; border-left: 1px dashed #94a3b8; }
            .org-kkcol::after { content: ''; position: absolute; top: 0; border-top: 1px dashed #94a3b8; }
            .org-kkcol:first-child::after { left: 50%; right: 0; }
            .org-kkcol:last-child::after { left: 0; right: 50%; }

            .org-dewan { position: relative; padding-top: 2rem; margin-top: 0; }
            .org-dewan::before { content: ''; position: absolute; top: 0; left: 50%; width: 1px; height: 2rem; background: #94a3b8; }
        }
    </style>

    <div class="text-center">
        <h2 class="text-3xl font-bold text-slate-900 mb-2">Struktur Organisasi</h2>
        <p class="text-slate-500 mb-2">Tahun Pelajaran {{ $academicYear }}</p>
        <p class="text-slate-400 text-sm mb-10">Klik nama untuk melihat detail</p>
    </div>

    @if ($holders->isEmpty())
        <p class="text-center text-slate-400">Struktur organisasi belum tersedia.</p>
    @else
        <div class="overflow-x-auto pb-4">
            <div class="mx-auto max-w-6xl">

                {{-- Puncak: Komite sekolah - Kepala sekolah - Institusi pasangan (garis koordinasi) --}}
                <div class="org-top">
                    <div class="org-side"><div class="org-static">{{ $static['left'] }}</div></div>
                    <div class="org-link"></div>
                    <div class="org-center">
                        @include('partials.struktur-node', ['title' => $defs['kepsek']['label'], 'holders' => $of('kepsek'), 'tone' => 'violet'])
                    </div>
                    <div class="org-link"></div>
                    <div class="org-side"><div class="org-static">{{ $static['right'] }}</div></div>
                </div>

                <div class="org-body">

                    {{-- Sub koordinator tata usaha --}}
                    <div class="org-tu">
                        <div class="hidden lg:block"></div>
                        <div class="org-tu-cell">
                            @include('partials.struktur-node', ['title' => $defs['tu']['label'], 'holders' => $of('tu'), 'tone' => 'slate'])
                        </div>
                    </div>

                    {{-- Wakil kepala sekolah beserta unit di bawahnya --}}
                    <div class="org-wk">
                        @foreach ($wakasekKeys as $wk)
                            <div class="org-col">
                                @include('partials.struktur-node', ['title' => $defs[$wk]['label'], 'holders' => $of($wk), 'tone' => 'violet'])

                                @foreach ($unitKeysOf($wk) as $unit)
                                    <div class="org-unit">
                                        <div class="org-gap"></div>
                                        @include('partials.struktur-node', ['title' => $defs[$unit]['label'], 'holders' => $of($unit), 'tone' => 'teal'])
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>

                    {{-- Kompetensi keahlian (garis koordinasi putus-putus) --}}
                    <div class="org-kk">
                        @foreach (['tkr' => 'TKR', 'tkj' => 'TKJ'] as $col => $colLabel)
                            <div class="org-kkcol">
                                <div class="org-stack">
                                    @foreach ($kkKeysOf($col) as $key)
                                        @include('partials.struktur-node', ['title' => $defs[$key]['label'], 'holders' => $of($key), 'tone' => 'teal'])
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Dewan guru / pembina ekstrakurikuler --}}
                <div class="org-dewan">
                    @include('partials.struktur-node', ['title' => $static['bottom'], 'holders' => $of('pembina_ekskul'), 'tone' => 'slate', 'emptyText' => 'Seluruh guru dan pembina ekstrakurikuler'])
                </div>
            </div>
        </div>

        <div class="flex flex-wrap items-center justify-center gap-x-8 gap-y-2 mt-8 text-xs text-slate-500">
            <span class="inline-flex items-center gap-2"><span class="inline-block w-8 border-t border-slate-400"></span> Garis komando</span>
            <span class="inline-flex items-center gap-2"><span class="inline-block w-8 border-t border-dashed border-slate-400"></span> Garis koordinasi</span>
        </div>
    @endif

    {{-- ============ JENDELA DETAIL PEGAWAI / GURU ============ --}}
    <div x-show="person" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 p-4"
         @click.self="person = null" @keydown.escape.window="person = null">
        <template x-if="person">
            <div class="bg-white rounded-2xl shadow-xl max-w-md w-full max-h-[90vh] overflow-y-auto">
                <div class="bg-gradient-to-br from-sky-800 to-sky-600 px-6 pt-8 pb-6 text-center">
                    <div class="w-28 h-28 mx-auto rounded-full bg-white/20 overflow-hidden flex items-center justify-center text-4xl text-white mb-4">
                        <template x-if="person.photo">
                            <img :src="person.photo" :alt="person.name" class="w-full h-full object-cover">
                        </template>
                        <template x-if="!person.photo"><span>👤</span></template>
                    </div>
                    <h3 class="text-xl font-bold text-white" x-text="person.name"></h3>
                    <div class="flex flex-wrap justify-center gap-1.5 mt-3">
                        <template x-for="p in person.positions" :key="p">
                            <span class="px-2.5 py-1 rounded-full bg-white/20 text-white text-xs font-semibold" x-text="p"></span>
                        </template>
                    </div>
                </div>

                <dl class="px-6 py-3 divide-y divide-slate-100 text-sm text-left">
                    <div x-show="person.nip" class="py-3 flex justify-between gap-4">
                        <dt class="text-slate-500">NIP</dt>
                        <dd class="font-medium text-slate-900 text-right" x-text="person.nip"></dd>
                    </div>
                    <div x-show="person.tmt" class="py-3 flex justify-between gap-4">
                        <dt class="text-slate-500">TMT</dt>
                        <dd class="font-medium text-slate-900 text-right" x-text="person.tmt"></dd>
                    </div>
                    <div x-show="person.subjects && person.subjects.length" class="py-3 flex justify-between gap-4">
                        <dt class="text-slate-500 shrink-0">Mata pelajaran</dt>
                        <dd class="font-medium text-slate-900 text-right" x-text="person.subjects.join(', ')"></dd>
                    </div>
                    <div x-show="person.teaching" class="py-3 flex justify-between gap-4">
                        <dt class="text-slate-500 shrink-0">Mengajar di</dt>
                        <dd class="font-medium text-slate-900 text-right" x-text="person.teaching"></dd>
                    </div>
                    <p x-show="!person.nip && !person.tmt && !(person.subjects && person.subjects.length) && !person.teaching"
                       class="py-4 text-slate-400 text-center">Informasi lainnya belum dilengkapi.</p>
                </dl>

                <div class="px-6 pb-6">
                    <button type="button" @click="person = null"
                            class="w-full py-2.5 rounded-lg border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50">Tutup</button>
                </div>
            </div>
        </template>
    </div>
</div>
