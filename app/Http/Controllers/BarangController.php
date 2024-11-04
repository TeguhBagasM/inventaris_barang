<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;
use App\Models\DetailPeminjaman;
use App\Models\Ruang;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

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
        $barang = Barang::with('ruang');
        
        return DataTables::of($barang)
            ->addIndexColumn()
            ->addColumn('gambar', function($row){
                return '<img src="'.asset($row->gambar).'" alt="'.$row->nama.'" 
                        class="card-img" style="object-fit: cover;max-width: 50px; max-height: 50px;">';
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
        $ruang = Ruang::all();
        $kategori = Kategori::all();
        $title = 'Kelola Barang';
        return view('pages.barang.add-barang', compact('title', 'ruang', 'kategori'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'jumlah' => 'required|integer',
            'ruang_id' => 'required|exists:ruangs,id',
            'kategori_id' => 'required|exists:kategoris,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        try {
            $imagePath = null;
    
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                // Tambahkan timestamp pada nama file
                $timestamp = date('dmYHis');  // Format: ddmmyyyyhhiiss
                $newName = Str::slug($request->nama) . '-' . $timestamp . '.' . 
                          $request->file('image')->getClientOriginalExtension();
                
                Storage::disk('public')->putFileAs('images', $request->file('image'), $newName);
                $imagePath = 'storage/images/' . $newName;
            }
    
            $barang = new Barang([
                'nama' => $request->nama,
                'jumlah' => $request->jumlah,
                'stok' => $request->jumlah,
                'gambar' => $imagePath,
                'ruang_id' => $request->ruang_id,
            ]);
    
            $barang->kategori()->associate($request->kategori_id);
            $barang->save();
    
            session()->flash('status', 'success');
            session()->flash('message', 'Barang berhasil ditambahkan.');
    
            return redirect()->route('barang.index');
    
        } catch (\Exception $e) {
            if (isset($newName) && Storage::disk('public')->exists('images/' . $newName)) {
                Storage::disk('public')->delete('images/' . $newName);
            }
    
            Log::error('Error creating barang: ' . $e->getMessage());
            
            session()->flash('status', 'error');
            session()->flash('message', 'Gagal menambahkan Barang. Silakan coba lagi.');
            
            return redirect()->back()->withInput();
        }
    }

    public function show(Barang $barang)
    {
        $barang->load('kategori', 'ruang');
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
        $ruang = Ruang::all();
        $kategori = Kategori::all();
        $title = 'Kelola Barang';
        return view('pages.barang.edit-barang', compact('barang', 'title', 'ruang', 'kategori'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'nama' => 'required',
            'jumlah' => 'required|integer',
            'ruang_id' => 'required|exists:ruangs,id',
            'kategori_id' => 'required|exists:kategoris,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $updateData = [
                'nama' => $request->nama,
                'jumlah' => $request->jumlah,
                'stok' => $request->jumlah,
                'ruang_id' => $request->ruang_id,
            ];

            // Jika ada file gambar baru yang diupload
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $timestamp = date('dmYHis');
                $newName = Str::slug($request->nama) . '-' . $timestamp . '.' . 
                        $request->file('image')->getClientOriginalExtension();
                
                // Hapus gambar lama jika ada
                if ($barang->gambar) {
                    $oldImagePath = str_replace('storage/images/', '', $barang->gambar);
                    Storage::disk('public')->delete('images/' . $oldImagePath);
                }
                
                Storage::disk('public')->putFileAs('images', $request->file('image'), $newName);
                $updateData['gambar'] = 'storage/images/' . $newName;
            }
            // Jika nama barang berubah dan ada gambar lama, rename file gambar
            elseif ($barang->nama !== $request->nama && $barang->gambar) {
                $oldImagePath = str_replace('storage/images/', '', $barang->gambar);
                $extension = pathinfo($oldImagePath, PATHINFO_EXTENSION);
                
                // Ambil timestamp dari nama file lama atau generate baru jika tidak ada
                $oldNameParts = explode('-', pathinfo($oldImagePath, PATHINFO_FILENAME));
                $timestamp = count($oldNameParts) > 1 ? end($oldNameParts) : date('dmYHis');
                
                $newName = Str::slug($request->nama) . '-' . $timestamp . '.' . $extension;
                
                // Rename file di storage
                if (Storage::disk('public')->exists('images/' . $oldImagePath)) {
                    Storage::disk('public')->move('images/' . $oldImagePath, 'images/' . $newName);
                    $updateData['gambar'] = 'storage/images/' . $newName;
                }
            }

            $barang->update($updateData);
            $barang->kategori()->associate($request->kategori_id);
            $barang->save();

            session()->flash('status', 'success');
            session()->flash('message', 'Barang berhasil diperbarui.');

            return redirect()->route('barang.index');

        } catch (\Exception $e) {
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
