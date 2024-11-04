<?php

namespace App\Http\Controllers;

use App\Models\Gedung;
use Illuminate\Http\Request;
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

        // Handle upload gambar
        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar');
            // Mendapatkan ekstensi file asli
            $extension = $gambar->getClientOriginalExtension();
            // Membuat nama file baru berdasarkan nama gedung
            $fileName = Str::slug($request->nama_gedung) . '.' . $extension;
            // Menyimpan file dengan nama yang baru
            $path = $gambar->storeAs('gedung-images', $fileName, 'public');
            $validated['gambar'] = $path;
        }

        Gedung::create($validated);

        return redirect()->route('gedung.index')
            ->with('success', 'Gedung berhasil ditambahkan!');
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
    
        // Jika ada file gambar baru yang diupload
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($gedung->gambar) {
                Storage::disk('public')->delete($gedung->gambar);
            }
            
            $gambar = $request->file('gambar');
            $extension = $gambar->getClientOriginalExtension();
            $fileName = Str::slug($request->nama_gedung) . '.' . $extension;
            $path = $gambar->storeAs('gedung-images', $fileName, 'public');
            $validated['gambar'] = $path;
        }
        // Jika tidak ada file baru tapi nama gedung berubah dan ada gambar lama
        elseif ($gedung->nama_gedung !== $request->nama_gedung && $gedung->gambar) {
            $oldPath = $gedung->gambar;
            $extension = pathinfo($oldPath, PATHINFO_EXTENSION);
            $newFileName = Str::slug($request->nama_gedung) . '.' . $extension;
            $newPath = 'gedung-images/' . $newFileName;
    
            // Rename file di storage
            Storage::disk('public')->move($oldPath, $newPath);
            $validated['gambar'] = $newPath;
        }
    
        $gedung->update($validated);
    
        return redirect()->route('gedung.index')
            ->with('success', 'Gedung berhasil diperbarui!');
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
