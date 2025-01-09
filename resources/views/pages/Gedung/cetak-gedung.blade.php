<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Gedung</title>
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
        .gedung-info {
            margin-bottom: 30px;
        }
        .ruang-table {
            margin-left: 20px;
            width: calc(100% - 20px);
        }
        .section-title {
            font-weight: bold;
            margin: 15px 0 10px 0;
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
        <h3>Laporan Data Gedung</h3>
    </div>

    @foreach($gedung as $g)
    <div class="gedung-info">
        <table>
            <tr>
                <th width="200">Nama Gedung</th>
                <td>{{ $g->nama_gedung }}</td>
            </tr>
            <tr>
                <th>Luas Gedung</th>
                <td>{{ number_format($g->luas_gedung, 0, ',', '.') }} m²</td>
            </tr>
            <tr>
                <th>Tahun Perolehan</th>
                <td>{{ $g->tahun_perolehan }}</td>
            </tr>
            <tr>
                <th>Nilai Bangunan</th>
                <td>Rp {{ number_format($g->nilai_bangunan, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Jumlah Ruang</th>
                <td>{{ $g->jumlah_ruang }} ruangan</td>
            </tr>
            <tr>
                <th>Peruntukkan</th>
                <td>{{ $g->peruntukkan }}</td>
            </tr>
        </table>

        <p class="section-title">Daftar Ruangan:</p>
        <table class="ruang-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Ruang</th>
                    <th>Ukuran</th>
                    <th>Kondisi</th>
                    <th>Peruntukkan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($g->ruangs as $index => $ruang)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $ruang->nama_ruang }}</td>
                    <td>{{ $ruang->ukuran }}</td>
                    <td>{{ $ruang->kondisi }}</td>
                    <td>{{ $ruang->peruntukkan }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">Belum ada data ruangan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @endforeach

    <div class="footer">
        <p>Cimahi, {{ date('d F Y') }}</p>
        <div class="signature-space"></div>
        <p>{{ Auth::user()->name }}</p>
        <p>Sarana Prasarana Sekolah</p>
    </div>

    <div class="page-number">
        <div style="float: left">SIMANIS - Laporan Gedung</div>
        <div style="float: right">{{ date('d - m - Y') }} &nbsp;&nbsp;&nbsp; {{ date('H:i') }}</div>
    </div>
</body>
</html>