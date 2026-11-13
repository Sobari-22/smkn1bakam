<?php

namespace App\Http\Controllers;

use App\Models\Concentration;
use App\Models\Facility;
use App\Models\Gallery;
use App\Models\Page;
use App\Models\Program;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Handles simple static/public pages. Each of these views is a stub for now
 * (resources/views/*.blade.php) — build out the real content per page next,
 * following the same layout as home.blade.php.
 */
class PageController extends Controller
{
    public function profil(): View
    {
        $pages = Page::whereIn('slug', ['sejarah', 'visi-misi', 'sambutan-kepala'])
            ->get()
            ->keyBy('slug');

        $facilities = Facility::ordered()->get();

        return view('profil', compact('pages', 'facilities'));
    }

    public function program(): View
    {
        // Semua program + konsentrasinya tampil di satu halaman daftar.
        // Detail tiap konsentrasi ada di halaman sendiri (lihat concentration()).
        $programs = Program::with('concentrations')->get();

        return view('program.index', compact('programs'));
    }

    public function concentration(Concentration $concentration): View
    {
        // Halaman informasi lengkap satu konsentrasi (menggantikan modal).
        $program = Program::findOrFail($concentration->program_id);

        // Untuk sidebar "Konsentrasi Lainnya".
        $others = Concentration::where('id', '!=', $concentration->id)->get();

        return view('program.show', compact('concentration', 'program', 'others'));
    }

    public function galeri(Request $request): View
    {
        $query = Gallery::ordered();

        if ($category = $request->get('category')) {
            $query->where('category', $category);
        }

        $galleries = $query->paginate(12)->withQueryString();
        $categories = Gallery::whereNotNull('category')->distinct()->pluck('category');

        return view('galeri', compact('galleries', 'categories'));
    }

    public function kontak(): View
    {
        return view('kontak');
    }
 
    public function kontakStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:2000'],
        ], [], [
            'name' => 'nama lengkap',
            'email' => 'email',
            'subject' => 'subjek',
            'message' => 'pesan',
        ]);
 
        ContactMessage::create($validated);
 
        // No mail-sending yet — admin reads submissions from the Dashboard
        // Admin "Pesan Masuk" list per §4.B. Wire Mail::send() here later if
        // the school also wants an email notification on new messages.
 
        return back()->with('success', 'Pesan Anda berhasil dikirim. Tim kami akan segera menghubungi Anda.');
    }
}