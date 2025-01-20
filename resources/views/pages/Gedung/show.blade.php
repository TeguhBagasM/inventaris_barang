@extends('index')

@section('content')
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card mb-4 shadow">
                    <div class="card-header pb-0 bg-gradient-info">
                        <h4 class="text-center text-white mb-3">Detail Gedung</h4>
                    </div>

                    <div class="card-body px-4 pt-4 pb-4">
                        <div class="row g-4">
                            <div class="col-md-4">
                                <div class="position-relative">
                                    <img src="{{ $gedung->gambar ? asset($gedung->gambar) : asset('assets/img/no-image.jpg') }}"
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

                                <div class="card mt-3 bg-gradient-success">
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

                                <div class="card mt-3 bg-gradient-warning">
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
                            
                            <div class="col-md-8">
                                <div class="table-responsive">
                                    <table class="table table-striped align-items-center mb-4">
                                        <tbody>
                                            <tr>
                                                <th class="text-uppercase text-dark text-xs font-weight-bolder" width="30%">Nama Gedung</th>
                                                <td class="text-sm">{{ $gedung->nama_gedung }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-uppercase text-dark text-xs font-weight-bolder">Tahun Perolehan</th>
                                                <td class="text-sm">
                                                    <span class="badge badge-sm bg-gradient-success">{{ $gedung->tahun_perolehan }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="text-uppercase text-dark text-xs font-weight-bolder">Peruntukkan</th>
                                                <td class="text-sm">
                                                    {{ $gedung->peruntukkan }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="text-uppercase text-dark text-xs font-weight-bolder">Keterangan</th>
                                                <td class="text-sm">{{ $gedung->keterangan ?? '-' }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div class="mt-4">
                                    <h6 class="text-uppercase text-dark font-weight-bolder mb-3">Informasi Ruangan</h6>
                                    <div class="table-responsive">
                                        <table class="table table-hover table-striped table-sm">
                                            <thead class="bg-gradient-info text-white">
                                                <tr>
                                                    <th class="text-uppercase text-xs font-weight-bolder">Nama Ruangan</th>
                                                    <th class="text-uppercase text-xs font-weight-bolder">Ukuran</th>
                                                    <th class="text-uppercase text-xs font-weight-bolder">Kondisi</th>
                                                    <th class="text-uppercase text-xs font-weight-bolder">Peruntukkan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($gedung->ruangs as $ruang)
                                                    <tr>
                                                        <td class="text-sm">{{ $ruang->nama_ruang }}</td>
                                                        <td class="text-sm">{{ $ruang->ukuran }}</td>
                                                        <td class="text-sm">{{ $ruang->kondisi }}</td>
                                                        <td class="text-sm">{{ $ruang->peruntukkan }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center text-sm">Belum ada data ruangan</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer text-center py-3">
                        <a href="{{ route('gedung.index') }}" class="btn btn-danger">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection