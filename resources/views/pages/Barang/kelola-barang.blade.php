@extends('index')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="">Pendataan Barang</h4>
                            </div>
                            {{-- <form action="" method="get">
                                <div class="pe-md-3 d-flex align-items-center float-end">
                                    <div class="input-group">
                                        <span style="max-height: 42px" class="input-group-text text-body"><i
                                                class="fas fa-search" aria-hidden="true"></i></span>
                                        <input style="max-height: 42px;" type="text" class="form-control"
                                            placeholder="Masukan Nama Produk" name="keyword">
                                        <button class="btn btn-outline-secondary" type="submit">Cari</button>
                                    </div>
                                </div>
                            </form> --}}
                        </div>

                        <hr class="bg-dark px-auto">
                        @if (Session::has('status'))
                            <div class="alert alert-success text-white opacity-5" role="alert">
                                {{ Session::get('message') }}
                            </div>
                        @endif
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('barang.create') }}">
                                <div class="mt-2 text-white btn bg-gradient-success">Tambah Produk</div>
                            </a>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-dark text-sm font-weight-bolder">No</th>
                                        <th class="text-uppercase text-dark text-sm font-weight-bolder">Gambar</th>
                                        <th class="text-uppercase text-dark text-sm font-weight-bolder">Nama Barang</th>
                                        <th class="text-uppercase text-dark text-sm font-weight-bolder ">
                                            Stok</th>
                                        <th class="text-uppercase text-dark text-sm font-weight-bolder ">Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($barang as $k)
                                        <tr class="ps-2">
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <h6 class="ps-2 text-secondary text-sm font-weight-bold">
                                                        {{ $loop->iteration }}</h6>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <img src="{{ asset($k->gambar) }}" alt="{{ $k->nama }}"
                                                        class="card-img"
                                                        style="object-fit: cover;max-width: 100px; max-height: 100px;"
                                                        >
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <h6 class="text-secondary text-sm font-weight-bold ps-2">
                                                        {{ $k->nama }}</h6>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <h6 class="text-secondary text-sm font-weight-bold ps-2">
                                                        {{ $k->stok }}</h6>
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                <a href="{{ route('barang.edit', $k->id) }}"
                                                    class="btn bg-gradient-warning">Edit</a>

                                                <a href="{{ route('barang.destroy', $k->id) }}"
                                                    onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this product?')) document.getElementById('delete-form-{{ $k->id }}').submit();"
                                                    class="btn bg-gradient-danger">Hapus</a>

                                                <form id="delete-form-{{ $k->id }}"
                                                    action="{{ route('barang.destroy', $k->id) }}" method="POST"
                                                    style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>

                                                <a href="{{ route('barang.show', $k->id) }}"
                                                    class="btn bg-gradient-info">Detail</a>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                            <div class="mx-5 my-2">
                                {{ $barang->withQueryString()->links() }}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
