<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\Bhp;
// use App\Models\Kategori;
// use Illuminate\Http\Request;
use App\Models\DetailPeminjaman;
use App\Models\Gedung;
use App\Models\Ruang;
use App\Models\ToDoList;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ViewController extends Controller
{
    public function index()
    {
        $title = 'Dashboard';

        $jumlahBarang = Barang::count();
        $jumlahBhp = Bhp::count();
        $jumlahRuang = Ruang::count();
        $jumlahGedung = Gedung::count();
        $jumlahUser = User::count();
        $jumlahTugas = ToDoList::where('status', 'pending')->count();
        $jumlahDiminta = BarangKeluar::where('status', 'disetujui')->count();
        $jumlahPeminjam = DetailPeminjaman::whereNull('tanggal_kembali')->count();

        $kategoriData = DB::table('kategoris')
            ->leftJoin('barangs', 'kategoris.id', '=', 'barangs.kategori_id')
            ->select('kategoris.nama as nama_kategori', DB::raw('count(barangs.id) as total'))
            ->groupBy('kategoris.nama')
            ->get();

        $monthlyLoans = DB::table('detail_peminjamans')
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('count(*) as total'))
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $ruangData = DB::table('ruangs')
            ->leftJoin('barangs', 'ruangs.id', '=', 'barangs.ruang_id')
            ->leftJoin('gedungs', 'ruangs.gedung_id', '=', 'gedungs.id')
            ->select(
                'ruangs.nama_ruang',
                'gedungs.nama_gedung',
                DB::raw('count(barangs.id) as total_barang'),
                'ruangs.kondisi'
            )
            ->groupBy('ruangs.nama_ruang', 'gedungs.nama_gedung', 'ruangs.kondisi')
            ->orderBy('total_barang', 'desc')
            ->get();

            $todos = ToDoList::where('user_id', Auth::id())
                        ->orderBy('created_at', 'desc')
                        ->where('status', 'pending')
                        ->paginate(10);

        return view('pages.dashboard', compact(
            'title', 
            'jumlahBarang', 
            'jumlahBhp', 
            'jumlahRuang', 
            'jumlahGedung', 
            'jumlahUser', 
            'jumlahDiminta', 
            'jumlahTugas', 
            'jumlahPeminjam',
            'kategoriData',
            'monthlyLoans',
            'ruangData',
            'todos'
        ));
    }
}
