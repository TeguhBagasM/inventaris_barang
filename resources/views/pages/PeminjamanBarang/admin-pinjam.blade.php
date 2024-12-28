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
                                        <label for="tanggal_peminjaman" class="form-label">Tanggal Peminjaman</label>
                                        <input type="date" class="form-control" id="tanggal_peminjaman" name="tanggal_peminjaman"
                                            value="{{ date('Y-m-d') }}" required readonly>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="barang_id" class="form-label">Barang</label>
                                            <select class="form-select select2-barang" id="barang_id" name="barang_id" required>
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
                                            <input type="number" class="form-control" min="1" id="jumlah" max="{{ $barang->stok }}" name="jumlah" required>
                                        </div>
                                        <div class="col-md-4 align-self-end">
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
        
                    let barangDipinjam = [];
        
                    $('#tambahBarang').click(function() {
                        const barangId = $('#barang_id').val();
                        const jumlah = parseInt($('#jumlah').val());
                        const stokBarang = parseInt($('#barang_id option:selected').data('stok'));
                        const barangNama = $('#barang_id option:selected').data('nama');

        
                        if (!barangId || !jumlah || jumlah <= 0) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Pilih barang dan masukkan jumlah yang valid.',
                            });
                            return;
                        }
                        if (jumlah > stokBarang) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: `Jumlah barang "${barangNama}" melebihi stok tersedia (${stokBarang}).`,
                            });
                            return;
                        }                               
        
                        const exists = barangDipinjam.find(b => b.barang_id === barangId);
                        if (exists) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Barang sudah ada di daftar peminjaman.',
                            });
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
                                            <i class="fas fa-trash" style="font-size: 14px;"></i> Hapus
                                        </button>
                                    </td>
                                </tr>
                            `);
                        });
                    }
        
                    $('#submitPeminjaman').click(function() {
                        $.ajax({
                            url: '{{ route("admin-pinjam") }}',
                            method: 'POST',
                            data: {
                                _token: $('input[name="_token"]').val(),
                                user_id: $('#user_id').val(),
                                tanggal_peminjaman: $('#tanggal_peminjaman').val(),
                                barangs: barangDipinjam
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: response.message,
                                });
                                location.reload();
                            },
                            error: function(xhr) {
                                // Handle validation errors
                                if (xhr.status === 422) {
                                    let errorMessage = xhr.responseJSON.message;
                                    let detailedErrors = '';
                                    
                                    // Jika ada errors spesifik
                                    if (xhr.responseJSON.errors) {
                                        if (Array.isArray(xhr.responseJSON.errors)) {
                                            // Error stok barang
                                            detailedErrors = xhr.responseJSON.errors.join('\n');
                                        } else {
                                            // Error validasi field
                                            detailedErrors = Object.values(xhr.responseJSON.errors).flat().join('\n');
                                        }
                                    }

                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal!',
                                        text: errorMessage,
                                        html: detailedErrors ? `<div class="text-left">${detailedErrors.replace(/\n/g, '<br>')}</div>` : ''
                                    });
                                } else {
                                    // Error umum
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal!',
                                        text: xhr.responseJSON.message || 'Terjadi kesalahan saat memproses peminjaman'
                                    });
                                }
                            }
                        });
                    });
                });
            </script>
        @endpush        
@endsection