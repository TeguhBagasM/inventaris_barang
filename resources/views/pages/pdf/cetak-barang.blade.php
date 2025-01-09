<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Asset</title>
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
            margin-bottom: 0;
        }
        .title {
            text-align: center;
            margin: 20px 0;
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
        .signature-space {
            margin: 30px 0;
            height: 60px;
        }
        .page-number {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            padding: 0 20px;
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
        <hr>
    </div>
    <div class="title">
        <h3>Laporan Asset</h3>
    </div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Serial No.</th>
                <th>Stok</th>
                <th>Ruangan</th>
                <th>Kondisi</th>
                <th>Tahun</th>
            </tr>
        </thead>
        <tbody>
            @foreach($barang as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->nama }}</td>
                <td>{{ $item->serial_number }}</td>
                <td>{{ $item->stok }}</td>
                <td>{{ $item->ruang->nama_ruang }}</td>
                <td>{{ $item->kondisi }}</td>
                <td>{{ $item->tahun_pengadaan }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Cimahi, {{ date('d F Y') }}</p>
        <div class="signature-space"></div>
        <p>{{ Auth::user()->name }}</p>
        <p>Sarana Prasarana Sekolah</p>
    </div>

    <div class="page-number">
        <div style="float: left">SIMANIS - Laporan Asset</div>
        <div style="float: right">{{ date('d - m - Y') }} &nbsp;&nbsp;&nbsp; {{ date('H:i') }}</div>
    </div> 
</body>
</html>