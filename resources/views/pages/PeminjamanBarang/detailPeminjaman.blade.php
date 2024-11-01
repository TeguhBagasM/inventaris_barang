    @extends('index')
    @section('content')
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="">Detail Peminjaman Barang</h4>
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
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-dark text-sm font-weight-bolder">No</th>
                                            <th class="text-uppercase text-dark text-sm font-weight-bolder">Nama Barang</th>
                                            <th class="text-uppercase text-dark text-sm font-weight-bolder">Jumlah Peminjaman
                                            </th>
                                            <th class="text-uppercase text-dark text-sm font-weight-bolder">Tanggal Di Pinjam
                                            </th>
                                            <th class="text-uppercase text-dark text-sm font-weight-bolder">Status</th>
                                            {{-- <th class="text-uppercase text-dark text-sm font-weight-bolder">Aksi</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($details as $detail)
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
                                                            {{ $detail->barang->nama }}</h6>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        <h6 class="text-secondary text-sm font-weight-bold ps-2">
                                                            {{ $detail->jumlah }}</h6>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        <h6 class="text-secondary text-sm font-weight-bold ps-2">
                                                            {{ \Carbon\Carbon::parse($detail->keluar)->format('D M, Y') }}</h6>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        @if ($detail->masuk)
                                                            <span class="badge bg-success">Di Kembalikan</span>
                                                        @else
                                                            <span class="badge bg-warning text-dark">Di Pinjam</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                {{-- <td class="align-middle">
                                                    @if (!$detail->masuk)
                                                        <form action="{{ route('kembali', $detail->id) }}" method="POST">
                                                            @csrf
                                                            <button type="submit"
                                                                class="btn bg-gradient-warning">Kembalikan</button>
                                                        </form>
                                                    @endif
                                                </td> --}}
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mx-5 my-2">
                                {{ $details->withQueryString()->links() }}
                            </div>
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
