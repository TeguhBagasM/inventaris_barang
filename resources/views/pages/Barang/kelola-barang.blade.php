@extends('index')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="">Pendataan Asset</h4>
                            </div>
                        </div>

                        <hr class="bg-dark px-auto">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('barang.create') }}">
                                <div class="mt-2 text-white btn bg-gradient-success">Tambah Asset</div>
                            </a>
                            <a href="{{ route('barang.cetak') }}" class="mt-2 text-white btn bg-info" target="_blank"><i class="fas fa-print me-2"></i>Cetak</a>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0" id="datatables">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-dark text-sm font-weight-bolder">No</th>
                                        <th class="text-uppercase text-dark text-sm font-weight-bolder">Nama Asset</th>
                                        <th class="text-uppercase text-dark text-sm font-weight-bolder">Merk</th>
                                        <th class="text-uppercase text-dark text-sm font-weight-bolder">Stok</th>
                                        <th class="text-uppercase text-dark text-sm font-weight-bolder">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($barangs as $barang)
                                        <tr class="ps-2">
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <h6 class="ps-2 text-secondary text-sm font-weight-bold">
                                                        {{ $loop->iteration }}</h6>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <h6 class="text-secondary text-sm font-weight-bold ps-2">
                                                        {{ $barang->nama }}</h6>
                                                </div>
                                            </td>
                                            
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <h6 class="text-secondary text-sm font-weight-bold ps-2">
                                                        {{ $barang->merk }}</h6>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <h6 class="text-secondary text-sm font-weight-bold ps-2">
                                                        {{ $barang->stok }}</h6>
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                <a href="{{ route('barang.edit', $barang->id) }}"
                                                    class="btn bg-gradient-dark btn-sm"><i class="fa-solid fa-pencil" style="font-size: 14px"></i></a>

                                                <form id="delete-form-{{ $barang->id }}"
                                                    action="{{ route('barang.destroy', $barang->id) }}" method="POST"
                                                    style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                                <button onclick="deleteBarang({{ $barang->id }})" 
                                                    class="btn btn-sm bg-gradient-danger">
                                                    <i class="fa-solid fa-trash" style="font-size: 14px"></i>
                                                </button>

                                                <a href="{{ route('barang.show', $barang->id) }}"
                                                    class="btn btn-sm bg-gradient-info"><i class="fa-solid fa-eye" style="font-size: 14px"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            // Show SweetAlert for flash messages
            @if(Session::has('status'))
                Swal.fire({
                    icon: '{{ Session::get("status") }}',
                    title: '{{ Session::get("status") == "success" ? "Berhasil!" : "Oops..." }}',
                    text: '{{ Session::get("message") }}',
                    showConfirmButton: false,
                    timer: 3000
                });
            @endif
        });

        function deleteBarang(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Barang yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`{{ route('barang.destroy', '') }}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message || 'Barang berhasil dihapus',
                        showConfirmButton: false,
                        timer: 3000
                    }).then(() => {
                        window.location.reload();
                    });
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: error.message || 'Gagal menghapus barang',
                        showConfirmButton: false,
                        timer: 3000
                    });
                });
            }
        });
        }
    </script>
    @endpush
@endsection