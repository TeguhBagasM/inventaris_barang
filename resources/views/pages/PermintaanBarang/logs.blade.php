@extends('index')
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="row">
                        <div class="col-12 col-md-6 order-1 order-md-1">
                            <h4>Logs Permintaan BHP</h4>
                        </div>

                        <div class="col-12 col-md-6 d-flex flex-column flex-md-row justify-content-md-end align-items-start align-items-md-center order-2 order-md-2 mt-3 mt-md-0">
                            <a href="{{ route('admin.minta') }}" class="btn bg-gradient-success text-white mb-2 mb-md-0 me-md-2">
                                <i class="fas fa-plus me-2"></i> Permintaan Baru
                            </a>
                            <a href="{{ route('permintaan.cetak') }}" class="btn bg-info text-white mb-2 mb-md-0 me-md-2" target="_blank">
                                <i class="fas fa-print me-2"></i> Cetak
                            </a>
                        </div>
                    </div>
                    <hr class="bg-dark px-auto">
                </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        @if($logs->isEmpty())
                            <form action="{{ route('log.permintaan') }}" method="GET" class="row mb-4 ms-4">
                                <div class="col-md-4">
                                    <label>Tanggal Permintaan</label>
                                    <input type="date" name="tanggal_minta" class="form-control" 
                                           value="{{ request('tanggal_minta') }}">
                                </div>
                                <div class="col-md-4 align-self-end mt-4">
                                    <button type="submit" class="btn bg-gradient-success me-2">Filter</button>
                                    <a href="{{ route('log.permintaan') }}" class="btn bg-gradient-orange text-white">Reset</a>
                                </div>
                            </form>
                            <div class="text-center py-5">
                                <div class="mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" class="bi bi-inbox text-secondary" viewBox="0 0 16 16">
                                        <path d="M4.98 4a.5.5 0 0 0-.39.188L1.54 8H6a.5.5 0 0 1 .5.5 1.5 1.5 0 1 0 3 0A.5.5 0 0 1 10 8h4.46l-3.05-3.812A.5.5 0 0 0 11.02 4H4.98zm9.954 5H10.45a2.5 2.5 0 0 1-4.9 0H1.066l.32 2.562a.5.5 0 0 0 .497.438h12.234a.5.5 0 0 0 .496-.438L14.933 9zM3.809 3.563A1.5 1.5 0 0 1 4.981 3h6.038a1.5 1.5 0 0 1 1.172.563l3.7 4.625a.5.5 0 0 1 .105.374l-.39 3.124A1.5 1.5 0 0 1 14.117 13H1.883a1.5 1.5 0 0 1-1.489-1.314l-.39-3.124a.5.5 0 0 1 .106-.374l3.7-4.625z"/>
                                    </svg>
                                </div>
                                <h6 class="text-secondary">Belum ada BHP yang diminta</h6>
                                <p class="text-muted">Data log permintaan BHP akan muncul di sini</p>
                            </div>
                        @else
                            <form action="{{ route('log.permintaan') }}" method="GET" class="row mb-4 ms-4">
                                <div class="col-md-4">
                                    <label>Tanggal Permintaan</label>
                                    <input type="date" name="tanggal_minta" class="form-control" 
                                           value="{{ request('tanggal_minta') }}">
                                </div>
                                <div class="col-md-4 align-self-end mt-4">
                                    <button type="submit" class="btn bg-gradient-success me-2">Filter</button>
                                    <a href="{{ route('log.permintaan') }}" class="btn bg-gradient-orange text-white">Reset</a>
                                </div>
                            </form>
                            <div class="table-responsive p-0">
                                <table class="table table-striped align-items-center mb-0" id="datatables">
                                    <thead>
                                        <tr class="text-center">
                                            <th>No</th>
                                            <th>Peminta</th>
                                            <th>Jumlah Item</th>
                                            <th>Tanggal Permintaan</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($logs as $log)
                                            <tr class="text-center">
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $log->user->name }}</td>
                                                <td>{{ $log->total_jumlah }}</td>                                        
                                                <td>{{ \Carbon\Carbon::parse($log->tanggal_minta)->translatedFormat('l, d M Y') }}</td>
                                                <td>
                                                    @if ($log->status == 'diajukan')
                                                        <span class="badge bg-secondary">Menunggu Konfirmasi</span>
                                                    @elseif ($log->status == 'disetujui')
                                                        <span class="badge bg-gradient-success">Disetujui</span>
                                                    @else
                                                        <span class="badge bg-gradient-danger">Ditolak</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($log->status == 'diajukan')
                                                        <a href="#" onclick="konfirmasiPermintaan({{ $log->id }})"
                                                            class="btn bg-gradient-success text-white btn-sm ps-3 pe-3">
                                                            <i class="fas fa-check-circle me-2" style="font-size: 11px"></i>Konfirmasi
                                                        </a>
                                                        <a href="{{ route('permintaan.konfirmasi-tolak', $log->id) }}"
                                                            class="btn bg-gradient-danger text-white btn-sm ps-3 pe-3"><i class="fas fa-times-circle me-2" style="font-size: 11px"></i>Tolak</a>
                                                    @else
                                                    <button type="button" class="btn btn-sm bg-gradient-danger" onclick="confirmDelete('{{ $log->id }}')">
                                                        <i class="fa-solid fa-trash" style="font-size: 14px"></i>
                                                    </button>
                                                    <form id="delete-form-{{ $log->id }}" action="{{ route('permintaan.destroy', $log->id) }}" method="POST" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mx-5 my-2">
                                {{ $logs->withQueryString()->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
    <script>
        @if(Session::has('status'))
            Swal.fire({
                icon: '{{ Session::get("status") }}',
                title: '{{ Session::get("status") == "success" ? "Berhasil!" : "Oops..." }}',
                text: '{{ Session::get("message") }}',
                showConfirmButton: false,
                timer: 2000
            });
        @endif

        function konfirmasiPermintaan(id) {
            Swal.fire({
                title: 'Konfirmasi Permintaan',
                text: 'Apakah Anda yakin ingin mengkonfirmasi permintaan ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Konfirmasi',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/permintaan/${id}/konfirmasi`, {
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: data.message,
                                showConfirmButton: false,
                                timer: 2000
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            throw new Error(data.message);
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: error.message || 'Terjadi kesalahan saat mengkonfirmasi permintaan'
                        });
                    });
                }
            });
        }
    </script>
    @endpush
    <style>
        .pagination .page-item.active .page-link {
            border-color: white;
            color: white;
        }
    </style>
@endsection