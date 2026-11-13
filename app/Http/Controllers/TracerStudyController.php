<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\TracerResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TracerStudyController extends Controller
{
    /**
     * Single page with two Alpine-driven tabs ("Isi Survei Alumni" and
     * "Statistik Alumni") — matches the design, which shows both tabs
     * under the same BKK nav item without a separate page reload.
     */
    public function index(Request $request): View
    {
        $programs = Program::with('concentrations')->get();
        $year = $request->get('tahun'); // 'semua' | '2025' | '2024' | '2023'
        $stats = $this->computeStats($year !== 'semua' ? $year : null);
        $availableYears = TracerResponse::select('tahun_lulus')->distinct()->orderByDesc('tahun_lulus')->pluck('tahun_lulus');

        return view('bkk.tracer-study', compact('programs', 'stats', 'availableYears', 'year'));
    }

    /**
     * Step 1 of the form: verify NISN + tahun lulus + nama. If a response
     * already exists for that combination, the alumnus is editing/updating
     * rather than creating a new row (see planning doc — "Perbarui Data").
     */
    public function verify(Request $request)
    {
        $validated = $request->validate([
            'nisn' => ['required', 'digits_between:8,10'],
            'tahun_lulus' => ['required', 'digits:4'],
            'alumni_nama' => ['required', 'string', 'max:255'],
        ]);

        $existing = TracerResponse::where('nisn', $validated['nisn'])
            ->where('tahun_lulus', $validated['tahun_lulus'])
            ->first();

        return response()->json([
            'exists' => (bool) $existing,
            'data' => $existing,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nisn' => ['required', 'digits_between:8,10'],
            'tahun_lulus' => ['required', 'digits:4'],
            'alumni_nama' => ['required', 'string', 'max:255'],
            'program_id' => ['nullable', 'exists:programs,id'],
            'concentration_id' => ['nullable', 'exists:concentrations,id'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'domisili' => ['nullable', 'string', 'max:255'],
            'status_kegiatan' => ['required', 'in:bekerja,kuliah,wiraswasta,bekerja_kuliah,belum'],

            'nama_instansi' => ['nullable', 'string', 'max:255'],
            'bidang_instansi' => ['nullable', 'string', 'max:255'],
            'posisi_jabatan' => ['nullable', 'string', 'max:255'],
            'kesesuaian_kerja' => ['nullable', 'in:sangat_sesuai,sesuai,kurang_sesuai,tidak_sesuai'],
            'lama_tunggu_kerja' => ['nullable', 'in:kurang_1_bulan,1_3_bulan,3_6_bulan,lebih_6_bulan'],

            'nama_kampus' => ['nullable', 'string', 'max:255'],
            'program_studi' => ['nullable', 'string', 'max:255'],
            'jenjang' => ['nullable', 'string', 'max:50'],
            'kuliah_linier' => ['nullable', 'boolean'],

            'jenis_usaha' => ['nullable', 'string', 'max:255'],
            'bidang_usaha' => ['nullable', 'string', 'max:255'],
            'lama_usaha' => ['nullable', 'string', 'max:100'],
            'jumlah_tenaga_kerja' => ['nullable', 'integer', 'min:0'],

            'alasan_belum' => ['nullable', 'string', 'max:255'],

            'evaluasi_kesiapan' => ['nullable', 'integer', 'min:1', 'max:5'],
            'saran' => ['nullable', 'string', 'max:2000'],
            // TODO: add 'g-recaptcha-response' => ['required'] once reCAPTCHA is wired up
        ]);

        TracerResponse::updateOrCreate(
            ['nisn' => $validated['nisn'], 'tahun_lulus' => $validated['tahun_lulus']],
            $validated
        );

        return redirect()
            ->route('bkk.tracer-study.index', ['tahun' => 'semua'])
            ->with('success', 'Terima kasih telah mengisi Tracer Study!');
    }

    /**
     * Shared stat computation, used by both this controller's own
     * "Statistik Alumni" tab and the compact summary embedded on the
     * main /bkk page (via BkkController).
     */
    public function computeStats(?string $year = null): array
    {
        $base = TracerResponse::query();
        if ($year) {
            $base->where('tahun_lulus', $year);
        }

        $total = (clone $base)->count();

        $statusCounts = (clone $base)
            ->selectRaw('status_kegiatan, count(*) as total')
            ->groupBy('status_kegiatan')
            ->pluck('total', 'status_kegiatan');

        $bekerjaGroup = (clone $base)->whereIn('status_kegiatan', ['bekerja', 'bekerja_kuliah']);
        $kesesuaianTotal = (clone $bekerjaGroup)->whereNotNull('kesesuaian_kerja')->count();
        $kesesuaianSesuai = (clone $bekerjaGroup)->whereIn('kesesuaian_kerja', ['sangat_sesuai', 'sesuai'])->count();
        $kesesuaianPersen = $kesesuaianTotal > 0 ? (int) round($kesesuaianSesuai / $kesesuaianTotal * 100) : 0;

        $waitCounts = (clone $bekerjaGroup)
            ->whereNotNull('lama_tunggu_kerja')
            ->selectRaw('lama_tunggu_kerja, count(*) as total')
            ->groupBy('lama_tunggu_kerja')
            ->pluck('total', 'lama_tunggu_kerja');

        // Approximate average wait time using bucket midpoints (weeks -> months).
        $waitMidpoints = ['kurang_1_bulan' => 0.5, '1_3_bulan' => 2, '3_6_bulan' => 4.5, 'lebih_6_bulan' => 7];
        $waitSampleCount = $waitCounts->sum();
        $avgWaitMonths = $waitSampleCount > 0
            ? round($waitCounts->reduce(fn ($carry, $count, $key) => $carry + $count * ($waitMidpoints[$key] ?? 0), 0) / $waitSampleCount, 1)
            : 0;

        $viaBkk = (clone $base)->where('via_bkk', true)->count();

        $trendByYear = TracerResponse::selectRaw('tahun_lulus, count(*) as total, sum(case when status_kegiatan in ("bekerja","bekerja_kuliah") then 1 else 0 end) as bekerja')
            ->groupBy('tahun_lulus')
            ->orderBy('tahun_lulus')
            ->get();

        return [
            'total' => $total,
            'status_counts' => $statusCounts,
            'status_labels' => TracerResponse::statusLabels(),
            'kesesuaian_persen' => $kesesuaianPersen,
            'avg_wait_months' => $avgWaitMonths,
            'wait_counts' => $waitCounts,
            'wait_labels' => TracerResponse::waitTimeLabels(),
            'via_bkk' => $viaBkk,
            'trend_by_year' => $trendByYear,
        ];
    }
}
