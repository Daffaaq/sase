<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CategoryArchiveIncomingController;
use App\Http\Controllers\CategoryArchiveOutgoingController;
use App\Http\Controllers\CategoryIncomingLetterController;
use App\Http\Controllers\CategoryOutgoingKadivController;
use App\Http\Controllers\CategoryOutgoingLetterController;
use App\Http\Controllers\CatgeoryArchiveIncomingKadivController;
use App\Http\Controllers\CatgeoryArchiveOutgoingKadivController;
use App\Http\Controllers\CatgeoryIncomingKadivController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ManajemenUserController;
use App\Http\Controllers\SuratAllSuperadminController;
use App\Http\Controllers\SuratGuestSuperadminController;
use App\Http\Controllers\SuratMasukKadivController;
use App\Http\Controllers\SuratPegawaiSuperadminController;
use App\Http\Controllers\SuratSuperadminController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('konstruksi');
});

Route::get('/nonexistent', function () {
    abort(404);
});

Route::get('/login-username', function () {
    return view('Auth.loginUsername');
});

Route::middleware(['guest'])->group(function () {
    Route::get('/surat-Tamu', [SuratGuestSuperadminController::class, 'viewform'])->name('view-form');
    Route::post('/surat-Tamu/post', [SuratGuestSuperadminController::class, 'sendSurat'])->name('post-surat-eksternal');
    Route::get('/login-email', [LoginController::class, 'indexEmail'])->name('login-email');
    Route::get('/login-username', [LoginController::class, 'indexUsername'])->name('login-username');
    Route::post('/login-email/post', [LoginController::class, 'loginEmail'])->name('login-email-post');
    Route::post('/login-username/post', [LoginController::class, 'loginUsername'])->name('login-username-post');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'checkStatus:aktif', 'checkRole:kadiv', 'check.uuid'])->group(function () {
    Route::get('/dashboardkadiv', [DashboardController::class, 'indexKadiv'])->name('dashboardKadiv');
    Route::prefix('/dashboardkadiv')->group(function () {
        Route::resource('/Kategori-Surat-Masuk-Kadiv', CatgeoryIncomingKadivController::class);
        Route::post('/Kategori-Surat-Masuk-Kadiv/list', [CatgeoryIncomingKadivController::class, 'list'])->name('kategori-surat-masuk-list-kadiv');
    });

    Route::prefix('/dashboardkadiv')->group(function () {
        Route::resource('/Kategori-Surat-Keluar-Kadiv', CategoryOutgoingKadivController::class);
        Route::post('/Kategori-Surat-Keluar-Kadiv/list', [CategoryOutgoingKadivController::class, 'list'])->name('kategori-surat-keluar-list-kadiv');
    });
    Route::prefix('/dashboardkadiv')->group(function () {
        Route::resource('/Kategori-Arsip-Surat-Masuk-Kadiv', CatgeoryArchiveIncomingKadivController::class);
        Route::post('/Kategori-Arsip-Surat-Masuk-Kadiv/list', [CatgeoryArchiveIncomingKadivController::class, 'list'])->name('kategori-arsip-surat-masuk-list-kadiv');
    });
    Route::prefix('/dashboardkadiv')->group(function () {
        Route::resource('/Kategori-Arsip-Surat-Out-Kadiv', CatgeoryArchiveOutgoingKadivController::class);
        Route::post('/Kategori-Arsip-Surat-Out-Kadiv/list', [CatgeoryArchiveOutgoingKadivController::class, 'list'])->name('kategori-arsip-surat-keluar-list-kadiv');
    });
    Route::prefix('/dashboardkadiv')->group(function () {
        Route::get('/Surat-Masuk', [SuratMasukKadivController::class, 'index'])->name('surat-masuk-index-kadiv');
        Route::post('/Surat-Masuk/{uuid}/accept', [SuratMasukKadivController::class, 'accepted'])->name('surat-masuk.accept.kadiv');
        Route::post('/Surat-Masuk/{uuid}/reject', [SuratMasukKadivController::class, 'rejected'])->name('surat-masuk.reject.kadiv');
        Route::post('/Surat-Masuk/list', [SuratMasukKadivController::class, 'list'])->name('surat-masuk-list-kadiv');
    });
});

Route::middleware(['auth', 'checkStatus:aktif', 'checkRole:superadmin', 'check.uuid'])->group(function () {
    Route::get('/dashboardSuperadmin', [DashboardController::class, 'indexSuperadmin'])->name('dashboardSuperadmin');
    Route::prefix('/dashboardSuperadmin')->group(function () {
        Route::get('/Users', [ManajemenUserController::class, 'index'])->name('manajemen-user-index');
        Route::get('/Users/create', [ManajemenUserController::class, 'create']);
        Route::post('/Users/store', [ManajemenUserController::class, 'store'])->name('manajemen-user-store');
        Route::get('/Users/edit/{uuid}', [ManajemenUserController::class, 'edit']);
        Route::put('/Users/update/{uuid}', [ManajemenUserController::class, 'update']);
        Route::delete('/Users/destroy/{uuid}', [ManajemenUserController::class, 'destroy']);
        Route::post('Users/list', [ManajemenUserController::class, 'list'])->name('users.list');
    });
    Route::prefix('/dashboardSuperadmin')->group(function () {
        Route::resource('/Kategori-Surat-Masuk', CategoryIncomingLetterController::class);
        Route::post('/Kategori-Surat-Masuk/list', [CategoryIncomingLetterController::class, 'list'])->name('kategori-surat-masuk-list');
    });
    Route::prefix('/dashboardSuperadmin')->group(function () {
        Route::resource('/Kategori-Surat-Keluar', CategoryOutgoingLetterController::class);
        Route::post('/Kategori-Surat-Keluar/list', [CategoryOutgoingLetterController::class, 'list'])->name('kategori-surat-keluar-list');
    });
    Route::prefix('/dashboardSuperadmin')->group(function () {
        Route::resource('/Kategori-Arsip-Surat-Masuk', CategoryArchiveIncomingController::class);
        Route::post('/Kategori-Arsip-Surat-Masuk/list', [CategoryArchiveIncomingController::class, 'list'])->name('kategori-arsip-surat-masuk-list');
    });
    Route::prefix('/dashboardSuperadmin')->group(function () {
        Route::resource('/Kategori-Arsip-Surat-Keluar', CategoryArchiveOutgoingController::class);
        Route::post('/Kategori-Arsip-Surat-Keluar/list', [CategoryArchiveOutgoingController::class, 'list'])->name('kategori-arsip-surat-keluar-list');
    });
});

Route::middleware(['auth', 'checkStatus:aktif', 'checkRole:pegawai', 'check.uuid'])->group(function () {
    Route::get('/dashboardpegawai', [DashboardController::class, 'indexPegawai'])->name('dashboardPegawai');
});
