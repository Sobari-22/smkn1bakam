{{--
    Satu kotak jabatan pada bagan struktur organisasi.
    Variabel: $title (nama jabatan), $holders (Collection<TeacherPosition> pemegang jabatan),
              $tone (violet|teal|slate), $payloadOf (Closure pembuat data jendela detail),
              $emptyText (opsional, teks bila belum ada pemegang jabatan).
    Klik nama -> membuka jendela detail (state `person` ada di partials/struktur-organisasi).
--}}
@php
    // Nama kelas ditulis lengkap (tidak disusun lewat interpolasi) agar terbaca oleh scan Tailwind.
    $tones = [
        'violet' => ['head' => 'bg-violet-600 text-white', 'border' => 'border-violet-200', 'avatar' => 'bg-violet-50 text-violet-700'],
        'teal'   => ['head' => 'bg-teal-600 text-white',   'border' => 'border-teal-200',   'avatar' => 'bg-teal-50 text-teal-700'],
        'slate'  => ['head' => 'bg-slate-500 text-white',  'border' => 'border-slate-200',  'avatar' => 'bg-slate-100 text-slate-600'],
    ];
    $t = $tones[$tone ?? 'violet'] ?? $tones['violet'];
@endphp

<div class="w-full bg-white rounded-lg border {{ $t['border'] }} shadow-sm overflow-hidden text-left">
    <p class="flex items-center justify-center min-h-[2.25rem] px-2.5 py-1.5 text-[11px] font-bold text-center leading-tight {{ $t['head'] }}">{{ $title }}</p>

    @forelse ($holders as $holder)
        @php $teacher = $holder->teacher; @endphp
        <button type="button" @click="person = @js($payloadOf($holder))" title="Klik untuk melihat detail"
                class="w-full flex items-center gap-2.5 px-2.5 py-2 text-left border-t first:border-t-0 border-slate-100 hover:bg-slate-50 focus:outline-none focus:bg-slate-50 transition">
            <span class="shrink-0 w-8 h-8 rounded-full overflow-hidden flex items-center justify-center text-sm {{ $t['avatar'] }}">
                @if ($teacher->photo)
                    <img src="{{ asset('storage/'.$teacher->photo) }}" alt="" class="w-full h-full object-cover" loading="lazy" onerror="this.style.display='none'">
                @else
                    👤
                @endif
            </span>
            <span class="min-w-0">
                <span class="block text-sm font-semibold text-slate-900 leading-snug break-words">{{ $teacher->name }}</span>
                @if ($holder->class_label)
                    <span class="block text-[11px] text-slate-500">{{ $holder->class_label }}</span>
                @endif
            </span>
        </button>
    @empty
        <p class="px-2.5 py-3 text-xs italic text-slate-400 text-center">{{ $emptyText ?? 'Belum diisi' }}</p>
    @endforelse
</div>
