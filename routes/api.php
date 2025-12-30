<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PenghuniController;
use App\Http\Controllers\Api\DonasiController;
use App\Http\Controllers\Api\BarangController;
use App\Http\Controllers\Api\FeedbackController;
use App\Http\Controllers\Api\NotifikasiController;
use App\Http\Controllers\Api\AktivitasLogController;
use App\Http\Controllers\Api\LaporanDonasiController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES (Tanpa Auth)
|--------------------------------------------------------------------------
*/

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/verify-code', [AuthController::class, 'verifyCode']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
});

// Feedback public (bisa dari guest)
Route::post('/feedback', [FeedbackController::class, 'store']);

// Public Donasi Routes (untuk mobile tanpa login)
Route::prefix('donasi')->group(function () {
    Route::get('/public', [DonasiController::class, 'publicList']);
    Route::get('/public/statistics', [DonasiController::class, 'publicStatistics']);
});

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES (Perlu Auth)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {
    
    // Auth
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/profile', [AuthController::class, 'profile']);

    /*
    |----------------------------------------------------------------------
    | PENGHUNI ROUTES (Admin Only)
    |----------------------------------------------------------------------
    */
    Route::prefix('penghuni')->middleware('admin')->group(function () {
        Route::get('/', [PenghuniController::class, 'index']);
        Route::get('/statistics', [PenghuniController::class, 'statistics']);
        Route::get('/{id}', [PenghuniController::class, 'show']);
        Route::post('/', [PenghuniController::class, 'store']);
        Route::put('/{id}', [PenghuniController::class, 'update']);
        Route::delete('/{id}', [PenghuniController::class, 'destroy']);
    });

    /*
    |----------------------------------------------------------------------
    | DONASI ROUTES
    |----------------------------------------------------------------------
    */
    Route::prefix('donasi')->group(function () {
        // User authenticated - bisa submit donasi dan lihat riwayat
        Route::get('/my-donations', [DonasiController::class, 'myDonations']);
        Route::post('/', [DonasiController::class, 'store']);
        
        // Admin only
        Route::middleware('admin')->group(function () {
            Route::get('/', [DonasiController::class, 'index']);
            Route::get('/admin/pending', [DonasiController::class, 'pending']);
            Route::get('/admin/statistics', [DonasiController::class, 'statistics']);
            Route::post('/admin/store', [DonasiController::class, 'storeByAdmin']);
            Route::patch('/{id}/verify', [DonasiController::class, 'verify']);
            Route::post('/{id}/thank-you', [DonasiController::class, 'sendThankYou']);
            Route::put('/{id}', [DonasiController::class, 'update']);
            Route::delete('/{id}', [DonasiController::class, 'destroy']);
        });
        
        // Route show harus di bawah agar tidak menangkap route lain
        Route::get('/{id}', [DonasiController::class, 'show']);
    });

    /*
    |----------------------------------------------------------------------
    | BARANG/STOK ROUTES (Admin Only)
    |----------------------------------------------------------------------
    */
    Route::prefix('barang')->middleware('admin')->group(function () {
        Route::get('/', [BarangController::class, 'index']);
        Route::get('/statistics', [BarangController::class, 'statistics']);
        Route::post('/check-notifications', [BarangController::class, 'checkNotifications']);
        Route::get('/{id}', [BarangController::class, 'show']);
        Route::post('/', [BarangController::class, 'store']);
        Route::put('/{id}', [BarangController::class, 'update']);
        Route::delete('/{id}', [BarangController::class, 'destroy']);
        
        // Pengambilan Stok
        Route::post('/ambil-stok', [BarangController::class, 'ambilStok']);
        Route::get('/riwayat-pengambilan/{barangId?}', [BarangController::class, 'riwayatPengambilan']);
    });

    /*
    |----------------------------------------------------------------------
    | FEEDBACK ROUTES
    |----------------------------------------------------------------------
    */
    Route::prefix('feedback')->middleware('admin')->group(function () {
        Route::get('/', [FeedbackController::class, 'index']);
        Route::patch('/{id}/mark-as-read', [FeedbackController::class, 'markAsRead']);
        Route::delete('/{id}', [FeedbackController::class, 'destroy']);
    });

    /*
    |----------------------------------------------------------------------
    | NOTIFIKASI ROUTES
    |----------------------------------------------------------------------
    */
    Route::prefix('notifikasi')->group(function () {
        Route::get('/', [NotifikasiController::class, 'index']);
        Route::get('/unread-count', [NotifikasiController::class, 'unreadCount']);
        Route::patch('/{id}/mark-as-read', [NotifikasiController::class, 'markAsRead']);
        Route::patch('/mark-all-as-read', [NotifikasiController::class, 'markAllAsRead']);
        Route::delete('/{id}', [NotifikasiController::class, 'destroy']);
    });

    /*
    |----------------------------------------------------------------------
    | AKTIVITAS LOG ROUTES (Admin Only)
    |----------------------------------------------------------------------
    */
    Route::prefix('aktivitas-log')->middleware('admin')->group(function () {
        Route::get('/', [AktivitasLogController::class, 'index']);
    });

    /*
    |----------------------------------------------------------------------
    | LAPORAN DONASI ROUTES (Admin Only)
    |----------------------------------------------------------------------
    */
    Route::prefix('laporan-donasi')->middleware('admin')->group(function () {
        Route::get('/', [LaporanDonasiController::class, 'index']);
        Route::post('/', [LaporanDonasiController::class, 'store']);
        Route::post('/{id}/send', [LaporanDonasiController::class, 'send']);
        Route::delete('/{id}', [LaporanDonasiController::class, 'destroy']);
    });
});

/*
|--------------------------------------------------------------------------
| FALLBACK ROUTE
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    return response()->json([
        'success' => false,
        'message' => 'Endpoint tidak ditemukan'
    ], 404);
});