@extends('index')
@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-info text-white">
                    <h4 class="mb-0">Konfirmasi Penolakan Peminjaman</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('peminjaman.tolak', $peminjaman->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Alasan Penolakan</label>
                            <textarea 
                                name="keterangan" 
                                id="keterangan" 
                                class="form-control @error('keterangan') is-invalid @enderror" 
                                rows="4" 
                                placeholder="Masukkan alasan penolakan peminjaman"
                                required
                            >{{ old('keterangan') }}</textarea>
                            
                            @error('keterangan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- <div class="alert alert-warning" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Anda akan menolak peminjaman dengan detail:
                            <ul class="mb-0 mt-2">
                                <li>Tanggal Peminjaman: {{ \Carbon\Carbon::parse($peminjaman->tanggal_peminjaman)->translatedFormat('l, d F Y') }}</li>
                                <li>Total Barang: {{ $peminjaman->detailPeminjamans->sum('jumlah') }} Barang</li>
                            </ul>
                        </div> --}}

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('log.peminjaman') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-times-circle me-2"></i>Tolak Peminjaman
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection