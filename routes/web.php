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
use App\Http\Controllers\GedungController;
use App\Http\Controllers\KategoriBarangController; // Add this line
use App\Http\Controllers\RuangController;

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

    // Routes for admin only
    Route::middleware('admin')->group(function () {
        Route::resource('User', UserController::class);
        Route::resource('gedung', GedungController::class);
        Route::resource('ruang', RuangController::class);
    });

    // Routes accessible by admin and operator
    Route::middleware(['admin_or_operator'])->group(function () {
        Route::resource('lokasi', LokasiController::class);
        Route::get('lokasi-data', [LokasiController::class, 'getData'])->name('lokasi.data');
        Route::resource('barang', BarangController::class);
        Route::get('barang-data', [BarangController::class, 'getData'])->name('barang.data');
        Route::resource('kategori', KategoriController::class);
        Route::get('/log-peminjaman', [PeminjamanController::class, 'log'])->name('log.peminjaman');
        Route::post('/pengembalian/{id}', [PeminjamanController::class, 'kembali'])->name('pengembalian.kembali');
    });

    // Routes for members only
    Route::middleware('member')->group(function () {
        Route::get('/peminjaman', [PeminjamanController::class, 'index']);
        Route::get('/detailPeminjaman', [PeminjamanController::class, 'detail']);
        Route::post('/pinjam', [PeminjamanController::class, 'pinjam']);
    });

    // Routes for profile management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
