@extends('index')
@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-info text-white">
                    <h4 class="mb-0">Konfirmasi Penolakan Permintaan</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('permintaan.tolak', $permintaan->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Alasan Penolakan</label>
                            <textarea 
                                name="keterangan" 
                                id="keterangan" 
                                class="form-control @error('keterangan') is-invalid @enderror" 
                                rows="4" 
                                placeholder="Masukkan alasan penolakan permintaan"
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
                            Anda akan menolak permintaan dengan detail:
                            <ul class="mb-0 mt-2">
                                <li>Tanggal permintaan: {{ \Carbon\Carbon::parse($permintaan->tanggal_permintaan)->translatedFormat('l, d F Y') }}</li>
                                <li>Total Barang: {{ $permintaan->detailpermintaans->sum('jumlah') }} Barang</li>
                            </ul>
                        </div> --}}

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('log.permintaan') }}" class="btn btn-danger">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                            <button type="submit" class="btn bg-gradient-success">
                                <i class="fas fa-times-circle me-2"></i>Tolak permintaan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection