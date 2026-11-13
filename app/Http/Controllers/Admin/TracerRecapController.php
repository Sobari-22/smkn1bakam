<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TracerResponse;
use Illuminate\View\View;

class TracerRecapController extends Controller
{
    public function index(): View
    {
        $statusCounts = TracerResponse::selectRaw('status_kegiatan, count(*) as total')
            ->groupBy('status_kegiatan')
            ->pluck('total', 'status_kegiatan');

        $stats = [
            'bekerja' => $statusCounts['bekerja'] ?? 0,
            'kuliah' => $statusCounts['kuliah'] ?? 0,
            'wiraswasta' => $statusCounts['wiraswasta'] ?? 0,
            'bekerja_kuliah' => $statusCounts['bekerja_kuliah'] ?? 0,
            'belum' => $statusCounts['belum'] ?? 0,
        ];

        $responses = TracerResponse::with('concentration')
            ->latest()
            ->paginate(15);

        return view('admin.tracer-study.index', compact('stats', 'responses'));
    }
}
