<?php

use App\Http\Controllers\AdminReportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// =================================================
// AREA PUBLIK
// =================================================

// 1. Halaman Utama (Login Siswa)
Route::get('/', [AuthController::class, 'showLogin'])->name('login');

// 2. Proses Login (POST)
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// 3. Register Siswa
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// 4. Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// =================================================
// HALAMAN LOGIN ADMIN (URL: /admin)
// =================================================
Route::get('/admin', [AuthController::class, 'showAdminLogin'])->name('admin.login');

// =================================================
// AREA SISWA (Prefix: /siswa)
// =================================================
Route::middleware(['auth', 'role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/dashboard', [ReportController::class, 'index'])->name('dashboard');
    Route::get('/create', [ReportController::class, 'create'])->name('create');
    Route::post('/store', [ReportController::class, 'store'])->name('store');
    Route::delete('/delete/{id}', [ReportController::class, 'destroy'])->name('destroy');
    Route::get('/report/{id}', [ReportController::class, 'show'])->name('show');
});

// =================================================
// AREA ADMIN (Prefix: /panel -> Hasilnya URL: /panel/dashboard)
// =================================================
Route::middleware(['auth', 'role:admin'])->prefix('panel')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminReportController::class, 'index'])->name('dashboard');
    Route::get('/report/{id}', [AdminReportController::class, 'show'])->name('show');
    Route::post('/report/{id}/status', [AdminReportController::class, 'updateStatus'])->name('status');
    Route::post('/report/{id}/comment', [AdminReportController::class, 'comment'])->name('comment');
    Route::delete('/report/{id}', [AdminReportController::class, 'destroy'])->name('destroy');
});