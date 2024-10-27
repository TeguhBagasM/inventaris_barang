@extends('index')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="">Lokasi</h4>
                            </div>
                        </div>

                        <hr class="bg-dark px-auto">
                        @if (Session::has('status'))
                            <div class="alert alert-success text-white opacity-5" role="alert">
                                {{ Session::get('message') }}
                            </div>
                        @endif
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('lokasi.create') }}">
                                <div class="mt-2 text-white btn bg-gradient-success">Tambah Lokasi</div>
                            </a>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        @if($lokasi->isEmpty())
                            <div class="text-center py-5">
                                <div class="mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" class="bi bi-geo-alt text-secondary" viewBox="0 0 16 16">
                                        <path d="M12.166 8.94c-.524 1.062-1.234 2.12-1.96 3.07A31.493 31.493 0 0 1 8 14.58a31.481 31.481 0 0 1-2.206-2.57c-.726-.95-1.436-2.008-1.96-3.07C3.304 7.867 3 6.862 3 6a5 5 0 0 1 10 0c0 .862-.305 1.867-.834 2.94M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10"/>
                                        <path d="M8 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4m0 1a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                                    </svg>
                                </div>
                                <h6 class="text-secondary">Belum ada data lokasi</h6>
                                <p class="text-muted">Silakan tambah lokasi baru dengan klik tombol 'Tambah Lokasi' di atas</p>
                            </div>
                        @else
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-dark text-sm font-weight-bolder">No</th>
                                            <th class="text-uppercase text-dark text-sm font-weight-bolder">Nama</th>
                                            <th class="text-uppercase text-dark text-sm font-weight-bolder">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($lokasi as $k)
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
                                                            {{ $k->nama }}</h6>
                                                    </div>
                                                </td>
                                                <td class="align-middle">
                                                    <a href="{{ route('lokasi.edit', $k->id) }}"
                                                        class="btn bg-gradient-warning">Edit</a>

                                                    <a href="{{ route('lokasi.destroy', $k->id) }}"
                                                        onclick="event.preventDefault(); if(confirm('Apakah Anda yakin ingin menghapus lokasi ini?')) document.getElementById('delete-form-{{ $k->id }}').submit();"
                                                        class="btn bg-gradient-danger">Hapus</a>

                                                    <form id="delete-form-{{ $k->id }}"
                                                        action="{{ route('lokasi.destroy', $k->id) }}" method="POST"
                                                        style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>

                                                    <a href="{{ route('lokasi.show', $k->id) }}"
                                                        class="btn bg-gradient-info">Detail</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="mx-5 my-2">
                                    {{ $lokasi->withQueryString()->links() }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection