@extends('index')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4 p-4">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="">Peminjaman Barang</h4>
                            </div>
                        </div>
                        <hr class="bg-dark px-auto">
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger text-white opacity-5" role="alert">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if (Session::has('status'))
                            <div class="alert alert-success text-white opacity-5" role="alert">
                                {{ Session::get('message') }}
                            </div>
                        @endif
                        <div class="p-0">
                            <form action="/admin-pinjam" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="user_id" class="form-label">Pilih Peminjam</label>
                                        <select class="form-select select2-users" id="user_id" name="user_id" required>
                                            <option value="">Pilih Peminjam</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}">
                                                    {{ $user->name }} - {{ ucfirst($user->level) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="keluar" class="form-label">Tanggal Peminjaman</label>
                                        <input type="date" class="form-control" id="keluar" name="keluar"
                                            value="{{ date('Y-m-d') }}" required readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="jumlah" class="form-label">Jumlah</label>
                                        <input type="number" class="form-control" min="1" id="jumlah"
                                            name="jumlah" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="barang_id" class="form-label">Barang</label>
                                        <select class="form-select select2-barang" id="barang_id" name="barang_id" required>
                                            <option value="">Pilih Barang</option>
                                            @foreach ($barangs as $barang)
                                                <option value="{{ $barang->id }}">
                                                    {{ $barang->nama }} (Stok: {{ $barang->stok }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary w-100">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            // Inisialisasi Select2 untuk pemilihan user
            $('.select2-users').select2({
                placeholder: 'Pilih Peminjam',
                allowClear: true,
                width: '100%',
                theme: 'bootstrap-5',
                language: {
                    noResults: function() {
                        return "Data tidak ditemukan";
                    }
                }
            });

            // Inisialisasi Select2 untuk pemilihan barang
            $('.select2-barang').select2({
                placeholder: 'Pilih Barang',
                allowClear: true,
                width: '100%',
                theme: 'bootstrap-5',
                language: {
                    noResults: function() {
                        return "Data tidak ditemukan";
                    }
                }
            });

            // Optional: Tambahkan event listener untuk memperbarui max jumlah berdasarkan stok
            $('#barang_id').on('change', function() {
                var selectedOption = $(this).find('option:selected');
                var stok = selectedOption.text().match(/Stok: (\d+)/);
                if(stok) {
                    $('#jumlah').attr('max', stok[1]);
                }
            });
        });
    </script>
    @endpush
@endsection