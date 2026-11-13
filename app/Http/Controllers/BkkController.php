<?php

namespace App\Http\Controllers;

use App\Models\BkkListing;
use App\Models\TracerResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BkkController extends Controller
{
    public function index(Request $request): View
    {
        $query = BkkListing::active()->latest('posted_at');

        if ($type = $request->get('type')) {
            $query->where('type', $type);
        }

        $listings = $query->get();

        $stats = [
            'mitra_industri' => BkkListing::distinct('company_name')->count('company_name'),
            'lowongan_aktif' => BkkListing::active()->count(),
            'total_alumni' => TracerResponse::count(),
            'kesesuaian_persen' => $this->kesesuaianPersen(),
        ];

        $tracerSummary = app(TracerStudyController::class)->computeStats();

        return view('bkk.index', compact('listings', 'stats', 'tracerSummary'));
    }

    private function kesesuaianPersen(): int
    {
        $bekerja = TracerResponse::whereIn('status_kegiatan', ['bekerja', 'bekerja_kuliah'])
            ->whereNotNull('kesesuaian_kerja');

        $total = (clone $bekerja)->count();

        if ($total === 0) {
            return 0;
        }

        $sesuai = (clone $bekerja)->whereIn('kesesuaian_kerja', ['sangat_sesuai', 'sesuai'])->count();

        return (int) round($sesuai / $total * 100);
    }
}
