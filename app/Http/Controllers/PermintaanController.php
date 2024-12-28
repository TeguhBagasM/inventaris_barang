<?php

namespace App\Http\Controllers;

use App\Models\BarangKeluar;
use App\Models\Bhp;
use App\Models\DetailBarangKeluar;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PermintaanController extends Controller
{
    public function index()
    {
        $title = 'Permintaan Barang';
        $bhps = Bhp::where('stok', '>', 0)->get();
        return view('pages.PermintaanBarang.index', compact('title', 'bhps'));
    }

    public function indexAdmin()
    {
        $title = 'Permintaan Barang';
        $bhps = Bhp::where('stok', '>', 0)->get();
        $users = User::whereIn('level', ['guru'])->get();
        return view('pages.PermintaanBarang.admin-permintaan', compact('bhps', 'users', 'title'));
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
    public function log(Request $request)
    {
        $title = 'Logs Permintaan BHP';

        $tanggalMinta = $request->input('tanggal_minta');

        $query = BarangKeluar::with(['detailBarangKeluars', 'user']);

        if ($tanggalMinta) {
            $query->whereDate('tanggal_minta', $tanggalMinta);
        }

        $logs = $query->orderBy('tanggal_minta', 'desc')->paginate(10);

        foreach ($logs as $log) {
            $log->total_jumlah = $log->detailBarangKeluars->isEmpty() ? 0 : $log->detailBarangKeluars->sum('jumlah');
        }

        return view('pages.PermintaanBarang.logs', compact('title', 'logs'));
    }
    public function konfirmasi($id)
    {
        try {
            DB::beginTransaction();
            
            $barangKeluar = BarangKeluar::with('detailBarangKeluars.bhp')->findOrFail($id);
            
            if ($barangKeluar->status !== 'diajukan') {
                return response()->json([
                    'status' => false,
                    'message' => 'Status permintaan tidak valid untuk dikonfirmasi'
                ], 422);
            }

            foreach ($barangKeluar->detailBarangKeluars as $detail) {
                if ($detail->jumlah > $detail->bhp->stok) {
                    return response()->json([
                        'status' => false,
                        'message' => "Stok {$detail->bhp->nama} tidak mencukupi"
                    ], 422);
                }
            }

            foreach ($barangKeluar->detailBarangKeluars as $detail) {
                $bhp = $detail->bhp;
                $bhp->stok -= $detail->jumlah;
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
            $barangKeluar = BarangKeluar::findOrFail($id);

            if ($barangKeluar->status !== 'diajukan') {
                return back()->with([
                    'status' => 'error',
                    'message' => 'Status permintaan tidak valid untuk ditolak'
                ]);
            }

            $barangKeluar->status = 'ditolak';
            $barangKeluar->keterangan = $request->keterangan;
            $barangKeluar->save();

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
        return view('pages.PermintaanBarang.konfirmasi-tolak', compact('title', 'permintaan'));
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
        // Memastikan user hanya bisa melihat permintaannya sendiri
        if ($barangKeluar->user_id !== Auth::id()) {
            abort(403);
        }
    
        return view('pages.permintaanBarang.detailSpesifik', compact('barangKeluar', 'title'));
    }
}
