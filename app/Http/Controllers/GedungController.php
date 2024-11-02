<?php

namespace App\Http\Controllers;

use App\Models\Gedung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            $path = $gambar->store('gedung-images', 'public');
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

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($gedung->gambar) {
                Storage::disk('public')->delete($gedung->gambar);
            }
            
            $gambar = $request->file('gambar');
            $path = $gambar->store('gedung-images', 'public');
            $validated['gambar'] = $path;
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
