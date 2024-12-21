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
            $validatedData = $request->validate([
                'user_id' => 'required|exists:users,id',
                'tanggal_peminjaman' => 'required|date',
                'barangs' => 'required|array',
                'barangs.*.barang_id' => 'required|exists:barangs,id',
                'barangs.*.jumlah' => [
                    'required', 
                    'integer', 
                    'min:1',
                    function($attribute, $value, $fail) {
                        preg_match('/barangs\.(\d+)\.jumlah/', $attribute, $matches);
                        $index = $matches[1];
                        $barangId = request('barangs')[$index]['barang_id'];
                        
                        $barang = Barang::find($barangId);
                        
                        if ($value > $barang->stok) {
                            $fail("Stok barang {$barang->nama} tidak mencukupi. Tersedia: {$barang->stok}");
                        }
                    }
                ],
            ], [
                'user_id.required' => 'Silakan pilih pengguna',
                'user_id.exists' => 'Pengguna tidak valid',
                'tanggal_peminjaman.required' => 'Tanggal peminjaman harus diisi',
                'barangs.required' => 'Minimal satu barang harus dipilih',
            ]);
    
            if (empty($validatedData['user_id'])) {
                return response()->json([
                    'message' => 'Silakan pilih pengguna terlebih dahulu'
                ], 422);
            }
    
            if (empty($validatedData['tanggal_peminjaman'])) {
                return response()->json([
                    'message' => 'Tanggal peminjaman harus diisi'
                ], 422);
            }
    
            $peminjaman = Peminjaman::create([
                'user_id' => $validatedData['user_id'],
                'tanggal_peminjaman' => $validatedData['tanggal_peminjaman'],
                'status' => 'menunggu konfirmasi'
            ]);
    
            $errorMessages = [];
            foreach ($validatedData['barangs'] as $barangData) {
                $barang = Barang::findOrFail($barangData['barang_id']);
                
                if ($barangData['jumlah'] > $barang->stok) {
                    $errorMessages[] = "Stok barang {$barang->nama} tidak mencukupi. Tersedia: {$barang->stok}";
                    continue;
                }
    
                $barang->decrement('stok', $barangData['jumlah']);
    
                DetailPeminjaman::create([
                    'peminjaman_id' => $peminjaman->id,
                    'barang_id' => $barangData['barang_id'],
                    'jumlah' => $barangData['jumlah'],
                    'tanggal_pinjam' => $validatedData['tanggal_peminjaman'],
                    'status' => 'menunggu konfirmasi'
                ]);
            }
    
            if (!empty($errorMessages)) {
                DB::rollBack();
                return response()->json([
                    'message' => 'Beberapa barang tidak dapat dipinjam',
                    'errors' => $errorMessages
                ], 422);
            }
    
            DB::commit();
    
            return response()->json([
                'message' => 'Berhasil Meminjam Barang'
            ], 200);
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function pinjam(Request $request)
    {
        DB::beginTransaction();
        try {
            $validatedData = $request->validate([
                'user_id' => 'required|exists:users,id',
                'tanggal_peminjaman' => 'required|date',
                'barangs' => 'required|array',
                'barangs.*.barang_id' => 'required|exists:barangs,id',
                'barangs.*.jumlah' => [
                    'required', 
                    'integer', 
                    'min:1',
                    function($attribute, $value, $fail) {
                        preg_match('/barangs\.(\d+)\.jumlah/', $attribute, $matches);
                        $index = $matches[1];
                        $barangId = request('barangs')[$index]['barang_id'];
                        
                        $barang = Barang::find($barangId);
                        
                        if ($value > $barang->stok) {
                            $fail("Stok barang {$barang->nama} tidak mencukupi. Tersedia: {$barang->stok}");
                        }
                    }
                ],
            ], [
                'user_id.required' => 'Silakan pilih pengguna',
                'user_id.exists' => 'Pengguna tidak valid',
                'tanggal_peminjaman.required' => 'Tanggal peminjaman harus diisi',
                'barangs.required' => 'Minimal satu barang harus dipilih',
            ]);
    
            if (empty($validatedData['user_id'])) {
                return response()->json([
                    'message' => 'Silakan pilih pengguna terlebih dahulu'
                ], 422);
            }
    
            if (empty($validatedData['tanggal_peminjaman'])) {
                return response()->json([
                    'message' => 'Tanggal peminjaman harus diisi'
                ], 422);
            }
    
            $peminjaman = Peminjaman::create([
                'user_id' => $validatedData['user_id'],
                'tanggal_peminjaman' => $validatedData['tanggal_peminjaman'],
                'status' => 'menunggu konfirmasi',
            ]);
    
            $errorMessages = [];
            foreach ($validatedData['barangs'] as $barangData) {
                $barang = Barang::findOrFail($barangData['barang_id']);
                
                if ($barangData['jumlah'] > $barang->stok) {
                    $errorMessages[] = "Stok barang {$barang->nama} tidak mencukupi. Tersedia: {$barang->stok}";
                    continue;
                }
    
                $barang->decrement('stok', $barangData['jumlah']);
    
                DetailPeminjaman::create([
                    'peminjaman_id' => $peminjaman->id,
                    'barang_id' => $barangData['barang_id'],
                    'jumlah' => $barangData['jumlah'],
                    'tanggal_pinjam' => $validatedData['tanggal_peminjaman'],
                    'status' => 'menunggu konfirmasi'
                ]);
            }
    
            if (!empty($errorMessages)) {
                DB::rollBack();
                return response()->json([
                    'message' => 'Beberapa barang tidak dapat dipinjam',
                    'errors' => $errorMessages
                ], 422);
            }
    
            DB::commit();
    
            return response()->json([
                'message' => 'Berhasil Meminjam Barang'
            ], 200);
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
    

    public function detail()
    {
        $title = 'Data Peminjaman';
        $details = Peminjaman::where('user_id', Auth::id())
            ->with(['detailPeminjamans.barang'])
            ->orderBy('created_at', 'desc')
            ->paginate(12); 

        return view('pages.peminjamanBarang.detailPeminjaman', compact('title', 'details'));
    }

    public function detailPeminjamanSpesifik($id)
    {
        $title = 'Detail Peminjaman Spesifik';
        $peminjaman = Peminjaman::with('detailPeminjamans.barang')
            ->where('user_id', Auth::id())
            ->findOrFail($id);
    
        return view('pages.peminjamanBarang.detailPeminjamanSpesifik', compact('title', 'peminjaman'));
    }

    public function cetak()
    {
        $peminjaman = Peminjaman::with(['user', 'detailPeminjamans.barang'])
            ->get();
        
        $pdf = Pdf::loadView('pages.pdf.cetak-peminjaman', [
            'peminjaman' => $peminjaman
        ]);
        return $pdf->stream('laporan-peminjaman-'.date('Y-m-d').'.pdf');
    }

    public function cetakBukti($id)
    {
        $peminjaman = Peminjaman::with(['user', 'detailPeminjamans.barang'])
            ->findOrFail($id);
        
        $kodePeminjaman = date('dmY', strtotime($peminjaman->tanggal_peminjaman)) . 
            str_pad($peminjaman->id, 4, '0', STR_PAD_LEFT);
        
        $qrCode = QrCode::create($kodePeminjaman)
            ->setSize(300)  
            ->setMargin(10)
            ->setEncoding(new Encoding('UTF-8'))
            ->setErrorCorrectionLevel(ErrorCorrectionLevel::High)
            ->setRoundBlockSizeMode(RoundBlockSizeMode::Margin);

        $writer = new PngWriter();
        $result = $writer->write($qrCode);
        
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
                
                if ($detail['jumlah_kembali'] > $detailPeminjaman->jumlah) {
                    throw ValidationException::withMessages([
                        'detail_peminjamans' => 'Jumlah kembali tidak valid.'
                    ]);
                }
                $barang = $detailPeminjaman->barang;
                $barang->stok += $detail['jumlah_kembali'];
                $barang->kondisi = $detail['kondisi'];
                $barang->save();

                $detailPeminjaman->update([
                    'tanggal_kembali' => now(),
                    'status' => $detail['jumlah_kembali'] == $detailPeminjaman->jumlah ? 'kembali' : 'dipinjam'
                ]);

                if ($detailPeminjaman->status != 'kembali') {
                    $semuaBarangKembali = false;
                }
            }

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
            $id = intval(substr($kodePeminjaman, -4));
            
            $detailPeminjaman = Peminjaman::findOrFail($id);
        
            if ($detailPeminjaman->masuk !== null) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Barang sudah dikembalikan sebelumnya.'
                ], 400);
            }
        
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
    public function log(Request $request)
    {
        $title = 'Logs Peminjaman';

        $tanggalPinjam = $request->input('tanggal_peminjaman');

        $query = Peminjaman::with('detailPeminjamans');

        if ($tanggalPinjam) {
            $query->whereDate('tanggal_peminjaman', $tanggalPinjam);
        }

        $logs = $query->orderBy('tanggal_peminjaman', 'desc')->paginate(10);

        foreach ($logs as $log) {
            $log->total_jumlah = $log->detailPeminjamans->isEmpty() ? 0 : $log->detailPeminjamans->sum('jumlah');
        }

        return view('pages.peminjamanBarang.logs', compact('title', 'logs'));
    }

    public function konfirmasiPeminjaman($id)
    {
        DB::beginTransaction();
        try {
            $peminjaman = Peminjaman::findOrFail($id);
            
            $peminjaman->update([
                'status' => 'dipinjam'
            ]);
    
            $peminjaman->detailPeminjamans()->update([
                'status' => 'dipinjam'
            ]);
    
            DB::commit();
    
            return response()->json([
                'message' => 'Peminjaman berhasil dikonfirmasi'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal mengkonfirmasi peminjaman: ' . $e->getMessage()
            ], 500);
        }
    }
    public function konfirmasiTolak($id)
    {
        $title = 'Form Tolak Peminjaman';
        $peminjaman = Peminjaman::findOrFail($id);
        return view('pages.PeminjamanBarang.konfirmasi-tolak', compact('title', 'peminjaman'));
    }
    
    public function tolakPeminjaman(Request $request, $id)
    {
        $request->validate([
            'keterangan' => 'required|string|max:255'
        ]);
    
        DB::beginTransaction();
        try {
            $peminjaman = Peminjaman::findOrFail($id);
            
            foreach ($peminjaman->detailPeminjamans as $detail) {
                $barang = Barang::findOrFail($detail->barang_id);
                $barang->increment('stok', $detail->jumlah);
            }
    
            $peminjaman->update([
                'status' => 'ditolak',
                'keterangan' => $request->keterangan
            ]);
    
            $peminjaman->detailPeminjamans()->update([
                'status' => 'ditolak'
            ]);
    
            DB::commit();
    
            session()->flash('status', 'success');
            session()->flash('message', 'Peminjaman berhasil ditolak');

            return redirect()->route('log.peminjaman');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menolak peminjaman: ' . $e->getMessage());
        }
    }
}