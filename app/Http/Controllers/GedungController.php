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
            if ($request->hasFile('gambar') && $request->file('gambar')->isValid()) {
                $gambar = $request->file('gambar');
                $timestamp = date('dmYHis');
                $fileName = Str::slug($request->nama_gedung) . '-' . $timestamp . '.' . 
                        $gambar->getClientOriginalExtension();
                
                $path = $request->file('gambar')->storeAs('gedung-images', $fileName, 'public');
                
                Log::info('Upload path: ' . $path);
                Log::info('Storage URL: ' . Storage::url($path));
                
                $validated['gambar'] = $path;
            }

            $gedung = Gedung::create($validated);

            if ($gedung) {
                session()->flash('status', 'success');
                session()->flash('message', 'Gedung berhasil ditambahkan!');
            } else {
                session()->flash('status', 'error');
                session()->flash('message', 'Gagal menambahkan gedung, Silakan coba lagi.');
            }
            return redirect()->route('gedung.index');

        } catch (\Exception $e) {
            if (isset($path)) {
                Storage::disk('public')->delete($path);
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
