<?php

use App\Events\NewReportEvent;
use App\Http\Controllers\AdminReportController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserSettingController;
use App\Livewire\Admin\ReportsIndex as AdminReportsIndex;
use App\Livewire\Siswa\ReportsIndex as SiswaReportsIndex;
use Illuminate\Support\Facades\Route;

// 1. Halaman Utama (Login Siswa)
Route::get('/', [AuthController::class, 'showLogin'])->name('login');

// 2. Proses Login (POST)
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// 3. Register Siswa
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// 4. Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// login admin area

Route::get('/admin', [AuthController::class, 'showAdminLogin'])->name('admin.login');
Route::post('/admin', [AuthController::class, 'loginAdmin'])->name('admin.login.post');

// siswa

Route::middleware(['auth', 'role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/dashboard', SiswaReportsIndex::class)->name('dashboard');
    Route::get('/create', [ReportController::class, 'create'])->name('create');
    Route::post('/store', [ReportController::class, 'store'])->name('store');
    Route::delete('/delete/{id}', [ReportController::class, 'destroy'])->name('destroy');
    Route::get('/report/{id}', [ReportController::class, 'show'])->name('show');
    Route::get('/report/{id}/edit', [ReportController::class, 'edit'])->name('edit');
    Route::put('/report/{id}', [ReportController::class, 'update'])->name('update');

    Route::get('/settings', [UserSettingController::class, 'edit'])->name('settings.edit');
    Route::put('/settings', [UserSettingController::class, 'update'])->name('settings.update');
    Route::delete('/settings', [UserSettingController::class, 'destroy'])->name('settings.destroy');
});

// admin panel
Route::middleware(['auth', 'role:admin'])->prefix('panel')->name('admin.')->group(function () {
    Route::get('/dashboard', AdminReportsIndex::class)->name('dashboard');
    Route::get('/report/{id}', [AdminReportController::class, 'show'])->name('show');
    Route::post('/report/{id}/status', [AdminReportController::class, 'updateStatus'])->name('update-status');
    Route::post('/report/{id}/response', [AdminReportController::class, 'storeResponse'])->name('response');
    Route::delete('/report/{id}', [AdminReportController::class, 'destroy'])->name('destroy');
    Route::get('/users', [AdminUserController::class, 'index'])->name('users');
    Route::delete('/users/{id}', [AdminUserController::class, 'destroy'])->name('users.destroy');
});