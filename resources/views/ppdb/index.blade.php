@extends('layouts.app')

@section('title', 'SPMB Online')

@section('content')

<div x-data="{ tab: '{{ $defaultTab }}' }">

    {{-- ============ HERO ============ --}}
    <section class="bg-gradient-to-br from-sky-800 to-sky-600 py-16 relative overflow-hidden">
        <div class="absolute -right-20 -bottom-20 w-96 h-96 rounded-full bg-white/5"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <p class="text-sky-200 font-semibold text-sm tracking-wide mb-3">SMKN 1 BAKAM</p>
            <h1 class="text-4xl sm:text-5xl font-extrabold text-white mb-5">SPMB Online {{ now()->year }}/{{ now()->year + 1 }}</h1>
            <p class="text-sky-100 max-w-2xl">Sistem Penerimaan Murid Baru secara online — mudah, cepat, dan transparan.</p>
        </div>
    </section>

    {{-- ============ TAB SWITCHER ============ --}}
    <div class="bg-white border-b border-slate-200">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 flex gap-8">
            <button @click="tab = 'form'"
                    :class="tab === 'form' ? 'text-sky-700 border-sky-700' : 'text-slate-400 border-transparent'"
                    class="py-4 border-b-2 font-semibold text-sm flex items-center gap-2 transition">
                📝 Formulir Pendaftaran
            </button>
            <button @click="tab = 'status'"
                    :class="tab === 'status' ? 'text-sky-700 border-sky-700' : 'text-slate-400 border-transparent'"
                    class="py-4 border-b-2 font-semibold text-sm flex items-center gap-2 transition">
                🔍 Cek Status Pendaftaran
            </button>
        </div>
    </div>

    {{-- ============ TAB: FORMULIR PENDAFTARAN ============ --}}
    <section x-show="tab === 'form'" x-cloak class="bg-slate-50 py-12">

        {{-- SPMB closed (Setting: ppdb_status != 'open') — show a notice
             instead of the stepper form. Toggle via Setting::set('ppdb_status', 'open'|'closed'). --}}
        @unless ($ppdbOpen)
            <div class="max-w-2xl mx-auto px-4">
                <div class="bg-white rounded-2xl shadow-sm p-10 text-center">
                    <p class="text-5xl mb-5">🔒</p>
                    <h2 class="text-2xl font-bold text-slate-900 mb-3">Pendaftaran Belum Dibuka</h2>
                    <p class="text-slate-500 leading-relaxed">{{ $closedMessage }}</p>

                    <div class="mt-6 inline-block rounded-xl bg-sky-50 border border-sky-100 px-6 py-4">
                        <p class="text-xs font-bold text-sky-700 tracking-wide mb-1">PENDAFTARAN DIBUKA</p>
                        <p class="text-lg font-bold text-sky-900">{{ $openDate ?? 'Tanggal belum ditentukan' }}</p>
                    </div>
                    <button @click="tab = 'status'" class="mt-6 px-6 py-3 rounded-lg bg-sky-700 hover:bg-sky-800 text-white font-semibold transition">
                        Cek Status Pendaftaran Lama
                    </button>
                </div>

                {{-- Jadwal & Persyaratan stay visible even when closed, so
                     people know when to come back. --}}
                <div class="grid sm:grid-cols-2 gap-6 mt-8">
                    <div class="bg-white rounded-xl border border-slate-200 p-6">
                        <h3 class="font-bold text-sky-800 flex items-center gap-2 mb-4">🗓️ Jadwal SPMB</h3>
                        <ul class="space-y-2 text-sm text-slate-600">
                            @forelse ($schedule as $item)
                                <li>{{ $item['label'] }}: {{ $item['value'] }}</li>
                            @empty
                                <li>Jadwal belum diumumkan.</li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="bg-white rounded-xl border border-slate-200 p-6">
                        <h3 class="font-bold text-sky-800 flex items-center gap-2 mb-4">📋 Persyaratan</h3>
                        <ul class="space-y-2 text-sm text-slate-600">
                            <li>Lulusan SMP/MTs sederajat</li>
                            <li>Memiliki NIK dan NISN</li>
                            <li>Mengisi formulir online dengan lengkap</li>
                            <li>Menghadiri wawancara jika dipanggil</li>
                        </ul>
                    </div>
                </div>
            </div>
        @else
        @php
            // Bila server menolak data, buka kembali langkah yang berisi kesalahan pertama.
            $errorKeys = array_keys($errors->getMessages());
            $startStep = 1;
            if ($errors->any()) {
                $startStep = count(array_intersect($errorKeys, ['full_name', 'gender', 'nisn', 'nik', 'no_kk', 'address'])) ? 1
                    : (count(array_intersect($errorKeys, ['program_id', 'concentration_id'])) ? 2 : 3);
            }
        @endphp
        <div class="max-w-2xl mx-auto px-4"
             x-data="ppdbForm({{ $selectedProgramId ?? 'null' }}, {{ $selectedConcentrationId ?? 'null' }}, @js(old()), {{ $startStep }})">

            {{-- Stepper --}}
            <div class="flex items-center mb-8">
                <template x-for="(label, index) in steps" :key="index">
                    <div class="flex items-center" :class="index < steps.length - 1 ? 'flex-1' : ''">
                        <div class="flex flex-col items-center gap-2 shrink-0">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition"
                                 :class="step > index + 1 ? 'bg-emerald-500 text-white' : (step === index + 1 ? 'bg-sky-700 text-white' : 'bg-slate-200 text-slate-400')">
                                <span x-show="step > index + 1">✓</span>
                                <span x-show="step <= index + 1" x-text="index + 1"></span>
                            </div>
                            <span class="text-xs font-medium text-center whitespace-nowrap"
                                  :class="step >= index + 1 ? 'text-slate-700' : 'text-slate-400'" x-text="label"></span>
                        </div>
                        <div class="flex-1 h-0.5 mx-2 mb-6" :class="step > index + 1 ? 'bg-emerald-400' : 'bg-slate-200'" x-show="index < steps.length - 1"></div>
                    </div>
                </template>
            </div>

            @if (session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 rounded-xl px-5 py-4 text-sm">{{ session('error') }}</div>
            @endif
            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 rounded-xl px-5 py-4 text-sm">
                    <p class="font-semibold mb-1">Data belum tersimpan. Mohon perbaiki:</p>
                    <ul class="list-disc ml-5 space-y-0.5">
                        @foreach ($errors->all() as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('ppdb.store') }}" @submit="submitting = true">
                @csrf

                <div class="bg-white rounded-2xl shadow-sm p-8">

                    {{-- STEP 1: Data Diri --}}
                    <div x-show="step === 1">
                        <h2 class="text-2xl font-bold text-sky-900 mb-6">Data Diri Calon Peserta Didik</h2>

                        <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">NAMA LENGKAP</label>
                        <input type="text" name="full_name" x-model="form.full_name" placeholder="Nama lengkap sesuai akta/ijazah"
                               class="w-full px-4 py-3 rounded-lg border border-slate-200 mb-5 focus:outline-none focus:ring-2 focus:ring-sky-500">
                        @error('full_name') <p class="text-red-500 text-xs -mt-4 mb-4">{{ $message }}</p> @enderror

                        <div class="grid sm:grid-cols-2 gap-5 mb-5">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">JENIS KELAMIN</label>
                                <select name="gender" x-model="form.gender" class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500">
                                    <option value="">-- Pilih --</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">NISN</label>
                                <input type="text" name="nisn" x-model="form.nisn" maxlength="10" placeholder="10 digit NISN"
                                       class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500">
                            </div>
                        </div>

                        <div class="grid sm:grid-cols-2 gap-5 mb-5">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">NIK (KTP/KIA)</label>
                                <input type="text" name="nik" x-model="form.nik" maxlength="16" placeholder="16 digit NIK"
                                       class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">NO. KARTU KELUARGA</label>
                                <input type="text" name="no_kk" x-model="form.no_kk" maxlength="16" placeholder="16 digit No. KK"
                                       class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500">
                            </div>
                        </div>

                        <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">DOMISILI (ALAMAT LENGKAP)</label>
                        <textarea name="address" x-model="form.address" rows="3" placeholder="RT/RW, Desa/Kelurahan, Kecamatan, Kabupaten, Provinsi"
                                  class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500"></textarea>

                        <div class="flex justify-end mt-8">
                            <button type="button" @click="next(1)" class="px-6 py-3 rounded-lg bg-sky-700 hover:bg-sky-800 text-white font-semibold transition">
                                Lanjutkan →
                            </button>
                        </div>
                    </div>

                    {{-- STEP 2: Program & Konsentrasi --}}
                    <div x-show="step === 2">
                        <h2 class="text-2xl font-bold text-sky-900 mb-6">Pilih Program & Konsentrasi Keahlian</h2>

                        <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">PROGRAM KEAHLIAN</label>
                        <select name="program_id" x-model="form.program_id" @change="loadConcentrations()"
                                class="w-full px-4 py-3 rounded-lg border border-slate-200 mb-5 focus:outline-none focus:ring-2 focus:ring-sky-500">
                            <option value="">-- Pilih Program Keahlian --</option>
                            @foreach ($programs as $program)
                                <option value="{{ $program->id }}">{{ $program->name }}</option>
                            @endforeach
                        </select>

                        <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">KONSENTRASI KEAHLIAN</label>
                        <select name="concentration_id" x-model="form.concentration_id"
                                :disabled="!form.program_id"
                                class="w-full px-4 py-3 rounded-lg border border-slate-200 mb-2 focus:outline-none focus:ring-2 focus:ring-sky-500 disabled:bg-slate-50 disabled:text-slate-400">
                            <option value="" x-text="form.program_id ? '-- Pilih Konsentrasi --' : '-- Pilih program terlebih dahulu --'"></option>
                            <template x-for="c in concentrations" :key="c.id">
                                <option :value="c.id" x-text="(c.title ?? c.name) + ' (' + c.name + ')'"></option>
                            </template>
                        </select>

                        <div class="flex justify-between mt-8">
                            <button type="button" @click="step = 1" class="px-6 py-3 rounded-lg border border-slate-200 text-slate-500 font-semibold text-sm">← Sebelumnya</button>
                            <button type="button" @click="next(2)" class="px-6 py-3 rounded-lg bg-sky-700 hover:bg-sky-800 text-white font-semibold transition">Lanjutkan →</button>
                        </div>
                    </div>

                    {{-- STEP 3: Fasilitas Pendukung --}}
                    <div x-show="step === 3">
                        <h2 class="text-2xl font-bold text-sky-900 mb-2">Kepemilikan Kartu Bantuan Sosial</h2>
                        <p class="text-slate-500 text-sm mb-6">Centang kartu yang Anda/keluarga miliki. Data ini digunakan untuk mempertimbangkan beasiswa dan bantuan biaya pendidikan. Jika tidak memiliki satu pun, pilih &quot;Tidak ada&quot;.</p>

                        <label class="flex items-center gap-3 border border-slate-200 rounded-xl px-5 py-4 mb-3 cursor-pointer hover:border-sky-300">
                            <input type="checkbox" name="has_kip" value="1" x-model="form.has_kip" @change="if (form.has_kip) form.no_aid = false" class="w-5 h-5 rounded text-sky-600">
                            <span class="text-slate-700">Kartu Indonesia Pintar (KIP)</span>
                        </label>
                        <label class="flex items-center gap-3 border border-slate-200 rounded-xl px-5 py-4 mb-3 cursor-pointer hover:border-sky-300">
                            <input type="checkbox" name="has_kis" value="1" x-model="form.has_kis" @change="if (form.has_kis) form.no_aid = false" class="w-5 h-5 rounded text-sky-600">
                            <span class="text-slate-700">Kartu Indonesia Sehat (KIS)</span>
                        </label>
                        <label class="flex items-center gap-3 border border-slate-200 rounded-xl px-5 py-4 mb-3 cursor-pointer hover:border-sky-300">
                            <input type="checkbox" name="has_pkh" value="1" x-model="form.has_pkh" @change="if (form.has_pkh) form.no_aid = false" class="w-5 h-5 rounded text-sky-600">
                            <span class="text-slate-700">Program Keluarga Harapan (PKH)</span>
                        </label>
                        <label class="flex items-center gap-3 border border-slate-200 rounded-xl px-5 py-4 mb-3 cursor-pointer hover:border-sky-300"
                               :class="form.no_aid ? 'border-sky-400 bg-sky-50' : ''">
                            <input type="checkbox" name="no_aid" value="1" x-model="form.no_aid"
                                   @change="if (form.no_aid) { form.has_kip = false; form.has_kis = false; form.has_pkh = false }"
                                   class="w-5 h-5 rounded text-sky-600">
                            <span class="text-slate-700">Tidak ada <span class="text-slate-400 text-sm">(tidak memiliki kartu di atas)</span></span>
                        </label>

                        <p class="text-slate-400 text-xs mt-2">* Kepemilikan kartu bantuan sosial tidak menjamin penerimaan, namun menjadi pertimbangan tambahan dalam seleksi.</p>

                        <div class="flex justify-between mt-8">
                            <button type="button" @click="step = 2" class="px-6 py-3 rounded-lg border border-slate-200 text-slate-500 font-semibold text-sm">← Sebelumnya</button>
                            <button type="button" @click="next(3)" class="px-6 py-3 rounded-lg bg-sky-700 hover:bg-sky-800 text-white font-semibold transition">Lanjutkan →</button>
                        </div>
                    </div>

                    {{-- STEP 4: Konfirmasi --}}
                    <div x-show="step === 4">
                        <h2 class="text-2xl font-bold text-sky-900 mb-2">Konfirmasi Data Pendaftaran</h2>
                        <p class="text-slate-500 text-sm mb-6">Periksa kembali seluruh data sebelum mengirimkan formulir.</p>

                        <div class="border border-slate-200 rounded-xl overflow-hidden divide-y divide-slate-100">
                            <template x-for="row in summaryRows()" :key="row.label">
                                <div class="flex px-5 py-3.5 odd:bg-slate-50/60">
                                    <span class="w-40 shrink-0 font-semibold text-slate-700 text-sm" x-text="row.label"></span>
                                    <span class="text-slate-600 text-sm" x-text="row.value || '—'"></span>
                                </div>
                            </template>
                        </div>

                        <div class="bg-sky-50 border border-sky-100 rounded-xl px-5 py-4 mt-5 text-sm text-sky-900">
                            📌 Nomor registrasi akan diberikan setelah data berhasil dikirim.
                        </div>

                        <div class="flex justify-between mt-8">
                            <button type="button" @click="step = 3" class="px-6 py-3 rounded-lg border border-slate-200 text-slate-500 font-semibold text-sm">← Sebelumnya</button>
                            <button type="submit" :disabled="submitting"
                                    class="px-6 py-3 rounded-lg bg-emerald-600 hover:bg-emerald-700 disabled:bg-slate-300 text-white font-semibold transition">
                                <span x-show="!submitting">✓ Kirim Pendaftaran</span>
                                <span x-show="submitting">Mengirim...</span>
                            </button>
                        </div>
                    </div>

                </div>
            </form>

            {{-- ============ JADWAL & PERSYARATAN ============ --}}
            <div class="grid sm:grid-cols-2 gap-6 mt-8">
                <div class="bg-white rounded-xl border border-slate-200 p-6">
                    <h3 class="font-bold text-sky-800 flex items-center gap-2 mb-4">🗓️ Jadwal SPMB</h3>
                    <ul class="space-y-2 text-sm text-slate-600">
                        @forelse ($schedule as $item)
                            <li>{{ $item['label'] }}: {{ $item['value'] }}</li>
                        @empty
                            <li>Jadwal belum diumumkan.</li>
                        @endforelse
                    </ul>
                </div>
                <div class="bg-white rounded-xl border border-slate-200 p-6">
                    <h3 class="font-bold text-sky-800 flex items-center gap-2 mb-4">📋 Persyaratan</h3>
                    <ul class="space-y-2 text-sm text-slate-600">
                        <li>Lulusan SMP/MTs sederajat</li>
                        <li>Memiliki NIK dan NISN</li>
                        <li>Mengisi formulir online dengan lengkap</li>
                        <li>Menghadiri wawancara jika dipanggil</li>
                    </ul>
                </div>
            </div>
        </div>
        @endunless
    </section>

    {{-- ============ TAB: CEK STATUS PENDAFTARAN ============ --}}
    <section x-show="tab === 'status'" x-cloak class="bg-slate-50 py-16 min-h-[50vh]">
        <div class="max-w-md mx-auto px-4" x-data="checkStatus()">
            <div class="bg-white rounded-2xl shadow-sm p-8">
                <h2 class="text-2xl font-bold text-sky-900 mb-2">Cek Status Pendaftaran</h2>
                <p class="text-slate-500 text-sm mb-6">Masukkan nomor registrasi yang Anda terima setelah mendaftar.</p>

                <template x-if="!result">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 tracking-wide mb-2">NOMOR REGISTRASI</label>
                        <input type="text" x-model="regNumber" placeholder="Contoh: {{ now()->year }}001"
                               class="w-full px-4 py-3 rounded-lg border border-slate-200 mb-5 focus:outline-none focus:ring-2 focus:ring-sky-500">
                        <button @click="check()" :disabled="checking"
                                class="w-full py-3.5 rounded-lg bg-sky-700 hover:bg-sky-800 disabled:bg-slate-300 text-white font-semibold transition">
                            <span x-show="!checking">Cek Status</span>
                            <span x-show="checking">Memeriksa...</span>
                        </button>
                        <p x-show="notFound" class="text-red-500 text-sm text-center mt-3">Nomor registrasi tidak ditemukan.</p>
                    </div>
                </template>

                <template x-if="result">
                    <div>
                        <div class="text-center mb-5">
                            <span class="inline-block px-4 py-1.5 rounded-full text-sm font-bold"
                                  :class="{
                                      'bg-amber-100 text-amber-700': result.status === 'pending',
                                      'bg-emerald-100 text-emerald-700': result.status === 'diterima',
                                      'bg-red-100 text-red-700': result.status === 'ditolak',
                                  }"
                                  x-text="statusLabel(result.status)"></span>
                        </div>
                        <div class="space-y-2 text-sm border-t border-slate-100 pt-4">
                            <div class="flex justify-between"><span class="text-slate-400">No. Registrasi</span><span class="font-semibold text-slate-700" x-text="result.registration_number"></span></div>
                            <div class="flex justify-between"><span class="text-slate-400">Nama</span><span class="font-semibold text-slate-700" x-text="result.full_name"></span></div>
                            <div class="flex justify-between"><span class="text-slate-400">Program</span><span class="font-semibold text-slate-700" x-text="result.program"></span></div>
                            <div class="flex justify-between"><span class="text-slate-400">Konsentrasi</span><span class="font-semibold text-slate-700" x-text="result.concentration || '—'"></span></div>
                            <div class="flex justify-between"><span class="text-slate-400">Tanggal Daftar</span><span class="font-semibold text-slate-700" x-text="result.created_at"></span></div>
                        </div>
                        <button @click="result = null; regNumber = ''" class="w-full mt-6 py-3 rounded-lg border border-slate-200 text-slate-500 font-semibold text-sm">Cek Nomor Lain</button>
                    </div>
                </template>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
