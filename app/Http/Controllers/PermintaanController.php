<?php

namespace App\Http\Controllers;

use App\Models\Bhp;
use App\Models\User;
use Illuminate\Http\Request;

class PermintaanController extends Controller
{
    public function index()
    {
        $title = 'Pemintaan Barang';
        $bhps = Bhp::where('stok', '>', 0)->get();
        return view('pages.PermintaanBarang.permintaan', compact('title', 'bhps'));
    }

    public function indexAdmin()
    {
        $title = 'Permintaan Barang';
        $bhps = Bhp::where('stok', '>', 0)->get();
        $users = User::whereIn('level', ['guru'])->get();
        return view('pages.PermintaanBarang.admin-permintaan', compact('bhps', 'users', 'title'));
    }
}
