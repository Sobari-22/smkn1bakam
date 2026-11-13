@extends('layouts.app')

@section('title', 'Tracer Study Alumni')

@section('content')

<div x-data="{ tab: '{{ request('tab', 'survei') }}' }">

    {{-- ============ TAB SWITCHER ============ --}}
    <div class="bg-white border-b border-slate-200">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 flex gap-8">
            <button @click="tab = 'survei'"
                    :class="tab === 'survei' ? 'text-sky-700 border-sky-700' : 'text-slate-400 border-transparent'"
                    class="py-4 border-b-2 font-semibold text-sm flex items-center gap-2 transition">
                📝 Isi Survei Alumni
            </button>
            <button @click="tab = 'statistik'"
                    :class="tab === 'statistik' ? 'text-sky-700 border-sky-700' : 'text-slate-400 border-transparent'"
                    class="py-4 border-b-2 font-semibold text-sm flex items-center gap-2 transition">
                📊 Statistik Alumni
            </button>
        </div>
    </div>

    {{-- ============ TAB: ISI SURVEI ALUMNI ============ --}}
    <section x-show="tab === 'survei'" x-cloak class="bg-slate-50 py-16 min-h-[60vh]">
        <div class="max-w-2xl mx-auto px-4">

            @if (session('success'))
                <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl px-5 py-4 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div x-data="tracerForm()" class="bg-white rounded-2xl shadow-sm p-8">

                {{-- STEP 1: Verifikasi --}}
                <template x-if="step === 1">
                    <div>
                        <h2 class="text-2xl font-bold text-sky-900 mb-2">Verifikasi Data Alumni</h2>
                        <p class="text-slate-500 text-sm mb-6">Masukkan data berikut untuk memulai survei. Data ini digunakan untuk verifikasi kelulusan Anda.</p>

                        <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">NISN</label>
                        <input type="text" x-model="form.nisn" placeholder="10 digit NISN" maxlength="10"
                               class="w-full px-4 py-3 rounded-lg border border-slate-200 mb-5 focus:outline-none focus:ring-2 focus:ring-sky-500">

                        <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">Tahun Lulus</label>
                        <select x-model="form.tahun_lulus" class="w-full px-4 py-3 rounded-lg border border-slate-200 mb-5 focus:outline-none focus:ring-2 focus:ring-sky-500">
                            <option value="">-- Pilih Tahun --</option>
                            @for ($y = now()->year; $y >= now()->year - 10; $y--)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endfor
                        </select>

                        <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">Nama Lengkap</label>
                        <input type="text" x-model="form.alumni_nama" placeholder="Nama sesuai ijazah"
                               class="w-full px-4 py-3 rounded-lg border border-slate-200 mb-6 focus:outline-none focus:ring-2 focus:ring-sky-500">

                        <button @click="verify()" :disabled="verifying"
                                class="w-full py-3.5 rounded-lg bg-sky-700 hover:bg-sky-800 disabled:bg-slate-300 text-white font-semibold transition">
                            <span x-show="!verifying">Lanjut ke Survei →</span>
                            <span x-show="verifying">Memeriksa data...</span>
                        </button>
                        <p x-show="verifyMessage" x-text="verifyMessage" class="text-sky-600 text-sm text-center mt-3"></p>
                    </div>
                </template>

                {{-- STEP 2: Status kegiatan --}}
                <template x-if="step === 2">
                    <div>
                        <h2 class="text-2xl font-bold text-sky-900 mb-2">Status Kegiatan Saat Ini</h2>
                        <p class="text-slate-500 text-sm mb-6">Pilih salah satu yang paling sesuai dengan kondisi Anda sekarang.</p>

                        <div class="grid grid-cols-2 gap-3 mb-6">
                            <template x-for="opt in statusOptions" :key="opt.value">
                                <button @click="form.status_kegiatan = opt.value; step = 3"
                                        :class="form.status_kegiatan === opt.value ? 'border-sky-600 bg-sky-50' : 'border-slate-200'"
                                        class="border-2 rounded-xl p-4 text-left hover:border-sky-400 transition">
                                    <span class="text-2xl block mb-1" x-text="opt.icon"></span>
                                    <span class="text-sm font-semibold text-slate-800" x-text="opt.label"></span>
                                </button>
                            </template>
                        </div>
                        <button @click="step = 1" class="text-slate-400 text-sm hover:text-slate-600">← Kembali</button>
                    </div>
                </template>

                {{-- STEP 3: Field dinamis + kontak + evaluasi + submit --}}
                <template x-if="step === 3">
                    <div>
                        <h2 class="text-2xl font-bold text-sky-900 mb-6" x-text="statusLabel(form.status_kegiatan)"></h2>

                        {{-- Bekerja / Bekerja sambil Kuliah --}}
                        <template x-if="['bekerja','bekerja_kuliah'].includes(form.status_kegiatan)">
                            <div class="space-y-4 mb-6">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">Nama Instansi/Perusahaan</label>
                                    <input type="text" x-model="form.nama_instansi" class="w-full px-4 py-3 rounded-lg border border-slate-200">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">Bidang/Sektor Usaha</label>
                                    <input type="text" x-model="form.bidang_instansi" class="w-full px-4 py-3 rounded-lg border border-slate-200">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">Posisi/Jabatan</label>
                                    <input type="text" x-model="form.posisi_jabatan" class="w-full px-4 py-3 rounded-lg border border-slate-200">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">Kesesuaian dengan Jurusan SMK</label>
                                    <select x-model="form.kesesuaian_kerja" class="w-full px-4 py-3 rounded-lg border border-slate-200">
                                        <option value="">-- Pilih --</option>
                                        <option value="sangat_sesuai">Sangat Sesuai</option>
                                        <option value="sesuai">Sesuai</option>
                                        <option value="kurang_sesuai">Kurang Sesuai</option>
                                        <option value="tidak_sesuai">Tidak Sesuai</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">Waktu Tunggu Kerja Pertama</label>
                                    <select x-model="form.lama_tunggu_kerja" class="w-full px-4 py-3 rounded-lg border border-slate-200">
                                        <option value="">-- Pilih --</option>
                                        <option value="kurang_1_bulan">Kurang dari 1 bulan</option>
                                        <option value="1_3_bulan">1–3 bulan</option>
                                        <option value="3_6_bulan">3–6 bulan</option>
                                        <option value="lebih_6_bulan">Lebih dari 6 bulan</option>
                                    </select>
                                </div>
                            </div>
                        </template>

                        {{-- Kuliah / Bekerja sambil Kuliah --}}
                        <template x-if="['kuliah','bekerja_kuliah'].includes(form.status_kegiatan)">
                            <div class="space-y-4 mb-6">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">Nama Perguruan Tinggi</label>
                                    <input type="text" x-model="form.nama_kampus" class="w-full px-4 py-3 rounded-lg border border-slate-200">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">Program Studi</label>
                                    <input type="text" x-model="form.program_studi" class="w-full px-4 py-3 rounded-lg border border-slate-200">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">Jenjang</label>
                                    <select x-model="form.jenjang" class="w-full px-4 py-3 rounded-lg border border-slate-200">
                                        <option value="">-- Pilih --</option>
                                        <option value="D3">D3</option>
                                        <option value="D4">D4</option>
                                        <option value="S1">S1</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">Kesesuaian dengan Jurusan SMK</label>
                                    <select x-model="form.kuliah_linier" class="w-full px-4 py-3 rounded-lg border border-slate-200">
                                        <option value="">-- Pilih --</option>
                                        <option value="1">Linier</option>
                                        <option value="0">Tidak Linier</option>
                                    </select>
                                </div>
                            </div>
                        </template>

                        {{-- Wiraswasta --}}
                        <template x-if="form.status_kegiatan === 'wiraswasta'">
                            <div class="space-y-4 mb-6">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">Jenis Usaha</label>
                                    <input type="text" x-model="form.jenis_usaha" class="w-full px-4 py-3 rounded-lg border border-slate-200">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">Bidang Usaha</label>
                                    <input type="text" x-model="form.bidang_usaha" class="w-full px-4 py-3 rounded-lg border border-slate-200">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">Lama Usaha Berjalan</label>
                                    <input type="text" x-model="form.lama_usaha" placeholder="mis. 1 tahun 2 bulan" class="w-full px-4 py-3 rounded-lg border border-slate-200">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">Jumlah Tenaga Kerja (opsional)</label>
                                    <input type="number" min="0" x-model="form.jumlah_tenaga_kerja" class="w-full px-4 py-3 rounded-lg border border-slate-200">
                                </div>
                            </div>
                        </template>

                        {{-- Belum bekerja/kuliah --}}
                        <template x-if="form.status_kegiatan === 'belum'">
                            <div class="mb-6">
                                <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">Alasan (opsional)</label>
                                <select x-model="form.alasan_belum" class="w-full px-4 py-3 rounded-lg border border-slate-200">
                                    <option value="">-- Pilih --</option>
                                    <option value="mencari_kerja">Sedang mencari kerja</option>
                                    <option value="kendala_biaya">Kendala biaya melanjutkan kuliah</option>
                                    <option value="lainnya">Lainnya</option>
                                </select>
                            </div>
                        </template>

                        {{-- Kontak --}}
                        <div class="grid sm:grid-cols-2 gap-4 mb-6">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">No. HP/WhatsApp</label>
                                <input type="text" x-model="form.no_hp" class="w-full px-4 py-3 rounded-lg border border-slate-200">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">Domisili Saat Ini</label>
                                <input type="text" x-model="form.domisili" class="w-full px-4 py-3 rounded-lg border border-slate-200">
                            </div>
                        </div>

                        {{-- Evaluasi opsional --}}
                        <div class="mb-6">
                            <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">Kesiapan Kerja dari Bekal SMK (opsional)</label>
                            <div class="flex gap-2">
                                <template x-for="n in 5" :key="n">
                                    <button type="button" @click="form.evaluasi_kesiapan = n"
                                            :class="form.evaluasi_kesiapan >= n ? 'text-amber-400' : 'text-slate-200'"
                                            class="text-3xl leading-none">★</button>
                                </template>
                            </div>
                        </div>
                        <div class="mb-6">
                            <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">Saran untuk Sekolah (opsional)</label>
                            <textarea x-model="form.saran" rows="3" class="w-full px-4 py-3 rounded-lg border border-slate-200"></textarea>
                        </div>

                        <div class="flex gap-3">
                            <button @click="step = 2" class="px-5 py-3 rounded-lg border border-slate-200 text-slate-500 font-semibold text-sm">← Kembali</button>
                            <button @click="submit()" :disabled="submitting"
                                    class="flex-1 py-3 rounded-lg bg-sky-700 hover:bg-sky-800 disabled:bg-slate-300 text-white font-semibold transition">
                                <span x-show="!submitting">Kirim Data</span>
                                <span x-show="submitting">Mengirim...</span>
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </section>

    {{-- ============ TAB: STATISTIK ALUMNI ============ --}}
    <section x-show="tab === 'statistik'" x-cloak class="bg-slate-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
                <div>
                    <p class="text-sky-600 font-semibold text-xs tracking-wide mb-2">DATA ALUMNI BKK</p>
                    <h2 class="text-3xl font-bold text-slate-900">Statistik Tracer Study</h2>
                </div>
                <div class="flex gap-2">
                    @foreach (['semua' => 'Semua'] + $availableYears->mapWithKeys(fn ($y) => [(string) $y => (string) $y])->all() as $value => $label)
                        <a href="{{ route('bkk.tracer-study.index', ['tahun' => $value, 'tab' => 'statistik']) }}"
                           class="px-4 py-2 rounded-lg text-sm font-semibold transition
                                  {{ ($year ?? 'semua') === $value ? 'bg-sky-700 text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="mb-6">
                <x-tracer-cta />
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <x-tracer-stat-card border="border-sky-500" icon="👥" :value="$stats['total']" label="Total Responden" sublabel="Lulusan tersurvei" />
                <x-tracer-stat-card border="border-emerald-500" icon="🎯" :value="$stats['kesesuaian_persen'].'%'" label="Kesesuaian Bidang" sublabel="Bekerja sesuai keahlian" />
                <x-tracer-stat-card border="border-orange-500" icon="⏱️" :value="$stats['avg_wait_months'].' bulan'" label="Rata-rata Tunggu" sublabel="Waktu dapat pekerjaan" />
                <x-tracer-stat-card border="border-amber-500" icon="🤝" :value="$stats['via_bkk']" label="Tersalurkan BKK" sublabel="Alumni via jalur BKK" />
            </div>

            <div class="grid lg:grid-cols-2 gap-6 mb-6">
                <x-tracer-status-distribution :stats="$stats" />
                <x-tracer-kesesuaian-donut :stats="$stats" />
            </div>

            <div class="grid lg:grid-cols-2 gap-6">
                <x-tracer-trend-chart :trend="$stats['trend_by_year']" />
                <x-tracer-wait-time :stats="$stats" />
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
function tracerForm() {
    return {
        step: 1,
        verifying: false,
        submitting: false,
        verifyMessage: '',
        form: {
            nisn: '', tahun_lulus: '', alumni_nama: '',
            status_kegiatan: '', no_hp: '', domisili: '',
            nama_instansi: '', bidang_instansi: '', posisi_jabatan: '', kesesuaian_kerja: '', lama_tunggu_kerja: '',
            nama_kampus: '', program_studi: '', jenjang: '', kuliah_linier: '',
            jenis_usaha: '', bidang_usaha: '', lama_usaha: '', jumlah_tenaga_kerja: '',
            alasan_belum: '', evaluasi_kesiapan: null, saran: '',
        },
        statusOptions: [
            { value: 'bekerja', icon: '🧑‍💼', label: 'Bekerja' },
            { value: 'kuliah', icon: '🎓', label: 'Kuliah' },
            { value: 'wiraswasta', icon: '🚀', label: 'Wiraswasta' },
            { value: 'bekerja_kuliah', icon: '📚', label: 'Bekerja sambil Kuliah' },
            { value: 'belum', icon: '⏳', label: 'Belum Bekerja/Kuliah' },
        ],
        statusLabel(value) {
            const found = this.statusOptions.find(o => o.value === value);
            return found ? found.label : '';
        },
        async verify() {
            if (!this.form.nisn || !this.form.tahun_lulus || !this.form.alumni_nama) {
                this.verifyMessage = 'Mohon lengkapi NISN, tahun lulus, dan nama lengkap.';
                return;
            }
            this.verifying = true;
            this.verifyMessage = '';
            try {
                const res = await fetch('{{ route('bkk.tracer-study.verify') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '{{ csrf_token() }}',
                    },
                    body: JSON.stringify(this.form),
                });
                const data = await res.json();
                if (data.exists && data.data) {
                    this.verifyMessage = 'Data Anda sudah tercatat sebelumnya — lanjutkan untuk memperbarui data.';
                    this.form = { ...this.form, ...data.data };
                }
                this.step = 2;
            } catch (e) {
                this.verifyMessage = 'Terjadi kesalahan, silakan coba lagi.';
            } finally {
                this.verifying = false;
            }
        },
        async submit() {
            this.submitting = true;
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('bkk.tracer-study.store') }}';
            form.innerHTML = `@csrf`;
            for (const [key, value] of Object.entries(this.form)) {
                if (value === null || value === '') continue;
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = value;
                form.appendChild(input);
            }
            document.body.appendChild(form);
            form.submit();
        },
    };
}
</script>
@endpush

@endsection
