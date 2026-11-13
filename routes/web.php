<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PpdbController;
use App\Http\Controllers\BkkController;
use App\Http\Controllers\TracerStudyController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminAuthController;


/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
| Everything below is accessible without login, matching "Publik / Calon
| Siswa" in the perencanaan document. Admin routes (Dashboard, E-Rapor)
| live in a separate routes/admin.php behind auth+role middleware — not
| included in this pass yet.
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/profil', [PageController::class, 'profil'])->name('profil');

Route::get('/program', [PageController::class, 'program'])->name('program.index');
Route::get('/program/konsentrasi/{concentration}', [PageController::class, 'concentration'])
    ->name('program.concentration');

Route::get('/berita', [NewsController::class, 'index'])->name('berita.index');
Route::get('/berita/{news:slug}', [NewsController::class, 'show'])->name('berita.show');
Route::post('/berita/{news:slug}/komentar', [NewsController::class, 'storeComment'])->name('berita.comment');

Route::get('/galeri', [PageController::class, 'galeri'])->name('galeri');

Route::get('/e-library', [LibraryController::class, 'index'])->name('elibrary.index');

Route::get('/bkk', [BkkController::class, 'index'])->name('bkk');

Route::prefix('bkk/tracer-study')->name('bkk.tracer-study.')->group(function () {
    Route::get('/', [TracerStudyController::class, 'index'])->name('index');
    Route::post('/verify', [TracerStudyController::class, 'verify'])->name('verify');
    Route::post('/', [TracerStudyController::class, 'store'])->name('store');
});

// Tracer Study: see routes/tracer.php once that module is built (form still
// being finalized per the perencanaan doc).

Route::get('/kontak', [PageController::class, 'kontak'])->name('kontak');
Route::post('/kontak', [PageController::class, 'kontakStore'])->name('kontak.store');


// Alamat lama /ppdb dialihkan ke /spmb supaya tautan yang sudah tersebar tetap jalan.
Route::redirect('/ppdb', '/spmb', 301);
Route::redirect('/ppdb/cek-status', '/spmb/cek-status', 301);

Route::prefix('spmb')->name('ppdb.')->group(function () {
    Route::get('/', [PpdbController::class, 'create'])->name('create');
    Route::post('/', [PpdbController::class, 'store'])->name('store');
    Route::get('/berhasil/{registrationNumber}', [PpdbController::class, 'success'])->name('success');
    Route::get('/cek-status', [PpdbController::class, 'create'])->name('check-status'); // same page, tab switched client-side
    Route::post('/cek-status/check', [PpdbController::class, 'checkStatusApi'])->name('check-status.check');
    Route::get('/konsentrasi/{program}', [PpdbController::class, 'concentrationsByProgram'])->name('concentrations-by-program');
});

Route::get('/login', [AdminAuthController::class, 'show'])->name('login');
Route::post('/login', [AdminAuthController::class, 'login']);
Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
require __DIR__.'/admin.php';