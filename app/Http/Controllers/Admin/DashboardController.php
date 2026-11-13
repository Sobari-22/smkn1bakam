<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\PpdbRegistrant;
use App\Models\Program;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total' => PpdbRegistrant::count(),
            'verified' => PpdbRegistrant::where('status', 'verified')->count(),
            'pending' => PpdbRegistrant::where('status', 'pending')->count(),
            'rejected' => PpdbRegistrant::where('status', 'rejected')->count(),
            'news_active' => News::count(),
            'news_draft' => News::whereNull('published_at')->count(),
        ];

        $latestRegistrants = PpdbRegistrant::with(['program', 'concentration'])
            ->latest()
            ->take(5)
            ->get();

        // "Aktivitas Terbaru" is a simple merged feed of recent PPDB
        // registrations, status changes, and news — not a dedicated
        // activity_log table (kept lightweight; add one later if a fuller
        // audit trail is needed).
        $activity = collect()
            ->concat(
                PpdbRegistrant::latest()->take(3)->get()->map(fn ($r) => [
                    'icon' => '📝',
                    'text' => "Pendaftar baru: {$r->full_name}".($r->concentration ? " ({$r->concentration->name})" : ''),
                    'time' => $r->created_at,
                ])
            )
            ->concat(
                News::latest()->take(2)->get()->map(fn ($n) => [
                    'icon' => '📰',
                    'text' => "Berita baru: {$n->title}",
                    'time' => $n->created_at,
                ])
            )
            ->sortByDesc('time')
            ->take(6)
            ->values();

        $quotaByProgram = Program::with('concentrations')->get()->map(function ($program) {
            $quota = $program->concentrations->sum('quota');
            $filled = PpdbRegistrant::where('program_id', $program->id)->count();

            return [
                'name' => $program->name,
                'code' => $program->concentrations->first()?->name ?? '-',
                'quota' => $quota,
                'filled' => $filled,
                'percent' => $quota > 0 ? min(100, round($filled / $quota * 100)) : 0,
            ];
        });

        return view('admin.dashboard.index', compact('stats', 'latestRegistrants', 'activity', 'quotaByProgram'));
    }
}
