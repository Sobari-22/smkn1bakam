{{--
    Isian form Guru & Staf (dipakai form tambah dan form edit).
    Variabel: $teacher (Teacher|null), $bagName ('new' atau 'teacher-{id}'),
              $concentrations, $positionDefs, $positionGroups, $taken.
    Posisi di bagan dan jenis garis ditentukan dari jabatan (config/struktur.php),
    jadi admin tidak perlu mengisi urutan atau jenis garis.
--}}
@php
    $bag = $errors->getBag($bagName);
    $mine = old('_form') === $bagName;
    $val = fn (string $field, $default = null) => $mine ? old($field, $default) : $default;

    $initSubjects = $mine ? array_values((array) old('subjects', [])) : ($teacher ? $teacher->subject_list : []);
    $initPositions = $mine
        ? collect((array) old('positions', []))->map(fn ($k) => ['k' => $k, 'c' => (string) old('position_classes.'.$k, '')])->values()->all()
        : ($teacher ? $teacher->positions->map(fn ($p) => ['k' => $p->position_key, 'c' => (string) $p->class_label])->values()->all() : []);
    $initConc = $mine ? array_map('intval', (array) old('concentrations', [])) : ($teacher ? $teacher->concentrations->pluck('id')->all() : []);
    $initAll = $mine ? (bool) old('teaches_all_programs') : (bool) ($teacher->teaches_all_programs ?? false);
    $initActive = $mine ? (bool) old('is_active') : ($teacher ? (bool) $teacher->is_active : true);

    $defsJs = collect($positionDefs)->map(fn ($d) => [
        'label' => $d['label'], 'place' => $d['place'], 'line' => $d['line'],
        'teaches' => $d['teaches'] ?? true, 'class' => $d['class'] ?? false,
    ]);
@endphp

