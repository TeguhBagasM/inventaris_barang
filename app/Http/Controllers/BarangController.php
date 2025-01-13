<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;
use App\Models\DetailPeminjaman;
use App\Models\Peminjaman;
use App\Models\Ruang;
use Barryvdh\DomPDF\Facade\Pdf;
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
        $title = 'Kelola Asset';
        $barangs = Barang::all();
        return view('pages.barang.kelola-barang', compact('title', 'barangs'));
    }

    // public function getData()
    // {
    //     $barang = Barang::with('ruang');
        
    //     return DataTables::of($barang)
    //         ->addIndexColumn()
    //         ->addColumn('gambar', function($row){
    //             return '<img src="'.asset($row->gambar).'" alt="'.$row->nama.'" 
    //                     class="card-img" style="object-fit: cover;max-width: 50px; max-height: 50px;">';
    //         })
    //         ->addColumn('action', function($row){
    //             $actionBtn = '
    //                 <a href="'.route('barang.edit', $row->id).'" class="btn bg-gradient-dark btn-sm"><i class="fa-solid fa-pencil" style="font-size: 14px;"></i></a>
    //                 <a href="'.route('barang.show', $row->id).'" class="btn bg-gradient-info btn-sm"><i class="fa-solid fa-eye" style="font-size: 14px;"></i></a>
    //                 <button onclick="deleteBarang('.$row->id.')" class="btn bg-gradient-danger btn-sm"><i class="fa-solid fa-trash-alt" style="font-size: 14px;"></i></button>
    //             ';
    //             return $actionBtn;
    //         })
    //         ->rawColumns(['action', 'gambar'])
    //         ->make(true);
    // }
    
    public function cetak()
    {
        $barang = Barang::with('ruang')->get();
        $pdf = PDF::loadView('pages.pdf.cetak-barang', [
            'barang' => $barang
        ]);
        return $pdf->stream('laporan-barang-'.date('Y-m-d').'.pdf');
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $ruang = Ruang::all();
        $kategori = Kategori::all();
        $title = 'Tambah Asset';
        return view('pages.barang.add-barang', compact('title', 'ruang', 'kategori'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'merk' => 'required',
            'spesifikasi' => 'required',
            'serial_number' => 'required',
            'stok' => 'required|integer',
            'tahun_pengadaan' => 'required|integer',
            'sumber_dana' => 'required',
            'kondisi' => 'required',
            'ruang_id' => 'required|exists:ruangs,id',
            'kategori_id' => 'required|exists:kategoris,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
    
        try {
            $imagePath = null;
    
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $timestamp = date('dmYHis');  
                $newName = Str::slug($request->nama) . '-' . $timestamp . '.' . 
                          $request->file('image')->getClientOriginalExtension();
                Storage::disk('public')->putFileAs('images', $request->file('image'), $newName);
                $imagePath = 'images/' . $newName;
            }
    
            $barang = new Barang([
                'nama' => $request->nama,
                'merk' => $request->merk,
                'spesifikasi' => $request->spesifikasi,
                'serial_number' => $request->serial_number,
                'stok' => $request->stok,
                'tahun_pengadaan' => $request->tahun_pengadaan,
                'sumber_dana' => $request->sumber_dana,
                'kondisi' => $request->kondisi,
                'gambar' => $imagePath,
                'ruang_id' => $request->ruang_id,
            ]);
    
            $barang->kategori()->associate($request->kategori_id);
            $barang->save();
    
            session()->flash('status', 'success');
            session()->flash('message', 'Asset berhasil ditambahkan.');
    
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
        $title = 'Detail Asset';

        $detail = Peminjaman::with(['user', 'detailPeminjamans' => function($query) use ($barang) {
            $query->where('barang_id', $barang->id);
        }])
        ->whereHas('detailPeminjamans', function($query) use ($barang) {
            $query->where('barang_id', $barang->id);
        })
        ->select('peminjamans.user_id')
        ->selectRaw('SUM(detail_peminjamans.jumlah) as total_peminjaman')
        ->join('detail_peminjamans', 'peminjamans.id', '=', 'detail_peminjamans.peminjaman_id')
        ->where('detail_peminjamans.barang_id', $barang->id)
        ->groupBy('peminjamans.user_id')
        ->get();

        return view('pages.barang.detail-barang', compact('barang', 'title', 'detail'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Barang $barang)
    {
        $ruang = Ruang::all();
        $kategori = Kategori::all();
        $title = 'Edit Asset';
        return view('pages.barang.edit-barang', compact('barang', 'title', 'ruang', 'kategori'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'nama' => 'required',
            'merk' => 'required',
            'spesifikasi' => 'required',
            'serial_number' => 'required',
            'stok' => 'required|integer',
            'tahun_pengadaan' => 'required|integer',
            'sumber_dana' => 'required',
            'kondisi' => 'required',
            'ruang_id' => 'required|exists:ruangs,id',
            'kategori_id' => 'required|exists:kategoris,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        try {
            $updateData = $request->except(['image', 'kategori_id']);
    
            // Handle file upload
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                // Delete old image if exists
                if ($barang->gambar) {
                    Storage::disk('public')->delete(str_replace('storage/', '', $barang->gambar));
                }
                
                $timestamp = date('dmYHis');
                $newName = Str::slug($request->nama) . '-' . $timestamp . '.' . 
                          $request->file('image')->getClientOriginalExtension();
                
                // Store new image
                $path = $request->file('image')->storeAs('images', $newName, 'public');
                
                // Add logging for debugging
                Log::info('Update path: ' . $path);
                Log::info('Storage URL: ' . Storage::url($path));
                
                $updateData['gambar'] = $path;
            }
            // Handle rename if only name changed
            elseif ($barang->nama !== $request->nama && $barang->gambar) {
                $oldPath = str_replace('storage/', '', $barang->gambar);
                if (Storage::disk('public')->exists($oldPath)) {
                    $extension = pathinfo($oldPath, PATHINFO_EXTENSION);
                    $timestamp = date('dmYHis');
                    $newPath = 'images/' . Str::slug($request->nama) . '-' . $timestamp . '.' . $extension;
                    
                    Storage::disk('public')->move($oldPath, $newPath);
                    $updateData['gambar'] = $newPath;
                }
            }
    
            $barang->update($updateData);
            $barang->kategori()->associate($request->kategori_id);
            $barang->save();
    
            session()->flash('status', 'success');
            session()->flash('message', 'Asset berhasil diperbarui.');
    
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
        if ($barang->gambar) {
            Storage::disk('public')->delete($barang->gambar);
        }
        
        $barang->delete();

        return response()->json([
            'message' => 'Asset berhasil dihapus!',
            'success' => true
        ]);
    }
}
