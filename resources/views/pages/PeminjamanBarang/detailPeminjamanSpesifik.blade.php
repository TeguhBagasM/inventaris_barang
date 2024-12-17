@extends('index')
@section('content')
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-gradient-info text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Detail Peminjaman Barang</h4>
                        <a href="/detailPeminjaman" class="btn btn-outline-light">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="bg-light p-3 rounded">
                                    <h6 class="text-muted mb-2">Tanggal Peminjaman</h6>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-calendar-alt text-primary me-3"></i>
                                        <span class="h5 mb-0">
                                            {{ \Carbon\Carbon::parse($peminjaman->tanggal_peminjaman)->translatedFormat('l, d F Y') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="bg-light p-3 rounded">
                                    <h6 class="text-muted mb-2">Total Barang</h6>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-boxes text-primary me-3"></i>
                                        <span class="h5 mb-0">
                                            {{ $peminjaman->detailPeminjamans->sum('jumlah') }} Barang
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="bg-light p-3 rounded">
                                    <h6 class="text-muted mb-2">Status Peminjaman</h6>
                                    @switch($peminjaman->status)
                                        @case('menunggu konfirmasi')
                                            <span class="badge bg-secondary fs-6">Menunggu Konfirmasi</span>
                                            @break
                                        @case('dipinjam')
                                            <span class="badge bg-warning fs-6">Dipinjam</span>
                                            @break
                                        @case('ditolak')
                                            <span class="badge bg-danger fs-6">Ditolak</span>
                                            @break
                                        @case('selesai')
                                            <span class="badge bg-success fs-6">Selesai</span>
                                            @break
                                    @endswitch
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="bg-light p-3 rounded">
                                    <h6 class="text-muted mb-2">Keterangan</h6>
                                    @if ($peminjaman->keterangan != NULL)
                                        <p class="mb-0">{{ $peminjaman->keterangan }}</p>
                                    @else 
                                        <p class="text-muted mb-0">Tanpa Keterangan</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
