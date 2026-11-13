<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class GalleryAdminController extends Controller
{
    public function index(): View
    {
        $galleries = Gallery::ordered()->paginate(12);
        $categories = ['Praktikum', 'Kegiatan', 'Prestasi', 'Fasilitas'];

        return view('admin.gallery.index', compact('galleries', 'categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'type' => ['required', 'in:photo,video'],
            'event_date' => ['nullable', 'date'],
            'file' => ['required_if:type,photo', 'nullable', 'image', 'max:5120'],
            'video_url' => ['required_if:type,video', 'nullable', 'url', 'max:500'],
        ]);

        $path = null;
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('galeri', 'public');
        }

        Gallery::create([
            'title' => $validated['title'],
            'category' => $validated['category'] ?? null,
            'type' => $validated['type'],
            'event_date' => $validated['event_date'] ?? null,
            'file_path' => $path ?? '',
            'video_url' => $validated['video_url'] ?? null,
            'order' => Gallery::max('order') + 1,
        ]);

        return back()->with('success', 'Dokumentasi berhasil ditambahkan.');
    }

    public function update(Request $request, Gallery $gallery): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'event_date' => ['nullable', 'date'],
            'file' => ['nullable', 'image', 'max:5120'],
            'video_url' => ['nullable', 'url', 'max:500'],
        ]);

        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('galeri', 'public');
        }

        $gallery->update($validated);

        return back()->with('success', 'Dokumentasi berhasil diperbarui.');
    }

    public function destroy(Gallery $gallery): RedirectResponse
    {
        $gallery->delete();

        return back()->with('success', 'Dokumentasi berhasil dihapus.');
    }
}
