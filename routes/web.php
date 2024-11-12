<?php

use App\Http\Controllers\{
    BarangController,
    DetailPeminjamanController,
    GedungController,
    KategoriController,
    PeminjamanController,
    PermintaanController,
    ProfileController,
    RuangController,
    TodoListController,
    UserController,
    ViewController
};
use Illuminate\Support\Facades\Route;

// Route untuk halaman login
Route::get('/', function () {
    return view('auth.login');
})->middleware('guest');

// Middleware untuk autentikasi pengguna
Route::middleware('auth')->group(function () {
    
    // Route dashboard
    Route::get('/dashboard', [ViewController::class, 'index'])->name('dashboard');

    // Routes untuk barang (bisa diakses admin, petugas1, petugas3)
    Route::middleware(['can.access.barang'])->group(function() {
        Route::resource('barang', BarangController::class);
        Route::get('barang-data', [BarangController::class, 'getData'])->name('barang.data');
    });

    // Routes untuk log peminjaman (bisa diakses admin, petugas2)
    Route::middleware(['can.access.log'])->group(function() {
        Route::get('/log-peminjaman', [PeminjamanController::class, 'log'])->name('log.peminjaman');
        Route::post('/pengembalian/{id}', [PeminjamanController::class, 'kembali'])->name('pengembalian.kembali');
    });

    // Routes untuk todolist (bisa diakses admin dan semua petugas)
    Route::middleware(['can.access.todolist'])->group(function() {
        Route::get('/todolist', [TodoListController::class, 'index'])->name('todolist.index');
        Route::post('/todolist', [TodoListController::class, 'store'])->name('todolist.store');
        Route::post('/todolist/update-status', [TodoListController::class, 'updateStatus'])->name('todolist.updateStatus');
    });

    // Routes khusus admin
    Route::middleware('admin')->group(function () {
        Route::resource('user', UserController::class);
        Route::resource('gedung', GedungController::class);
        Route::resource('ruang', RuangController::class);
        Route::resource('kategori', KategoriController::class);
    });

    // Routes khusus guru
    Route::middleware('guru')->group(function() {
        Route::get('/peminjaman', [PeminjamanController::class, 'index']);
        Route::get('/permintaan', [PermintaanController::class, 'index']);
        Route::get('/detailPeminjaman', [PeminjamanController::class, 'detail']);
        Route::get('/detailPermintaan', [PermintaanController::class, 'detail']);
        Route::post('/pinjam', [PeminjamanController::class, 'pinjam']);
        Route::post('/permintaan', [PeminjamanController::class, 'permintaan']);
    });

    // Routes khusus siswa
    Route::middleware('siswa')->group(function () {
        Route::get('/peminjaman', [PeminjamanController::class, 'index']);
        Route::get('/detailPeminjaman', [PeminjamanController::class, 'detail']);
        Route::post('/pinjam', [PeminjamanController::class, 'pinjam']);
    });

    // Routes untuk manajemen profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route untuk autentikasi
require __DIR__ . '/auth.php';
