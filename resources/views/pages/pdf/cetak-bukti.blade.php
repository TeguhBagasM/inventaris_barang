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
            gap: 20px;
            margin: 20px 0;
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
        
        /* Mengatur QR code container */
        .qr-container {
            width: 200px;
            text-align: center;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            height: fit-content;
        }
        
        .qr-container img {
            width: 100px;
            height: 100px;
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
            margin-top: 60px;
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
            
            .qr-container {
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="title">
        <h3>Bukti Peminjaman</h3>
    </div>

    <div class="main-container">
        <!-- Tabel di kiri -->
        <div class="table-container">
            <table>
                <tr>
                    <td width="200">Tanggal Peminjaman</td>
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
        </div>

        <!-- QR Code di kanan -->
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