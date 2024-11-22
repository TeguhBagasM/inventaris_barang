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
        return view('pages.User.kelola-user', compact('users', 'title'));
    }
    public function create()
    {
        $title = "Tambah User";
        return view('pages.User.add-user', compact('title'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'level' => 'required',
            'password' => 'required|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'level' => $request->level,
            'password' => Hash::make($request->password),
        ]);

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
        return view('pages.User.edit-user', compact('user', 'title'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'level' => 'required',
            'password' => 'nullable|min:8',
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'level' => $request->level,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
        ]);

        if ($user) {
            session()->flash('status', 'success');
            session()->flash('message', 'User Berhasil Di Edit.');
        } else {
            session()->flash('status', 'error');
            session()->flash('message', 'Gagal Mengedit User.');
        }

        return redirect()->route('user.index')->with('status', 'User updated successfully!');
    }


    public function destroy($id)
    {
        $user = User::destroy($id);
        if ($user) {
            session()->flash('status', 'success');
            session()->flash('message', 'User Berhasil Di Hapus.');
        } else {
            session()->flash('status', 'error');
            session()->flash('message', 'Gagal Menghapus User.');
        }
        return redirect()->route('user.index')->with('status', 'User deleted successfully!');
    }
}
