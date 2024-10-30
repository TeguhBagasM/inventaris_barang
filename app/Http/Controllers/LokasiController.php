<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class LokasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Kelola Lokasi';
        return view('pages.lokasi.kelola-lokasi', compact('title'));
    }

    public function getData()
    {
        $lokasi = Lokasi::with('barang');
        
        return DataTables::of($lokasi)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $actionBtn = '
                    <a href="'.route('lokasi.edit', $row->id).'" class="btn bg-gradient-warning btn-sm">Edit</a>
                    <button onclick="deleteLocation('.$row->id.')" class="btn bg-gradient-danger btn-sm">Hapus</button>
                ';
                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $barang = Barang::all();
        $lokasi = Lokasi::all();
        $title = 'Tambah Lokasi';
        return view('pages.lokasi.add-lokasi', compact('title', 'barang', 'lokasi'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
        ]);

        $newName = null; // inisialisasi $newName untuk penanganan gambar


        // Simpan data lokasi ke dalam tabel 'lokasis'
        $lokasi = Lokasi::create([
            'nama' => $request->nama,
        ]);

        if ($lokasi) {
            session()->flash('status', 'success');
            session()->flash('message', 'Lokasi berhasil ditambahkan.');
        } else {
            session()->flash('status', 'error');
            session()->flash('message', 'Gagal menambahkan lokasi. Silakan coba lagi.');
        }

        return redirect()->route('lokasi.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Lokasi $lokasi)
    {
        // $lokasi->load('barang');
        // $title = 'Detail Lokasi';

        // return view('pages.lokasi.detail-lokasi', compact('lokasi', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lokasi $lokasi)
    {
        $title = 'Edit Lokasi';
        return view('pages.lokasi.edit-lokasi', compact('lokasi', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lokasi $lokasi)
    {
        $request->validate([
            'nama' => 'required',
        ]);

        $newName = null; // inisialisasi $newName untuk penanganan gambar

        // // Proses upload gambar
        // if ($request->hasFile('image') && $request->file('image')->isValid()) {
        //     $newName = $request->nama . '-' . now()->timestamp . '.' . $request->file('image')->getClientOriginalExtension();
        //     $request->file('image')->storeAs('public/images', $newName);

        //     // Hapus gambar lama jika ada
        //     if ($lokasi->gambar && Storage::exists('public/images/' . $lokasi->gambar)) {
        //         Storage::delete('public/images/' . $lokasi->gambar);
        //     }
        // }

        $updated = $lokasi->update([
            'nama' => $request->nama,
        ]);

        if ($updated) {
            session()->flash('status', 'success');
            session()->flash('message', 'Lokasi berhasil diperbarui.');
        } else {
            session()->flash('status', 'error');
            session()->flash('message', 'Gagal memperbarui lokasi. Silakan coba lagi.');
        }

        return redirect()->route('lokasi.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lokasi $lokasi)
    {
        try {
            $deleted = $lokasi->delete();

            if ($deleted) {
                if (request()->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Lokasi berhasil dihapus.'
                    ]);
                }

                session()->flash('status', 'success');
                session()->flash('message', 'Lokasi berhasil dihapus.');
            } else {
                throw new \Exception('Failed to delete lokasi');
            }

            return redirect()->route('lokasi.index');

        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus lokasi. Silakan coba lagi.'
                ], 500);
            }

            session()->flash('status', 'error');
            session()->flash('message', 'Gagal menghapus lokasi. Silakan coba lagi.');

            return redirect()->back();
        }
    }
}