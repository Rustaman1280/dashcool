<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SpmbController;
use App\Http\Controllers\AuthController;

// Guest Routes
Route::middleware(['guest.custom'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Logout Route
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Redirect root to Dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
})->name('home');

// Protected Routes (Requires Authentication)
Route::middleware(['auth.custom'])->group(function () {
    
    // Main Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // SPMB Route Group with prefix /spmb and name prefix spmb.
    Route::prefix('spmb')->name('spmb.')->group(function () {
        Route::get('/', [SpmbController::class, 'index'])->name('index');
        
        // 1. Input SPMB Pendaftar Routes
        Route::get('/input', [SpmbController::class, 'create'])->name('create');
        Route::post('/input', [SpmbController::class, 'store'])->name('store');

        // 2. Data & Verifikasi Pendaftar Routes
        Route::get('/pendaftar', [SpmbController::class, 'pendaftar'])->name('pendaftar');
        Route::get('/pendaftar/{id}', [SpmbController::class, 'detail'])->name('detail');
        Route::post('/pendaftar/{id}/status', [SpmbController::class, 'updateStatus'])->name('pendaftar.status');

        // 3. Rekap SPMB Routes
        Route::get('/rekap', [SpmbController::class, 'rekap'])->name('rekap');
        Route::get('/rekap/export', [SpmbController::class, 'exportRekap'])->name('rekap.export');

        // 4. Update Kelas SPMB Routes
        Route::get('/update-kelas', [SpmbController::class, 'kelas'])->name('kelas');
        Route::post('/update-kelas', [SpmbController::class, 'updateKelas'])->name('kelas.update');

        // 5. Set SPMB & Pengaturan Jalur Routes
        Route::get('/pengaturan', [SpmbController::class, 'pengaturan'])->name('pengaturan');
        Route::post('/pengaturan', [SpmbController::class, 'storeJalur'])->name('pengaturan.store');
        Route::put('/pengaturan/{id}', [SpmbController::class, 'updateJalur'])->name('pengaturan.update');
        Route::delete('/pengaturan/{id}', [SpmbController::class, 'destroyJalur'])->name('pengaturan.destroy');
        Route::post('/pengaturan/sistem', [SpmbController::class, 'updateSistem'])->name('pengaturan.sistem');

        // Tahun Ajaran Routes
        Route::post('/pengaturan/tahun-ajaran', [SpmbController::class, 'storeTahunAjaran'])->name('pengaturan.tahun-ajaran.store');
        Route::put('/pengaturan/tahun-ajaran/{id}', [SpmbController::class, 'updateTahunAjaran'])->name('pengaturan.tahun-ajaran.update');
        Route::delete('/pengaturan/tahun-ajaran/{id}', [SpmbController::class, 'destroyTahunAjaran'])->name('pengaturan.tahun-ajaran.destroy');
        Route::post('/pengaturan/tahun-ajaran/{id}/toggle-active', [SpmbController::class, 'toggleActiveTahunAjaran'])->name('pengaturan.tahun-ajaran.toggle-active');
    });

});
