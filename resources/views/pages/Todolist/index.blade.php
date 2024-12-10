@extends('index')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="">Daftar Tugas</h4>
                            </div>
                        </div>

                        <hr class="bg-dark px-auto">
                        <div class="d-flex justify-content-between mb-1">
                            <a href="{{ route('todolist.create') }}">
                                <div class="mt-2 text-white btn bg-gradient-success">Tambah Tugas</div>
                            </a>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <form action="{{ route('todolist.updateStatus') }}" method="POST">
                            @csrf
                            <div class="ms-4">
                                <button type="submit" class="btn bg-gradient-success" id="submitButton" style="display: none;">
                                    Selesaikan Tugas
                                </button>
                            </div>
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th width="5%" class="text-center">#</th>
                                            <th class="text-uppercase text-dark text-sm font-weight-bolder">Judul</th>
                                            <th class="text-uppercase text-dark text-sm font-weight-bolder">Deskripsi</th>
                                            <th class="text-uppercase text-dark text-sm font-weight-bolder">Prioritas</th>
                                            <th class="text-uppercase text-dark text-sm font-weight-bolder">Status</th>
                                            <th class="text-uppercase text-dark text-sm font-weight-bolder">Dibuat oleh</th>
                                            <th class="text-uppercase text-dark text-sm font-weight-bolder">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($todos as $todo)
                                            <tr>
                                                <td class="text-center">
                                                    @if ($todo->status === 'pending')
                                                        <input type="checkbox" name="todo_ids[]" value="{{ $todo->id }}" class="todo-checkbox cursor-pointer">
                                                    @endif
                                                </td>
                                                <td>
                                                    <h6 class="text-secondary text-sm font-weight-bold ps-2">{{ $todo->judul }}</h6>
                                                </td>
                                                <td>
                                                    <h6 class="text-secondary text-sm font-weight-bold ps-2">{{ $todo->deskripsi }}</h6>
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $todo->prioritas === 'Tinggi' ? 'danger' : 'info' }}">{{ $todo->prioritas }}</span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $todo->status === 'selesai' ? 'success' : 'warning' }}">{{ $todo->status }}</span>
                                                </td>
                                                <td>
                                                    <h6 class="text-secondary text-sm font-weight-bold ps-2">{{ $todo->user->level ?? 'Tidak Diketahui' }}</h6>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn bg-gradient-dark btn-sm" data-bs-toggle="modal" data-bs-target="#editTodoModal{{ $todo->id }}">
                                                        <i class="fa-solid fa-pencil" style="font-size: 14px"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm bg-gradient-danger" onclick="confirmDelete('{{ $todo->id }}')">
                                                        <i class="fa-solid fa-trash" style="font-size: 14px"></i>
                                                    </button>
                                                    <form id="delete-form-{{ $todo->id }}" action="{{ route('todolist.destroy', $todo->id) }}" method="POST" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-2">
                                                    <p class="text-secondary text-sm mb-0">Tidak ada tugas yang tersedia</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>                                
                            </div>
                        <div class="mx-5 my-2">
                            {{ $todos->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
        const addTodoForm = document.querySelector('#addTodoModal form');
        const todoUpdateForm = document.querySelector('form[action="{{ route("todolist.updateStatus") }}"]');
        const checkboxes = document.querySelectorAll('.todo-checkbox');
        const submitButton = document.getElementById('submitButton');

        if (addTodoForm) {
        addTodoForm.addEventListener('submit', function(e) {
            const judul = document.getElementById('judul');
            const prioritas = document.getElementById('prioritas');
            
            if (!judul || !judul.value.trim() || !prioritas || prioritas.value === '') {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Validasi Gagal',
                    text: 'Harap lengkapi judul dan prioritas tugas',
                    confirmButtonText: 'Oke'
                });
                return false;
            }
        });
    }

        @if(Session::has('status') && Session::get('error_type') == 'update_status')
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ Session::get("message") }}',
            });
        @endif

        if (todoUpdateForm) {
            todoUpdateForm.addEventListener('submit', function(e) {
                const checkedBoxes = document.querySelectorAll('.todo-checkbox:checked');
                
                if (checkedBoxes.length === 0) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Pilih minimal satu tugas untuk diselesaikan',
                    });
                }
            });
        }

        if (checkboxes.length > 0 && submitButton) {
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const isAnyChecked = Array.from(checkboxes).some(cb => cb.checked);
                    submitButton.style.display = isAnyChecked ? 'block' : 'none';
                });
            });
        }
    });

    @if(Session::has('status') && Session::get('error_type') !== 'update_status')
        Swal.fire({
            icon: '{{ Session::get("status") }}',
            title: '{{ Session::get("status") == "success" ? "Berhasil!" : "Oops..." }}',
            text: '{{ Session::get("message") }}',
            showConfirmButton: false,
            timer: 3000
        });
    @endif

    function confirmDelete(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
    </script>
    @endpush
@endsection