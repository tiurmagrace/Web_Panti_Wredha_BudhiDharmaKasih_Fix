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
 * AKTIVITAS LOG CONTROLLER
 */
class AktivitasLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AktivitasLog::with('user');

        if ($request->has('search') && $request->search) {
            $query->where('text', 'like', "%{$request->search}%");
        }

        $logs = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $logs
        ]);
    }
}
