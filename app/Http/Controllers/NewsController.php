<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\NewsComment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function index(Request $request): View
    {
        $query = News::published()
            ->withCount('approvedComments')
            ->latest('published_at');

        // Tabel `news` tidak punya kolom excerpt (excerpt hanyalah accessor di model),
        // jadi pencarian dilakukan pada judul dan isi berita.
        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        if ($category = $request->get('kategori')) {
            $query->where('category', $category);
        }

        $news = $query->paginate(9)->withQueryString();

        // Static for now — swap for News::distinct()->pluck('category') once
        // there's enough real data to know the canonical category list.
        $categories = ['Prestasi', 'Kegiatan', 'PPDB', 'Pengumuman'];

        return view('berita.index', compact('news', 'categories'));
    }

    public function show(News $news): View
    {
        // Berita yang belum terbit / dijadwalkan tidak boleh dibuka lewat URL langsung.
        abort_unless($news->published_at && $news->published_at->lte(now()), 404);

        $news->load('approvedComments');

        // "Baca Juga": utamakan berita dengan kategori yang sama, lalu lengkapi dengan yang terbaru.
        $related = News::published()
            ->withCount('approvedComments')
            ->where('id', '!=', $news->id)
            ->where('category', $news->category)
            ->latest('published_at')
            ->take(5)
            ->get();

        if ($related->count() < 5) {
            $more = News::published()
                ->withCount('approvedComments')
                ->where('id', '!=', $news->id)
                ->whereNotIn('id', $related->pluck('id'))
                ->latest('published_at')
                ->take(5 - $related->count())
                ->get();

            $related = $related->concat($more);
        }

        return view('berita.show', compact('news', 'related'));
    }

    /**
     * Comments are never shown immediately — they're saved as 'pending' and
     * only appear on the article after an admin approves them in the
     * Dashboard Admin comment moderation screen.
     */
    public function storeComment(Request $request, News $news): RedirectResponse
    {
        $validated = $request->validate([
            'nama_pengirim' => ['required', 'string', 'max:255'],
            'isi_komentar' => ['required', 'string', 'max:2000'],
            // TODO: add 'g-recaptcha-response' => ['required'] once reCAPTCHA is wired up
        ]);

        NewsComment::create([
            'news_id' => $news->id,
            'nama_pengirim' => $validated['nama_pengirim'],
            'isi_komentar' => $validated['isi_komentar'],
            'status' => 'pending',
        ]);

        return back()->with('success', 'Komentar Anda sedang menunggu persetujuan admin.');
    }
}