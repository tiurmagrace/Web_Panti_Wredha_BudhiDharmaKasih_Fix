<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\Notifikasi;
use App\Models\AktivitasLog;
use App\Models\LaporanDonasi;
use App\Models\Donasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

/**
 * LAPORAN DONASI CONTROLLER
 */
class LaporanDonasiController extends Controller
{
    public function index(Request $request)
    {
        $query = LaporanDonasi::with('donasi');

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $laporan = $query->orderBy('created_at', 'desc')->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $laporan
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'donasi_id' => 'required|exists:donasi,id',
            'email_donatur' => 'required|email',
            'isi_laporan' => 'required|string',
            'bukti_terima' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->except('bukti_terima');
        $data['status'] = 'draft';

        // Handle bukti upload
        if ($request->hasFile('bukti_terima')) {
            $path = $request->file('bukti_terima')->store('laporan', 'public');
            $data['bukti_terima'] = $path;
        }

        $laporan = LaporanDonasi::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil dibuat',
            'data' => $laporan
        ], 201);
    }

    public function send($id)
    {
        $laporan = LaporanDonasi::with('donasi')->find($id);

        if (!$laporan) {
            return response()->json([
                'success' => false,
                'message' => 'Laporan tidak ditemukan'
            ], 404);
        }

        // TODO: Kirim email laporan ke donatur
        // Mail::to($laporan->email_donatur)->send(new LaporanDonasiMail($laporan));

        $laporan->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        // Log aktivitas
        AktivitasLog::create([
            'user_id' => auth()->id(),
            'kategori' => 'Donasi',
            'text' => "Mengirim laporan donasi ke {$laporan->email_donatur}",
            'time' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil dikirim'
        ]);
    }

    public function destroy($id)
    {
        $laporan = LaporanDonasi::find($id);

        if (!$laporan) {
            return response()->json([
                'success' => false,
                'message' => 'Laporan tidak ditemukan'
            ], 404);
        }

        $laporan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil dihapus'
        ]);
    }
}