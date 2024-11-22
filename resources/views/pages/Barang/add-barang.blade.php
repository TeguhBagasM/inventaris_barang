@extends('index')

@section('content')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <h4 class="">Tambah Barang</h4>
                            <hr style="background-color: black">
                        </div>

                        <div class="card-body px-0 pt-0 pb-2 ps-4 me-4">
                            <form id="myForm" action="{{ route('barang.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="nama" class="form-label text-sm required-label">Nama Barang</label>
                                    <input type="text" class="form-control" name="nama" id="nama" required>
                                </div>
                                <div class="mb-3">
                                    <label for="merk" class="form-label text-sm required-label">Merk</label>
                                    <input type="text" class="form-control" name="merk" id="merk" required>
                                </div>
                                <div class="mb-3">
                                    <label for="spesifikasi" class="form-label text-sm required-label">Spesifikasi</label>
                                    <textarea class="form-control" name="spesifikasi" id="spesifikasi" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="serial_number" class="form-label text-sm required-label">Serial Number</label>
                                    <input type="text" class="form-control" name="serial_number" id="serial_number" required>
                                </div>
                                <div class="mb-3">
                                    <label for="stok" class="form-label text-sm required-label">Stok</label>
                                    <input type="number" class="form-control" name="stok" id="stok" required>
                                </div>
                                <div class="mb-3">
                                    <label for="tahun_pengadaan" class="form-label text-sm required-label">Tahun Pengadaan</label>
                                    <input type="number" class="form-control" name="tahun_pengadaan" id="tahun_pengadaan" 
                                           min="1900" max="{{ date('Y') }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="sumber_dana" class="form-label text-sm required-label">Sumber Dana</label>
                                    <input type="text" class="form-control" name="sumber_dana" id="sumber_dana" required>
                                </div>
                                <div class="mb-3">
                                    <label for="ruang_id" class="form-label text-sm required-label">Ruang</label>
                                    <select class="form-select" name="ruang_id" id="ruang_id" required>
                                        <option value="">Pilih Ruang...</option>
                                        @foreach ($ruang as $k)
                                            <option value="{{ $k->id }}">{{ $k->nama_ruang }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="kategori_id" class="form-label text-sm required-label">Kategori</label>
                                    <select class="form-select" name="kategori_id" id="kategori_id" required>
                                        <option value="">Pilih Kategori</option>
                                        @foreach ($kategori as $k)
                                            <option value="{{ $k->id }}">{{ $k->nama }}</option>
                                        @endforeach
                                    </select>
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
                                <div class="mb-3">
                                    <label for="image" class="form-label text-sm required-label">Foto</label>
                                    <input type="file" class="form-control" name="image" id="image"
                                        accept="image/*" onchange="previewImage(event)" required>
                                </div>
                                <div id="imagePreview" class="mb-3" style="display: none;">
                                    <img src="#" class="rounded" alt="Preview"
                                        style="max-width: 100%; max-height: 200px;">
                                </div>
                                <a href="{{ url()->previous() }}" class="btn bg-gradient-danger">Kembali</a>
                                <button type="submit" class="btn btn-success float-end">Simpan</button>
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