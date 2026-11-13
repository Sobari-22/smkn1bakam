<?php

use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\BkkAdminController;
use App\Http\Controllers\Admin\CommentModerationController;
use App\Http\Controllers\Admin\FacilityAdminController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ErapotController;
use App\Http\Controllers\Admin\GalleryAdminController;
use App\Http\Controllers\Admin\LibraryAdminController;
use App\Http\Controllers\Admin\NewsAdminController;
use App\Http\Controllers\Admin\PpdbAdminController;
use App\Http\Controllers\Admin\ProgramAdminController;
use App\Http\Controllers\Admin\SchoolProfileAdminController;
use App\Http\Controllers\Admin\SettingsAdminController;
use App\Http\Controllers\Admin\TeacherAdminController;
use App\Http\Controllers\Admin\TracerRecapController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
| Everything here requires login (Guru/Staf — see perencanaan doc §2).
| Pembatasan akses per role memakai middleware `permission:<kunci>`
| (lihat config/roles.php untuk peta role -> kunci akses). Dashboard dapat
| dibuka semua user yang login. Kunci 'akademik' dan 'pengaturan' tidak
| diberikan ke role mana pun selain superadmin ('*').
*/

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Profil akun sendiri: semua user yang login boleh (tanpa middleware permission).
    Route::get('/profil-saya', [AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil-saya', [AdminProfileController::class, 'update'])->name('profile.update');
    Route::put('/profil-saya/password', [AdminProfileController::class, 'updatePassword'])->name('profile.password');

    Route::redirect('/ppdb', '/admin/spmb');

    Route::middleware('permission:ppdb')->prefix('spmb')->name('ppdb.')->group(function () {
        Route::get('/', [PpdbAdminController::class, 'index'])->name('index');
        Route::patch('/pendaftaran', [PpdbAdminController::class, 'updateRegistration'])->name('registration');
        Route::patch('/jadwal', [PpdbAdminController::class, 'updateSchedule'])->name('schedule');
        Route::patch('/{registrant}/status', [PpdbAdminController::class, 'updateStatus'])->name('status');
        Route::get('/export', [PpdbAdminController::class, 'export'])->name('export');
        Route::get('/export/xlsx', [PpdbAdminController::class, 'exportXlsx'])->name('export.xlsx');
        Route::get('/export/pdf', [PpdbAdminController::class, 'exportPdf'])->name('export.pdf');
    });

    Route::middleware('permission:berita')->prefix('berita')->name('news.')->group(function () {
        Route::get('/', [NewsAdminController::class, 'index'])->name('index');
        Route::post('/', [NewsAdminController::class, 'store'])->name('store');
        Route::get('/{news}/edit', [NewsAdminController::class, 'edit'])->name('edit');
        Route::put('/{news}', [NewsAdminController::class, 'update'])->name('update');
        Route::delete('/{news}', [NewsAdminController::class, 'destroy'])->name('destroy');
    });

    Route::middleware('permission:berita')->prefix('komentar')->name('comments.')->group(function () {
        Route::get('/', [CommentModerationController::class, 'index'])->name('index');
        Route::patch('/{comment}/approve', [CommentModerationController::class, 'approve'])->name('approve');
        Route::patch('/{comment}/reject', [CommentModerationController::class, 'reject'])->name('reject');
    });

    Route::middleware('permission:pesan')->prefix('pesan')->name('messages.')->group(function () {
        Route::get('/', [ContactMessageController::class, 'index'])->name('index');
        Route::patch('/{message}/baca', [ContactMessageController::class, 'markRead'])->name('read');
        Route::patch('/{message}/belum-dibaca', [ContactMessageController::class, 'markUnread'])->name('unread');
        Route::delete('/{message}', [ContactMessageController::class, 'destroy'])->name('destroy');
    });

    Route::middleware('permission:profil')->prefix('profil-sekolah')->name('school-profile.')->group(function () {
        Route::get('/', [SchoolProfileAdminController::class, 'index'])->name('index');
        Route::put('/{section}', [SchoolProfileAdminController::class, 'update'])
            ->where('section', 'sejarah|visi-misi|sambutan-kepala')->name('update');
        Route::delete('/{section}', [SchoolProfileAdminController::class, 'destroy'])
            ->where('section', 'sejarah|visi-misi|sambutan-kepala')->name('destroy');
    });

    Route::middleware('permission:profil')->prefix('sarana')->name('facilities.')->group(function () {
        Route::get('/', [FacilityAdminController::class, 'index'])->name('index');
        Route::post('/', [FacilityAdminController::class, 'store'])->name('store');
        Route::put('/{facility}', [FacilityAdminController::class, 'update'])->name('update');
        Route::delete('/{facility}', [FacilityAdminController::class, 'destroy'])->name('destroy');
    });

    Route::middleware('permission:galeri')->prefix('galeri')->name('gallery.')->group(function () {
        Route::get('/', [GalleryAdminController::class, 'index'])->name('index');
        Route::post('/', [GalleryAdminController::class, 'store'])->name('store');
        Route::patch('/{gallery}', [GalleryAdminController::class, 'update'])->name('update');
        Route::delete('/{gallery}', [GalleryAdminController::class, 'destroy'])->name('destroy');
    });

    Route::middleware('permission:elibrary')->prefix('e-library')->name('library.')->group(function () {
        Route::get('/', [LibraryAdminController::class, 'index'])->name('index');
        Route::post('/', [LibraryAdminController::class, 'store'])->name('store');
        Route::patch('/{book}', [LibraryAdminController::class, 'update'])->name('update');
        Route::delete('/{book}', [LibraryAdminController::class, 'destroy'])->name('destroy');
    });

    Route::middleware('permission:bkk')->prefix('bkk')->name('bkk.')->group(function () {
        Route::get('/', [BkkAdminController::class, 'index'])->name('index');
        Route::post('/', [BkkAdminController::class, 'store'])->name('store');
        Route::patch('/{listing}', [BkkAdminController::class, 'update'])->name('update');
        Route::delete('/{listing}', [BkkAdminController::class, 'destroy'])->name('destroy');
    });

    Route::middleware('permission:akademik')->prefix('program')->name('program.')->group(function () {
        // Program keahlian (jurusan)
        Route::get('/', [ProgramAdminController::class, 'index'])->name('index');
        Route::get('/create', [ProgramAdminController::class, 'createProgram'])->name('create');
        Route::post('/', [ProgramAdminController::class, 'storeProgram'])->name('store');
        Route::get('/{program}/edit', [ProgramAdminController::class, 'editProgram'])->name('edit');
        Route::put('/{program}', [ProgramAdminController::class, 'updateProgram'])->name('update');

        // Konsentrasi
        Route::get('/{program}/konsentrasi/create', [ProgramAdminController::class, 'createConcentration'])->name('concentrations.create');
        Route::post('/{program}/konsentrasi', [ProgramAdminController::class, 'storeConcentration'])->name('concentrations.store');
        Route::get('/konsentrasi/{concentration}/edit', [ProgramAdminController::class, 'editConcentration'])->name('concentrations.edit');
        Route::put('/konsentrasi/{concentration}', [ProgramAdminController::class, 'updateConcentration'])->name('concentrations.update');
        Route::patch('/konsentrasi/{concentration}/kuota', [ProgramAdminController::class, 'updateQuota'])->name('concentrations.quota');
    });

    Route::middleware('permission:akademik')->prefix('guru')->name('teachers.')->group(function () {
        Route::get('/', [TeacherAdminController::class, 'index'])->name('index');
        Route::get('/template', [TeacherAdminController::class, 'template'])->name('template');
        Route::post('/import', [TeacherAdminController::class, 'import'])->name('import');
        Route::post('/', [TeacherAdminController::class, 'store'])->name('store');
        Route::patch('/{teacher}', [TeacherAdminController::class, 'update'])->name('update');
        Route::delete('/{teacher}', [TeacherAdminController::class, 'destroy'])->name('destroy');
    });

    Route::get('/e-rapor', [ErapotController::class, 'index'])->middleware('permission:erapor')->name('erapor.index');

    Route::get('/tracer-study', [TracerRecapController::class, 'index'])->middleware('permission:tracer')->name('tracer-study.index');

    Route::middleware('permission:pengaturan')->prefix('pengaturan')->name('settings.')->group(function () {
        Route::get('/', [SettingsAdminController::class, 'index'])->name('index');
        Route::post('/umum', [SettingsAdminController::class, 'updateGeneral'])->name('general');
        Route::post('/media-sosial', [SettingsAdminController::class, 'updateSocial'])->name('social');
        Route::post('/admin', [SettingsAdminController::class, 'storeAdmin'])->name('admins.store');
        Route::patch('/admin/{user}/password', [SettingsAdminController::class, 'updatePassword'])->name('admins.password');
    });

});