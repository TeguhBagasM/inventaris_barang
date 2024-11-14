<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bukti Peminjaman</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .content {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        .footer {
            margin-top: 50px;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>BUKTI PEMINJAMAN BARANG</h2>
        <p>Nomor: {{ $peminjaman->id }}/PB/{{ date('Y') }}</p>
    </div>

    <div class="content">
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
                <td>: {{ $peminjaman->barang->nama_barang }}</td>
            </tr>
            <tr>
                <td>Jumlah</td>
                <td>: {{ $peminjaman->jumlah }} unit</td>
            </tr>
            <tr>
                <td>Status</td>
                <td>: {{ $peminjaman->masuk ? 'Sudah Dikembalikan' : 'Belum Dikembalikan' }}</td>
            </tr>
            @if($peminjaman->masuk)
            <tr>
                <td>Tanggal Pengembalian</td>
                <td>: {{ \Carbon\Carbon::parse($peminjaman->masuk)->format('d/m/Y') }}</td>
            </tr>
            @endif
        </table>
    </div>

    <div class="footer">
        <p>Dicetak pada: {{ $tanggal_cetak }}</p>
        <br><br><br>
        <p>(_________________)</p>
        <p>Sarana Prasarana Sekolah</p>
    </div>
</body>
</html>