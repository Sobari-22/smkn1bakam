<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Support\RichText;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class NewsAdminController extends Controller
{
    public function index(): View
    {
        $news = News::latest()->paginate(10);

        return view('admin.news.index', compact('news'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'content' => ['required', 'string'],
            'thumbnail' => ['nullable', 'image', 'max:5120'],
            'action' => ['required', 'in:publish,draft'],
        ]);

        $path = null;
        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('news', 'public');
        }

        News::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']).'-'.Str::random(5),
            'category' => $validated['category'],
            'content' => RichText::toHtml($validated['content']),
            'thumbnail' => $path,
            'published_at' => $validated['action'] === 'publish' ? now() : null,
        ]);

        return back()->with('success', $validated['action'] === 'publish' ? 'Berita berhasil diterbitkan.' : 'Berita disimpan sebagai draft.');
    }

    public function edit(News $news): View
    {
        $categories = ['Prestasi', 'PPDB', 'Kegiatan', 'Pengumuman'];

        // Kategori lama di luar daftar standar tetap bisa dipilih agar tidak hilang diam-diam.
        if ($news->category && ! in_array($news->category, $categories, true)) {
            $categories[] = $news->category;
        }

        $content = RichText::toPlain($news->content);

        return view('admin.news.edit', compact('news', 'categories', 'content'));
    }

    public function update(Request $request, News $news): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'content' => ['required', 'string'],
            'thumbnail' => ['nullable', 'image', 'max:5120'],
            'remove_thumbnail' => ['nullable', 'boolean'],
            'action' => ['required', 'in:save,publish,draft'],
        ]);

        // Slug sengaja tidak diubah supaya link berita yang sudah dibagikan tetap berfungsi.
        $news->title = trim($validated['title']);
        $news->category = $validated['category'];
        $news->content = RichText::toHtml($validated['content']);

        if ($request->hasFile('thumbnail')) {
            $this->deleteThumbnail($news);
            $news->thumbnail = $request->file('thumbnail')->store('news', 'public');
        } elseif ($request->boolean('remove_thumbnail')) {
            $this->deleteThumbnail($news);
            $news->thumbnail = null;
        }

        switch ($validated['action']) {
            case 'publish':
                // Berita yang sudah terbit mempertahankan tanggal terbitnya;
                // draft atau yang masih terjadwal diterbitkan sekarang.
                if (! $news->published_at || $news->published_at->isFuture()) {
                    $news->published_at = now();
                }
                $message = 'Berita berhasil diperbarui dan diterbitkan.';
                break;

            case 'draft':
                $news->published_at = null;
                $message = 'Berita dikembalikan ke draft.';
                break;

            default:
                $message = 'Perubahan berita berhasil disimpan.';
        }

        $news->save();

        return redirect()->route('admin.news.index')->with('success', $message);
    }

    public function destroy(News $news): RedirectResponse
    {
        $this->deleteThumbnail($news);
        $news->delete();

        return back()->with('success', 'Berita berhasil dihapus.');
    }

    private function deleteThumbnail(News $news): void
    {
        if (! empty($news->thumbnail)) {
            Storage::disk('public')->delete($news->thumbnail);
        }
    }
}
