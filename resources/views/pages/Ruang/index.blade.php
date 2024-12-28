@extends('index')
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h4>Kelola Ruang</h4>
                    <a href="{{ route('ruang.create') }}" class="btn bg-gradient-success mt-2">Tambah Ruang</a>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table id="ruang-table" class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Ruang</th>
                                    <th>Gedung</th>
                                    <th>Ukuran</th>
                                    <th>Kondisi</th>
                                    <th>Peruntukkan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function () {
        $('#ruang-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('ruang.index') }}',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'nama_ruang', name: 'nama_ruang' },
                { data: 'gedung', name: 'gedung.nama_gedung' },
                { data: 'ukuran', name: 'ukuran' },
                { data: 'kondisi', name: 'kondisi' },
                { data: 'peruntukkan', name: 'peruntukkan' },
                { data: 'aksi', name: 'aksi', orderable: false, searchable: false },
            ]
        });
    });
    @if(Session::has('status'))
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
