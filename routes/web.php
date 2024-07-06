<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ManajemenUserController;
use App\Http\Controllers\SuratAllSuperadminController;
use App\Http\Controllers\SuratGuestSuperadminController;
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
    return view('Guest.kirim_surat');
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

Route::middleware(['auth', 'checkStatus:aktif', 'checkRole:kadiv'])->group(function () {
    Route::get('/dashboardkadiv', [DashboardController::class, 'indexKadiv']);
});

Route::middleware(['auth', 'checkStatus:aktif', 'checkRole:superadmin'])->group(function () {
    Route::get('/dashboardSuperadmin', [DashboardController::class, 'indexSuperadmin'])->name('dashboardSuperadmin');
    Route::prefix('/dashboardSuperadmin')->group(function () {
        Route::get('/Users', [ManajemenUserController::class, 'index'])->name('manajemen-user-index');
        Route::get('/Users/create', [ManajemenUserController::class, 'create']);
        Route::post('/Users/store', [ManajemenUserController::class, 'store'])->name('manajemen-user-store');
        Route::get('/Users/edit/{id}', [ManajemenUserController::class, 'edit']);
        Route::put('/Users/update/{id}', [ManajemenUserController::class, 'update']);
        Route::delete('/Users/destroy/{id}', [ManajemenUserController::class, 'destroy']);
        Route::post('Users/list', [ManajemenUserController::class, 'list'])->name('users.list');
    });
    Route::prefix('/dashboardSuperadmin')->group(function () {
        Route::get('/surat-internal', [SuratSuperadminController::class, 'index'])->name('manajemen-letter-in-index');
        Route::get('/surat-internal/create', [SuratSuperadminController::class, 'create']);
        Route::post('/surat-internal/storeinternal', [SuratSuperadminController::class, 'storeSuratInternal'])->name('manajemen-letter-internal-store');
        Route::get('/surat-internal/edit/{id}', [SuratSuperadminController::class, 'editSuratInternal']);
        Route::put('/surat-internal/update/{id}', [SuratSuperadminController::class, 'updateSuratInternal'])->name('manajemen-letter-internal-update');
        Route::delete('/surat-internal/destroy/{id}', [SuratSuperadminController::class, 'destroy']);
        Route::post('surat-internal/list', [SuratSuperadminController::class, 'list'])->name('sin.list');
    });
    Route::prefix('/dashboardSuperadmin')->group(function () {
        Route::get('/surat-all', [SuratAllSuperadminController::class, 'index'])->name('manajemen-letter-index');
        Route::post('surat-all/list', [SuratAllSuperadminController::class, 'list'])->name('sin.all.list');
    });
});

Route::middleware(['auth', 'checkStatus:aktif', 'checkRole:pegawai'])->group(function () {
    Route::get('/dashboardpegawai', [DashboardController::class, 'indexPegawai']);
});
