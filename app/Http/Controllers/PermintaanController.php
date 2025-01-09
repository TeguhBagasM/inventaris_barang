<?php

namespace App\Http\Controllers;

use App\Models\BarangKeluar;
use App\Models\Bhp;
use App\Models\DetailBarangKeluar;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PermintaanController extends Controller
{
    public function index()
    {
        $title = 'Permintaan Barang';
        $bhps = Bhp::where('stok', '>', 0)->get();
        return view('pages.permintaanBarang.index', compact('title', 'bhps'));
    }

    public function indexAdmin()
    {
        $title = 'Permintaan Barang';
        $bhps = Bhp::where('stok', '>', 0)->get();
        $users = User::whereIn('level', ['guru'])->get();
        return view('pages.permintaanBarang.admin-permintaan', compact('bhps', 'users', 'title'));
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,id',
                'tanggal_minta' => 'required|date',
                'keterangan' => 'nullable|string',
                'bhps' => 'required|array|min:1',
                'bhps.*.bhp_id' => 'required|exists:bhps,id',
                'bhps.*.jumlah' => 'required|integer|min:1'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $errors = [];
            foreach ($request->bhps as $item) {
                $bhp = Bhp::find($item['bhp_id']);
                if ($item['jumlah'] > $bhp->stok) {
                    $errors[] = "Stok {$bhp->nama} tidak mencukupi. Stok tersedia: {$bhp->stok}";
                }
            }

            if (!empty($errors)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi stok gagal',
                    'errors' => $errors
                ], 422);
            }

            DB::beginTransaction();

            $barangKeluar = BarangKeluar::create([
                'user_id' => $request->user_id,
                'tanggal_minta' => $request->tanggal_minta,
                'keterangan' => $request->keterangan,
                'status' => 'diajukan'
            ]);

            foreach ($request->bhps as $item) {
                DetailBarangKeluar::create([
                    'barang_keluar_id' => $barangKeluar->id,
                    'bhp_id' => $item['bhp_id'],
                    'jumlah' => $item['jumlah']
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Permintaan BHP berhasil diajukan'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat memproses permintaan'
            ], 500);
        }
    }
    public function mintaAdmin(Request $request)
    {
        DB::beginTransaction();
        try {
            $validatedData = $request->validate([
                'user_id' => 'required|exists:users,id',
                'tanggal_minta' => 'required|date',
                'keterangan' => 'nullable|string|max:255',
                'bhps' => 'required|array',
                'bhps.*.bhp_id' => 'required|exists:bhps,id',
                'bhps.*.jumlah' => [
                    'required', 
                    'integer', 
                    'min:1',
                    function ($attribute, $value, $fail) {
                        preg_match('/bhps\.(\d+)\.jumlah/', $attribute, $matches);
                        $index = $matches[1];
                        $bhpId = request('bhps')[$index]['bhp_id'];
    
                        $bhp = Bhp::find($bhpId);
    
                        if ($value > $bhp->stok) {
                            $fail("Stok BHP {$bhp->nama} tidak mencukupi. Tersedia: {$bhp->stok}");
                        }
                    }
                ],
            ], [
                'user_id.required' => 'Silakan pilih pengguna',
                'user_id.exists' => 'Pengguna tidak valid',
                'tanggal_minta.required' => 'Tanggal permintaan harus diisi',
                'bhps.required' => 'Minimal satu BHP harus dipilih',
                'keterangan.max' => 'Keterangan tidak boleh lebih dari 255 karakter',
            ]);
    
            $permintaan = BarangKeluar::create([
                'user_id' => $validatedData['user_id'],
                'tanggal_minta' => $validatedData['tanggal_minta'],
                'keterangan' => $validatedData['keterangan'] ?? null,
                'status' => 'diajukan',
            ]);
    
            $errorMessages = [];
            foreach ($validatedData['bhps'] as $bhpData) {
                $bhp = Bhp::findOrFail($bhpData['bhp_id']);
    
                if ($bhpData['jumlah'] > $bhp->stok) {
                    $errorMessages[] = "Stok BHP {$bhp->nama} tidak mencukupi. Tersedia: {$bhp->stok}";
                    continue;
                }
    
                $bhp->decrement('stok', $bhpData['jumlah']);
    
                DetailBarangKeluar::create([
                    'barang_keluar_id' => $permintaan->id,
                    'bhp_id' => $bhpData['bhp_id'],
                    'jumlah' => $bhpData['jumlah'],
                ]);
            }
    
            if (!empty($errorMessages)) {
                DB::rollBack();
                return response()->json([
                    'message' => 'Beberapa BHP tidak dapat diproses',
                    'errors' => $errorMessages
                ], 422);
            }
    
            DB::commit();
    
            return response()->json([
                'message' => 'Berhasil Meminta BHP'
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
    
    public function log(Request $request)
    {
        $title = 'Logs Permintaan';

        $tanggalMinta = $request->input('tanggal_minta');

        $query = BarangKeluar::with(['detailBarangKeluars', 'user']);

        if ($tanggalMinta) {
            $query->whereDate('tanggal_minta', $tanggalMinta);
        }

        $logs = $query->orderBy('tanggal_minta', 'desc')->paginate(10);

        foreach ($logs as $log) {
            $log->total_jumlah = $log->detailBarangKeluars->isEmpty() ? 0 : $log->detailBarangKeluars->sum('jumlah');
        }

        return view('pages.permintaanBarang.logs', compact('title', 'logs'));
    }
    public function konfirmasi($id)
    {
        try {
            DB::beginTransaction();
            
            $barangKeluar = BarangKeluar::with(['detailBarangKeluars.bhp' => function($query) {
                $query->lockForUpdate(); 
            }])->findOrFail($id);
            
            if ($barangKeluar->status !== 'diajukan') {
                return response()->json([
                    'status' => false,
                    'message' => 'Status permintaan tidak valid untuk dikonfirmasi'
                ], 422);
            }
    
            foreach ($barangKeluar->detailBarangKeluars as $detail) {
                $bhp = Bhp::lockForUpdate()->find($detail->bhp_id);
                
                if ($detail->jumlah > $bhp->stok) {
                    DB::rollBack();
                    return response()->json([
                        'status' => false,
                        'message' => "Stok {$bhp->nama} tidak mencukupi"
                    ], 422);
                }
            }
    
            foreach ($barangKeluar->detailBarangKeluars as $detail) {
                $bhp = Bhp::find($detail->bhp_id);
                $bhp->stok = $bhp->stok - $detail->jumlah;
                $bhp->save();
            }
    
            $barangKeluar->status = 'disetujui';
            $barangKeluar->save();
    
            DB::commit();
    
            return response()->json([
                'status' => true,
                'message' => 'Permintaan BHP berhasil dikonfirmasi'
            ]);
    
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error in konfirmasi barang keluar: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat mengkonfirmasi permintaan'
            ], 500);
        }
    }

    public function tolak(Request $request, $id)
    {
        $request->validate([
            'keterangan' => 'required|string|max:255'
        ]);
    
        DB::beginTransaction();
        try {
            $barangKeluar = BarangKeluar::with('detailBarangKeluars.bhp')->findOrFail($id);
    
            if ($barangKeluar->status !== 'diajukan') {
                return back()->with([
                    'status' => 'error',
                    'message' => 'Status permintaan tidak valid untuk ditolak'
                ]);
            }
    
            foreach ($barangKeluar->detailBarangKeluars as $detail) {
                $bhp = $detail->bhp;
                $bhp->increment('stok', $detail->jumlah);
            }
    
            $barangKeluar->update([
                'status' => 'ditolak',
                'keterangan' => $request->keterangan
            ]);
    
            DB::commit(); 
            session()->flash('status', 'success');
            session()->flash('message', 'Permintaan berhasil ditolak');
    
            return redirect()->route('log.permintaan');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menolak permintaan: ' . $e->getMessage()
            ]);
        }
    }

    public function konfirmasiTolak($id)
    {
        $title = 'Form Tolak Permintaan';
        $permintaan = BarangKeluar::findOrFail($id);
        return view('pages.permintaanBarang.konfirmasi-tolak', compact('title', 'permintaan'));
    }

    public function detailPermintaan()
    {
        $title = 'Data Permintaan';
        $details = BarangKeluar::where('user_id', Auth::id())
            ->with(['detailBarangKeluars.bhp'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);
    
        return view('pages.permintaanBarang.detailPermintaan', compact('title', 'details'));
    }
    
    public function detailSpesifik(BarangKeluar $barangKeluar)
    {
        $title = "Detail Spesifik Permintaan";
        if ($barangKeluar->user_id !== Auth::id()) {
            abort(403);
        }
    
        return view('pages.permintaanBarang.detailSpesifik', compact('barangKeluar', 'title'));
    }
    public function cetakPermintaan()
    {
        $barangKeluar = BarangKeluar::with(['user', 'detailBarangKeluars.bhp'])->get();
        
        if ($barangKeluar->isEmpty()) {
            abort(404, 'Data permintaan barang tidak ditemukan.');
        }

        $pdf = Pdf::loadView('pages.pdf.cetak-permintaan', [
            'barangKeluar' => $barangKeluar
        ]);
        
        return $pdf->stream('laporan-permintaan-'.date('Y-m-d').'.pdf');
    }

}
