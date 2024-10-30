<?php

use App\Models\DetailPeminjaman;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ViewController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\DetailPeminjamanController;
use App\Http\Controllers\KategoriBarangController; // Add this line

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('pages.dashboard', [
//         'title' => 'Dashboard'
//     ]);
// })->middleware(['auth', 'verified']);

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [ViewController::class, 'index'])->name('dashboard');
    Route::middleware(['admin'])->group(function () {
    Route::resource('lokasi', LokasiController::class);
    Route::get('lokasi-data', [LokasiController::class, 'getData'])->name('lokasi.data');
    Route::resource('barang', BarangController::class)->middleware('admin');
    Route::get('barang-data', [BarangController::class, 'getData'])->name('barang.data');
    Route::resource('kategori', KategoriController::class)->middleware('admin');
    Route::get('/log-peminjaman', [PeminjamanController::class, 'log'])->name('log.peminjaman')->middleware('admin');
    });
    Route::resource('User', UserController::class)->middleware('operator');

    Route::get('/peminjaman', [PeminjamanController::class, 'index'])->middleware('member');
    Route::get('/detailPeminjaman', [PeminjamanController::class, 'detail'])->middleware('member');
    Route::post('/pinjam', [PeminjamanController::class, 'pinjam'])->middleware('member');
    Route::post('/kembali/{id}', [PeminjamanController::class, 'kembali'])->name('kembali')->middleware('member');


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
