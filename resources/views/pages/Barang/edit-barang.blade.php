@extends('index')

@section('content')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <h4 class="">Edit Produk</h4>
                            <hr style="background-color: black">
                            
                        </div>

                        <div class="card-body px-0 pt-0 pb-2 ps-4 me-4">
                            <form action="{{ route('barang.update', $barang->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label for="nama" class="form-label text-sm required-label">Nama Barang</label>
                                    <input type="text" class="form-control" name="nama" id="nama"
                                        value="{{ $barang->nama }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="jumlah" class="form-label text-sm required-label">Jumlah</label>
                                    <input type="number" class="form-control" name="jumlah" id="jumlah"
                                        value="{{ $barang->jumlah }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="ruang_id" class="form-label text-sm">Ruang</label>
                                    <select class="form-select" name="ruang_id" id="ruang_id">
                                        @foreach ($ruang as $item)
                                            <option value="{{ $item->id }}"
                                                @if ($item->id === $barang->ruang_id) selected @endif>{{ $item->nama_ruang }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="kategori_id" class="form-label text-sm">Kategori</label>
                                    <select class="form-select" name="kategori_id" id="kategori_id">
                                        <option value="" disabled selected>Pilih Kategori</option>
                                        @foreach ($kategori as $k)
                                            <option value="{{ $k->id }}" 
                                                @if($barang->kategori_id == $k->id) selected @endif>
                                                {{ $k->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="image" class="form-label text-sm">Gambar</label>
                                    <input type="file" class="form-control" name="image" id="image"
                                        accept="image/*" onchange="previewImage(event)">
                                </div>
                                <div id="imagePreview" class="mb-3">
                                    <img src="{{ asset($barang->gambar) }}" class="rounded" alt="Preview"
                                        style="max-width: 100%; max-height: 200px;">
                                </div>
                                <a href="{{ url()->previous() }}" class="btn bg-gradient-danger">Back</a>
                                <button type="submit" class="btn btn-success float-end">Simpan Perubahan</button>
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
            }
        }

        // Tampilkan preview gambar saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            const preview = document.querySelector('#imagePreview img');
            if (preview.src) {
                document.getElementById('imagePreview').style.display = 'block';
            } else {
                document.getElementById('imagePreview').style.display = 'none';
            }
        });
    </script>
@endsection