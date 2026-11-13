<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use App\Models\Program;

class FrontendController extends Controller
{
    public function index()
    {
        // Ambil 1 berita terbaru untuk Hero Prestasi
        $heroNews = News::latest()->first(); 
        
        // Ambil berita untuk Kabar Terbaru
        $latestNews = News::latest()->take(4)->get();
        
        // Ambil data program keahlian
        $programs = Program::all();

        return view('frontend.home', compact('heroNews', 'latestNews', 'programs'));
    }
}