@extends('index')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4>Logs Peminjaman Barang</h4>
                            </div>
                            <div>
                                <a href="{{ route('peminjaman.cetak') }}" class="mt-2 text-white btn bg-gradient-success">Cetak</a>
                            </div>
                        </div>
                        <hr class="bg-dark px-auto">
                        @if (Session::has('status'))
                            <div class="alert alert-success text-white opacity-5" role="alert">
                                {{ Session::get('message') }}
                            </div>
                        @endif
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        @if($logs->isEmpty())
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
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr class="text-center">
                                        <th class="text-uppercase text-dark text-sm font-weight-bolder">No</th>
                                        <th class="text-uppercase text-dark text-sm font-weight-bolder">Peminjam</th>
                                        <th class="text-uppercase text-dark text-sm font-weight-bolder">Barang</th>
                                        <th class="text-uppercase text-dark text-sm font-weight-bolder">Jumlah
                                        </th>
                                        <th class="text-uppercase text-dark text-sm font-weight-bolder">Tanggal Di Pinjam
                                        </th>
                                        <th class="text-uppercase text-dark text-sm font-weight-bolder">Status
                                        </th>
                                        <th class="text-uppercase text-dark text-sm font-weight-bolder">Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($logs as $log)
                                        <tr class="ps-2">
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <h6 class="ps-2 text-secondary text-sm font-weight-bold">
                                                        {{ $loop->iteration }}</h6>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <h6 class="text-secondary text-sm font-weight-bold ps-2">
                                                        {{ $log->user->name }}</h6>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <h6 class="text-secondary text-sm font-weight-bold ps-2">
                                                        {{ $log->barang->nama }}</h6>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <h6 class="text-secondary text-sm font-weight-bold ps-2">
                                                        {{ $log->jumlah }}</h6>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <h6 class="text-secondary text-sm font-weight-bold ps-2">
                                                        {{ \Carbon\Carbon::parse($log->keluar)->translatedFormat('l, d M Y') }}</h6>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <h6 class="text-secondary text-sm font-weight-bold ps-2 {{ !$log->masuk ? 'bg-danger badge font-weight-bold py-1 px-2 text-white' : '' }}">
                                                        {{ $log->masuk ? \Carbon\Carbon::parse($log->masuk)->translatedFormat('l, d M Y') : 'Belum dikembalikan' }}
                                                    </h6>
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                @if (!$log->masuk)
                                                    <form action="{{ route('pengembalian.kembali', $log->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" 
                                                                class="btn bg-gradient-warning btn-sm" 
                                                                onclick="return confirm('Apakah Anda yakin ingin mengembalikan barang?')">
                                                            Kembalikan
                                                        </button>
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

    <style>
        .pagination .page-item.active .page-link {
            border-color: white;
            color: white;
        }
    </style>
@endsection
