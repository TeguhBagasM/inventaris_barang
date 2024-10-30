@extends('index')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="">Lokasi</h4>
                            </div>
                        </div>

                        <hr class="bg-dark px-auto">
                        <div id="alert-container"></div>
                        @if (Session::has('status'))
                            <div class="alert alert-{{ Session::get('status') }} text-white" role="alert">
                                {{ Session::get('message') }}
                            </div>
                        @endif
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('lokasi.create') }}">
                                <div class="mt-2 text-white btn bg-gradient-success">Tambah Lokasi</div>
                            </a>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0" id="lokasiTable">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-dark text-sm font-weight-bolder">No</th>
                                        <th class="text-uppercase text-dark text-sm font-weight-bolder">Nama</th>
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
            $('#lokasiTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('lokasi.data') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'nama', name: 'nama' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json'
                }
            });
        });

        function deleteLocation(id) {
            if(confirm('Apakah Anda yakin ingin menghapus lokasi ini?')) {
                $.ajax({
                    url: `/lokasi/${id}`,
                    type: 'DELETE',
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if(response.success) {
                            // Refresh DataTable
                            $('#lokasiTable').DataTable().ajax.reload();
                            
                            // Tampilkan pesan sukses
                            $('#alert-container').html(`
                                <div class="alert alert-success text-white opacity-5" role="alert">
                                    ${response.message}
                                </div>
                            `);
                            
                            // Hilangkan pesan setelah 3 detik
                            setTimeout(() => {
                                $('#alert-container .alert').fadeOut();
                            }, 5000);
                        }
                    },
                    error: function(xhr) {
                        let message = 'Gagal menghapus lokasi';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        
                        $('#alert-container').html(`
                            <div class="alert alert-danger text-white opacity-5" role="alert">
                                ${message}
                            </div>
                        `);
                        
                        setTimeout(() => {
                            $('#alert-container .alert').fadeOut();
                        }, 3000);
                    }
                });
            }
        }
    </script>
    @endpush
@endsection