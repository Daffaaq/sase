<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ManajemenUserController;
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
    return view('Auth.loginEmail');
});
Route::get('/login-username', function () {
    return view('Auth.loginUsername');
});

Route::middleware(['guest'])->group(function () {
    Route::get('/login-email', [LoginController::class, 'indexEmail'])->name('login-email');
    Route::get('/login-username', [LoginController::class, 'indexUsername'])->name('login-username');
    Route::post('/login-email/post', [LoginController::class, 'loginEmail'])->name('login-email-post');
    Route::post('/login-username/post', [LoginController::class, 'loginUsername'])->name('login-username-post');
});

Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

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
});

Route::middleware(['auth', 'checkStatus:aktif', 'checkRole:pegawai'])->group(function () {
    Route::get('/dashboardpegawai', [DashboardController::class, 'indexPegawai']);
});
