@extends('index')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h4 class="">Edit Data Barang Habis Pakai</h4>
                        <hr class="bg-dark px-auto">
                    </div>
                    <div class="card-body px-0 pt-0 pb-2 ps-4 me-4">
                        <form action="{{ route('bhp.update', $bhp->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="nama" class="form-label text-sm required-label">Nama Barang</label>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama', $bhp->nama) }}">
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="spesifikasi" class="form-label text-sm required-label">Spesifikasi</label>
                                <input type="text" class="form-control @error('spesifikasi') is-invalid @enderror" id="spesifikasi" name="spesifikasi" value="{{ old('spesifikasi', $bhp->spesifikasi) }}">
                                @error('spesifikasi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="tahun_pengadaan" class="form-label text-sm required-label">Tahun Pengadaan</label>
                                <input type="number" class="form-control @error('tahun_pengadaan') is-invalid @enderror" name="tahun_pengadaan" id="tahun_pengadaan" value="{{ old('tahun_pengadaan', $bhp->tahun_pengadaan) }}">
                                @error('tahun_pengadaan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="sumber_dana" class="form-label text-sm required-label">Sumber Dana</label>
                                <input type="text" class="form-control @error('sumber_dana') is-invalid @enderror" id="sumber_dana" name="sumber_dana" value="{{ old('sumber_dana', $bhp->sumber_dana) }}">
                                @error('sumber_dana')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="stok" class="form-label text-sm required-label">Stok</label>
                                <input type="number" class="form-control @error('stok') is-invalid @enderror" id="stok" name="stok" value="{{ old('stok', $bhp->stok) }}">
                                @error('stok')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <a href="{{ route('bhp.index') }}" class="btn bg-gradient-danger">Kembali</a>
                            <button type="submit" class="btn bg-gradient-success float-end">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection