<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PpdbRegistrant;
use App\Models\Setting;
use App\Support\SimpleXlsx;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PpdbAdminController extends Controller
{
    public function index(Request $request): View
    {
        $query = PpdbRegistrant::with(['program', 'concentration'])->latest();

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $registrants = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => PpdbRegistrant::count(),
            'verified' => PpdbRegistrant::where('status', 'verified')->count(),
            'pending' => PpdbRegistrant::where('status', 'pending')->count(),
            'rejected' => PpdbRegistrant::where('status', 'rejected')->count(),
        ];

        $ppdbOpen = Setting::get('ppdb_status', 'open') === 'open';
        $ppdbOpenDate = Setting::get('ppdb_open_date', '');
        $schedule = Setting::spmbSchedule();

        return view('admin.ppdb.index', compact('registrants', 'stats', 'ppdbOpen', 'ppdbOpenDate', 'schedule'));
    }

    /**
     * Buka/tutup pendaftaran SPMB. Saat ditutup, tanggal pembukaan wajib diisi
     * karena itulah yang ditampilkan ke calon siswa di halaman publik.
     */
    public function updateRegistration(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ppdb_status' => ['required', 'in:open,closed'],
            'ppdb_open_date' => ['nullable', 'required_if:ppdb_status,closed', 'date'],
        ], [
            'ppdb_open_date.required_if' => 'Tanggal pembukaan pendaftaran wajib diisi saat SPMB ditutup.',
            'ppdb_open_date.date' => 'Format tanggal pembukaan tidak valid.',
        ]);

        Setting::set('ppdb_status', $validated['ppdb_status']);
        Setting::set('ppdb_open_date', $validated['ppdb_open_date'] ?? '');

        return back()->with('success', $validated['ppdb_status'] === 'open'
            ? 'Pendaftaran SPMB sekarang DIBUKA.'
            : 'Pendaftaran SPMB sekarang DITUTUP.');
    }

    /**
     * Atur jadwal SPMB (daftar kegiatan + tanggalnya) yang tampil ke calon siswa.
     * Nilai tanggal berupa teks bebas, mis. "1–25 Agustus 2026".
     */
    public function updateSchedule(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'schedule' => ['required', 'array', 'min:1', 'max:12'],
            'schedule.*.label' => ['required', 'string', 'max:60'],
            'schedule.*.value' => ['required', 'string', 'max:80'],
        ], [
            'schedule.required' => 'Minimal harus ada satu jadwal.',
            'schedule.min' => 'Minimal harus ada satu jadwal.',
            'schedule.max' => 'Maksimal 12 jadwal.',
            'schedule.*.label.required' => 'Nama kegiatan pada jadwal wajib diisi.',
            'schedule.*.label.max' => 'Nama kegiatan maksimal 60 karakter.',
            'schedule.*.value.required' => 'Tanggal pada jadwal wajib diisi.',
            'schedule.*.value.max' => 'Tanggal/keterangan waktu maksimal 80 karakter.',
        ]);

        Setting::saveSpmbSchedule($validated['schedule']);

        return back()->with('success', 'Jadwal SPMB berhasil diperbarui.');
    }

    public function updateStatus(Request $request, PpdbRegistrant $registrant): RedirectResponse
    {
        $request->validate(['status' => ['required', 'in:pending,verified,rejected']]);

        $registrant->update(['status' => $request->status]);

        return back()->with('success', "Status {$registrant->full_name} diperbarui.");
    }

    private const STATUS_LABELS = [
        'verified' => 'Diterima',
        'pending' => 'Dalam Verifikasi',
        'rejected' => 'Ditolak',
    ];

    /** Data untuk ekspor: urut tanggal daftar, mengikuti filter ?status= dari halaman. */
    private function exportQuery(Request $request)
    {
        $query = PpdbRegistrant::with(['program', 'concentration'])->orderBy('created_at')->orderBy('id');

        if (array_key_exists((string) $request->query('status'), self::STATUS_LABELS)) {
            $query->where('status', $request->query('status'));
        }

        return $query;
    }

    private function exportFilename(Request $request, string $ext): string
    {
        $status = array_key_exists((string) $request->query('status'), self::STATUS_LABELS)
            ? '-'.$request->query('status') : '';

        return 'spmb'.$status.'-'.now()->format('Ymd-His').'.'.$ext;
    }

    public function exportXlsx(Request $request)
    {
        if (! class_exists(\ZipArchive::class)) {
            return back()->with('error', 'Ekspor Excel membutuhkan ekstensi PHP "zip". Aktifkan extension=zip di php.ini lalu restart server.');
        }

        $headers = ['No', 'No. Registrasi', 'Nama Lengkap', 'L/P', 'NIK', 'NISN', 'No. KK', 'Domisili',
            'Program Keahlian', 'Konsentrasi', 'KIP', 'KIS', 'PKH', 'Status', 'Tanggal Daftar'];

        $rows = [];
        foreach ($this->exportQuery($request)->get() as $i => $r) {
            $rows[] = [
                $i + 1,
                $r->registration_number,
                $r->full_name,
                $r->gender,
                (string) $r->nik,
                (string) $r->nisn,
                (string) $r->no_kk,
                $r->address,
                $r->program->name ?? '-',
                $r->concentration->name ?? '-',
                $r->has_kip ? 'Ya' : 'Tidak',
                $r->has_kis ? 'Ya' : 'Tidak',
                $r->has_pkh ? 'Ya' : 'Tidak',
                self::STATUS_LABELS[$r->status] ?? $r->status,
                $r->created_at->timezone('Asia/Jakarta')->format('d-m-Y H:i'),
            ];
        }

        return SimpleXlsx::download($this->exportFilename($request, 'xlsx'), $headers, $rows, 'Pendaftar SPMB');
    }

    public function exportPdf(Request $request)
    {
        if (! class_exists(\Dompdf\Dompdf::class)) {
            return back()->with('error', 'Ekspor PDF membutuhkan library dompdf. Jalankan di terminal: composer require dompdf/dompdf');
        }

        $registrants = $this->exportQuery($request)->get();
        $status = $request->query('status');

        $html = view('admin.ppdb.export-pdf', [
            'registrants' => $registrants,
            'statusLabels' => self::STATUS_LABELS,
            'filterLabel' => self::STATUS_LABELS[$status] ?? null,
            'schoolName' => Setting::get('school_name', 'SMKN 1 Bakam'),
            'printedAt' => now()->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d F Y, H:i').' WIB',
        ])->render();

        $cacheDir = storage_path('app/dompdf');
        if (! is_dir($cacheDir)) {
            @mkdir($cacheDir, 0775, true);
        }

        $options = new \Dompdf\Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('isRemoteEnabled', false);
        $options->set('tempDir', $cacheDir);
        $options->set('fontCache', $cacheDir);

        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$this->exportFilename($request, 'pdf').'"',
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        $filename = $this->exportFilename($request, 'csv');

        $query = $this->exportQuery($request);

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['No. Reg', 'Nama', 'L/P', 'NIK', 'NISN', 'No. KK', 'Domisili', 'Program', 'Konsentrasi', 'KIP', 'KIS', 'PKH', 'Status', 'Tanggal Daftar']);

            $query->chunk(200, function ($rows) use ($handle) {
                foreach ($rows as $r) {
                    fputcsv($handle, [
                        $r->registration_number, $r->full_name, $r->gender, $r->nik, $r->nisn, $r->no_kk, $r->address,
                        $r->program->name ?? '-', $r->concentration->name ?? '-',
                        $r->has_kip ? 'Ya' : 'Tidak', $r->has_kis ? 'Ya' : 'Tidak', $r->has_pkh ? 'Ya' : 'Tidak',
                        $r->status, $r->created_at->format('d-m-Y'),
                    ]);
                }
            });

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}