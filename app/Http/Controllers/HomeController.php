<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Program;
use App\Models\Setting;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        // Slide 1 of the hero is always the static "main" slide (handled in the
        // Blade view). Slides 2+ come from the latest published news so the
        // hero carousel stays fresh without manual curation.
        $heroNews = News::published()
            ->latest('published_at')
            ->take(3)
            ->get();

        $programs = Program::with('concentrations')->get();

        $latestNews = News::published()
            ->latest('published_at')
            ->take(4)
            ->get();

        $stats = [
            'programs_count' => Program::count(),
            'concentrations_count' => \App\Models\Concentration::count(),
        ];

        return view('home', [
            'heroNews' => $heroNews,
            'programs' => $programs,
            'latestNews' => $latestNews,
            'stats' => $stats,
        ]);
    }
}
