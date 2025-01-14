<?php

namespace App\Http\Controllers;

use App\Models\Gedung;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GedungController extends Controller
{
    public function index()
    {
        $title = 'Kelola Gedung';
        $gedungs = Gedung::latest()->get();
        return view('pages.gedung.index', compact('gedungs', 'title'));
    }

    public function create()
    {
        $title = 'Tambah Gedung';
        return view('pages.gedung.create', compact('title'));
    }


    public function show($id)
    {
        $title = 'Detail Gedung';
        $gedung = Gedung::with('ruangs')->findOrFail($id);
        return view('pages.gedung.show', compact('gedung', 'title'));
    }

    public function cetak()
    {
        $gedung = Gedung::with('ruangs')->get();
        $pdf = Pdf::loadView('pages.gedung.cetak-gedung', compact('gedung'));
        return $pdf->stream('laporan-gedung.pdf');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_gedung' => 'required|string|max:255',
            'luas_gedung' => 'required|numeric',
            'tahun_perolehan' => 'required|digits:4',
            'nilai_bangunan' => 'required|numeric',
            'peruntukkan' => 'required|string|max:255',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'keterangan' => 'nullable|string',
        ]);
    
        try {
            $imagePath = null;
    
            if ($request->hasFile('gambar') && $request->file('gambar')->isValid()) {
                $timestamp = date('dmYHis');
                $newName = Str::slug($request->nama_gedung) . '-' . $timestamp . '.' .
                           $request->file('gambar')->getClientOriginalExtension();
    
                Storage::disk('public')->putFileAs('gedung-images', $request->file('gambar'), $newName);
    
                $sourcePath = storage_path('app/public/gedung-images/' . $newName);
                $destinationPath = public_path('storage/gedung-images/' . $newName);
    
                if (!file_exists(public_path('storage/gedung-images'))) {
                    mkdir(public_path('storage/gedung-images'), 0755, true);
                }
    
                copy($sourcePath, $destinationPath);
    
                $imagePath = 'storage/gedung-images/' . $newName;
            }
    
            Gedung::create([
                'nama_gedung' => $request->nama_gedung,
                'luas_gedung' => $request->luas_gedung,
                'tahun_perolehan' => $request->tahun_perolehan,
                'nilai_bangunan' => $request->nilai_bangunan,
                'peruntukkan' => $request->peruntukkan,
                'gambar' => $imagePath,
                'keterangan' => $request->keterangan,
            ]);
    
            session()->flash('status', 'success');
            session()->flash('message', 'Gedung berhasil ditambahkan.');
    
            return redirect()->route('gedung.index');
        } catch (\Exception $e) {
            if (isset($newName) && Storage::disk('public')->exists('gedung-images/' . $newName)) {
                Storage::disk('public')->delete('gedung-images/' . $newName);
            }
    
            Log::error('Error creating gedung: ' . $e->getMessage());
    
            session()->flash('status', 'error');
            session()->flash('message', 'Gagal menambahkan Gedung. Silakan coba lagi.');
    
            return redirect()->back()->withInput();
        }
    }    
        

    public function edit(Gedung $gedung)
    {
        $title = 'Edit Gedung';
        return view('pages.gedung.edit', compact('gedung', 'title'));
    }

    public function update(Request $request, Gedung $gedung)
    {
        $request->validate([
            'nama_gedung' => 'required|string|max:255',
            'luas_gedung' => 'required|numeric',
            'tahun_perolehan' => 'required|digits:4',
            'nilai_bangunan' => 'required|numeric',
            'peruntukkan' => 'required|string|max:255',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'keterangan' => 'nullable|string',
        ]);
    
        try {
            $updateData = $request->except('gambar');
    
            if ($request->hasFile('gambar') && $request->file('gambar')->isValid()) {
                if ($gedung->gambar) {
                    Storage::disk('public')->delete(str_replace('storage/', '', $gedung->gambar));
    
                    if (file_exists(public_path($gedung->gambar))) {
                        unlink(public_path($gedung->gambar));
                    }
                }
    
                $timestamp = date('dmYHis');
                $newName = Str::slug($request->nama_gedung) . '-' . $timestamp . '.' .
                           $request->file('gambar')->getClientOriginalExtension();
    
                Storage::disk('public')->putFileAs('gedung-images', $request->file('gambar'), $newName);
    
                $sourcePath = storage_path('app/public/gedung-images/' . $newName);
                $destinationPath = public_path('storage/gedung-images/' . $newName);
    
                if (!file_exists(public_path('storage/gedung-images'))) {
                    mkdir(public_path('storage/gedung-images'), 0755, true);
                }
    
                copy($sourcePath, $destinationPath);
    
                $updateData['gambar'] = 'storage/gedung-images/' . $newName;
            }
    
            $gedung->update($updateData);
    
            session()->flash('status', 'success');
            session()->flash('message', 'Gedung berhasil diperbarui.');
    
            return redirect()->route('gedung.index');
        } catch (\Exception $e) {
            Log::error('Error updating gedung: ' . $e->getMessage());
    
            session()->flash('status', 'error');
            session()->flash('message', 'Gagal memperbarui Gedung. Silakan coba lagi.');
    
            return redirect()->back()->withInput();
        }
    }
    

    public function destroy(Gedung $gedung)
    {
        if ($gedung->gambar) {
            Storage::disk('public')->delete($gedung->gambar);
        }
        
        $gedung->delete();

        return response()->json([
            'message' => 'Gedung berhasil dihapus!',
            'success' => true
        ]);
    }
}
