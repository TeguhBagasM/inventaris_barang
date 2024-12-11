<?php

namespace App\Http\Controllers;

use App\Models\Bhp;
use Illuminate\Http\Request;

class BhpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = "Kelola BHP";
        $bhps = Bhp::latest()->paginate(10);
        return view('pages.bhp.index', compact('bhps', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = "Tambah BHP";
        return view('pages.bhp.create',compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'spesifikasi' => 'required|string|max:255',
            'tahun_pengadaan' => 'required|integer|min:1900|max:' . date('Y'),
            'stok' => 'required|integer|min:0',
            'sumber_dana' => 'required|string|max:255'
        ]);

        $bhp = Bhp::create($validatedData);
        if ($bhp) {
            session()->flash('status', 'success');
            session()->flash('message', 'BHP berhasil ditambahkan.');
        } else {
            session()->flash('status', 'error');
            session()->flash('message', 'Gagal menambahkan bhp. Silakan coba lagi.');
        }

        return redirect()->route('bhp.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BHP $bhp)
    {
        $title = "Edit BHP";
        return view('pages.bhp.edit', compact('bhp', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BHP $bhp)
    {
        {
            $validatedData = $request->validate([
                'nama' => 'required|string|max:255',
                'spesifikasi' => 'required|string|max:255',
                'tahun_pengadaan' => 'required|integer|min:1900|max:' . date('Y'),
                'stok' => 'required|integer|min:0',
                'sumber_dana' => 'required|string|max:255'
            ]);
    
            $updated = $bhp->update($validatedData);
    
            if ($updated) {
                session()->flash('status', 'success');
                session()->flash('message', 'BHP berhasil diperbarui.');
            } else {
                session()->flash('status', 'error');
                session()->flash('message', 'Gagal memperbarui BHP. Silakan coba lagi.');
            }
    
            return redirect()->route('bhp.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BHP $bhp)
    {
        {
            $deleted = $bhp->delete();
    
            if ($deleted) {
                session()->flash('status', 'success');
                session()->flash('message', 'BHP berhasil dihapus.');
            } else {
                session()->flash('status', 'error');
                session()->flash('message', 'Gagal menghapus BHP. Silakan coba lagi.');
            }
    
            return redirect()->route('bhp.index');
        }
    }
}
