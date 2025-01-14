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
    
                // Simpan gambar ke storage public/images
                Storage::disk('public')->putFileAs('images', $request->file('gambar'), $newName);
    
                $sourcePath = storage_path('app/public/images/' . $newName);
                $destinationPath = public_path('storage/images/' . $newName);
    
                // Cek dan buat folder jika belum ada
                if (!file_exists(public_path('storage/images'))) {
                    mkdir(public_path('storage/images'), 0755, true);
                }
    
                // Salin file dari storage ke public
                copy($sourcePath, $destinationPath);
    
                $imagePath = 'storage/images/' . $newName;
            }
    
            // Simpan data gedung
            $gedung = new Gedung([
                'nama_gedung' => $request->nama_gedung,
                'luas_gedung' => $request->luas_gedung,
                'tahun_perolehan' => $request->tahun_perolehan,
                'nilai_bangunan' => $request->nilai_bangunan,
                'peruntukkan' => $request->peruntukkan,
                'gambar' => $imagePath,
                'keterangan' => $request->keterangan,
            ]);
    
            $gedung->save();
    
            session()->flash('status', 'success');
            session()->flash('message', 'Gedung berhasil ditambahkan.');
    
            return redirect()->route('gedung.index');
    
        } catch (\Exception $e) {
            // Hapus gambar jika ada kesalahan
            if (isset($newName) && Storage::disk('public')->exists('images/' . $newName)) {
                Storage::disk('public')->delete('images/' . $newName);
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
        $validated = $request->validate([
            'nama_gedung' => 'required|string|max:255',
            'luas_gedung' => 'required|numeric',
            'tahun_perolehan' => 'required|digits:4',
            'nilai_bangunan' => 'required|numeric',
            'peruntukkan' => 'required|string|max:255',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'keterangan' => 'nullable|string'
        ]);
    
        try {
            // Handle file upload
            if ($request->hasFile('gambar') && $request->file('gambar')->isValid()) {
                // Delete old image if exists
                if ($gedung->gambar && Storage::disk('public')->exists($gedung->gambar)) {
                    Storage::disk('public')->delete($gedung->gambar);
                }
                
                $timestamp = date('dmYHis');
                $fileName = Str::slug($request->nama_gedung) . '-' . $timestamp . '.' . 
                           $request->file('gambar')->getClientOriginalExtension();
                
                // Store new image
                $path = $request->file('gambar')->storeAs('gedung-images', $fileName, 'public');
                
                // Add logging for debugging
                Log::info('Update path: ' . $path);
                Log::info('Storage URL: ' . Storage::url($path));
                
                $validated['gambar'] = $path;
            }
            // Handle rename if only name changed
            elseif ($gedung->nama_gedung !== $request->nama_gedung && $gedung->gambar) {
                $oldPath = $gedung->gambar;
                if (Storage::disk('public')->exists($oldPath)) {
                    $extension = pathinfo($oldPath, PATHINFO_EXTENSION);
                    $timestamp = date('dmYHis');
                    $newPath = 'gedung-images/' . Str::slug($request->nama_gedung) . '-' . $timestamp . '.' . $extension;
                    
                    Storage::disk('public')->move($oldPath, $newPath);
                    $validated['gambar'] = $newPath;
                }
            }
    
            $updated = $gedung->update($validated);
    
            if ($updated) {
                session()->flash('status', 'success');
                session()->flash('message', 'Gedung berhasil diperbarui.');
            } else {
                throw new \Exception('Gagal memperbarui gedung');
            }
    
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
