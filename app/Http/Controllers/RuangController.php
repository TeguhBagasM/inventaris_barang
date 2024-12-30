<?php

namespace App\Http\Controllers;

use App\Models\Gedung;
use App\Models\Ruang;
use Illuminate\Http\Request;

class RuangController extends Controller
{
    public function index()
    {
        $title = 'Kelola Ruangan';
        $ruangs = Ruang::with('gedung')->latest()->get();
        return view('pages.ruang.index', compact('ruangs', 'title'));
    }

    public function create()
    {
        $title = 'Tambah Ruangan';
        $gedungs = Gedung::all();
        return view('pages.ruang.create', compact('gedungs', 'title'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_ruang' => 'required|string|max:255',
            'gedung_id' => 'required|exists:gedungs,id',
            'ukuran' => 'required|numeric',
            'kondisi' => 'required',
            'peruntukkan' => 'required|string|max:255',
            'keterangan' => 'nullable|string'
        ]);

        $ruang = Ruang::create($validated);

        if ($ruang) {
            $gedung = Gedung::find($request->gedung_id);
            $gedung->increment('jumlah_ruang');
            
            session()->flash('status', 'success');
            session()->flash('message', 'Ruangan berhasil ditambahkan!');
        } else {
            session()->flash('status', 'error');
            session()->flash('message', 'Gagal menambahkan ruang. Silakan coba lagi.');
        }

        return redirect()->route('ruang.index');
    }

    public function createFromGedung(Gedung $gedung)
    {
        $title = 'Tambah Ruangan';
        $gedungs = Gedung::all();
        return view('pages.ruang.create-from-gedung', compact('gedung', 'gedungs', 'title'));
    }

    public function storeFromGedung(Request $request, Gedung $gedung)
    {
        $validated = $request->validate([
            'nama_ruang' => 'required|string|max:255',
            'ukuran' => 'required|numeric',
            'kondisi' => 'required',
            'peruntukkan' => 'required|string|max:255',
            'keterangan' => 'nullable|string'
        ]);

        $validated['gedung_id'] = $gedung->id;

        $ruang = Ruang::create($validated);

        if ($ruang) {
            // Update jumlah ruang di gedung
            $gedung->increment('jumlah_ruang');
            session()->flash('status', 'success');
            session()->flash('message', 'Ruangan berhasil ditambahkan!');
            } else {
                session()->flash('status', 'error');
                session()->flash('message', 'Gagal menambahkan ruang. Silakan coba lagi.');
            }

            return redirect()->route('ruang.index');
    }

    public function edit(Ruang $ruang)
    {
        $title = 'Edit Ruangan';
        $gedungs = Gedung::all();
        return view('pages.ruang.edit', compact('ruang', 'gedungs', 'title'));
    }

    public function update(Request $request, Ruang $ruang)
    {
        $validated = $request->validate([
            'nama_ruang' => 'required|string|max:255',
            'gedung_id' => 'required|exists:gedungs,id',
            'ukuran' => 'required|numeric',
            'kondisi' => 'required|in:Baik,Rusak Ringan,Rusak Berat',
            'peruntukkan' => 'required|string|max:255',
            'keterangan' => 'nullable|string'
        ]);

        // Jika gedung berubah, update jumlah ruang di gedung lama dan baru
        if ($ruang->gedung_id != $request->gedung_id) {
            $oldGedung = Gedung::find($ruang->gedung_id);
            $newGedung = Gedung::find($request->gedung_id);
            
            $oldGedung->decrement('jumlah_ruang');
            $newGedung->increment('jumlah_ruang');
        }

        $updated = $ruang->update($validated);

        if ($updated) {
            session()->flash('status', 'success');
            session()->flash('message', 'Ruangan berhasil diperbarui.');
        } else {
            session()->flash('status', 'error');
            session()->flash('message', 'Gagal memperbarui ruangan. Silakan coba lagi.');
        }

        return redirect()->route('ruang.index');
    }

    public function destroy(Ruang $ruang)
    {
        // Kurangi jumlah ruang di gedung
        $gedung = Gedung::find($ruang->gedung_id);
        $gedung->decrement('jumlah_ruang');

        $deleted = $ruang->delete();

        if ($deleted) {
            session()->flash('status', 'success');
            session()->flash('message', 'Ruangan berhasil dihapus.');
        } else {
            session()->flash('status', 'error');
            session()->flash('message', 'Gagal menghapus ruangan. Silakan coba lagi.');
        }

        return redirect()->route('ruang.index');
    }
}
