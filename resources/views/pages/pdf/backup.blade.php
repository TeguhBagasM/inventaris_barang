<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Bukti Peminjaman</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .header {
            text-align: center;
            position: relative;
            height: 80px;
        }
        .header img {
            height: 70px;
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
        }
        .header img:first-child {
            left: 10px; 
        }
        .header img:last-child {
            right: 10px; 
            margin-left: 40px;
        }
        .header-content {
            display: inline-block;
            text-align: center; 
            width: calc(100% - 100px); 
        }
        .header-text {
            margin: 5px 0;
        }
        hr {
            border-top: 2px solid black;
            margin-top: 10px;
            margin-bottom: 20px;
        }
        .title {
            text-align: center;
            margin: 20px 0;
        }
        
        /* Tambahkan style untuk container utama */
        .main-content {
            position: relative;
            width: 100%;
        }
        
        /* Style untuk tabel informasi */
        .info-table {
            width: 70%;
            float: left;
        }
        .info-table td {
            padding: 8px;
            vertical-align: top;
        }
        .info-table td:first-child {
            width: 200px;
        }
        
        /* Style untuk QR code container */
        .qr-container {
            position: absolute;
            top: 0;
            right: 0;
            width: 25%;
            text-align: center;
            padding: 10px;
            border: 1px solid #000;
        }
        .qr-code {
            margin-bottom: 10px;
        }
        .kode-peminjaman {
            font-size: 14px;
            margin-top: 5px;
            font-weight: bold;
        }

        .footer {
            clear: both;
            margin-top: 200px;  /* Sesuaikan margin atas footer */
            text-align: right;
        }
        
        .footer p {
            margin: 5px 0;
        }
        
        .signature {
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('assets/img/logo-cimahi.png') }}" alt="Logo Cimahi">
        <div class="header-content">
            <h2 class="header-text">SMK NEGERI 2 CIMAHI</h2>
            <p class="header-text">JL. Kamarung No. 69 Kel. Citereup Kec. Cimahi Utara</p>
            <p class="header-text">Email: smkn2cmi@yahoo.com Kota Cimahi 40512</p>
        </div>
        <img src="{{ public_path('assets/img/logo-smk.png') }}" alt="Logo SMKN 2 Cimahi">
    </div>
    <hr>

    <div class="title">
        <h3>Bukti Peminjaman</h3>
    </div>

    <div class="main-content">
        <!-- Tabel Informasi -->
        <table class="info-table">
            <tr>
                <td>Tanggal Peminjaman</td>
                <td>: {{ \Carbon\Carbon::parse($peminjaman->keluar)->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td>Nama Peminjam</td>
                <td>: {{ $peminjaman->user->name }}</td>
            </tr>
            <tr>
                <td>Barang yang Dipinjam</td>
                <td>: {{ $peminjaman->barang->nama }}</td>
            </tr>
            <tr>
                <td>Jumlah</td>
                <td>: {{ $peminjaman->jumlah }} unit</td>
            </tr>
            @if($peminjaman->masuk)
            <tr>
                <td>Tanggal Pengembalian</td>
                <td>: {{ \Carbon\Carbon::parse($peminjaman->masuk)->format('d/m/Y') }}</td>
            </tr>
            @endif
        </table>

        <!-- QR Code Container -->
        <div class="qr-container">
            <div class="qr-code">
                <img src="data:image/png;base64,{{ $qrcode }}" alt="QR Code" style="width: 100px; height: 100px;">
            </div>
            <p>Kode Peminjaman:</p>
            <p class="kode-peminjaman">{{ $kode_peminjaman }}</p>
        </div>
    </div>

    <div class="footer">
        <p>Dicetak pada: {{ $tanggal_cetak }}</p>
        <div class="signature">
            <p>{{ Auth::user()->name }}</p>
            <p>Sarana Prasarana Sekolah</p>
        </div>
    </div>
</body>
</html>