<div class="space-y-5"
     x-data="{
        subjects: @js($initSubjects), draft: '', warn: '',
        chosen: @js($initPositions), all: @js($initAll),
        defs: @js($defsJs), taken: @js($taken), selfId: @js($teacher?->id),
        addSubject() {
            const v = this.draft.trim(); this.warn = '';
            if (!v) return;
            if (this.subjects.some(s => s.toLowerCase() === v.toLowerCase())) { this.warn = 'Mata pelajaran itu sudah ada.'; return; }
            if (this.subjects.length >= 10) { this.warn = 'Maksimal 10 mata pelajaran.'; return; }
            this.subjects.push(v.substring(0, 60)); this.draft = '';
        },
        addPosition(e) {
            const k = e.target.value;
            if (k && !this.chosen.some(c => c.k === k)) this.chosen.push({ k: k, c: '' });
            e.target.value = '';
        },
        isTaken(k) { return !!this.taken[k] && this.taken[k].id !== this.selfId; },
        get noTeach() { return this.chosen.some(c => this.defs[c.k] && this.defs[c.k].teaches === false); }
     }">

    <input type="hidden" name="_form" value="{{ $bagName }}">

    @if ($bag->any())
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm">
            <ul class="list-disc pl-4 space-y-0.5">
                @foreach ($bag->all() as $message)
                    <li>{{ $message }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid sm:grid-cols-2 gap-5">
        <div>
            <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">NAMA LENGKAP</label>
            <input type="text" name="name" value="{{ $val('name', $teacher?->name) }}" placeholder="Nama lengkap beserta gelar"
                   class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500">
        </div>
        <div>
            <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">NIP</label>
            <input type="text" name="nip" value="{{ $val('nip', $teacher?->nip) }}" placeholder="Nomor Induk Pegawai (opsional)"
                   class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500">
        </div>
        <div>
            <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">TMT (TANGGAL MULAI TUGAS)</label>
            <input type="date" name="tmt" value="{{ $val('tmt', $teacher?->tmt?->format('Y-m-d')) }}"
                   class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500">
        </div>
        <div>
            <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">FOTO {{ $teacher?->photo ? '(kosongkan bila tidak diganti)' : '(opsional)' }}</label>
            <input type="file" name="photo" accept="image/*"
                   class="w-full text-sm text-slate-600 file:mr-4 file:px-4 file:py-2.5 file:rounded-lg file:border-0 file:bg-slate-100 file:text-slate-700 file:font-semibold hover:file:bg-slate-200 border border-slate-200 rounded-lg">
            <p class="text-slate-400 text-xs mt-1">JPG/PNG/WEBP, maks. 2MB</p>
        </div>
    </div>

    {{-- ============ JABATAN ============ --}}
    <div>
        <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">JABATAN DI STRUKTUR ORGANISASI</label>
        <select @change="addPosition($event)"
                class="w-full px-4 py-3 rounded-lg border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-sky-500">
            <option value="">Tambah jabatan...</option>
            @foreach ($positionGroups as $group => $items)
                <optgroup label="{{ $group }}">
                    @foreach ($items as $def)
                        <option value="{{ $def['key'] }}"
                                :disabled="chosen.some(c => c.k === '{{ $def['key'] }}') || isTaken('{{ $def['key'] }}')"
                                x-text="'{{ $def['label'] }}' + (isTaken('{{ $def['key'] }}') ? ' (dipegang ' + taken['{{ $def['key'] }}'].name + ')' : '')">{{ $def['label'] }}</option>
                    @endforeach
                </optgroup>
            @endforeach
        </select>
        <p class="text-slate-400 text-xs mt-1">Boleh lebih dari satu. Posisi dan jenis garis di bagan terisi otomatis dari jabatan yang dipilih. Kosongkan bila tidak punya jabatan struktural.</p>

        <div class="mt-3 space-y-2">
            <template x-for="(c, i) in chosen" :key="c.k">
                <div class="flex items-start gap-3 bg-slate-50 rounded-lg px-4 py-3">
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-sm text-slate-900" x-text="defs[c.k] ? defs[c.k].label : c.k"></p>
                        <p class="text-xs text-slate-500 mt-0.5" x-text="defs[c.k] ? 'Posisi di bagan: ' + defs[c.k].place + ' · garis ' + defs[c.k].line : ''"></p>
                        <input type="text" x-show="defs[c.k] && defs[c.k].class" x-model="c.c" maxlength="50" placeholder="Kelas, contoh: XI TKJ 1"
                               class="mt-2 w-full max-w-[14rem] px-3 py-2 text-sm rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500">
                    </div>
                    <button type="button" @click="chosen.splice(i, 1)" aria-label="Hapus jabatan" class="text-slate-400 hover:text-red-500 text-lg leading-none px-1">✕</button>
                </div>
            </template>
        </div>

        <div class="hidden">
            <template x-for="c in chosen" :key="c.k">
                <div>
                    <input type="hidden" name="positions[]" :value="c.k">
                    <input type="hidden" :name="'position_classes[' + c.k + ']'" :value="c.c">
                </div>
            </template>
        </div>
    </div>

    {{-- ============ MENGAJAR (disembunyikan untuk Kepala sekolah / tata usaha) ============ --}}
    <p x-show="noTeach" x-cloak class="text-xs text-slate-500 bg-slate-50 rounded-lg px-4 py-3">
        Jabatan ini tidak mengajar, jadi mata pelajaran dan "mengajar di" tidak perlu diisi.
    </p>

    <div x-show="!noTeach" class="grid sm:grid-cols-2 gap-5">
        <div>
            <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">MATA PELAJARAN</label>
            <div class="flex flex-wrap items-center gap-2 px-2.5 py-2 min-h-[46px] rounded-lg border border-slate-200 focus-within:ring-2 focus-within:ring-sky-500">
                <template x-for="(s, i) in subjects" :key="s">
                    <span class="inline-flex items-center gap-1.5 bg-slate-100 text-slate-700 rounded-full pl-3 pr-1.5 py-1 text-xs font-medium">
                        <span x-text="s"></span>
                        <button type="button" @click="subjects.splice(i, 1)" aria-label="Hapus" class="text-slate-400 hover:text-slate-700 px-1">✕</button>
                    </span>
                </template>
                <input type="text" x-model="draft" maxlength="60" placeholder="Ketik lalu tekan Enter"
                       @keydown.enter.prevent="addSubject()"
                       @keydown="if ($event.key === ',') { $event.preventDefault(); addSubject(); }"
                       @keydown.backspace="if (!draft && subjects.length) subjects.pop()"
                       @blur="addSubject()"
                       class="flex-1 min-w-[9rem] px-1 py-1 text-sm focus:outline-none bg-transparent">
            </div>
            <p x-show="warn" x-text="warn" x-cloak class="text-red-500 text-xs mt-1"></p>
            <p class="text-slate-400 text-xs mt-1">Boleh lebih dari satu. Tekan Enter atau koma untuk menambah.</p>
            <div class="hidden">
                <template x-for="s in subjects" :key="s">
                    <input type="hidden" name="subjects[]" :value="s">
                </template>
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">MENGAJAR DI</label>
            <div class="flex flex-wrap gap-x-5 gap-y-2 pt-2">
                @foreach ($concentrations as $c)
                    <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer" :class="all ? 'opacity-50' : ''">
                        <input type="checkbox" name="concentrations[]" value="{{ $c->id }}" :disabled="all" @checked(in_array($c->id, $initConc)) class="w-4 h-4 rounded text-sky-600">
                        {{ $c->name }}
                    </label>
                @endforeach
                <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer">
                    <input type="checkbox" name="teaches_all_programs" value="1" x-model="all" class="w-4 h-4 rounded text-sky-600">
                    Semua program
                </label>
            </div>
            <p class="text-slate-400 text-xs mt-1">Pilih "Semua program" untuk mata pelajaran umum.</p>
        </div>
    </div>

    <div>
        <input type="hidden" name="is_active" value="0">
        <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer">
            <input type="checkbox" name="is_active" value="1" @checked($initActive) class="w-4 h-4 rounded text-sky-600">
            Status aktif
        </label>
    </div>
</div>
