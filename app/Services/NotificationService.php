<?php

namespace App\Services;

use App\Models\Notifikasi;
use App\Models\Barang;
use App\Models\Donasi;
use App\Models\User;

class NotificationService
{
    /**
     * Kirim notifikasi ke admin
     */
    public static function notifyAdmin(string $type, string $title, string $text, array $metadata = [])
    {
        return Notifikasi::create([
            'user_id' => null, // null = untuk admin
            'type' => $type,
            'title' => $title,
            'text' => $text,
            'date' => now(),
            'status' => 'unread',
            'metadata' => $metadata,
        ]);
    }

    /**
     * Kirim notifikasi ke user tertentu
     */
    public static function notifyUser(int $userId, string $type, string $title, string $text, array $metadata = [])
    {
        return Notifikasi::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'text' => $text,
            'date' => now(),
            'status' => 'unread',
            'metadata' => $metadata,
        ]);
    }

    /**
     * Notifikasi donasi baru masuk (untuk admin)
     */
    public static function donasiMasuk(Donasi $donasi)
    {
        $jenisText = $donasi->jenis === 'Tunai' ? "sebesar {$donasi->jumlah}" : "berupa {$donasi->detail}";
        
        return self::notifyAdmin(
            'donasi_masuk',
            'Donasi Baru Masuk',
            "Donasi {$donasi->jenis} dari {$donasi->donatur} {$jenisText} menunggu verifikasi.",
            ['donasi_id' => $donasi->id]
        );
    }

    /**
     * Notifikasi donasi diterima (untuk user/donatur)
     */
    public static function donasiDiterima(Donasi $donasi)
    {
        if (!$donasi->user_id) return null;

        return self::notifyUser(
            $donasi->user_id,
            'donasi_diterima',
            'Donasi Anda Telah Diterima',
            "Terima kasih! Donasi {$donasi->jenis} Anda telah diterima dan diverifikasi oleh panti. Semoga menjadi berkah bagi Anda.",
            ['donasi_id' => $donasi->id]
        );
    }

    /**
     * Notifikasi donasi ditolak (untuk user/donatur)
     */
    public static function donasiDitolak(Donasi $donasi, string $alasan = null)
    {
        if (!$donasi->user_id) return null;

        $text = "Mohon maaf, donasi {$donasi->jenis} Anda tidak dapat diverifikasi.";
        if ($alasan) {
            $text .= " Alasan: {$alasan}";
        }

        return self::notifyUser(
            $donasi->user_id,
            'donasi_ditolak',
            'Status Donasi',
            $text,
            ['donasi_id' => $donasi->id]
        );
    }

    /**
     * Notifikasi ucapan terima kasih (untuk user/donatur)
     */
    public static function ucapanTerimakasih(Donasi $donasi, string $pesan = null)
    {
        if (!$donasi->user_id) return null;

        $text = $pesan ?? "Panti Jompo mengucapkan terima kasih atas donasi {$donasi->jenis} yang Anda berikan. Donasi Anda sangat berarti bagi para penghuni panti.";

        return self::notifyUser(
            $donasi->user_id,
            'ucapan_terimakasih',
            'Ucapan Terima Kasih',
            $text,
            ['donasi_id' => $donasi->id]
        );
    }

    /**
     * Notifikasi stok menipis (untuk admin)
     */
    public static function stokMenipis(Barang $barang)
    {
        // Cek apakah sudah ada notifikasi serupa dalam 24 jam terakhir
        $existing = Notifikasi::where('type', 'stok_menipis')
            ->where('metadata->barang_id', $barang->id)
            ->where('created_at', '>=', now()->subDay())
            ->exists();

        if ($existing) return null;

        return self::notifyAdmin(
            'stok_menipis',
            'Stok Barang Menipis',
            "Stok {$barang->nama} tinggal {$barang->sisa_stok} {$barang->satuan}. Segera lakukan pengadaan.",
            ['barang_id' => $barang->id, 'sisa_stok' => $barang->sisa_stok]
        );
    }

    /**
     * Notifikasi barang hampir kadaluarsa (untuk admin)
     */
    public static function barangHampirKadaluarsa(Barang $barang)
    {
        // Cek apakah sudah ada notifikasi serupa dalam 24 jam terakhir
        $existing = Notifikasi::where('type', 'hampir_kadaluarsa')
            ->where('metadata->barang_id', $barang->id)
            ->where('created_at', '>=', now()->subDay())
            ->exists();

        if ($existing) return null;

        $sisaHari = now()->diffInDays($barang->expired);
        $expiredDate = $barang->expired->format('d M Y');

        return self::notifyAdmin(
            'hampir_kadaluarsa',
            'Barang Hampir Kadaluarsa',
            "{$barang->nama} akan kadaluarsa dalam {$sisaHari} hari ({$expiredDate}). Segera gunakan atau distribusikan.",
            ['barang_id' => $barang->id, 'expired' => $barang->expired->toDateString()]
        );
    }

    /**
     * Notifikasi barang sudah kadaluarsa (untuk admin)
     */
    public static function barangKadaluarsa(Barang $barang)
    {
        $existing = Notifikasi::where('type', 'kadaluarsa')
            ->where('metadata->barang_id', $barang->id)
            ->where('created_at', '>=', now()->subDay())
            ->exists();

        if ($existing) return null;

        return self::notifyAdmin(
            'kadaluarsa',
            'Barang Kadaluarsa',
            "{$barang->nama} sudah melewati tanggal kadaluarsa ({$barang->expired->format('d M Y')}). Segera buang atau pisahkan dari stok.",
            ['barang_id' => $barang->id]
        );
    }

    /**
     * Cek semua barang dan kirim notifikasi yang diperlukan
     */
    public static function checkBarangNotifications()
    {
        $notifications = [];

        // Cek stok menipis (sisa stok <= 20% dari stok awal atau <= 5)
        $stokMenipis = Barang::whereRaw('sisa_stok <= GREATEST(brg_masuk * 0.2, 5)')
            ->where('sisa_stok', '>', 0)
            ->get();

        foreach ($stokMenipis as $barang) {
            $notif = self::stokMenipis($barang);
            if ($notif) $notifications[] = $notif;
        }

        // Cek hampir kadaluarsa (dalam 30 hari)
        $hampirKadaluarsa = Barang::whereNotNull('expired')
            ->whereDate('expired', '>', now())
            ->whereDate('expired', '<=', now()->addDays(30))
            ->where('sisa_stok', '>', 0)
            ->get();

        foreach ($hampirKadaluarsa as $barang) {
            $notif = self::barangHampirKadaluarsa($barang);
            if ($notif) $notifications[] = $notif;
        }

        // Cek sudah kadaluarsa
        $kadaluarsa = Barang::whereNotNull('expired')
            ->whereDate('expired', '<', now())
            ->where('sisa_stok', '>', 0)
            ->get();

        foreach ($kadaluarsa as $barang) {
            $notif = self::barangKadaluarsa($barang);
            if ($notif) $notifications[] = $notif;
        }

        return $notifications;
    }
}
