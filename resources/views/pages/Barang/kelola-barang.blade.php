@extends('index')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="">Pendataan Barang</h4>
                            </div>
                        </div>

                        <hr class="bg-dark px-auto">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('barang.create') }}">
                                <div class="mt-2 text-white btn bg-gradient-success">Tambah Barang</div>
                            </a>
                            <a href="{{ route('barang.cetak') }}" class="mt-2 text-white btn bg-gradient-success" target="_blank">Cetak</a>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0" id="barangTable">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-dark text-sm font-weight-bolder">No</th>
                                        <th class="text-uppercase text-dark text-sm font-weight-bolder">Nama Barang</th>
                                        <th class="text-uppercase text-dark text-sm font-weight-bolder">Merk</th>
                                        <th class="text-uppercase text-dark text-sm font-weight-bolder">Stok</th>
                                        <th class="text-uppercase text-dark text-sm font-weight-bolder">Aksi</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#barangTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('barang.data') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'nama', name: 'nama' },
                    { data: 'merk', name: 'merk' },
                    { data: 'stok', name: 'stok' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json'
                }
            });

            // Show SweetAlert for flash messages
            @if(Session::has('status'))
                Swal.fire({
                    icon: '{{ Session::get("status") }}',
                    title: '{{ Session::get("status") == "success" ? "Berhasil!" : "Oops..." }}',
                    text: '{{ Session::get("message") }}',
                    showConfirmButton: false,
                    timer: 3000
                });
            @endif
        });

        function deleteBarang(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Barang yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/barang/${id}`,
                        type: 'DELETE',
                        data: {
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if(response.success) {
                                // Refresh DataTable
                                $('#barangTable').DataTable().ajax.reload();
                                
                                // Tampilkan pesan sukses dengan SweetAlert
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: response.message,
                                    showConfirmButton: false,
                                    timer: 3000
                                });
                            }
                        },
                        error: function(xhr) {
                            let message = 'Gagal menghapus barang';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                message = xhr.responseJSON.message;
                            }
                            
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: message,
                                showConfirmButton: false,
                                timer: 3000
                            });
                        }
                    });
                }
            });
        }
    </script>
    @endpush
@endsection