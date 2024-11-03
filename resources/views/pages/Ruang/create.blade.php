@extends('index')

@section('content')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <h4 class="">Tambah Ruang</h4>
                            <hr style="background-color: black">
                            @if (Session::has('success'))
                                <div class="alert alert-success text-white opacity-5" role="alert">
                                    {{ Session::get('success') }}
                                </div>
                            @endif
                        </div>

                        <div class="card-body px-0 pt-0 pb-2 ps-4 me-4">
                            <form action="{{ route('ruang.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="nama_ruang" class="form-label text-sm required-label">Nama Ruang</label>
                                    <input type="text" class="form-control @error('nama_ruang') is-invalid @enderror" 
                                           name="nama_ruang" id="nama_ruang" value="{{ old('nama_ruang') }}" required>
                                    @error('nama_ruang')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="gedung_id" class="form-label text-sm required-label">Gedung</label>
                                    <select class="form-select @error('gedung_id') is-invalid @enderror" 
                                            name="gedung_id" id="gedung_id" required>
                                        <option value="">Pilih Gedung...</option>
                                        @foreach ($gedungs as $gedung)
                                            <option value="{{ $gedung->id }}" 
                                                {{ old('gedung_id') == $gedung->id ? 'selected' : '' }}>
                                                {{ $gedung->nama_gedung }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('gedung_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="ukuran" class="form-label text-sm required-label">Ukuran (m²)</label>
                                    <input type="number" step="0.01" class="form-control @error('ukuran') is-invalid @enderror" 
                                           name="ukuran" id="ukuran" value="{{ old('ukuran') }}" required>
                                    @error('ukuran')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="kondisi" class="form-label text-sm required-label">Kondisi</label>
                                    <select class="form-select @error('kondisi') is-invalid @enderror" 
                                            name="kondisi" id="kondisi" required>
                                        <option value="">Pilih Kondisi...</option>
                                        <option value="Baik" {{ old('kondisi') == 'Baik' ? 'selected' : '' }}>Baik</option>
                                        <option value="Rusak Ringan" {{ old('kondisi') == 'Rusak Ringan' ? 'selected' : '' }}>
                                            Rusak Ringan</option>
                                        <option value="Rusak Berat" {{ old('kondisi') == 'Rusak Berat' ? 'selected' : '' }}>
                                            Rusak Berat</option>
                                    </select>
                                    @error('kondisi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- <div class="mb-3">
                                    <label for="kondisi" class="form-label text-sm required-label">Kondisi</label>
                                    <input type="text" class="form-control @error('kondisi') is-invalid @enderror" 
                                           name="kondisi" id="kondisi" value="{{ old('kondisi') }}" required>
                                    @error('kondisi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div> --}}
                                <div class="mb-3">
                                    <label for="peruntukkan" class="form-label text-sm required-label">Peruntukkan</label>
                                    <input type="text" class="form-control @error('peruntukkan') is-invalid @enderror" 
                                           name="peruntukkan" id="peruntukkan" value="{{ old('peruntukkan') }}" required>
                                    @error('peruntukkan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="keterangan" class="form-label text-sm">Keterangan</label>
                                    <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                                              name="keterangan" id="keterangan" rows="3">{{ old('keterangan') }}</textarea>
                                    @error('keterangan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <a href="{{ route('ruang.index') }}" class="btn bg-gradient-danger">Kembali</a>
                                <button type="submit" class="btn bg-gradient-success float-end">Simpan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection