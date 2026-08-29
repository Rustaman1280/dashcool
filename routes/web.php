<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SpmbController;
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\AuthController;

// Guest Routes
Route::middleware(['guest.custom'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Public SPMB Portal Routes (For Students & Parents - Mobile Optimized)
Route::prefix('spmb')->name('spmb.public.')->group(function () {
    Route::get('/daftar', [SpmbController::class, 'publicRegister'])->name('register');
    Route::post('/daftar', [SpmbController::class, 'publicStore'])->name('store');
    Route::get('/berhasil/{id}', [SpmbController::class, 'publicSuccess'])->name('success');
    Route::get('/cek-status', [SpmbController::class, 'publicStatus'])->name('status');
    Route::post('/cek-status', [SpmbController::class, 'publicCheckStatus'])->name('check');
    Route::get('/bukti/{id}', [SpmbController::class, 'publicProof'])->name('proof');
});

// Shortcuts for easy access
Route::get('/daftar', function () {
    return redirect()->route('spmb.public.register');
})->name('daftar');

Route::get('/status', function () {
    return redirect()->route('spmb.public.status');
})->name('status');

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
    });

    Route::prefix('master-data')->name('master-data.')->group(function () {
        Route::get('/', [MasterDataController::class, 'index'])->name('index');

        // Tahun Ajaran Routes
        Route::post('/tahun-ajaran', [MasterDataController::class, 'storeTahunAjaran'])->name('tahun-ajaran.store');
        Route::put('/tahun-ajaran/{id}', [MasterDataController::class, 'updateTahunAjaran'])->name('tahun-ajaran.update');
        Route::delete('/tahun-ajaran/{id}', [MasterDataController::class, 'destroyTahunAjaran'])->name('tahun-ajaran.destroy');
        Route::post('/tahun-ajaran/{id}/toggle-active', [MasterDataController::class, 'toggleActiveTahunAjaran'])->name('tahun-ajaran.toggle-active');
    });

});
