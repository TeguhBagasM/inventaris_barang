@extends('index')

@section('content')
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <h4 class="">Tambah Kategori</h4>
                            <hr style="background-color: black">
                        </div>

                        <div class="card-body px-0 pt-0 pb-2 ps-4 me-4">
                            <form id="myForm" action="{{ route('kategori.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="nama" class="form-label text-sm required-label">Nama Kategori</label>
                                    <input type="text" class="form-control" name="nama" id="nama" required>
                                </div>
                                <a href="/kategori" class="btn bg-gradient-danger ">Back</a>
                                <button type="submit" class="btn btn-success float-end">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
