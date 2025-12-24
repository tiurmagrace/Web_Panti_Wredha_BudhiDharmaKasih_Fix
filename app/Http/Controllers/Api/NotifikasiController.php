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
 * NOTIFIKASI CONTROLLER
 */
class NotifikasiController extends Controller
{
    public function index(Request $request)
    {
        $query = Notifikasi::query();

        // Jika user adalah donatur, hanya tampilkan notifikasi mereka
        if (auth()->check() && auth()->user()->isDonatur()) {
            $query->where('user_id', auth()->id());
        }

        $notifikasi = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $notifikasi
        ]);
    }

    public function unreadCount()
    {
        $query = Notifikasi::where('status', 'unread');

        if (auth()->check() && auth()->user()->isDonatur()) {
            $query->where('user_id', auth()->id());
        }

        return response()->json([
            'success' => true,
            'unread_count' => $query->count()
        ]);
    }

    public function markAsRead($id)
    {
        $notifikasi = Notifikasi::find($id);

        if (!$notifikasi) {
            return response()->json([
                'success' => false,
                'message' => 'Notifikasi tidak ditemukan'
            ], 404);
        }

        // Cek authorization
        if ($notifikasi->user_id && $notifikasi->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $notifikasi->update(['status' => 'read']);

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi ditandai sebagai dibaca'
        ]);
    }

    public function markAllAsRead()
    {
        $query = Notifikasi::unread();

        if (auth()->user()->isDonatur()) {
            $query->where('user_id', auth()->id());
        }

        $query->update(['status' => 'read']);

        return response()->json([
            'success' => true,
            'message' => 'Semua notifikasi ditandai sebagai dibaca'
        ]);
    }

    public function destroy($id)
    {
        $notifikasi = Notifikasi::find($id);

        if (!$notifikasi) {
            return response()->json([
                'success' => false,
                'message' => 'Notifikasi tidak ditemukan'
            ], 404);
        }

        $notifikasi->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi berhasil dihapus'
        ]);
    }
}
