@extends('index')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="">Kelola Kategori</h4>
                            </div>
                        </div>

                        <hr class="bg-dark px-auto">
                        @if (Session::has('status'))
                            <div class="alert alert-success text-white opacity-5" role="alert">
                                {{ Session::get('message') }}
                            </div>
                        @endif
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('kategori.create') }}">
                                <div class="mt-2 text-white btn bg-gradient-success">Tambah Kategori</div>
                            </a>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-dark text-sm font-weight-bolder">No</th>
                                        <th class="text-uppercase text-dark text-sm font-weight-bolder">Nama</th>
                                        <th class="text-uppercase text-dark text-sm font-weight-bolder ">Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($kategori as $k)
                                        <tr class="ps-2">
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <h6 class="ps-2 text-secondary text-sm font-weight-bold">
                                                        {{ $loop->iteration }}</h6>
                                                </div>
                                            </td>
                                            {{-- <td>
                                                <div class="d-flex px-2 py-1">
                                                    <img src="{{ asset('/storage/public/images/' . $k->gambar) }}"
                                                        class="card-img"
                                                        style="object-fit: cover;max-width: 100px; max-height: 100px;"
                                                        alt="...">
                                                </div>
                                            </td> --}}
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <h6 class="text-secondary text-sm font-weight-bold ps-2">
                                                        {{ $k->nama }}</h6>
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                <a href="{{ route('kategori.edit', $k->id) }}"
                                                    class="btn bg-gradient-warning">Edit</a>

                                                <a href="{{ route('kategori.destroy', $k->id) }}"
                                                    onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this product?')) document.getElementById('delete-form-{{ $k->id }}').submit();"
                                                    class="btn bg-gradient-danger">Hapus</a>

                                                <form id="delete-form-{{ $k->id }}"
                                                    action="{{ route('kategori.destroy', $k->id) }}" method="POST"
                                                    style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>

                                                <a href="{{ route('kategori.show', $k->id) }}"
                                                    class="btn bg-gradient-info">Detail</a>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                            <div class="mx-5 my-2">
                                {{ $kategori->withQueryString()->links() }}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
