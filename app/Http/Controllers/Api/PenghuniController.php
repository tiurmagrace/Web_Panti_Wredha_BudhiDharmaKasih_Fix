<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Penghuni;
use App\Models\AktivitasLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PenghuniController extends Controller
{
    /**
     * Get All Penghuni
     */
    public function index(Request $request)
    {
        $query = Penghuni::query();

        if ($request->has('paviliun') && $request->paviliun) {
            $query->where('paviliun', $request->paviliun);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('kota', 'like', "%{$search}%");
            });
        }

        $query->orderBy('created_at', 'desc');
        $penghuni = $query->get();

        return response()->json([
            'success' => true,
            'data' => $penghuni
        ]);
    }

    /**
     * Get Single Penghuni
     */
    public function show($id)
    {
        $penghuni = Penghuni::find($id);

        if (!$penghuni) {
            return response()->json([
                'success' => false,
                'message' => 'Data penghuni tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $penghuni
        ]);
    }

    /**
     * Create Penghuni - Validasi minimal
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Nama wajib diisi',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = $request->all();
            
            // Bersihkan data - hapus field kosong dan konversi tipe data
            $cleanData = [];
            foreach ($data as $key => $value) {
                if ($value !== null && $value !== '') {
                    if ($key === 'usia') {
                        $cleanData[$key] = is_numeric($value) ? (int) $value : null;
                    } else {
                        $cleanData[$key] = $value;
                    }
                }
            }
            
            $penghuni = Penghuni::create($cleanData);

            // Log aktivitas
            if (auth()->check()) {
                AktivitasLog::create([
                    'user_id' => auth()->id(),
                    'kategori' => 'Penghuni',
                    'text' => "Menambahkan data penghuni: {$penghuni->nama}",
                    'time' => now(),
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Data penghuni berhasil ditambahkan',
                'data' => $penghuni
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update Penghuni
     */
    public function update(Request $request, $id)
    {
        $penghuni = Penghuni::find($id);

        if (!$penghuni) {
            return response()->json([
                'success' => false,
                'message' => 'Data penghuni tidak ditemukan'
            ], 404);
        }

        $penghuni->update($request->all());

        if (auth()->check()) {
            AktivitasLog::create([
                'user_id' => auth()->id(),
                'kategori' => 'Penghuni',
                'text' => "Mengupdate data penghuni: {$penghuni->nama}",
                'time' => now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data penghuni berhasil diupdate',
            'data' => $penghuni
        ]);
    }

    /**
     * Delete Penghuni
     */
    public function destroy($id)
    {
        $penghuni = Penghuni::find($id);

        if (!$penghuni) {
            return response()->json([
                'success' => false,
                'message' => 'Data penghuni tidak ditemukan'
            ], 404);
        }

        $nama = $penghuni->nama;
        $penghuni->delete();

        if (auth()->check()) {
            AktivitasLog::create([
                'user_id' => auth()->id(),
                'kategori' => 'Penghuni',
                'text' => "Menghapus data penghuni: {$nama}",
                'time' => now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data penghuni berhasil dihapus'
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
                'total' => Penghuni::count(),
            ]
        ]);
    }
}
