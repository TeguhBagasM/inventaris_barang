<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Lokasi;
use App\Models\Kategori;
use Illuminate\Http\Request;
use App\Models\KategoriBarang;
use App\Models\DetailPeminjaman;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Kelola Barang';
        return view('pages.barang.kelola-barang', compact('title'));
    }

    public function getData()
    {
        $barang = Barang::with('lokasi');
        
        return DataTables::of($barang)
            ->addIndexColumn()
            ->addColumn('gambar', function($row){
                return '<img src="'.asset($row->gambar).'" alt="'.$row->nama.'" 
                        class="card-img" style="object-fit: cover;max-width: 100px; max-height: 100px;">';
            })
            ->addColumn('action', function($row){
                $actionBtn = '
                    <a href="'.route('barang.edit', $row->id).'" class="btn bg-gradient-warning btn-sm"><i class="fa-solid fa-pencil" style="font-size: 14px;"></i></a>
                    <button onclick="deleteBarang('.$row->id.')" class="btn bg-gradient-danger btn-sm"><i class="fa-solid fa-trash-alt" style="font-size: 14px;"></i></button>
                    <a href="'.route('barang.show', $row->id).'" class="btn bg-gradient-info btn-sm"><i class="fa-solid fa-eye" style="font-size: 14px;"></i></a>
                ';
                return $actionBtn;
            })
            ->rawColumns(['action', 'gambar'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $lokasi = Lokasi::all();
        $kategori = Kategori::all();
        $title = 'Kelola Barang';
        return view('pages.barang.add-barang', compact('title', 'lokasi', 'kategori'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'jumlah' => 'required|integer',
            'lokasi_id' => 'required|exists:lokasis,id',
            'kategori_id' => 'required|array',
            'kategori_id.*' => 'exists:kategoris,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $newName = null;

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $newName = $request->nama . '-' . now()->timestamp . '.' . $request->file('image')->getClientOriginalExtension();
            // Simpan file ke storage/app/public/images
            Storage::disk('public')->putFileAs('images', $request->file('image'), $newName);
        }

        $barang = Barang::create([
            'nama' => $request->nama,
            'jumlah' => $request->jumlah,
            'stok' => $request->jumlah,
            'gambar' => $newName ? 'storage/images/' . $newName : null, // Simpan path relatif
            'lokasi_id' => $request->lokasi_id,
        ]);

        $barang->kategoris()->attach($request->kategori_id);

        if ($barang) {
            session()->flash('status', 'success');
            session()->flash('message', 'Barang berhasil ditambahkan.');
        } else {
            session()->flash('status', 'error');
            session()->flash('message', 'Gagal menambahkan Barang. Silakan coba lagi.');
        }

        return redirect()->route('barang.index');
    }


    public function show(Barang $barang)
    {
        $barang->load('kategori', 'lokasi');
        $title = 'Kelola Barang';

        // Ambil detail peminjaman, kemudian grup berdasarkan user_id dan hitung jumlahnya
        $detail = DetailPeminjaman::select('user_id', DB::raw('SUM(jumlah) as total_peminjaman'))
            ->where('barang_id', $barang->id)
            ->groupBy('user_id')
            ->get();

        return view('pages.Barang.detail-barang', compact('barang', 'title', 'detail'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Barang $barang)
    {
        $lokasi = Lokasi::all();
        $kategori = Kategori::all();
        $title = 'Kelola Barang';
        return view('pages.barang.edit-barang', compact('barang', 'title', 'lokasi', 'kategori'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'nama' => 'required',
            'jumlah' => 'required|integer',
            'lokasi_id' => 'required|exists:lokasis,id',
            'kategori_id' => 'required|array',
            'kategori_id.*' => 'exists:kategoris,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        try {
            $updateData = [
                'nama' => $request->nama,
                'jumlah' => $request->jumlah,
                'stok' => $request->jumlah,
                'lokasi_id' => $request->lokasi_id,
            ];
    
            // Handle image upload if there's a new image
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                // Generate new image name
                $newName = $request->nama . '-' . now()->timestamp . '.' . $request->file('image')->getClientOriginalExtension();
                
                // Delete old image if exists
                if ($barang->gambar) {
                    $oldImagePath = str_replace('storage/images/', '', $barang->gambar);
                    Storage::disk('public')->delete('images/' . $oldImagePath);
                }
                
                // Store new image
                Storage::disk('public')->putFileAs('images', $request->file('image'), $newName);
                
                // Update image path in database
                $updateData['gambar'] = 'storage/images/' . $newName;
            }
    
            // Update barang data
            $barang->update($updateData);
    
            // Sync kategori
            $barang->kategori()->sync($request->kategori_id);
    
            session()->flash('status', 'success');
            session()->flash('message', 'Barang berhasil diperbarui.');
    
            return redirect()->route('barang.index');
    
        } catch (\Exception $e) {
            // Log error for debugging
            Log::error('Error updating barang: ' . $e->getMessage());
            
            session()->flash('status', 'error');
            session()->flash('message', 'Gagal memperbarui Barang. Silakan coba lagi.');
            
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Barang $barang)
    {
        try {
            // Hapus gambar dari storage jika ada
            if ($barang->gambar) {
                Storage::disk('public')->delete('images/' . $barang->gambar);
            }

            // Hapus semua relasi kategori
            $barang->kategori()->detach();

            // Hapus barang dari database
            $barang->delete();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Barang berhasil dihapus.'
                ]);
            }

            session()->flash('status', 'success');
            session()->flash('message', 'Barang berhasil dihapus.');

            return redirect()->route('barang.index');

        } catch (\Exception $e) {
            Log::error('Error deleting barang: ' . $e->getMessage());

            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus Barang. Silakan coba lagi.'
                ], 500);
            }

            session()->flash('status', 'error');
            session()->flash('message', 'Gagal menghapus Barang. Silakan coba lagi.');

            return redirect()->back();
        }
    }
}
