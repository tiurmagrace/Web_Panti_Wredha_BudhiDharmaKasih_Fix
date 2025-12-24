<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Donasi;
use App\Models\Notifikasi;
use App\Models\AktivitasLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DonasiController extends Controller
{
    /**
     * Get All Donasi
     */
    public function index(Request $request)
    {
        $query = Donasi::with('user');

        if ($request->has('jenis') && $request->jenis) {
            $query->where('jenis', $request->jenis);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('donatur', 'like', "%{$search}%")
                  ->orWhere('detail', 'like', "%{$search}%");
            });
        }

        $query->orderBy('created_at', 'desc');
        $donasi = $query->get();

        return response()->json([
            'success' => true,
            'data' => $donasi
        ]);
    }

    /**
     * Get Donasi by User
     */
    public function myDonations(Request $request)
    {
        $donasi = Donasi::where('user_id', auth()->id())
                        ->orderBy('tanggal', 'desc')
                        ->get();

        return response()->json([
            'success' => true,
            'data' => $donasi
        ]);
    }

    /**
     * Get Single Donasi
     */
    public function show($id)
    {
        $donasi = Donasi::with('user')->find($id);

        if (!$donasi) {
            return response()->json([
                'success' => false,
                'message' => 'Data donasi tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $donasi
        ]);
    }

    /**
     * Create Donasi - Validasi minimal
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'donatur' => 'required|string|max:255',
            'jenis' => 'required|in:Barang,Tunai',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Donatur dan Jenis wajib diisi',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();
        $data['user_id'] = auth()->id();
        $data['status_verifikasi'] = $data['status_verifikasi'] ?? 'approved';
        $data['tanggal'] = $data['tanggal'] ?? now()->toDateString();

        $donasi = Donasi::create($data);

        // Log aktivitas
        if (auth()->check()) {
            AktivitasLog::create([
                'user_id' => auth()->id(),
                'kategori' => 'Donasi',
                'text' => "Donasi masuk dari {$donasi->donatur} ({$donasi->jenis})",
                'time' => now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Donasi berhasil disimpan',
            'data' => $donasi
        ], 201);
    }

    /**
     * Update Donasi
     */
    public function update(Request $request, $id)
    {
        $donasi = Donasi::find($id);

        if (!$donasi) {
            return response()->json([
                'success' => false,
                'message' => 'Data donasi tidak ditemukan'
            ], 404);
        }

        $donasi->update($request->all());

        if (auth()->check()) {
            AktivitasLog::create([
                'user_id' => auth()->id(),
                'kategori' => 'Donasi',
                'text' => "Mengupdate donasi dari {$donasi->donatur}",
                'time' => now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data donasi berhasil diupdate',
            'data' => $donasi
        ]);
    }

    /**
     * Delete Donasi
     */
    public function destroy($id)
    {
        $donasi = Donasi::find($id);

        if (!$donasi) {
            return response()->json([
                'success' => false,
                'message' => 'Data donasi tidak ditemukan'
            ], 404);
        }

        $donatur = $donasi->donatur;
        $donasi->delete();

        if (auth()->check()) {
            AktivitasLog::create([
                'user_id' => auth()->id(),
                'kategori' => 'Donasi',
                'text' => "Menghapus donasi dari {$donatur}",
                'time' => now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data donasi berhasil dihapus'
        ]);
    }

    /**
     * Get Statistics
     */
    public function statistics()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'total_tunai' => Donasi::where('jenis', 'Tunai')->count(),
                'total_barang' => Donasi::where('jenis', 'Barang')->count(),
            ]
        ]);
    }
}
