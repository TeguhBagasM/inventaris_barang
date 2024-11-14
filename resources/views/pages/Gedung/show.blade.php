@extends('index')

@section('content')
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card mb-4 shadow">
                    <div class="card-header pb-0 bg-gradient-dark">
                        <h4 class="text-center text-white mb-3">Detail Gedung</h4>
                    </div>

                    <div class="card-body px-4 pt-4 pb-4">
                        <div class="row g-4">
                            <div class="col-md-4">
                                <div class="position-relative">
                                    <img src="{{ $gedung->gambar ? asset('storage/'.$gedung->gambar) : asset('assets/img/no-image.jpg') }}"
                                        class="img-fluid rounded shadow-sm"
                                        alt="{{ $gedung->nama_gedung }}"
                                        style="width: 100%; height: 250px; object-fit: cover;">
                                </div>
                                
                                <!-- Card Info Summary -->
                                <div class="card mt-3 bg-gradient-info text-white">
                                    <div class="card-body p-3">
                                        <div class="row">
                                            <div class="col-8">
                                                <div class="numbers">
                                                    <p class="text-sm mb-0 text-white font-weight-bold">Jumlah Ruang</p>
                                                    <h5 class="text-white font-weight-bolder mb-0">
                                                        {{ $gedung->jumlah_ruang }}
                                                        <span class="text-sm">ruangan</span>
                                                    </h5>
                                                </div>
                                            </div>
                                            <div class="col-4 text-end">
                                                <div class="icon icon-shape bg-white shadow text-center border-radius-md">
                                                    <i class="fas fa-door-open text-info text-lg opacity-10" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-8">
                                <div class="table-responsive">
                                    <table class="table table-striped align-items-center mb-4">
                                        <tbody>
                                            <tr>
                                                <th class="text-uppercase text-dark text-xs font-weight-bolder" width="30%">Nama Gedung</th>
                                                <td class="text-sm">{{ $gedung->nama_gedung }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-uppercase text-dark text-xs font-weight-bolder">Luas Gedung</th>
                                                <td class="text-sm">{{ number_format($gedung->luas_gedung, 0, ',', '.') }} m²</td>
                                            </tr>
                                            <tr>
                                                <th class="text-uppercase text-dark text-xs font-weight-bolder">Tahun Perolehan</th>
                                                <td class="text-sm">
                                                    <span class="badge badge-sm bg-gradient-success">{{ $gedung->tahun_perolehan }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="text-uppercase text-dark text-xs font-weight-bolder">Nilai Bangunan</th>
                                                <td class="text-sm fw-bold">
                                                    Rp {{ number_format($gedung->nilai_bangunan, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="text-uppercase text-dark text-xs font-weight-bolder">Peruntukkan</th>
                                                <td class="text-sm">
                                                    <span class="badge badge-sm bg-gradient-primary">{{ $gedung->peruntukkan }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="text-uppercase text-dark text-xs font-weight-bolder">Keterangan</th>
                                                <td class="text-sm">{{ $gedung->keterangan ?? '-' }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Building Details Summary Cards -->
                                <div class="row g-3 mt-2">
                                    <div class="col-md-6">
                                        <div class="card bg-gradient-success">
                                            <div class="card-body p-3">
                                                <div class="row">
                                                    <div class="col-8">
                                                        <div class="numbers">
                                                            <p class="text-sm mb-0 text-white font-weight-bold">Luas Area</p>
                                                            <h5 class="text-white font-weight-bolder mb-0">
                                                                {{ number_format($gedung->luas_gedung, 0, ',', '.') }}
                                                                <span class="text-sm">m²</span>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                    <div class="col-4 text-end">
                                                        <div class="icon icon-shape bg-white shadow text-center border-radius-md">
                                                            <i class="fas fa-ruler-combined text-success text-lg opacity-10" aria-hidden="true"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card bg-gradient-warning">
                                            <div class="card-body p-3">
                                                <div class="row">
                                                    <div class="col-8">
                                                        <div class="numbers">
                                                            <p class="text-sm mb-0 text-white font-weight-bold">Nilai Asset</p>
                                                            <h5 class="text-white font-weight-bolder mb-0">
                                                                {{ number_format($gedung->nilai_bangunan/1000000, 1, ',', '.') }}
                                                                <span class="text-sm">Juta</span>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                    <div class="col-4 text-end">
                                                        <div class="icon icon-shape bg-white shadow text-center border-radius-md">
                                                            <i class="fas fa-coins text-warning text-lg opacity-10" aria-hidden="true"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer text-center py-3">
                        <a href="{{ url()->previous() }}" class="btn btn-dark">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection