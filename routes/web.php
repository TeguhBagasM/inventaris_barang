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

Route::get('/', function () {
    return view('auth.login');
})->middleware('guest');

Route::middleware('auth')->group(function () {
    
    Route::get('/dashboard', [ViewController::class, 'index'])->name('dashboard');

    Route::middleware(['can.access.barang'])->group(function() {
        Route::resource('barang', BarangController::class);
        Route::get('barang-data', [BarangController::class, 'getData'])->name('barang.data');
        Route::get('/cetak', [BarangController::class, 'cetak'])->name('barang.cetak');
    });

    Route::middleware(['can.access.log'])->group(function() {
        Route::get('/log-peminjaman', [PeminjamanController::class, 'log'])->name('log.peminjaman');
        Route::get('/pengembalian/{id}/form', [PeminjamanController::class, 'showPengembalianForm'])->name('pengembalian.form');
        Route::post('/pengembalian/{id}/process', [PeminjamanController::class, 'processPengembalian'])->name('pengembalian.process');
        Route::get('/cetak-logs', [PeminjamanController::class, 'cetak'])->name('peminjaman.cetak');
        Route::get('/cetak-bukti/{id}', [PeminjamanController::class, 'cetakBukti'])->name('cetak.bukti');
        Route::get('/scan-qr', [PeminjamanController::class, 'scanQR'])->name('scan-qr');
        Route::post('/process-qr', [PeminjamanController::class, 'processQR'])->name('process-qr');
    });

    Route::middleware(['can.access.todolist'])->group(function() {
        Route::get('todolist', [TodoListController::class, 'index'])->name('todolist.index');
        Route::post('todolist', [TodoListController::class, 'store'])->name('todolist.store');
        Route::put('/todolist/{id}', [TodoListController::class, 'update'])->name('todolist.update');
        Route::delete('/todolist/{id}', [TodoListController::class, 'destroy'])->name('todolist.destroy');
        Route::post('/todolist/update-status', [TodoListController::class, 'updateStatus'])->name('todolist.updateStatus');
    });

    Route::middleware('admin')->group(function () {
        Route::resource('user', UserController::class);
        Route::resource('gedung', GedungController::class);
        Route::resource('ruang', RuangController::class);
        Route::get('gedung/{gedung}/tambah-ruang', [RuangController::class, 'createFromGedung'])->name('ruang.create-from-gedung');
        Route::post('gedung/{gedung}/tambah-ruang', [RuangController::class, 'storeFromGedung'])->name('ruang.store-from-gedung');
        Route::resource('kategori', KategoriController::class);
        Route::get('/admin-peminjam', [PeminjamanController::class, 'indexAdmin'])->name('admin.pinjam');
        Route::post('/admin-pinjam', [PeminjamanController::class, 'pinjamAdmin'])->name('admin-pinjam');
    });

    Route::middleware('guru')->group(function() {
        Route::get('/permintaan', [PermintaanController::class, 'index']);
        Route::get('/detailPermintaan', [PermintaanController::class, 'detail']);
        Route::post('/permintaan', [PeminjamanController::class, 'permintaan']);
    });

    Route::middleware(['can.access.pinjam'])->group(function() {
        Route::get('/peminjaman', [PeminjamanController::class, 'index']);
        Route::get('/detailPeminjaman', [PeminjamanController::class, 'detail']);
        Route::post('/pinjam', [PeminjamanController::class, 'pinjam'])->name('pinjam');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route untuk autentikasi
require __DIR__ . '/auth.php';
