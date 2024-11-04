<?php

namespace App\Http\Controllers;

use App\Models\ToDoList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodoListController extends Controller
{
    public function index()
    {
        $title = "Daftar Pekerjaan";
        $todos = ToDoList::where('user_id', Auth::id())
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);
        return view('pages.todolist.index', compact('todos', 'title'));
    }

    public function store(Request $request) 
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'prioritas' => 'required'
        ]);

        ToDoList::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'prioritas' => $request->prioritas,
            'status' => 'pending',
            'user_id' => Auth::id()
        ]);

        return redirect()->route('todolist.index')->with([
            'status' => 'success',
            'message' => 'Todo berhasil ditambahkan'
        ]);
    }

    public function updateStatus(Request $request)
    {
        $todoIds = $request->todo_ids ?? [];
        
        // Update semua todo yang dicentang menjadi selesai
        ToDoList::whereIn('id', $todoIds)->update(['status' => 'selesai']);
        
        return redirect()->back()->with([
            'status' => 'success',
            'message' => 'Daftar Pekerjaan telah diselesaikan'
        ]);
    }
}