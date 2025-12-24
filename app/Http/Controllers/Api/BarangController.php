<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\PengambilanStok;
use App\Models\AktivitasLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BarangController extends Controller
{
    /**
     * Get All Barang
     */
    public function index(Request $request)
    {
        $query = Barang::query();

        if ($request->has('kategori') && $request->kategori) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%");
            });
        }

        $query->orderBy('created_at', 'desc');
        $barang = $query->get();

        return response()->json([
            'success' => true,
            'data' => $barang
        ]);
    }

    /**
     * Get Single Barang
     */
    public function show($id)
    {
        $barang = Barang::find($id);

        if (!$barang) {
            return response()->json([
                'success' => false,
                'message' => 'Data barang tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $barang
        ]);
    }

    /**
     * Create Barang - Validasi minimal
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Nama barang wajib diisi',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();
        $data['brg_masuk'] = $data['brg_masuk'] ?? 0;
        $data['sisa_stok'] = $data['sisa_stok'] ?? $data['brg_masuk'];
        $data['tgl_masuk'] = $data['tgl_masuk'] ?? now()->toDateString();

        $barang = Barang::create($data);

        if (auth()->check()) {
            AktivitasLog::create([
                'user_id' => auth()->id(),
                'kategori' => 'Barang',
                'text' => "Menambahkan stok barang: {$barang->nama}",
                'time' => now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data barang berhasil ditambahkan',
            'data' => $barang
        ], 201);
    }

    /**
     * Update Barang
     */
    public function update(Request $request, $id)
    {
        $barang = Barang::find($id);

        if (!$barang) {
            return response()->json([
                'success' => false,
                'message' => 'Data barang tidak ditemukan'
            ], 404);
        }

        $barang->update($request->all());

        if (auth()->check()) {
            AktivitasLog::create([
                'user_id' => auth()->id(),
                'kategori' => 'Barang',
                'text' => "Mengupdate stok barang: {$barang->nama}",
                'time' => now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data barang berhasil diupdate',
            'data' => $barang
        ]);
    }

    /**
     * Delete Barang
     */
    public function destroy($id)
    {
        $barang = Barang::find($id);

        if (!$barang) {
            return response()->json([
                'success' => false,
                'message' => 'Data barang tidak ditemukan'
            ], 404);
        }

        $nama = $barang->nama;
        $barang->delete();

        if (auth()->check()) {
            AktivitasLog::create([
                'user_id' => auth()->id(),
                'kategori' => 'Barang',
                'text' => "Menghapus barang: {$nama}",
                'time' => now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data barang berhasil dihapus'
        ]);
    }

    /**
     * Ambil Stok
     */
    public function ambilStok(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'barang_id' => 'required|exists:barang,id',
            'jumlah' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $barang = Barang::find($request->barang_id);

        if ($barang->sisa_stok < $request->jumlah) {
            return response()->json([
                'success' => false,
                'message' => 'Stok tidak mencukupi'
            ], 400);
        }

        $pengambilan = PengambilanStok::create([
            'barang_id' => $request->barang_id,
            'jumlah' => $request->jumlah,
            'tanggal' => $request->tanggal ?? now()->toDateString(),
            'keperluan' => $request->keperluan ?? '-',
            'petugas' => $request->petugas ?? '-',
        ]);

        $barang->decrement('sisa_stok', $request->jumlah);

        if (auth()->check()) {
            AktivitasLog::create([
                'user_id' => auth()->id(),
                'kategori' => 'Barang',
                'text' => "Mengambil {$request->jumlah} {$barang->satuan} {$barang->nama}",
                'time' => now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Stok berhasil diambil',
            'data' => [
                'pengambilan' => $pengambilan,
                'sisa_stok' => $barang->fresh()->sisa_stok
            ]
        ], 201);
    }

    /**
     * Get Riwayat Pengambilan
     */
    public function riwayatPengambilan(Request $request, $barangId = null)
    {
        $query = PengambilanStok::with('barang');

        if ($barangId) {
            $query->where('barang_id', $barangId);
        }

        $riwayat = $query->orderBy('tanggal', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $riwayat
        ]);
    }

    /**
     * Get Statistics
     */
    public function statistics()
    {
        $total = Barang::count();
        $stokMenipis = Barang::where('sisa_stok', '<', 5)->count();
        $hampirExpired = Barang::whereNotNull('expired')
            ->whereDate('expired', '<=', now()->addDays(30))
            ->whereDate('expired', '>=', now())
            ->count();

        return response()->json([
            'success' => true,
            'data' => [
                'total' => $total,
                'stok_menipis' => $stokMenipis,
                'hampir_expired' => $hampirExpired,
            ]
        ]);
    }
}
