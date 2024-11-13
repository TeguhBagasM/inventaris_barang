<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Barang</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header img {
            height: 60px;
            margin: 0 20px;
        }
        .header-text {
            margin: 5px 0;
        }
        .title {
            text-align: center;
            margin: 20px 0;
        }
        .info {
            margin: 10px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
        }
        .footer-text {
            margin-bottom: 50px;
        }
        .signature {
            margin-top: 10px;
            text-align: center;
        }
        .page-number {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: right;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('assets/img/logo-cimahi.png') }}" alt="Logo Kiri" style="float: left;">
        <div style="display: inline-block;">
            <h2 class="header-text">SMK NEGERI 2 CIMAHI</h2>
            <p class="header-text">JL. Kamarung No. 69 Kel. Citereup Kec. Cimahi Utara</p>
            <p class="header-text">Email: smkn2cmi@yahoo.com Kota Cimahi 40512</p>
        </div>
        <img src="{{ public_path('assets/img/logo-smk.png') }}" alt="Logo Kanan" style="float: right;">
        <div style="clear: both;"></div>
        <hr style="border-top: 2px solid black; margin-top: 10px;">
    </div>

    <div class="title">
        <h3>Laporan Barang</h3>
        {{-- <p>01 November 2024 s.d 30 November 2024</p> --}}
        {{-- <p>Kategori Barang: Aset</p> --}}
    {{-- <p>Lokasi: Semua</p> --}}
    </div>

    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Stok</th>
                <th>Ruangan</th>
                <th>Kondisi</th>
                <th>Tahun</th>
                <th>Ket.</th>
            </tr>
        </thead>
        <tbody>
            @foreach($barang as $item)
            <tr>
                <td>{{ $item->nama }}</td>
                <td>{{ $item->stok }}</td>
                <td>{{ $item->ruang->nama_ruang }}</td>
                <td>{{ $item->kondisi }}</td>
                <td>{{ $item->tahun }}</td>
                <td>{{ $item->keterangan ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <div class="footer-text">
            Cimahi, {{ date('d F Y') }}
        </div>
        <div class="footer-text">
            Sarana Prasarana Sekolah
        </div>
        <div class="signature">
            <br><br><br>
            (Jane Cooper)
        </div>
    </div>

    <div class="page-number">
        SIMANIS - Laporan Inventaris &nbsp;&nbsp;&nbsp;&nbsp; {{ date('d - m - Y') }}<br>
        {{ date('H:i') }}
    </div>
</body>
</html>