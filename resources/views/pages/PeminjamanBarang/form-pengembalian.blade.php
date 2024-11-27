@extends('index')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h4>Form Pengembalian Barang</h4>
                    <hr style="background-color: black">
                    @if (Session::has('status'))
                        <div class="alert alert-{{ Session::get('status') }} text-white" role="alert">
                            {{ Session::get('message') }}
                        </div>
                    @endif
                </div>

                <div class="card-body">
                    <!-- Informasi Peminjaman -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card bg-gradient-info shadow-info">
                                <div class="card-body p-3">
                                    <h5 class="text-white mb-3">Detail Peminjaman</h5>
                                    <div class="details text-white">
                                        <p class="mb-1"><strong>Peminjam:</strong> {{ $peminjaman->user->name }}</p>
                                        <p class="mb-1"><strong>Tanggal Pinjam:</strong> {{ \Carbon\Carbon::parse($peminjaman->tanggal_peminjaman)->format('d M Y') }}</p>
                                        <p class="mb-1"><strong>Total Barang:</strong> {{ $detailPeminjamans->sum('jumlah') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Pengembalian -->
                    <form action="{{ route('pengembalian.process', $peminjaman->id) }}" method="POST">
                        @csrf
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Barang</th>
                                        <th>Jumlah Pinjam</th>
                                        <th>Jumlah Kembali</th>
                                        <th>Kondisi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($detailPeminjamans as $detail)
                                    <tr>
                                        <td>
                                            <input type="hidden" name="detail_peminjamans[{{ $loop->index }}][id]" value="{{ $detail->id }}">
                                            {{ $detail->barang->nama }}
                                        </td>
                                        <td>{{ $detail->jumlah }}</td>
                                        <td>
                                            <input type="number" 
                                                   name="detail_peminjamans[{{ $loop->index }}][jumlah_kembali]" 
                                                   class="form-control" 
                                                   max="{{ $detail->jumlah }}" 
                                                   min="1" 
                                                   required
                                                   value="{{ $detail->jumlah }}">
                                        </td>
                                        <td>
                                            <select name="detail_peminjamans[{{ $loop->index }}][kondisi]" class="form-control" required>
                                                <option value="Baik" {{ $detail->barang->kondisi == 'Baik' ? 'selected' : '' }}>Baik</option>
                                                <option value="Rusak Ringan" {{ $detail->barang->kondisi == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                                <option value="Rusak Berat" {{ $detail->barang->kondisi == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                                            </select>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            <a href="{{ url()->previous() }}" class="btn bg-gradient-danger">Kembali</a>
                            <button type="submit" class="btn bg-gradient-success float-end">
                                <i class="fas fa-check me-2"></i>Proses Pengembalian
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection