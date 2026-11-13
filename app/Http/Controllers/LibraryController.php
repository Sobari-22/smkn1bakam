<?php

namespace App\Http\Controllers;

use App\Models\LibraryBook;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LibraryController extends Controller
{
    public function index(Request $request): View
    {
        // Design's final version shows the full catalog table to the public
        // (No. Induk, Judul, Pengarang, Penerbit, Tahun, Klasifikasi, Stok) —
        // broader than the original planning doc, which limited public
        // fields to No. Induk/Judul/Pengarang/Jumlah. Kept as-is to match
        // the approved Figma design.
        $query = LibraryBook::query();

        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('pengarang', 'like', "%{$search}%");
            });
        }

        $books = $query->orderBy('judul')->paginate(12)->withQueryString();

        $libraryProfile = Page::where('slug', 'tentang-perpustakaan')->first();

        $stats = [
            'titles' => LibraryBook::count(),
            'copies' => LibraryBook::sum('jumlah'),
        ];

        return view('elibrary.index', compact('books', 'libraryProfile', 'stats'));
    }
}
