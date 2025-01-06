@extends('index')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h4 class="">Tambah Tugas</h4>
                        <hr class="bg-dark px-auto">
                    </div>
                    <div class="card-body px-0 pt-0 pb-2 ps-4 me-4">
                        <form action="{{ route('todolist.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="judul" class="form-label text-sm required-label">Judul</label>
                                <input type="text" class="form-control @error('judul') is-invalid @enderror" id="judul" name="judul" value="{{ old('judul') }}">
                                @error('judul')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="deskripsi" class="form-label text-sm required-label">deskripsi</label>
                                <input type="text" class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" value="{{ old('deskripsi') }}">
                                @error('deskripsi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="prioritas" class="form-label text-sm required-label">Prioritas</label>
                                <select class="form-select @error('prioritas') is-invalid @enderror" id="prioritas" name="prioritas">
                                    <option value="" disabled selected>Pilih Prioritas</option>
                                    <option value="Tinggi">Tinggi</option>
                                <option value="Rendah">Rendah</option>
                                </select>
                                @error('prioritas')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <a href="{{ route('todolist.index') }}" class="btn bg-gradient-danger">Kembali</a>
                            <button type="submit" class="btn bg-gradient-success float-end">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
