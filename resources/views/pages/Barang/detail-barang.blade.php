@extends('index')

@section('content')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <h4 class="text-center">Detail Barang</h4>
                            <hr class="bg-dark px-auto">
                        </div>

                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="row">
                                <div class="col-md-4">
                                    <img src="{{ asset($barang->gambar) }}"
                                        class="ms-3 img-fluid rounded" alt="{{ $barang->nama }}">
                                </div>
                                <div class="col-md-8">
                                    <table class="table table-sm align-items-center mb-0">
                                        <tbody>
                                            <tr>
                                                <th class="text-uppercase text-dark text-sm font-weight-bolder">Nama Barang
                                                </th>
                                                <td>{{ $barang->nama }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-uppercase text-dark text-sm font-weight-bolder">Jumlah</th>
                                                <td>{{ $barang->jumlah }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-uppercase text-dark text-sm font-weight-bolder">Lokasi
                                                </th>
                                                <td>{{ $barang->lokasi->nama }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-uppercase text-dark text-sm font-weight-bolder">Kategori
                                                </th>
                                                <td>
                                                    <ul style="list-style-type: none; padding: 0; margin: 0;">
                                                        @foreach ($barang->kategori as $item)
                                                            <li>{{ $item->nama }}</li>
                                                        @endforeach
                                                    </ul>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="table-responsive pt-2">
                                        <table class="table table-striped table-bordered table-hover table-sm">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Nama Pelanggan</th>
                                                    <th scope="col">Total Jumlah Peminjaman</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($detail as $k)
                                                    <tr>
                                                        <td>{{ $k->user->name }}</td> <!-- Asumsikan kolom 'name' ada di model 'User' -->
                                                        <td>{{ $k->total_peminjaman }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <center>
                        <div class="card-footer">
                            <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
                        </div>
                    </center>
                </div>
            </div>
        </div>
    </main>
@endsection
