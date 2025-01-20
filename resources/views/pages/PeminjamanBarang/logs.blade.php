@extends('index')
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-2">
                    <div class="row align-items-center">
                        <div class="col-12 col-md-6">
                            <h4 class="mb-0">Logs Peminjaman Asset</h4>
                        </div>
                
                        <div class="col-12 col-md-6 mt-3 mt-md-0 d-flex flex-wrap justify-content-md-end gap-2">
                            <a href="{{ route('scan-qr') }}" class="btn bg-gradient-info text-white">
                                <i class="fas fa-qrcode me-2"></i> Scan QR
                            </a>
                            <a href="{{ route('admin.pinjam') }}" class="btn bg-gradient-success text-white">
                                <i class="fas fa-plus me-2"></i> Peminjaman Baru
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="card-body py-3">
                    <form action="{{ route('peminjaman.cetak') }}" method="GET" class="row g-2 align-items-center" target="_blank">
                        <div class="col-12 col-md-2 d-flex align-items-center">
                            <input type="date" name="start_date" id="start_date" class="form-control">
                        </div>
                        <div class="col-12 col-md-2 d-flex align-items-center">
                            <input type="date" name="end_date" id="end_date" class="form-control">
                        </div>
                        <div class="col-12 col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn bg-gradient-info text-white mt-3">
                                <i class="fas fa-print me-2"></i> Cetak
                            </button>
                        </div>
                    </form>
                    <hr class="bg-dark mt-3">
                </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        @if($logs->isEmpty())
                        <form action="{{ route('log.peminjaman') }}" method="GET" class="row mb-4 ms-4">
                            <div class="col-md-4">
                                <label>Tanggal Pinjam</label>
                                <input type="date" name="tanggal_peminjaman" class="form-control" 
                                       value="{{ request('tanggal_peminjaman') }}">
                            </div>
                            <div class="col-md-4 align-self-end mt-4">
                                <button type="submit" class="btn bg-gradient-success me-2">Filter</button>
                                <a href="{{ route('log.peminjaman') }}" class="btn btn-secondary">Reset</a>
                            </div>
                        </form>
                            <div class="text-center py-5">
                                <div class="mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" class="bi bi-inbox text-secondary" viewBox="0 0 16 16">
                                        <path d="M4.98 4a.5.5 0 0 0-.39.188L1.54 8H6a.5.5 0 0 1 .5.5 1.5 1.5 0 1 0 3 0A.5.5 0 0 1 10 8h4.46l-3.05-3.812A.5.5 0 0 0 11.02 4H4.98zm9.954 5H10.45a2.5 2.5 0 0 1-4.9 0H1.066l.32 2.562a.5.5 0 0 0 .497.438h12.234a.5.5 0 0 0 .496-.438L14.933 9zM3.809 3.563A1.5 1.5 0 0 1 4.981 3h6.038a1.5 1.5 0 0 1 1.172.563l3.7 4.625a.5.5 0 0 1 .105.374l-.39 3.124A1.5 1.5 0 0 1 14.117 13H1.883a1.5 1.5 0 0 1-1.489-1.314l-.39-3.124a.5.5 0 0 1 .106-.374l3.7-4.625z"/>
                                    </svg>
                                </div>
                                <h6 class="text-secondary">Belum ada barang yang dipinjam</h6>
                                <p class="text-muted">Data log peminjaman barang akan muncul di sini</p>
                            </div>
                        @else
                        <form action="{{ route('log.peminjaman') }}" method="GET" class="row mb-4 ms-4">
                            <div class="col-md-4">
                                <label>Tanggal Pinjam</label>
                                <input type="date" name="tanggal_peminjaman" class="form-control" 
                                       value="{{ request('tanggal_peminjaman') }}">
                            </div>
                            <div class="col-md-4 align-self-end mt-4">
                                <button type="submit" class="btn bg-gradient-success me-2">Filter</button>
                                <a href="{{ route('log.peminjaman') }}" class="btn bg-gradient-orange text-white">Reset</a>
                            </div>
                        </form>
                        <div class="table-responsive p-0">
                            <table class="table table-striped align-items-center mb-0" id="datatables">
                                <thead>
                                    <tr class="text-center">
                                        <th>No</th>
                                        <th>Peminjam</th>
                                        <th>Jumlah Asset</th>
                                        <th>Tanggal Pinjam</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($logs as $log)
                                        <tr class="text-center">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $log->user->name ? $log->user->name : 'Tidak diketahui' }}</td>
                                            <td>{{ $log->total_jumlah }}</td>                                        
                                            <td>{{ \Carbon\Carbon::parse($log->tanggal_peminjaman)->translatedFormat('l, d M Y') }}</td>
                                            <td>
                                                @if ($log->status == 'menunggu konfirmasi')
                                                    <span class="badge bg-secondary">Belum Dikonfirmasi</span>
                                                @elseif ($log->status == 'dipinjam')
                                                    <span class="badge bg-warning">Belum Dikembalikan</span>
                                                @elseif ($log->status == 'ditolak')
                                                    <span class="badge bg-danger">Ditolak</span>
                                                @else
                                                <span class="badge bg-success">Dikembalikan</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($log->status == 'menunggu konfirmasi')
                                                <a href="#" onclick="konfirmasiPeminjaman({{ $log->id }})"
                                                    class="btn bg-gradient-success text-white btn-sm ps-3 pe-3"><i class="fas fa-check-circle me-2" style="font-size: 11px"></i>Konfirmasi</a>
                                                 <a href="{{ route('peminjaman.konfirmasi-tolak', $log->id) }}"
                                                    class="btn bg-gradient-danger text-white btn-sm ps-3 pe-3"><i class="fas fa-times-circle me-2" style="font-size: 11px"></i>Tolak</a>
                                                @elseif ($log->status == 'dipinjam')
                                                <a href="{{ route('pengembalian.form', $log->id) }}"
                                                   class="btn bg-gradient-orange text-white btn-sm ps-3 pe-3"><i class="fas fa-undo-alt me-2" style="font-size: 11px"></i>Kembalikan</a>
                                                   <a href="{{ route('cetak.bukti', ['id' => $log->id]) }}"
                                                      class="text-white btn btn-info btn-sm ps-3 pe-3" target="_blank"><i class="fas fa-print me-2" style="font-size: 11px"></i>Cetak</a>
                                                @else
                                                <button type="button" class="btn btn-sm bg-gradient-danger" onclick="confirmDelete('{{ $log->id }}')">
                                                    <i class="fa-solid fa-trash" style="font-size: 14px"></i>
                                                </button>
                                                <form id="delete-form-{{ $log->id }}" action="{{ route('peminjaman.destroy', $log->id) }}" method="POST" style="display: none;">
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
        function konfirmasiPeminjaman(id) {
        Swal.fire({
            title: 'Konfirmasi Peminjaman',
            text: 'Apakah Anda yakin ingin mengkonfirmasi peminjaman ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Konfirmasi',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/peminjaman/${id}/konfirmasi`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message,
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => {
                        location.reload();
                    });
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: error.message || 'Terjadi kesalahan saat mengkonfirmasi peminjaman'
                    });
                });
            }
        });
    }
    function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
        const dateInputs = document.querySelectorAll('input[type="date"]');

        dateInputs.forEach(input => {
            input.addEventListener('focus', (e) => {
                e.target.type = 'date';
            });
            
            input.addEventListener('blur', (e) => {
                if (!e.target.value) {
                    e.target.type = 'text';
                }
            });
        });

        document.getElementById('start_date').type = 'text';
        document.getElementById('start_date').placeholder = 'Mulai dari';
        document.getElementById('end_date').type = 'text';
        document.getElementById('end_date').placeholder = 'Sampai';
    </script>
    @endpush
    <style>
        .pagination .page-item.active .page-link {
            border-color: white;
            color: white;
        }
    </style>
@endsection
