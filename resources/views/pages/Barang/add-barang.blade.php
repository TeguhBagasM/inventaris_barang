@extends('index')

@section('content')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <h4 class="">Tambah Produk</h4>
                            <hr style="background-color: black">
                            @if (Session::has('status'))
                                <div class="alert alert-success text-white opacity-5" role="alert">
                                    {{ Session::get('message') }}
                                </div>
                            @endif
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
                                    <label for="jumlah" class="form-label text-sm required-label">Jumlah</label>
                                    <input type="number" class="form-control" name="jumlah" id="jumlah" required>
                                </div>
                                <div class="mb-3">
                                    <label for="ruang_id" class="form-label text-sm">Ruang</label>
                                    <select class="form-select" name="ruang_id" id="ruang_id" required>
                                        <option value="">Pilih Ruang...</option>
                                        @foreach ($ruang as $k)
                                            <option value="{{ $k->id }}">{{ $k->nama_ruang }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="kategori_id" class="form-label text-sm">Kategori</label>
                                    <select class="form-select" name="kategori_id" id="kategori_id">
                                        <option value="" disabled selected>Pilih Kategori</option>
                                        @foreach ($kategori as $k)
                                            <option value="{{ $k->id }}">{{ $k->nama }}</option>
                                        @endforeach
                                    </select>
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
                                <a href="{{ url()->previous() }}" class="btn bg-gradient-danger ">Back</a>
                                <button type="submit" class="btn btn-success float-end">Submit</button>
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
                document.getElementById('imagePreview').style.display = 'block'; // Tampilkan image preview
            } else {
                preview.src = "#";
                document.getElementById('imagePreview').style.display = 'none'; // Sembunyikan image preview
            }
        }
    </script>
@endsection
