<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Barang;
use Illuminate\Http\Request;
use App\Models\DetailPeminjaman;
use Barryvdh\DomPDF\Facade\Pdf;
// use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PeminjamanController extends Controller
{
    public function index()
    {
        $title = 'Peminjaman Barang';
        $barangs = Barang::get();
        return view('pages.PeminjamanBarang.peminjaman', compact('title', 'barangs'));
    }
    public function pinjam(Request $request)
    {
        // dd($request);
        // Cari barang berdasarkan ID atau kembalikan error 404 jika tidak ditemukan
        $barangValidate = Barang::findOrFail($request->barang_id);

        // Validasi input
        $validatedData = $request->validate([
            'user_id' => 'required|integer',
            'keluar' => 'required|date',
            'jumlah' => [
                'required',
                'integer',
                'min:1',
                // Maksimum jumlah yang dapat dipinjam adalah stok barang yang tersedia
                'max:' . $barangValidate->stok,
            ],
            'barang_id' => 'required|exists:barangs,id', // Pastikan tabel barang adalah 'barangs'
        ]);

        // Simpan detail peminjaman
        $detailPeminjaman = new DetailPeminjaman();
        // $detailPeminjaman->nama = $validatedData['nama'];
        $detailPeminjaman->user_id = $validatedData['user_id'];
        $detailPeminjaman->keluar = $validatedData['keluar'];
        $detailPeminjaman->jumlah = $validatedData['jumlah'];
        $detailPeminjaman->barang_id = $validatedData['barang_id'];
        $detailPeminjaman->save();

        // Kurangi stok barang
        $barang = Barang::find($validatedData['barang_id']);
        if ($barang) {
            $barang->stok -= $validatedData['jumlah'];
            $barang->save();
        }

        if ($barang && $detailPeminjaman) {
            session()->flash('status', 'success');
            session()->flash('message', 'Berhasil Meminjam Barang.');
        } else {
            session()->flash('status', 'error');
            session()->flash('message', 'Gagal Meminjam.');
        }

        // Redirect atau tampilkan pesan berhasil
        return redirect('/peminjaman');
    }

    public function detail()
    {
        $title = 'Detail Peminjaman';
        $details = DetailPeminjaman::where('user_id', Auth::id())
            ->with('barang')
            ->paginate(12); 

        return view('pages.peminjamanBarang.detailPeminjaman', compact('title', 'details'));
    }

    public function cetak()
    {
        $peminjaman = DetailPeminjaman::all();
        $barangs = Barang::get();
        $pdf = Pdf::loadView('pages.pdf.cetak-peminjaman', [
            'peminjaman' => $peminjaman,
            'barangs' => $barangs
        ]);
        return $pdf->stream('laporan-peminjaman-'.date('Y-m-d').'.pdf');
    }

    public function cetakBukti(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:detail_peminjaman,id'
        ]);
    
        $peminjaman = DetailPeminjaman::with(['barang', 'user'])
            ->findOrFail($request->id);
        
        // Generate kode peminjaman
        $kodePeminjaman = date('dmY', strtotime($peminjaman->keluar)) . 
            str_pad($peminjaman->id, 4, '0', STR_PAD_LEFT);
        
        // Generate QR Code menggunakan Endroid
        $qrCode = QrCode::create($kodePeminjaman)
            ->setSize(300)  
            ->setMargin(10)
            ->setEncoding(new Encoding('UTF-8'))
            ->setErrorCorrectionLevel(ErrorCorrectionLevel::High)
            ->setRoundBlockSizeMode(RoundBlockSizeMode::Margin);
    
        $writer = new PngWriter();
        $result = $writer->write($qrCode);
        
        // Convert ke base64
        $qrcode = base64_encode($result->getString());
    
        $pdf = Pdf::loadView('pages.pdf.cetak-bukti', [
            'peminjaman' => $peminjaman,
            'tanggal_cetak' => Carbon::now()->format('d/m/Y H:i'),
            'kode_peminjaman' => $kodePeminjaman,
            'qrcode' => $qrcode
        ]);
    
        return $pdf->stream('bukti-peminjaman-' . $kodePeminjaman . '.pdf');
    }

    public function showPengembalianForm($id)
    {
        $title = "Form Pengembalian Barang";
        $detailPeminjaman = DetailPeminjaman::with(['barang', 'user'])->findOrFail($id);
        
        if ($detailPeminjaman->masuk !== null) {
            session()->flash('status', 'error');
            session()->flash('message', 'Barang sudah dikembalikan sebelumnya.');
            return redirect()->back();
        }

        return view('pages.peminjamanBarang.form-pengembalian', compact('detailPeminjaman', 'title'));
    }

    public function processPengembalian(Request $request, $id)
    {
        $detailPeminjaman = DetailPeminjaman::findOrFail($id);
        
        // Validasi
        $request->validate([
            'kondisi' => 'required'
        ]);

        // Update stok dan kondisi barang
        $barang = Barang::findOrFail($detailPeminjaman->barang_id);
        $barang->stok += $detailPeminjaman->jumlah;
        $barang->kondisi = $request->kondisi; // Update kondisi barang
        $barang->save();

        // Update tanggal pengembalian
        $detailPeminjaman->masuk = Carbon::now();
        $detailPeminjaman->save();

        session()->flash('status', 'success');
        session()->flash('message', 'Berhasil Mengembalikan Barang.');

        return redirect()->route('log.peminjaman');
    }

    public function scanQR()
    {
        $title = "Scan Qr Code";
        return view('pages.peminjamanBarang.scan-qr', compact('title'));
    }

    public function processQR(Request $request)
    {
        $kodePeminjaman = $request->kode;
        
        try {
            // Extract ID dari kode peminjaman (4 karakter terakhir)
            $id = intval(substr($kodePeminjaman, -4));
            
            $detailPeminjaman = DetailPeminjaman::findOrFail($id);
        
            // Cek apakah sudah dikembalikan
            if ($detailPeminjaman->masuk !== null) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Barang sudah dikembalikan sebelumnya.'
                ], 400);
            }
        
            // Return URL untuk redirect ke form pengembalian
            return response()->json([
                'status' => 'success',
                'message' => 'QR Code valid',
                'redirect_url' => route('pengembalian.form', $id)
            ]);

        } catch (\Exception $e) {
            Log::error('Error processing QR code: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'QR Code tidak valid atau barang tidak ditemukan'
            ], 400);
        }
    }

    // public function log(Request $request)
    // {
    //     $title = 'Logs Peminjaman';
        
    //     $query = DetailPeminjaman::with(['barang', 'user']);

    //     // Filter tanggal
    //     if ($request->filled('start_date') && $request->filled('end_date')) {
    //         $query->whereBetween('keluar', [
    //             $request->start_date, 
    //             $request->end_date
    //         ]);
    //     }

    //     $logs = $query->orderBy('keluar', 'desc')->paginate(10);
        
    //     return view('pages.peminjamanBarang.logs', compact('logs', 'title'));
    // }
    public function log(Request $request)
    {
        $title = 'Log Peminjaman';
        
        $query = DetailPeminjaman::with(['barang', 'user']);

        // Filter tanggal pinjam spesifik
        if ($request->filled('tanggal_pinjam')) {
            $query->whereDate('keluar', $request->tanggal_pinjam);
        }

        $logs = $query->orderBy('keluar', 'desc')->paginate(10);
        
        return view('pages.peminjamanBarang.logs', compact('logs', 'title'));
    }
}
