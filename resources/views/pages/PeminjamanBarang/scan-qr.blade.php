@extends('index')
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>Scan QR Code Pengembalian</h4>
                        </div>
                        <div>
                            <a href="{{ route('log.peminjaman') }}" class="mt-2 btn btn-outline-primary">Kembali</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row justify-content-center">
                        <div class="col-md-6 text-center">
                            <div id="reader" class="mb-3"></div>
                            <div id="result"></div>
                            <div id="loading" style="display: none;">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-2">Memproses QR Code...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    function onScanSuccess(decodedText, decodedResult) {
    // Tampilkan loading
    document.getElementById('loading').style.display = 'block';
    
    // Kirim ke backend
    fetch('{{ route("process-qr") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            kode: decodedText
        })
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => Promise.reject(err));
        }
        return response.json();
    })
    .then(data => {
        document.getElementById('loading').style.display = 'none';
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: data.message,
            showConfirmButton: false,
            timer: 1500
        }).then(() => {
            window.location.href = '{{ route("log.peminjaman") }}';
        });
    })
    .catch(error => {
        document.getElementById('loading').style.display = 'none';
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: error.message || 'Terjadi kesalahan saat memproses QR code'
        });
    });
}

    function onScanFailure(error) {
        // handle scan failure, usually better to ignore and keep scanning.
        console.warn(`Code scan error = ${error}`);
    }

    let html5QrcodeScanner = new Html5QrcodeScanner(
        "reader",
        { 
            fps: 10, 
            qrbox: {width: 250, height: 250},
            rememberLastUsedCamera: true
        },
        /* verbose= */ false
    );
    html5QrcodeScanner.render(onScanSuccess, onScanFailure);
</script>
@endpush

<style>
    #reader {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 10px;
    }
    
    #reader button {
        background-color: #5e72e4 !important;
        color: white !important;
        border: none !important;
        padding: 8px 16px !important;
        border-radius: 4px !important;
        margin: 5px !important;
    }
</style>
@endsection