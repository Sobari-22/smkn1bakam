<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LibraryBook;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LibraryAdminController extends Controller
{
    public function index(): View
    {
        $books = LibraryBook::orderBy('no_induk_buku')->paginate(15);

        return view('admin.library.index', compact('books'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'no_induk_buku' => ['required', 'string', 'max:50', 'unique:library_books,no_induk_buku'],
            'no_inventaris_buku' => ['nullable', 'string', 'max:50'],
            'judul' => ['required', 'string', 'max:255'],
            'pengarang' => ['nullable', 'string', 'max:255'],
            'penerbit' => ['nullable', 'string', 'max:255'],
            'tahun_terbit' => ['nullable', 'digits:4'],
            'klasifikasi' => ['nullable', 'string', 'max:50'],
            'jumlah' => ['required', 'integer', 'min:0'],
        ]);

        LibraryBook::create($validated);

        return back()->with('success', 'Buku berhasil ditambahkan.');
    }

    public function update(Request $request, LibraryBook $book): RedirectResponse
    {
        $validated = $request->validate([
            'no_induk_buku' => ['required', 'string', 'max:50', 'unique:library_books,no_induk_buku,'.$book->id],
            'no_inventaris_buku' => ['nullable', 'string', 'max:50'],
            'judul' => ['required', 'string', 'max:255'],
            'pengarang' => ['nullable', 'string', 'max:255'],
            'penerbit' => ['nullable', 'string', 'max:255'],
            'tahun_terbit' => ['nullable', 'digits:4'],
            'klasifikasi' => ['nullable', 'string', 'max:50'],
            'jumlah' => ['required', 'integer', 'min:0'],
        ]);

        $book->update($validated);

        return back()->with('success', 'Data buku berhasil diperbarui.');
    }

    public function destroy(LibraryBook $book): RedirectResponse
    {
        $book->delete();

        return back()->with('success', 'Buku berhasil dihapus.');
    }
}
