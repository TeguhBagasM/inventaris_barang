@extends('index')

@section('content')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <h4 class="">Tambah Gedung</h4>
                            <hr style="background-color: black">
                            @if (Session::has('success'))
                                <div class="alert alert-success text-white opacity-5" role="alert">
                                    {{ Session::get('success') }}
                                </div>
                            @endif
                        </div>

                        <div class="card-body px-0 pt-0 pb-2 ps-4 me-4">
                            <form action="{{ route('gedung.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="nama_gedung" class="form-label text-sm required-label">Nama Gedung</label>
                                    <input type="text" class="form-control @error('nama_gedung') is-invalid @enderror" 
                                           name="nama_gedung" id="nama_gedung" value="{{ old('nama_gedung') }}" required>
                                    @error('nama_gedung')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="luas_gedung" class="form-label text-sm required-label">Luas Gedung (m²)</label>
                                    <input type="number" step="0.01" class="form-control @error('luas_gedung') is-invalid @enderror" 
                                           name="luas_gedung" id="luas_gedung" value="{{ old('luas_gedung') }}" required>
                                    @error('luas_gedung')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="tahun_perolehan" class="form-label text-sm required-label">Tahun Perolehan</label>
                                    <input type="number" class="form-control @error('tahun_perolehan') is-invalid @enderror" 
                                           name="tahun_perolehan" id="tahun_perolehan" min="1950" max="{{ date('Y') }}" 
                                           value="{{ old('tahun_perolehan') }}" required>
                                    @error('tahun_perolehan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="nilai_bangunan" class="form-label text-sm required-label">Nilai Bangunan</label>
                                    <input type="number" class="form-control @error('nilai_bangunan') is-invalid @enderror" 
                                           name="nilai_bangunan" id="nilai_bangunan" value="{{ old('nilai_bangunan') }}" required>
                                    @error('nilai_bangunan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="peruntukkan" class="form-label text-sm required-label">Peruntukkan</label>
                                    <input type="text" class="form-control @error('peruntukkan') is-invalid @enderror" 
                                           name="peruntukkan" id="peruntukkan" value="{{ old('peruntukkan') }}" required>
                                    @error('peruntukkan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="gambar" class="form-label text-sm">Foto Gedung</label>
                                    <input type="file" class="form-control @error('gambar') is-invalid @enderror" 
                                           name="gambar" id="gambar" accept="image/*" onchange="previewImage(event)">
                                    @error('gambar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div id="imagePreview" class="mb-3" style="display: none;">
                                    <img src="#" class="rounded" alt="Preview" style="max-width: 100%; max-height: 200px;">
                                </div>

                                <div class="mb-3">
                                    <label for="keterangan" class="form-label text-sm">Keterangan</label>
                                    <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                                              name="keterangan" id="keterangan" rows="3">{{ old('keterangan') }}</textarea>
                                    @error('keterangan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <a href="{{ route('gedung.index') }}" class="btn bg-gradient-danger">Kembali</a>
                                <button type="submit" class="btn bg-gradient-success float-end">Simpan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        function previewImage(event) {
            const preview = document.querySelector('#imagePreview img');
            const file = event.target.files[0];
            const reader = new FileReader();

            reader.onloadend = function() {
                preview.src = reader.result;
            }

            if (file) {
                reader.readAsDataURL(file);
                document.getElementById('imagePreview').style.display = 'block';
            } else {
                preview.src = "#";
                document.getElementById('imagePreview').style.display = 'none';
            }
        }
    </script>
@endsection