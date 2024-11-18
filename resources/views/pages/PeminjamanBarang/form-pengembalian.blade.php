@extends('index')

@section('content')
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
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
                        <!-- Detail Peminjaman Section -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card bg-gradient-info shadow-info">
                                    <div class="card-body p-3">
                                        <h5 class="text-white mb-3">Detail Peminjaman</h5>
                                        <div class="details text-white">
                                            <p class="mb-1"><strong>Peminjam:</strong> {{ $detailPeminjaman->user->name }}</p>
                                            <p class="mb-1"><strong>Barang:</strong> {{ $detailPeminjaman->barang->nama }}</p>
                                            <p class="mb-1"><strong>Jumlah:</strong> {{ $detailPeminjaman->jumlah }}</p>
                                            <p class="mb-1"><strong>Tanggal Pinjam:</strong> {{ \Carbon\Carbon::parse($detailPeminjaman->keluar)->format('d M Y') }}</p>
                                            <p class="mb-0"><strong>Kondisi Saat Dipinjam:</strong> {{ $detailPeminjaman->barang->kondisi }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Informasi Tambahan -->
                            <div class="col-md-6">
                                <div class="card bg-gradient-light">
                                    <div class="card-body p-3">
                                        <h5 class="mb-3">Informasi Barang</h5>
                                        <div class="details">
                                            <p class="mb-1"><strong>Merk:</strong> {{ $detailPeminjaman->barang->merk }}</p>
                                            <p class="mb-1"><strong>Spesifikasi:</strong> {{ $detailPeminjaman->barang->spesifikasi }}</p>
                                            <p class="mb-1"><strong>Serial Number:</strong> {{ $detailPeminjaman->barang->serial_number }}</p>
                                            <p class="mb-0"><strong>Tahun Pengadaan:</strong> {{ $detailPeminjaman->barang->tahun_pengadaan }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Pengembalian -->
                        <form action="{{ route('pengembalian.process', $detailPeminjaman->id) }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label required-label">Kondisi Barang Saat Pengembalian</label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="kondisi" value="Baik" 
                                                    id="kondisiBaik" required {{ $detailPeminjaman->barang->kondisi == 'Baik' ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="kondisiBaik">Baik</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="kondisi" value="Rusak Ringan" 
                                                    id="kondisiRusakRingan" required {{ $detailPeminjaman->barang->kondisi == 'Rusak Ringan' ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="kondisiRusakRingan">Rusak Ringan</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="kondisi" value="Rusak Berat" 
                                                    id="kondisiRusakBerat" required {{ $detailPeminjaman->barang->kondisi == 'Rusak Berat' ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="kondisiRusakBerat">Rusak Berat</label>
                                            </div>
                                        </div>
                                        @error('kondisi')
                                            <span class="text-danger text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
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
</main>
@endsection