@extends('index')

@section('content')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <h4 class="text-center">Detail Gedung</h4>
                            <hr class="bg-dark px-auto">
                        </div>

                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="row">
                                <div class="col-md-4">
                                    <img src="{{ $gedung->gambar ? asset('storage/'.$gedung->gambar) : asset('assets/img/no-image.jpg') }}" class="ms-3 img-fluid rounded" alt="{{ $gedung->nama_gedung }}">
                                </div>
                                <div class="col-md-8">
                                    <table class="table table-sm align-items-center mb-0">
                                        <tbody>
                                            <tr>
                                                <th class="text-uppercase text-dark text-sm font-weight-bolder">Nama Gedung</th>
                                                <td>{{ $gedung->nama_gedung }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-uppercase text-dark text-sm font-weight-bolder">Luas Gedung (m²)</th>
                                                <td>{{ $gedung->luas_gedung }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-uppercase text-dark text-sm font-weight-bolder">Tahun Perolehan</th>
                                                <td>{{ $gedung->tahun_perolehan }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-uppercase text-dark text-sm font-weight-bolder">Nilai Bangunan</th>
                                                <td>Rp {{ number_format($gedung->nilai_bangunan, 2, ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-uppercase text-dark text-sm font-weight-bolder">Jumlah Ruang</th>
                                                <td>{{ $gedung->jumlah_ruang }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-uppercase text-dark text-sm font-weight-bolder">Peruntukkan</th>
                                                <td>{{ $gedung->peruntukkan }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-uppercase text-dark text-sm font-weight-bolder">Keterangan</th>
                                                <td>{{ $gedung->keterangan ?? '-' }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
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
