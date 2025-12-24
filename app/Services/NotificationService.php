<?php
/**
 * =================================================================
 * FILE: app/Services/NotificationService.php
 * =================================================================
 */

namespace App\Services;

use App\Models\Notifikasi;
use App\Models\User;

class NotificationService
{
    /**
     * Create notification untuk user tertentu
     */
    public static function createForUser(int $userId, string $type, string $title, string $text, array $metadata = null)
    {
        return Notifikasi::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'text' => $text,
            'date' => now()->toDateString(),
            'status' => 'unread',
            'metadata' => $metadata,
        ]);
    }

    /**
     * Create notification untuk semua admin
     */
    public static function notifyAllAdmins(string $type, string $title, string $text, array $metadata = null)
    {
        $admins = User::where('role', 'admin')->get();
        
        foreach ($admins as $admin) {
            self::createForUser($admin->id, $type, $title, $text, $metadata);
        }
    }

    /**
     * Create notification global (tanpa user_id)
     */
    public static function createGlobal(string $type, string $title, string $text, array $metadata = null)
    {
        return Notifikasi::create([
            'user_id' => null,
            'type' => $type,
            'title' => $title,
            'text' => $text,
            'date' => now()->toDateString(),
            'status' => 'unread',
            'metadata' => $metadata,
        ]);
    }

    /**
     * Notify stok menipis
     */
    public static function notifyLowStock($barang)
    {
        self::notifyAllAdmins(
            'stok',
            'Stok Menipis',
            "Stok {$barang->nama} sudah menipis ({$barang->sisa_stok} {$barang->satuan})",
            ['barang_id' => $barang->id]
        );
    }

    /**
     * Notify donasi baru
     */
    public static function notifyNewDonation($donasi)
    {
        self::notifyAllAdmins(
            'donasi',
            'Donasi Baru Masuk',
            "Donasi {$donasi->jenis} dari {$donasi->donatur} menunggu verifikasi",
            ['donasi_id' => $donasi->id]
        );
    }

    /**
     * Notify donatur (donasi approved)
     */
    public static function notifyDonationApproved($donasi)
    {
        if ($donasi->user_id) {
            self::createForUser(
                $donasi->user_id,
                'donasi',
                'Donasi Diterima',
                'Terima kasih! Donasi Anda telah diterima dan diverifikasi',
                ['donasi_id' => $donasi->id]
            );
        }
    }
}
