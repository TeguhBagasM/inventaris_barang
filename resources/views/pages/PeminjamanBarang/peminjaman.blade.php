@extends('index')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4 p-4">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between">
                            <h4 class="">Peminjaman Barang</h4>
                        </div>
                        <hr class="bg-dark px-auto">
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger" role="alert">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if (Session::has('status'))
                            <div class="alert alert-success" role="alert">
                                {{ Session::get('message') }}
                            </div>
                        @endif

                        <form id="peminjamanForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="user_id" class="form-label">Nama</label>
                                    <input type="text" class="form-control" id="user_id" 
                                        value="{{ auth()->user()->name }}" readonly>
                                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="tanggal_peminjaman" class="form-label">Tanggal Peminjaman</label>
                                    <input type="date" class="form-control" id="tanggal_peminjaman" name="tanggal_peminjaman" 
                                        value="{{ date('Y-m-d') }}" required readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="barang_id" class="form-label">Barang</label>
                                    <select class="form-select select2" id="barang_id" name="barang_id" required>
                                        <option value="">Pilih Barang</option>
                                        @foreach ($barangs as $barang)
                                            <option value="{{ $barang->id }}" data-nama="{{ $barang->nama }}">
                                                {{ $barang->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="jumlah" class="form-label">Jumlah</label>
                                    <input type="number" class="form-control" min="1" id="jumlah" name="jumlah" required>
                                </div>
                                <div class="col-md-4 mb-3 align-self-end">
                                    <button type="button" id="tambahBarang" class="btn btn-success">
                                        <i class="fas fa-plus"></i> Tambah Barang
                                    </button>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-striped" id="barangDipinjamTable">
                                    <thead>
                                        <tr>
                                            <th>Barang</th>
                                            <th>Jumlah</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="barangDipinjamBody">
                                        <!-- Barang akan ditambahkan di sini -->
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-12 mt-3">
                                <button type="button" id="submitPeminjaman" class="btn btn-primary w-100" disabled>
                                    Submit Peminjaman
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: 'Pilih Barang',
                allowClear: true,
                width: '100%',
                theme: 'bootstrap-5'
            });

            let barangDipinjam = [];

            $('#tambahBarang').click(function() {
                const barangId = $('#barang_id').val();
                const jumlah = $('#jumlah').val();
                const barangNama = $('#barang_id option:selected').data('nama');

                if (!barangId || !jumlah || jumlah <= 0) {
                    alert('Pilih barang dan masukkan jumlah yang valid.');
                    return;
                }

                const exists = barangDipinjam.find(b => b.barang_id === barangId);
                if (exists) {
                    alert('Barang sudah ada di daftar peminjaman.');
                    return;
                }

                barangDipinjam.push({ barang_id: barangId, jumlah: parseInt(jumlah) });

                updateBarangTable();

                $('#barang_id').val('').trigger('change');
                $('#jumlah').val('');
                $('#submitPeminjaman').prop('disabled', false);
            });

            $(document).on('click', '.hapus-barang', function() {
                const barangId = $(this).data('id');
                barangDipinjam = barangDipinjam.filter(b => b.barang_id != barangId);
                updateBarangTable();

                if (barangDipinjam.length === 0) {
                    $('#submitPeminjaman').prop('disabled', true);
                }
            });

            function updateBarangTable() {
                const tableBody = $('#barangDipinjamBody');
                tableBody.empty();
                barangDipinjam.forEach(barang => {
                    tableBody.append(`
                        <tr>
                            <td>${$('#barang_id option[value="' + barang.barang_id + '"]').data('nama')}</td>
                            <td>${barang.jumlah}</td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm hapus-barang" data-id="${barang.barang_id}">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </td>
                        </tr>
                    `);
                });
            }

            $('#submitPeminjaman').click(function() {
                $.ajax({
                    url: '{{ route("pinjam") }}',
                    method: 'POST',
                    data: {
                        _token: $('input[name="_token"]').val(),
                        user_id: $('input[name="user_id"]').val(),
                        tanggal_peminjaman: $('#tanggal_peminjaman').val(),
                        barangs: barangDipinjam
                    },
                    success: function(response) {
                        alert(response.message);
                        location.reload();
                    },
                    error: function(xhr) {
                        alert('Gagal: ' + xhr.responseJSON.message);
                    }
                });
            });
        });
    </script>
    @endpush
@endsection
