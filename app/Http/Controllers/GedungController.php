<?php

namespace App\Http\Controllers;

use App\Models\Gedung;
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
        $gedung = Gedung::findOrFail($id);
        return view('pages.gedung.show', compact('gedung', 'title'));
    }


    public function store(Request $request)
    {
        // Validasi input
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
            // Handle upload gambar
            if ($request->hasFile('gambar') && $request->file('gambar')->isValid()) {
                $gambar = $request->file('gambar');
                $timestamp = date('dmYHis');
                $fileName = Str::slug($request->nama_gedung) . '-' . $timestamp . '.' . 
                        $gambar->getClientOriginalExtension();
                
                $path = $gambar->storeAs('gedung-images', $fileName, 'public');
                $validated['gambar'] = $path;
            }

            Gedung::create($validated);

            session()->flash('status', 'success');
            session()->flash('message', 'Gedung berhasil ditambahkan!');

            return redirect()->route('gedung.index');

        } catch (\Exception $e) {
            // Jika terjadi error, hapus file yang sudah terupload (jika ada)
            if (isset($fileName) && Storage::disk('public')->exists('gedung-images/' . $fileName)) {
                Storage::disk('public')->delete('gedung-images/' . $fileName);
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
            // Jika ada file gambar baru yang diupload
            if ($request->hasFile('gambar') && $request->file('gambar')->isValid()) {
                // Hapus gambar lama jika ada
                if ($gedung->gambar) {
                    Storage::disk('public')->delete($gedung->gambar);
                }
                
                $gambar = $request->file('gambar');
                $timestamp = date('dmYHis');
                $fileName = Str::slug($request->nama_gedung) . '-' . $timestamp . '.' . 
                           $gambar->getClientOriginalExtension();
                
                $path = $gambar->storeAs('gedung-images', $fileName, 'public');
                $validated['gambar'] = $path;
            }
            // Jika nama gedung berubah dan ada gambar lama, rename file gambar
            elseif ($gedung->nama_gedung !== $request->nama_gedung && $gedung->gambar) {
                $oldPath = $gedung->gambar;
                $extension = pathinfo($oldPath, PATHINFO_EXTENSION);
                
                // Ambil timestamp dari nama file lama atau generate baru jika tidak ada
                $oldNameParts = explode('-', pathinfo($oldPath, PATHINFO_FILENAME));
                $timestamp = count($oldNameParts) > 1 ? end($oldNameParts) : date('dmYHis');
                
                $newFileName = Str::slug($request->nama_gedung) . '-' . $timestamp . '.' . $extension;
                $newPath = 'gedung-images/' . $newFileName;
                
                // Rename file di storage
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->move($oldPath, $newPath);
                    $validated['gambar'] = $newPath;
                }
            }
    
            $gedung->update($validated);
    
            session()->flash('status', 'success');
            session()->flash('message', 'Gedung berhasil diperbarui!');
    
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
        // Hapus gambar terkait jika ada
        if ($gedung->gambar) {
            Storage::disk('public')->delete($gedung->gambar);
        }

        $gedung->delete();

        return redirect()->route('gedung.index')
            ->with('success', 'Gedung berhasil dihapus!');
    }
}
