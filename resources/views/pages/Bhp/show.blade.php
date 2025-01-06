@extends('index')

@section('content')
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card mb-4 shadow">
                    <div class="card-header pb-0 bg-gradient-info">
                        <h4 class="text-center text-white mb-3">Detail Barang Habis Pakai</h4>
                    </div>

                    <div class="card-body px-4 pt-4 pb-4">
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-striped align-items-center mb-4">
                                        <tbody>
                                            <tr>
                                                <th class="text-uppercase text-dark text-xs font-weight-bolder" width="30%">Nama BHP</th>
                                                <td class="text-sm">{{ $bhp->nama }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-uppercase text-dark text-xs font-weight-bolder">Spesifikasi</th>
                                                <td class="text-sm">{{ $bhp->spesifikasi }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-uppercase text-dark text-xs font-weight-bolder">Stok</th>
                                                <td class="text-sm">{{ $bhp->stok }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-uppercase text-dark text-xs font-weight-bolder">Tahun Pengadaan</th>
                                                <td class="text-sm">{{ $bhp->tahun_pengadaan }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-uppercase text-dark text-xs font-weight-bolder">Sumber Dana</th>
                                                <td class="text-sm">{{ $bhp->sumber_dana }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div class="mt-4">
                                    <h6 class="text-uppercase text-dark font-weight-bolder mb-3">Riwayat Pengeluaran</h6>
                                    <div class="table-responsive">
                                        <table class="table table-hover table-striped table-sm">
                                            <thead class="bg-gradient-info text-white">
                                                <tr>
                                                    <th class="text-uppercase text-xs font-weight-bolder">Nama Pengambil</th>
                                                    <th class="text-uppercase text-xs font-weight-bolder">Total Pengeluaran</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($detail as $k)
                                                    <tr>
                                                        <td class="text-sm">{{ $k->user->name }}</td>
                                                        <td class="text-sm">{{ $k->total_pengeluaran }} kali</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="2" class="text-center text-sm">Belum ada riwayat pengeluaran</td>
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
                        <a href="{{ url()->previous() }}" class="btn btn-danger">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection