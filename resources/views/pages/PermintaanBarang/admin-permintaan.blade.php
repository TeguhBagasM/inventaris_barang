@extends('index')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4 p-4">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between">
                            <h4 class="">Permintaan BHP</h4>
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

                        <form id="permintaanForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="user_id" class="form-label text-sm required-label">Pilih Peminta</label>
                                    <select class="form-select select2-users" id="user_id" name="user_id" required>
                                        <option value="">Pilih Peminta</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">
                                                {{ $user->name }} - {{ ucfirst($user->level) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="tanggal_minta" class="form-label text-sm required-label">Tanggal Permintaan</label>
                                    <input type="date" class="form-control" id="tanggal_minta" name="tanggal_minta" 
                                        value="{{ date('Y-m-d') }}" required readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="bhp_id" class="form-label text-sm required-label">BHP</label>
                                    <select class="form-select select2-bhp" id="bhp_id" name="bhp_id" required>
                                        <option value="">Pilih BHP</option>
                                        @foreach ($bhps as $bhp)
                                            <option value="{{ $bhp->id }}" 
                                                data-nama="{{ $bhp->nama }}"
                                                data-stok="{{ $bhp->stok }}">
                                                {{ $bhp->nama }} (Stok: {{ $bhp->stok }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="jumlah" class="form-label text-sm required-label">Jumlah</label>
                                    <input type="number" class="form-control" min="1" id="jumlah" name="jumlah" required>
                                </div>
                                <div class="col-md-4 align-self-end">
                                    <button type="button" id="tambahBhp" class="btn bg-gradient-success">
                                        <i class="fas fa-plus"></i> Tambah BHP
                                    </button>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-striped" id="bhpDimintaTable">
                                    <thead>
                                        <tr>
                                            <th>BHP</th>
                                            <th>Jumlah</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="bhpDimintaBody">
                                        <!-- BHP akan ditambahkan di sini -->
                                    </tbody>
                                </table>
                            </div>

                            <div class="mb-3">
                                <label for="keterangan" class="form-label text-sm required-label">Keterangan</label>
                                <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
                            </div>

                            <div class="col-12 mt-3">
                                <button type="button" id="submitPermintaan" class="btn bg-gradient-success w-100" disabled>
                                    Ajukan Permintaan
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
                        placeholder: 'Pilih Peminta',
                        allowClear: true,
                        width: '100%',
                        theme: 'bootstrap-5',
                        language: {
                            noResults: function() {
                                return "Data tidak ditemukan";
                            }
                        }
                    });
                    $('.select2-bhp').select2({
                        placeholder: 'Pilih BHP',
                        allowClear: true,
                        width: '100%',
                        theme: 'bootstrap-5',
                        language: {
                            noResults: function() {
                                return "Data tidak ditemukan";
                            }
                        }
                    });

            let bhpDiminta = [];

            $('#tambahBhp').click(function() {
                const bhpId = $('#bhp_id').val();
                const jumlah = parseInt($('#jumlah').val());
                const stokBhp = parseInt($('#bhp_id option:selected').data('stok'));
                const bhpNama = $('#bhp_id option:selected').data('nama');

                if (!bhpId || !jumlah || jumlah <= 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Pilih BHP dan masukkan jumlah yang valid.',
                    });
                    return;
                }

                if (jumlah > stokBhp) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: `Jumlah BHP "${bhpNama}" melebihi stok tersedia (${stokBhp}).`,
                    });
                    return;
                }

                const exists = bhpDiminta.find(b => b.bhp_id === bhpId);
                if (exists) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'BHP sudah ada di daftar permintaan.',
                    });
                    return;
                }

                bhpDiminta.push({ bhp_id: bhpId, jumlah: parseInt(jumlah) });
                updateBhpTable();
                
                $('#bhp_id').val('').trigger('change');
                $('#jumlah').val('');
                $('#submitPermintaan').prop('disabled', false);
            });

            $(document).on('click', '.hapus-bhp', function() {
                const bhpId = $(this).data('id');
                bhpDiminta = bhpDiminta.filter(b => b.bhp_id != bhpId);
                updateBhpTable();

                if (bhpDiminta.length === 0) {
                    $('#submitPermintaan').prop('disabled', true);
                }
            });

            function updateBhpTable() {
                const tableBody = $('#bhpDimintaBody');
                tableBody.empty();
                bhpDiminta.forEach(bhp => {
                    tableBody.append(`
                        <tr>
                            <td>${$('#bhp_id option[value="' + bhp.bhp_id + '"]').data('nama')}</td>
                            <td>${bhp.jumlah}</td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm hapus-bhp" data-id="${bhp.bhp_id}">
                                    <i class="fas fa-trash" style="font-size: 14px;"></i>
                                </button>
                            </td>
                        </tr>
                    `);
                });
            }

            $('#submitPermintaan').click(function() {
                $.ajax({
                    url: '{{ route("admin-minta") }}',
                    method: 'POST',
                    data: {
                        _token: $('input[name="_token"]').val(),
                        user_id: $('#user_id').val(),
                        tanggal_minta: $('#tanggal_minta').val(),
                        keterangan: $('#keterangan').val(),
                        bhps: bhpDiminta
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '{{ route("log.permintaan") }}';
                            }
                        });
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errorMessage = xhr.responseJSON.message;
                            let detailedErrors = '';
                            
                            if (xhr.responseJSON.errors) {
                                if (Array.isArray(xhr.responseJSON.errors)) {
                                    detailedErrors = xhr.responseJSON.errors.join('\n');
                                } else {
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
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: xhr.responseJSON.message || 'Terjadi kesalahan saat memproses permintaan'
                            });
                        }
                    }
                });
            });
        });
    </script>
    @endpush
@endsection