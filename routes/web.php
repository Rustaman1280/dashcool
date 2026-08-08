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
        
        // Pendaftar Routes
        Route::get('/pendaftar', [SpmbController::class, 'pendaftar'])->name('pendaftar');
        Route::get('/pendaftar/{id}', [SpmbController::class, 'detail'])->name('detail');
        Route::post('/pendaftar/{id}/status', [SpmbController::class, 'updateStatus'])->name('pendaftar.status');

        // Pengaturan Jalur & Kuota Routes
        Route::get('/pengaturan', [SpmbController::class, 'pengaturan'])->name('pengaturan');
        Route::post('/pengaturan', [SpmbController::class, 'storeJalur'])->name('pengaturan.store');
        Route::put('/pengaturan/{id}', [SpmbController::class, 'updateJalur'])->name('pengaturan.update');
        Route::delete('/pengaturan/{id}', [SpmbController::class, 'destroyJalur'])->name('pengaturan.destroy');
    });

});
