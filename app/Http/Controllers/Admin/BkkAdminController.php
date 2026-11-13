<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BkkListing;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BkkAdminController extends Controller
{
    public function index(): View
    {
        $listings = BkkListing::latest('posted_at')->get();

        return view('admin.bkk.index', compact('listings'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'company_name' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'contact_info' => ['nullable', 'string', 'max:255'],
            'type' => ['required', 'in:kerja_tetap,magang,magang_rekrutmen'],
            'qualifications' => ['nullable', 'string'],
        ]);

        $validated['posted_at'] = now();

        BkkListing::create($validated);

        return back()->with('success', 'Lowongan berhasil ditambahkan.');
    }

    public function update(Request $request, BkkListing $listing): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'company_name' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'contact_info' => ['nullable', 'string', 'max:255'],
            'type' => ['required', 'in:kerja_tetap,magang,magang_rekrutmen'],
            'qualifications' => ['nullable', 'string'],
        ]);

        $listing->update($validated);

        return back()->with('success', 'Lowongan berhasil diperbarui.');
    }

    public function destroy(BkkListing $listing): RedirectResponse
    {
        $listing->delete();

        return back()->with('success', 'Lowongan berhasil dihapus.');
    }
}
