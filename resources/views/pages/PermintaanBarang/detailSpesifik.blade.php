@extends('index')
@section('content')
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-gradient-info text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0 text-white font-weight-bold">Detail Permintaan Barang</h4>
                        <a href="{{ route('permintaan.detail') }}" class="btn btn-outline-light">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="bg-light p-3 rounded">
                                    <h6 class="text-muted mb-2">Tanggal Permintaan</h6>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-calendar-alt text-primary me-3"></i>
                                        <span class="h6 mb-0">
                                            {{ \Carbon\Carbon::parse($barangKeluar->tanggal_minta)->translatedFormat('l, d F Y') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="bg-light p-3 rounded">
                                    <h6 class="text-muted mb-2">Total Barang</h6>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-boxes text-primary me-3"></i>
                                        <span class="h6 mb-0">
                                            {{ $barangKeluar->detailBarangKeluars->sum('jumlah') }} Barang
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="bg-light p-3 rounded">
                                    <h6 class="text-muted mb-2">Status Permintaan</h6>
                                    @switch($barangKeluar->status)
                                        @case('diajukan')
                                            <span class="badge bg-secondary">Diajukan</span>
                                            @break
                                        @case('disetujui')
                                            <span class="badge bg-gradient-success">Disetujui</span>
                                            @break
                                        @case('ditolak')
                                            <span class="badge bg-danger">Ditolak</span>
                                            @break
                                    @endswitch
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="bg-light p-3 rounded">
                                    <h6 class="text-muted mb-2">Keterangan</h6>
                                    @if ($barangKeluar->keterangan)
                                        <p class="mb-0">{{ $barangKeluar->keterangan }}</p>
                                    @else 
                                        <p class="text-muted mb-0">Tanpa Keterangan</p>
                                    @endif
                                </div>
                            </div>

                            <div class="col-12 mt-4">
                                <div class="bg-light p-3 rounded">
                                    <h6 class="text-muted mb-3">Detail Barang yang Diminta</h6>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama Barang</th>
                                                    <th>Jumlah</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($barangKeluar->detailBarangKeluars as $index => $detail)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ $detail->bhp->nama }}</td>
                                                        <td>{{ $detail->jumlah }}</td>
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
            </div>
        </div>
    </div>
@endsection