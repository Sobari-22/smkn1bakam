@props(['compact' => true])

<div class="bg-gradient-to-r from-sky-800 to-sky-600 rounded-2xl px-6 py-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h3 class="text-white font-bold text-lg mb-1">Sudah Bekerja, Kuliah, atau Berwirausaha? Bagikan Kisahmu!</h3>
        <p class="text-sky-200 text-sm">Isi Tracer Study — hanya perlu NISN dan 5 menit</p>
    </div>
    <a href="{{ route('bkk.tracer-study.index') }}"
       class="shrink-0 px-5 py-2.5 rounded-lg bg-white text-sky-800 font-semibold text-sm hover:bg-sky-50 transition text-center">
        Isi Tracer Study Sekarang →
    </a>
</div>
