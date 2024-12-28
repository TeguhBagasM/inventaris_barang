@extends('index')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="">Data Peminjaman Barang</h4>
                            </div>
                        </div>
                        <hr class="bg-dark px-auto">
                        @if (Session::has('status'))
                            <div class="alert alert-success text-white opacity-5" role="alert">
                                {{ Session::get('message') }}
                            </div>
                        @endif
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            @if ($details->isEmpty())
                                <div class="d-flex flex-column align-items-center my-4">
                                    <i class="fas fa-exclamation-circle fa-3x text-warning mb-3"></i>
                                    <h5 class="text-muted">Belum ada data peminjaman</h5>
                                </div>
                            @else
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-dark text-sm font-weight-bolder">No</th>
                                            <th class="text-uppercase text-dark text-sm font-weight-bolder">Tanggal Peminjaman</th>
                                            <th class="text-uppercase text-dark text-sm font-weight-bolder">Total Barang</th>
                                            <th class="text-uppercase text-dark text-sm font-weight-bolder">Status</th>
                                            <th class="text-uppercase text-dark text-sm font-weight-bolder">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($details as $peminjaman)
                                            <tr>
                                                <td>
                                                    <h6 class="text-secondary text-sm font-weight-bold ps-2">
                                                        {{ $loop->iteration }}
                                                    </h6>
                                                </td>
                                                <td>
                                                    <h6 class="text-secondary text-sm font-weight-bold ps-2">
                                                        {{ \Carbon\Carbon::parse($peminjaman->tanggal_peminjaman)->translatedFormat('l, d F Y') }}
                                                    </h6>
                                                </td>
                                                <td>
                                                    <h6 class="text-secondary text-sm font-weight-bold ps-2">
                                                        {{ $peminjaman->detailPeminjamans->sum('jumlah') }} Barang
                                                    </h6>
                                                </td>
                                                <td>
                                                    @switch($peminjaman->status)
                                                        @case('menunggu konfirmasi')
                                                            <span class="badge bg-secondary">Menunggu Konfirmasi</span>
                                                            @break
                                                        @case('dipinjam')
                                                            <span class="badge bg-warning">Dipinjam</span>
                                                            @break
                                                        @case('ditolak')
                                                            <span class="badge bg-danger">Ditolak</span>
                                                            @break
                                                        @case('selesai')
                                                            <span class="badge bg-success">Selesai</span>
                                                            @break
                                                    @endswitch
                                                </td>
                                                <td>
                                                    <div class="d-flex">
                                                        <a href="{{ route('peminjaman.detail', $peminjaman->id) }}" 
                                                           class="btn btn-info btn-sm me-2 ps-3 pe-3">
                                                            <i class="fas fa-eye me-1" style="font-size: 14px"></i> Detail
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="mx-5 my-2">
                                    {{ $details->withQueryString()->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .pagination .page-item.active .page-link {
            border-color: white;
            color: white;
        }
    </style>
@endsection