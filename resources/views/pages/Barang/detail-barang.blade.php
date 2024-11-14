@extends('index')

@section('content')
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card mb-4 shadow">
                    <div class="card-header pb-0 bg-gradient-info">
                        <h4 class="text-center text-white mb-3">Detail Barang</h4>
                    </div>

                    <div class="card-body px-4 pt-4 pb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="position-relative">
                                    <img src="{{ asset($barang->gambar) }}"
                                        class="img-fluid rounded shadow-sm" 
                                        alt="{{ $barang->nama }}"
                                        style="width: 100%; object-fit: cover;">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="table-responsive">
                                    <table class="table table-striped align-items-center mb-4">
                                        <tbody>
                                            <tr>
                                                <th class="text-uppercase text-dark text-xs font-weight-bolder" width="30%">Nama Barang</th>
                                                <td class="text-sm">{{ $barang->nama }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-uppercase text-dark text-xs font-weight-bolder">Merk</th>
                                                <td class="text-sm">{{ $barang->merk }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-uppercase text-dark text-xs font-weight-bolder">Spesifikasi</th>
                                                <td class="text-sm">{{ $barang->spesifikasi }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-uppercase text-dark text-xs font-weight-bolder">Serial Number</th>
                                                <td class="text-sm">{{ $barang->serial_number }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-uppercase text-dark text-xs font-weight-bolder">Stok</th>
                                                <td class="text-sm">{{ $barang->stok }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-uppercase text-dark text-xs font-weight-bolder">Tahun Pengadaan</th>
                                                <td class="text-sm">{{ $barang->tahun_pengadaan }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-uppercase text-dark text-xs font-weight-bolder">Sumber Dana</th>
                                                <td class="text-sm">Rp {{ number_format((float)$barang->sumber_dana, 0, ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-uppercase text-dark text-xs font-weight-bolder">Ruang</th>
                                                <td class="text-sm">{{ $barang->ruang->nama_ruang }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-uppercase text-dark text-xs font-weight-bolder">Kategori</th>
                                                <td class="text-sm">{{ $barang->kategori->nama }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-uppercase text-dark text-xs font-weight-bolder">Kondisi</th>
                                                <td class="text-sm">
                                                    <span class="badge badge-sm bg-gradient-{{ $barang->kondisi == 'Baik' ? 'success' : ($barang->kondisi == 'Rusak Ringan' ? 'warning' : 'danger') }}">
                                                        {{ $barang->kondisi }}
                                                    </span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div class="mt-4">
                                    <h6 class="text-uppercase text-dark font-weight-bolder mb-3">Riwayat Peminjaman</h6>
                                    <div class="table-responsive">
                                        <table class="table table-hover table-striped table-sm">
                                            <thead class="bg-gradient-info text-white">
                                                <tr>
                                                    <th class="text-uppercase text-xs font-weight-bolder">Nama Peminjam</th>
                                                    <th class="text-uppercase text-xs font-weight-bolder">Total Peminjaman</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($detail as $k)
                                                    <tr>
                                                        <td class="text-sm">{{ $k->user->name }}</td>
                                                        <td class="text-sm">{{ $k->total_peminjaman }} kali</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="2" class="text-center text-sm">Belum ada riwayat peminjaman</td>
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
                        <a href="{{ url()->previous() }}" class="btn btn-info">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection