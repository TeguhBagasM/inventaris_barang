<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;
use App\Models\DetailPeminjaman;
use App\Models\Ruang;
use Illuminate\Support\Facades\DB;

class ViewController extends Controller
{
    public function index()
    {
        $title = 'Dashboard';

        $jumlahBarang = Barang::count();
        $jumlahKategori = Kategori::count();
        $jumlahRuang = Ruang::count();
        $jumlahPeminjam = DetailPeminjaman::whereNull('masuk')->count();

        $kategoriData = DB::table('kategoris')
            ->leftJoin('barangs', 'kategoris.id', '=', 'barangs.kategori_id')
            ->select('kategoris.nama as nama_kategori', DB::raw('count(barangs.id) as total'))
            ->groupBy('kategoris.nama')
            ->get();

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
            'jumlahRuang', 
            'jumlahPeminjam',
            'kategoriData',
            'monthlyLoans'
        ));
    }
}
