<?php

namespace App\Http\Controllers;

use App\Models\ToDoList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TodoListController extends Controller
{
    public function index()
    {
        $title = "Daftar Tugas";
        $todos = ToDoList::with('user')
                    // ->where('judul', '!=', 'testing')
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
        return view('pages.todolist.index', compact('todos', 'title'));
    }

    public function create() {
        $title = "Tambah Tugas Baru";
        return view('pages.Todolist.create', compact('title'));
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
            'message' => 'Tugas berhasil ditambahkan'
        ]);
    }

    public function updateStatus(Request $request)
    {
        try {
            $todoIds = $request->todo_ids;

            Log::info('Received todo_ids:', ['ids' => $todoIds]);

            if (empty($todoIds)) {
                return redirect()->back()->with([
                    'status' => 'error',
                    'message' => 'Pilih minimal satu tugas untuk diselesaikan'
                ]);
            }

            $todos = ToDoList::whereIn('id', $todoIds)->get();
            if ($todos->count() !== count($todoIds)) {
                return redirect()->back()->with([
                    'status' => 'error',
                    'message' => 'Ada ID tugas yang tidak valid'
                ]);
            }

            $updated = ToDoList::whereIn('id', $todoIds)
                            ->where('status', 'pending')
                            ->update(['status' => 'selesai']);

            if ($updated > 0) {
                return redirect()->back()->with([
                    'status' => 'success',
                    'message' => $updated . ' tugas berhasil diselesaikan'
                ]);
            }

            return redirect()->back()->with([
                'status' => 'error',
                'message' => 'Tidak ada tugas yang dapat diselesaikan'
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating todo status: ' . $e->getMessage());
            return redirect()->back()->with([
                'status' => 'error',
                'message' => 'Gagal menyelesaikan tugas: ' . $e->getMessage()
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'prioritas' => 'required'
        ]);

        $todo = ToDoList::findOrFail($id);
        $todo->update([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'prioritas' => $request->prioritas,
        ]);

        return redirect()->route('todolist.index')->with([
            'status' => 'success',
            'message' => 'Tugas berhasil diperbarui'
        ]);
    }

    public function destroy($id)
    {
        try {
            $todo = ToDoList::where('id', $id)
                            ->where('user_id', Auth::id())
                            ->firstOrFail();
            $todo->delete();

            return redirect()->route('todolist.index')->with([
                'status' => 'success',
                'message' => 'Tugas berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return redirect()->route('todolist.index')->with([
                'status' => 'error',
                'message' => 'Gagal menghapus tugas'
            ]);
        }
    }

}