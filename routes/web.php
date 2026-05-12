<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\BungaPinjamanController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PinjamanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SimpananController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/lang/{locale}', function (string $locale) {
    if (in_array($locale, ['id', 'en'])) {
        Session::put('locale', $locale);
    }

    return redirect()->back();
})->name('lang.switch');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::resource('roles', RoleController::class)->except(['show']);
    Route::resource('permissions', PermissionController::class)->except(['show']);
    Route::resource('menus', MenuController::class)->except(['show']);
    Route::resource('anggota', AnggotaController::class)->parameters(['anggota' => 'anggota']);
    Route::resource('bunga-pinjaman', BungaPinjamanController::class)->except(['show'])->parameters(['bunga_pinjaman' => 'bungaPinjaman']);
    Route::resource('simpanan', SimpananController::class)->except(['show']);

    Route::get('pinjaman/simulasi', [PinjamanController::class, 'simulasi'])->name('pinjaman.simulasi');
    Route::post('pinjaman/{pinjaman}/approve', [PinjamanController::class, 'approve'])->name('pinjaman.approve');
    Route::post('pinjaman/{pinjaman}/reject', [PinjamanController::class, 'reject'])->name('pinjaman.reject');
    Route::get('pinjaman/{pinjaman}/cetak-kontrak', [PinjamanController::class, 'cetakKontrak'])->name('pinjaman.cetak-kontrak');
    Route::resource('pinjaman', PinjamanController::class)->except(['show']);
});

require __DIR__.'/auth.php';
