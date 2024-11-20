@extends('index')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="">Kelola Ruang</h4>
                            </div>
                        </div>
                        <hr class="bg-dark px-auto">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('ruang.create') }}">
                                <div class="mt-2 text-white btn bg-gradient-success">Tambah Ruang</div>
                            </a>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-dark text-sm font-weight-bolder">No</th>
                                        <th class="text-uppercase text-dark text-sm font-weight-bolder">Nama Ruang</th>
                                        <th class="text-uppercase text-dark text-sm font-weight-bolder">Gedung</th>
                                        <th class="text-uppercase text-dark text-sm font-weight-bolder">Ukuran</th>
                                        <th class="text-uppercase text-dark text-sm font-weight-bolder">Kondisi</th>
                                        <th class="text-uppercase text-dark text-sm font-weight-bolder">Peruntukkan</th>
                                        <th class="text-uppercase text-dark text-sm font-weight-bolder">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ruangs as $ruang)
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
                                                        {{ $ruang->nama_ruang }}</h6>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <h6 class="text-secondary text-sm font-weight-bold ps-2">
                                                        {{ $ruang->gedung->nama_gedung }}</h6>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <h6 class="text-secondary text-sm font-weight-bold ps-2">
                                                        {{ $ruang->ukuran }} m²</h6>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <h6 class="text-secondary text-sm font-weight-bold ps-2 
                                                        {{ $ruang->kondisi == 'Baik' ? 'text-success' : 
                                                           ($ruang->kondisi == 'Rusak Ringan' ? 'text-warning' : 'text-danger') }}">
                                                        {{ $ruang->kondisi }}</h6>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <h6 class="text-secondary text-sm font-weight-bold ps-2">
                                                        {{ $ruang->peruntukkan }}</h6>
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                <a href="{{ route('ruang.edit', $ruang->id) }}"
                                                    class="btn bg-gradient-warning">Edit</a>

                                                <a href="{{ route('ruang.destroy', $ruang->id) }}"
                                                    onclick="event.preventDefault(); if(confirm('Apakah Anda yakin ingin menghapus ruang ini?')) document.getElementById('delete-form-{{ $ruang->id }}').submit();"
                                                    class="btn bg-gradient-danger">Hapus</a>

                                                <form id="delete-form-{{ $ruang->id }}"
                                                    action="{{ route('ruang.destroy', $ruang->id) }}" method="POST"
                                                    style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>

                                                <a href="{{ route('ruang.show', $ruang->id) }}"
                                                    class="btn bg-gradient-info">Detail</a>
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
        // Check if there's a flash message
        @if(Session::has('status'))
            Swal.fire({
                icon: '{{ Session::get("status") }}',
                title: '{{ Session::get("status") == "success" ? "Berhasil!" : "Oops..." }}',
                text: '{{ Session::get("message") }}',
                showConfirmButton: false,
                timer: 3000
            });
        @endif
    </script>
    @endpush
@endsection