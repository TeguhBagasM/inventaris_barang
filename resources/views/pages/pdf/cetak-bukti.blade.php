<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Bukti Peminjaman</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
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
            margin-bottom: 30px;
        }
    
        .title h3 {
            margin: 0;
            font-size: 24px;
        }
    
        /* Mengatur container utama menjadi flex row */
        .main-container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start; /* Menyelaraskan elemen ke atas */
            gap: 20px;
            margin: 20px 0;
            position: relative; 
        }
        
        /* Mengatur lebar tabel */
        .table-container {
            flex: 1;
            max-width: 70%;
        }
        
        .table-container table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .table-container td {
            padding: 6px 0;
            vertical-align: top;
        }

        .table-details {
            flex: 1;
            max-width: 55%;
            margin-top: 6px;
        }
        .table-details table {
            width: 100%;
        }
        
        /* Mengatur QR code container */
        .qr-container {
            width: 200px;
            text-align: center;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #fff;
            position: absolute; 
            top: 0;
            right: 0; 
        }
        
        .qr-container img {
            width: 120px;
            height: 120px;
            margin-bottom: 10px;
        }
        
        .qr-code-text {
            font-size: 12px;
            margin-top: 10px;
            word-break: break-all;
        }
        
        .qr-code-text p {
            margin: 5px 0;
        }
        
        .footer {
            margin-top: 200px;
            text-align: right;
        }
        
        .footer p {
            margin: 5px 0;
        }
    
        .signature-space {
            margin: 30px 0;
            height: 60px;
        }
    
        @media print {
            body {
                margin: 20px;
            }
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

    <div class="main-container">
        <!-- Tabel di kiri -->
        <div class="table-container">
            <table>
                <tr>
                    <td width="140">Tanggal Peminjaman</td>
                    <td>: {{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <td>Nama Peminjam</td>
                    <td>: {{ $peminjaman->user->name }}</td>
                </tr>
            </table>
        </div>
        <div class="table-details">
            <table border="1" cellspacing="0" cellpadding="6">
                <thead>
                <tr>
                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach($peminjaman->detailPeminjamans as $detail)
                <tr align="center">
                    <td>{{ $detail->barang->nama }}</td>
                    <td>{{ $detail->jumlah }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

        <div class="qr-container">
            <img src="data:image/png;base64,{{ $qrcode }}" alt="QR Code">
            <div class="qr-code-text">
                <p>Kode Peminjaman:</p>
                <p><strong>{{ $kode_peminjaman }}</strong></p>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>Dicetak pada: {{ $tanggal_cetak }}</p>
        <div class="signature-space"></div>
        <p>{{ Auth::user()->name }}</p>
        <p>Sarana Prasarana Sekolah</p>
    </div>
</body>
</html>