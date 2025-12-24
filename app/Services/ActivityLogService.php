<?php
/**
 * =================================================================
 * FILE: app/Services/ActivityLogService.php
 * =================================================================
 */

namespace App\Services;

use App\Models\AktivitasLog;

class ActivityLogService
{
    /**
     * Log aktivitas
     */
    public static function log(string $kategori, string $text, int $userId = null)
    {
        return AktivitasLog::create([
            'user_id' => $userId ?? auth()->id(),
            'kategori' => $kategori,
            'text' => $text,
            'time' => now(),
        ]);
    }

    /**
     * Log aktivitas penghuni
     */
    public static function logPenghuni(string $action, $penghuni)
    {
        $text = match($action) {
            'create' => "Menambahkan data penghuni: {$penghuni->nama}",
            'update' => "Mengupdate data penghuni: {$penghuni->nama}",
            'delete' => "Menghapus data penghuni: {$penghuni->nama}",
            default => "Aktivitas penghuni: {$penghuni->nama}",
        };

        return self::log('Penghuni', $text);
    }

    /**
     * Log aktivitas donasi
     */
    public static function logDonasi(string $action, $donasi)
    {
        $text = match($action) {
            'create' => "Menambahkan donasi dari {$donasi->donatur}",
            'update' => "Mengupdate donasi dari {$donasi->donatur}",
            'delete' => "Menghapus donasi dari {$donasi->donatur}",
            'approve' => "Menyetujui donasi dari {$donasi->donatur}",
            'reject' => "Menolak donasi dari {$donasi->donatur}",
            default => "Aktivitas donasi: {$donasi->donatur}",
        };

        return self::log('Donasi', $text);
    }

    /**
     * Log aktivitas barang
     */
    public static function logBarang(string $action, $barang, array $extra = [])
    {
        $text = match($action) {
            'create' => "Menambahkan stok barang: {$barang->nama}",
            'update' => "Mengupdate stok barang: {$barang->nama}",
            'delete' => "Menghapus barang: {$barang->nama}",
            'ambil' => "Mengambil {$extra['jumlah']} {$barang->satuan} {$barang->nama} untuk {$extra['keperluan']}",
            default => "Aktivitas barang: {$barang->nama}",
        };

        return self::log('Barang', $text);
    }
}
