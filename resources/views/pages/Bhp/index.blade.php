@extends('index')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="">Kelola BHP</h4>
                            </div>
                        </div>

                        <hr class="bg-dark px-auto">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('bhp.create') }}">
                                <div class="mt-2 text-white btn bg-gradient-success">Tambah BHP</div>
                            </a>
                            <a href="{{ route('bhp.cetak') }}" class="mt-2 text-white btn bg-info" target="_blank"><i class="fas fa-print me-2"></i>Cetak</a>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0" id="datatables">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-dark text-sm font-weight-bolder">No</th>
                                        <th class="text-uppercase text-dark text-sm font-weight-bolder">Nama BHP</th>
                                        <th class="text-uppercase text-dark text-sm font-weight-bolder">Spesifikasi</th>
                                        <th class="text-uppercase text-dark text-sm font-weight-bolder">Stok</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($bhps as $bhp)
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
                                                        {{ $bhp->nama }}</h6>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <h6 class="text-secondary text-sm font-weight-bold ps-2">
                                                        {{ $bhp->spesifikasi }}</h6>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <h6 class="text-secondary text-sm font-weight-bold ps-2">
                                                        {{ $bhp->stok }}</h6>
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                <a href="{{ route('bhp.edit', $bhp->id) }}"
                                                    class="btn bg-gradient-dark btn-sm"><i class="fa-solid fa-pencil" style="font-size: 14px"></i></a>

                                                <button type="button" 
                                                    class="btn btn-sm bg-gradient-danger"
                                                    onclick="confirmDelete('{{ $bhp->id }}')">
                                                    <i class="fa-solid fa-trash" style="font-size: 14px"></i>
                                                </button>

                                                <form id="delete-form-{{ $bhp->id }}"
                                                    action="{{ route('bhp.destroy', $bhp->id) }}" method="POST"
                                                    style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-2">
                                                    <p class="text-secondary text-sm mb-0">Tidak ada data bhp</p>
                                                </td>
                                            </tr>
                                        @endforelse
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
                timer: 3000
            });
        @endif

        // Function untuk konfirmasi delete
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