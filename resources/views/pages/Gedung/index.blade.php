@extends('index')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="">Kelola Gedung</h4>
                            </div>
                        </div>

                        <hr class="bg-dark px-auto">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('gedung.create') }}">
                                <div class="mt-2 text-white btn bg-gradient-success">Tambah Gedung</div>
                            </a>
                            <a href="{{ route('gedung.cetak') }}" class="mt-2 text-white btn bg-info" target="_blank"><i class="fas fa-print me-2"></i>Cetak</a>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0" id="datatables">
                                <thead>
                                    <tr class="text-center">
                                        <th class="text-uppercase text-dark text-sm font-weight-bolder">No</th>
                                        <th class="text-uppercase text-dark text-sm font-weight-bolder">Gambar</th>
                                        <th class="text-uppercase text-dark text-sm font-weight-bolder">Nama Gedung</th>
                                        <th class="text-uppercase text-dark text-sm font-weight-bolder">Jumlah Ruang</th>
                                        <th class="text-uppercase text-dark text-sm font-weight-bolder">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($gedungs as $gedung)
                                        <tr class="ps-2">
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <h6 class="ps-2 text-secondary text-sm font-weight-bold">
                                                        {{ $loop->iteration }}</h6>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <img src="{{ $gedung->gambar ? asset($gedung->gambar) : asset('assets/img/no-image.jpg') }}"
                                                        class="card-img"
                                                        style="object-fit: cover;max-width: 100px; max-height: 100px;"
                                                        alt="Gambar Gedung">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <h6 class="text-secondary text-sm font-weight-bold ps-2">
                                                        {{ $gedung->nama_gedung }}</h6>
                                                </div>
                                            </td>
                                            
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <h6 class="text-secondary text-sm font-weight-bold ps-2">
                                                        {{ $gedung->jumlah_ruang }}</h6>
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                <a href="{{ route('ruang.create-from-gedung', $gedung->id) }}" class="btn btn-sm bg-gradient-success"><i class="fa-solid fa-plus"></i> Ruangan</a>
                                                <a href="{{ route('gedung.edit', $gedung->id) }}"
                                                    class="btn bg-gradient-dark btn-sm"><i class="fa-solid fa-pencil" style="font-size: 14px"></i></a>

                                                <form id="delete-form-{{ $gedung->id }}"
                                                    action="{{ route('gedung.destroy', $gedung->id) }}" method="POST"
                                                    style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                                <button onclick="deleteGedung({{ $gedung->id }})" 
                                                    class="btn btn-sm bg-gradient-danger">
                                                    <i class="fa-solid fa-trash" style="font-size: 14px"></i>
                                                </button>

                                                <a href="{{ route('gedung.show', $gedung->id) }}"
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
    <script>
        @if(Session::has('status'))
                Swal.fire({
                    icon: '{{ Session::get("status") }}',
                    title: '{{ Session::get("status") == "success" ? "Berhasil!" : "Oops..." }}',
                    text: '{{ Session::get("message") }}',
                    showConfirmButton: false,
                    timer: 2000
                });
        @endif
        
        function deleteGedung(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Jika gedung dihapus, Ruangan di gedung ikut terhapus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Use fetch instead of jQuery AJAX for better compatibility
                fetch(`{{ route('gedung.destroy', '') }}/${id}`, {
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
                        text: data.message || 'Gedung berhasil dihapus',
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => {
                        window.location.reload();
                    });
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: error.message || 'Gagal menghapus gedung',
                        showConfirmButton: false,
                        timer: 2000
                    });
                });
            }
        });
    }
    </script>
    @endpush
@endsection