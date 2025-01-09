<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $title = "Kelola User";
        $users = User::where('id', '!=', Auth::id())->paginate(10);
        return view('pages.user.kelola-user', compact('users', 'title'));
    }
    public function create()
    {
        $title = "Tambah User";
        return view('pages.user.add-user', compact('title'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'level' => 'required',
            'password' => 'required|min:8',
        ], [
            // Pesan error custom
            'name.required' => 'Nama wajib diisi.',
            'name.string' => 'Nama harus berupa teks.',
            'name.max' => 'Nama maksimal 255 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'level.required' => 'Level pengguna wajib dipilih.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
        ]);
    
        // Menyimpan user ke database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'level' => $request->level,
            'password' => Hash::make($request->password),
        ]);
    
        // Notifikasi berdasarkan hasil penyimpanan
        if ($user) {
            session()->flash('status', 'success');
            session()->flash('message', 'User berhasil ditambahkan.');
        } else {
            session()->flash('status', 'error');
            session()->flash('message', 'Gagal menambahkan user.');
        }
    
        return redirect()->route('user.index');
    }
    

    public function edit($id)
    {
        $title = "Edit User";
        $user = User::findOrFail($id);
        return view('pages.user.edit-user', compact('user', 'title'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'level' => 'required',
            'current_password' => 'nullable|required_with:password', // Required jika password baru diisi
            'password' => 'nullable|min:8',
        ], [
            'current_password.required_with' => 'Password lama wajib diisi untuk mengganti password baru.',
        ]);

        // Validasi password lama jika password baru diisi
        if ($request->password && !Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Password lama tidak sesuai.'])->withInput();
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'level' => $request->level,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
        ]);

        if ($user) {
            session()->flash('status', 'success');
            session()->flash('message', 'User berhasil diperbarui.');
        } else {
            session()->flash('status', 'error');
            session()->flash('message', 'Gagal memperbarui user. Silakan coba lagi.');
        }

        return redirect()->route('user.index');
    }



    public function destroy($id)
    {
        $user = User::destroy($id);
        if ($user) {
            session()->flash('status', 'success');
            session()->flash('message', 'User berhasil dihapus.');
        } else {
            session()->flash('status', 'error');
            session()->flash('message', 'Gagal Menghapus User.');
        }
        return redirect()->route('user.index');
    }
}
