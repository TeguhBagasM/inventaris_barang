<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Barang;
use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
// use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class PeminjamanController extends Controller
{
    public function index()
    {
        $title = 'Peminjaman Barang';
        $barangs = Barang::where('stok', '>', 0)->get();
        return view('pages.PeminjamanBarang.peminjaman', compact('title', 'barangs'));
    }

    public function indexAdmin()
    {
        $title = 'Peminjaman Barang';
        $barangs = Barang::where('stok', '>', 0)->get();
        $users = User::whereIn('level', ['siswa', 'guru'])->get();
        return view('pages.PeminjamanBarang.admin-pinjam', compact('barangs', 'users', 'title'));
    }

    public function pinjamAdmin(Request $request)
    {
        DB::beginTransaction();
        try {
            // Validasi input
            $validatedData = $request->validate([
                'user_id' => 'required|exists:users,id',
                'tanggal_peminjaman' => 'required|date',
                'barangs' => 'required|array',
                'barangs.*.barang_id' => 'required|exists:barangs,id',
                'barangs.*.jumlah' => 'required|integer|min:1',
            ]);

            // Buat peminjaman utama
            $peminjaman = Peminjaman::create([
                'user_id' => $validatedData['user_id'],
                'tanggal_peminjaman' => $validatedData['tanggal_peminjaman'],
                'status' => 'dipinjam'
            ]);

            // Proses setiap barang
            foreach ($validatedData['barangs'] as $barangData) {
                // Cek stok barang
                $barang = Barang::findOrFail($barangData['barang_id']);
                
                if ($barangData['jumlah'] > $barang->stok) {
                    throw new \Exception("Stok barang {$barang->nama} tidak mencukupi");
                }

                // Kurangi stok barang
                $barang->decrement('stok', $barangData['jumlah']);

                // Buat detail peminjaman
                DetailPeminjaman::create([
                    'peminjaman_id' => $peminjaman->id,
                    'barang_id' => $barangData['barang_id'],
                    'jumlah' => $barangData['jumlah'],
                    'tanggal_pinjam' => $validatedData['tanggal_peminjaman'],
                    'status' => 'dipinjam'
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil Meminjam Barang'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error', 
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function pinjam(Request $request)
    {
        DB::beginTransaction();
        try {
            // Validasi input
            $validatedData = $request->validate([
                'user_id' => 'required|exists:users,id',
                'tanggal_peminjaman' => 'required|date',
                'barangs' => 'required|array',
                'barangs.*.barang_id' => 'required|exists:barangs,id',
                'barangs.*.jumlah' => 'required|integer|min:1',
            ]);
    
            // Buat peminjaman utama
            $peminjaman = Peminjaman::create([
                'user_id' => $validatedData['user_id'],
                'tanggal_peminjaman' => $validatedData['tanggal_peminjaman'],
                'status' => 'dipinjam',
            ]);
    
            // Proses barang
            $errors = [];
            foreach ($validatedData['barangs'] as $barangData) {
                $barang = Barang::find($barangData['barang_id']);
    
                if ($barangData['jumlah'] > $barang->stok) {
                    $errors[] = "Stok barang {$barang->nama} tidak mencukupi";
                    continue;
                }
    
                // Kurangi stok barang
                $barang->decrement('stok', $barangData['jumlah']);
    
                // Buat detail peminjaman
                DetailPeminjaman::create([
                    'peminjaman_id' => $peminjaman->id,
                    'barang_id' => $barangData['barang_id'],
                    'jumlah' => $barangData['jumlah'],
                    'status' => 'dipinjam',
                ]);
            }
    
            // Jika ada error stok
            if (!empty($errors)) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => $errors,
                ], 422);
            }
    
            DB::commit();
    
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil meminjam barang',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    

    public function detail()
    {
        $title = 'Detail Peminjaman';
        $details = Peminjaman::where('user_id', Auth::id())
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

    public function cetakBukti($id)
    {
        $peminjaman = Peminjaman::with(['user', 'detailPeminjamans.barang'])
            ->findOrFail($id);
        
        // Generate kode peminjaman
        $kodePeminjaman = date('dmY', strtotime($peminjaman->tanggal_peminjaman)) . 
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
        $peminjaman = Peminjaman::with(['detailPeminjamans.barang', 'user'])
            ->findOrFail($id);
        
        // Filter hanya detail peminjaman yang masih dipinjam
        $detailPeminjamans = $peminjaman->detailPeminjamans
            ->where('status', 'dipinjam');
        
        if ($detailPeminjamans->isEmpty()) {
            session()->flash('status', 'error');
            session()->flash('message', 'Semua barang sudah dikembalikan.');
            return redirect()->route('log.peminjaman');
        }

        return view('pages.peminjamanBarang.form-pengembalian', compact('peminjaman', 'detailPeminjamans', 'title'));
    }

    public function processPengembalian(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        
        $request->validate([
            'detail_peminjamans' => 'required|array',
            'detail_peminjamans.*.id' => 'exists:detail_peminjamans,id',
            'detail_peminjamans.*.kondisi' => 'required|in:Baik,Rusak Ringan,Rusak Berat',
            'detail_peminjamans.*.jumlah_kembali' => 'required|integer|min:1'
        ]);

        DB::beginTransaction();
        try {
            $semuaBarangKembali = true;
            
            foreach ($request->detail_peminjamans as $detail) {
                $detailPeminjaman = DetailPeminjaman::findOrFail($detail['id']);
                
                // Validasi jumlah kembali tidak melebihi yang dipinjam
                if ($detail['jumlah_kembali'] > $detailPeminjaman->jumlah) {
                    throw ValidationException::withMessages([
                        'detail_peminjamans' => 'Jumlah kembali tidak valid.'
                    ]);
                }

                // Update barang
                $barang = $detailPeminjaman->barang;
                $barang->stok += $detail['jumlah_kembali'];
                $barang->kondisi = $detail['kondisi'];
                $barang->save();

                // Update detail peminjaman
                $detailPeminjaman->update([
                    'tanggal_kembali' => now(),
                    'status' => $detail['jumlah_kembali'] == $detailPeminjaman->jumlah ? 'kembali' : 'dipinjam'
                ]);

                // Cek apakah masih ada barang yang belum kembali
                if ($detailPeminjaman->status != 'kembali') {
                    $semuaBarangKembali = false;
                }
            }

            // Update status peminjaman jika semua barang sudah kembali
            if ($semuaBarangKembali) {
                $peminjaman->update(['status' => 'selesai']);
            }

            DB::commit();

            session()->flash('status', 'success');
            session()->flash('message', 'Berhasil Mengembalikan Barang.');

            return redirect()->route('log.peminjaman');
        } catch (\Exception $e) {
            DB::rollBack();
            
            session()->flash('status', 'error');
            session()->flash('message', 'Gagal memproses pengembalian: ' . $e->getMessage());

            return redirect()->back()->withInput();
        }
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
            
            $detailPeminjaman = Peminjaman::findOrFail($id);
        
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
        $title = 'Logs Peminjaman Barang';

        // Ambil logs dan detail peminjaman
        $logs = Peminjaman::with('detailPeminjamans')->paginate(10);

        // Tambahkan total jumlah untuk setiap log
        foreach ($logs as $log) {
            $log->total_jumlah = $log->detailPeminjamans->isEmpty() ? 0 : $log->detailPeminjamans->sum('jumlah');
        }

        // Kirim data ke view
        return view('pages.peminjamanBarang.logs', compact('title', 'logs'));
    }


}
