<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Barang;
use App\Models\Lokasi;
use App\Models\Kategori;
use Illuminate\Http\Request;
use App\Models\DetailPeminjaman;
use Illuminate\Support\Facades\DB;

class ViewController extends Controller
{
    public function index()
    {
        $title = 'Dashboard';
    
        $jumlahBarang = Barang::count();
        $jumlahKategori = Kategori::count();
        $jumlahLokasi = Lokasi::count();
        $jumlahPeminjam = DetailPeminjaman::whereNull('masuk')->count();
    
        // Data untuk chart kategori
        $kategoriData = DB::table('kategoris')
            ->join('barang_kategori', 'kategoris.id', '=', 'barang_kategori.kategori_id')
            ->select('kategoris.nama as nama_kategori', DB::raw('count(*) as total'))
            ->groupBy('kategoris.nama')
            ->get();
    
        // Data untuk chart peminjaman bulanan
        $monthlyLoans = DB::table('detail_peminjaman')
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('count(*) as total'))
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    
        return view('pages.dashboard', compact(
            'title', 
            'jumlahBarang', 
            'jumlahKategori', 
            'jumlahLokasi', 
            'jumlahPeminjam',
            'kategoriData',
            'monthlyLoans'
        ));
    }
}
