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
                        @if (Session::has('success'))
                            <div class="alert alert-success text-white opacity-5" role="alert">
                                {{ Session::get('success') }}
                            </div>
                        @endif
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('gedung.create') }}">
                                <div class="mt-2 text-white btn bg-gradient-success">Tambah Gedung</div>
                            </a>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
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
                                                    <img src="{{ $gedung->gambar ? asset('storage/'.$gedung->gambar) : asset('assets/img/no-image.jpg') }}"
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
                                                <a href="{{ route('ruang.create-from-gedung', $gedung->id) }}" class="btn bg-gradient-success"><i class="fa-solid fa-plus"></i> Ruangan</a>
                                                <a href="{{ route('gedung.edit', $gedung->id) }}"
                                                    class="btn bg-gradient-warning"><i class="fa-solid fa-pencil" style="font-size: 14px"></i></a>

                                                <a href="{{ route('gedung.destroy', $gedung->id) }}"
                                                    onclick="event.preventDefault(); if(confirm('Apakah Anda yakin ingin menghapus gedung ini?')) document.getElementById('delete-form-{{ $gedung->id }}').submit();"
                                                    class="btn bg-gradient-danger"><i class="fa-solid fa-trash" style="font-size: 14px"></i></a>

                                                <form id="delete-form-{{ $gedung->id }}"
                                                    action="{{ route('gedung.destroy', $gedung->id) }}" method="POST"
                                                    style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                                <a href="{{ route('gedung.show', $gedung->id) }}"
                                                    class="btn bg-gradient-info"><i class="fa-solid fa-eye" style="font-size: 14px"></i></a>
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
@endsection