function ppdbForm(selectedProgramId, selectedConcentrationId, old, startStep) {
    old = (old && !Array.isArray(old)) ? old : {};
    return {
        step: startStep || 1,
        submitting: false,
        steps: ['Data Diri', 'Program & Konsentrasi', 'Fasilitas Pendukung', 'Konfirmasi'],
        concentrations: [],
        form: {
            full_name: old.full_name ?? '', gender: old.gender ?? '', nisn: old.nisn ?? '',
            nik: old.nik ?? '', no_kk: old.no_kk ?? '', address: old.address ?? '',
            program_id: old.program_id ?? selectedProgramId ?? '',
            concentration_id: old.concentration_id ?? selectedConcentrationId ?? '',
            has_kip: !!old.has_kip, has_kis: !!old.has_kis, has_pkh: !!old.has_pkh, no_aid: !!old.no_aid,
        },
        programNames: { @foreach ($programs as $p) {{ $p->id }}: '{{ addslashes($p->name) }}', @endforeach },
        init() {
            if (this.form.program_id) this.loadConcentrations(this.form.concentration_id || selectedConcentrationId);
        },
        next(fromStep) {
            if (fromStep === 1 && (!this.form.full_name || !this.form.gender || !this.form.nisn || !this.form.nik || !this.form.no_kk || !this.form.address)) {
                alert('Mohon lengkapi semua data diri.');
                return;
            }
            if (fromStep === 2 && !this.form.program_id) {
                alert('Mohon pilih program keahlian.');
                return;
            }
            if (fromStep === 3 && !(this.form.has_kip || this.form.has_kis || this.form.has_pkh || this.form.no_aid)) {
                alert('Pilih salah satu kartu yang dimiliki, atau pilih "Tidak ada".');
                return;
            }
            this.step = fromStep + 1;
        },
        async loadConcentrations(preselect = null) {
            this.form.concentration_id = '';
            this.concentrations = [];
            if (!this.form.program_id) return;
            try {
                const res = await fetch(`/spmb/konsentrasi/${this.form.program_id}`);
                this.concentrations = await res.json();
                if (preselect) this.form.concentration_id = preselect;
            } catch (e) {}
        },
        summaryRows() {
            const genderLabel = this.form.gender === 'L' ? 'Laki-laki' : (this.form.gender === 'P' ? 'Perempuan' : '');
            const concentration = this.concentrations.find(c => c.id == this.form.concentration_id);
            const kartu = [];
            if (this.form.has_kip) kartu.push('KIP');
            if (this.form.has_kis) kartu.push('KIS');
            if (this.form.has_pkh) kartu.push('PKH');
            return [
                { label: 'Nama Lengkap', value: this.form.full_name },
                { label: 'Jenis Kelamin', value: genderLabel },
                { label: 'NIK', value: this.form.nik },
                { label: 'NISN', value: this.form.nisn },
                { label: 'No. Kartu Keluarga', value: this.form.no_kk },
                { label: 'Domisili', value: this.form.address },
                { label: 'Program Keahlian', value: this.programNames[this.form.program_id] },
                { label: 'Konsentrasi', value: concentration ? (concentration.title ?? concentration.name) : '' },
                { label: 'Kartu Bantuan Sosial', value: kartu.length ? kartu.join(', ') : 'Tidak ada' },
            ];
        },
    };
}

function checkStatus() {
    return {
        regNumber: '',
        checking: false,
        notFound: false,
        result: null,
        async check() {
            if (!this.regNumber) return;
            this.checking = true;
            this.notFound = false;
            try {
                const res = await fetch('{{ route('ppdb.check-status.check') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({ registration_number: this.regNumber }),
                });
                const data = await res.json();
                if (data.found) {
                    this.result = data;
                } else {
                    this.notFound = true;
                }
            } catch (e) {
                this.notFound = true;
            } finally {
                this.checking = false;
            }
        },
        statusLabel(status) {
            return { pending: 'Menunggu Verifikasi', diterima: 'Diterima', ditolak: 'Ditolak' }[status] ?? status;
        },
    };
}
</script>
@endpush

@endsection