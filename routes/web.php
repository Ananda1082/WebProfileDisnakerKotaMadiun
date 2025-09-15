<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PelakuIndustriController;
use App\Http\Controllers\KbliController;
use App\Http\Controllers\SkalaController;
use App\Http\Controllers\JenisController;
use App\Http\Controllers\Admin\UserApprovalController;


Route::get('/', function () {
    return view('welcome');
}); // <<< tutup dulu
Route::view('/tentang-kami', 'tentang-kami')->name('tentang-kami');
Route::view('/kontak', 'kontak')->name('kontak');

// Route publik (read-only)
Route::get('/data-pelaku', [PelakuIndustriController::class, 'publicIndex'])
    ->name('data-pelaku')
    ->withoutMiddleware(['auth','verified']);
Route::get('/', [HomeController::class, 'index']);

Route::get('/kbli', [KbliController::class, 'index'])
    ->name('dasbor.kbli');

Route::middleware(['auth', 'approved'])->group(function () {
    // ... route aplikasi Anda (dashboard dsb)
});

Route::middleware(['auth', 'admin'])->prefix('admin')->as('admin.')->group(function () {
    Route::get('/users/pending', [UserApprovalController::class, 'index'])
        ->name('users.pending');
    Route::post('/users/{user}/approve', [UserApprovalController::class, 'approve'])
        ->name('users.approve');
    Route::delete('/users/{user}', [UserApprovalController::class, 'destroy'])
        ->name('users.destroy');
});
Route::middleware(['auth'])->group(function () {
    Route::resource('pelaku-industri', PelakuIndustriController::class);

    Route::post('/pelaku-industri/import', [PelakuIndustriController::class, 'import'])
        ->name('pelaku-industri.import');
    // routes/web.php
    Route::post('/pelaku-industri/bulk-destroy', [\App\Http\Controllers\PelakuIndustriController::class, 'bulkDestroy'])
    ->name('pelaku-industri.bulk-destroy');
    Route::get('/pelaku-industri/template', [PelakuIndustriController::class, 'template'])
        ->name('pelaku-industri.template');

    Route::get('/skala-usaha', [SkalaController::class, 'index'])->name('skala.index');
    Route::get('/jenis-perusahaan', [JenisController::class, 'index'])->name('jenis.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard')->middleware('verified');
});

require __DIR__.'/auth.php';
