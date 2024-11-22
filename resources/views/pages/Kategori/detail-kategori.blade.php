@extends('index')
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="mb-0">Detail Kategori: {{ $kategori->nama }}</h4>
                        <a href="{{ route('kategori.index') }}" class="btn btn-info">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Informasi Kategori -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Informasi Kategori</h5>
                                    <p class="card-text">
                                        <strong>Nama Kategori:</strong> {{ $kategori->nama }}
                                    </p>
                                    <p class="card-text">
                                        <strong>Jumlah Barang:</strong> {{ $kategori->barangs->count() }} item
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Daftar Barang -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Daftar Barang dalam Kategori</h5>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr class="text-center">
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Barang</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Stok</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($kategori->barangs as $index => $barang)
                                            <tr class="text-center">
                                                <td>
                                                    <span class="text-secondary text-xs font-weight-bold">{{ $index + 1 }}</span>
                                                </td>
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        @if($barang->gambar)
                                                            <div class="avatar avatar-sm me-3">
                                                                <img src="{{ asset($barang->gambar) }}" alt="{{ $barang->nama }}" />
                                                            </div>
                                                        @endif
                                                        <div class="d-flex flex-column justify-content-center">
                                                            <h6 class="mb-0 text-sm">{{ $barang->nama }}</h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="text-secondary text-xs font-weight-bold">
                                                        {{ $barang->stok }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-4">
                                                    <p class="text-secondary text-sm mb-0">Tidak ada barang dalam kategori ini</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection