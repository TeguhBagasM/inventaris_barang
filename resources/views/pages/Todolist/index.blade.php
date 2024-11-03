@extends('index')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="">To Do List</h4>
                            </div>
                        </div>

                        <hr class="bg-dark px-auto">
                        @if (Session::has('status'))
                            <div class="alert alert-success text-white opacity-5" role="alert">
                                {{ Session::get('message') }}
                            </div>
                        @endif
                        <div class="d-flex justify-content-between mb-3">
                            <button type="button" class="btn bg-gradient-success" data-bs-toggle="modal" data-bs-target="#addTodoModal">
                                Tambah Todo
                            </button>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <form action="{{ route('todolist.updateStatus') }}" method="POST">
                            @csrf
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th width="5%" class="text-center">#</th>
                                            <th class="text-uppercase text-dark text-sm font-weight-bolder">Judul</th>
                                            <th class="text-uppercase text-dark text-sm font-weight-bolder">Deskripsi</th>
                                            <th class="text-uppercase text-dark text-sm font-weight-bolder">Prioritas</th>
                                            <th class="text-uppercase text-dark text-sm font-weight-bolder">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($todos as $todo)
                                            <tr>
                                                <td class="text-center">
                                                    @if($todo->status === 'pending')
                                                        <input type="checkbox" name="todo_ids[]" value="{{ $todo->id }}" class="todo-checkbox cursor-pointer">
                                                    @endif
                                                </td>
                                                <td>
                                                    <h6 class="text-secondary text-sm font-weight-bold ps-2">
                                                        {{ $todo->judul }}
                                                    </h6>
                                                </td>
                                                <td>
                                                    <h6 class="text-secondary text-sm font-weight-bold ps-2">
                                                        {{ $todo->deskripsi }}
                                                    </h6>
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $todo->prioritas === 'Tinggi' ? 'danger' : 'info' }}">
                                                        {{ $todo->prioritas }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $todo->status === 'selesai' ? 'success' : 'warning' }}">
                                                        {{ $todo->status }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="m-3">
                                <button type="submit" class="btn bg-gradient-success" id="submitButton" style="display: none;">Selesaikan Todo</button>
                            </div>
                        </form>
                        <div class="mx-5 my-2">
                            {{ $todos->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Todo -->
    <div class="modal fade" id="addTodoModal" tabindex="-1" aria-labelledby="addTodoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('todolist.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addTodoModalLabel">Tambah Todo Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="judul" class="form-label">Judul</label>
                            <input type="text" class="form-control" id="judul" name="judul" required>
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="prioritas" class="form-label">Prioritas</label>
                            <select class="form-control" id="prioritas" name="prioritas" required>
                                <option value="Tinggi">Tinggi</option>
                                <option value="Rendah">Rendah</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.todo-checkbox');
            const submitButton = document.getElementById('submitButton');

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    // Cek apakah ada checkbox yang dicentang
                    const isAnyChecked = Array.from(checkboxes).some(cb => cb.checked);
                    
                    // Tampilkan atau sembunyikan tombol submit
                    submitButton.style.display = isAnyChecked ? 'block' : 'none';
                });
            });
        });
    </script>
    @endpush

@endsection