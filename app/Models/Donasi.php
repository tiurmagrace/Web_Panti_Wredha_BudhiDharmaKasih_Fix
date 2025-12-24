<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Donasi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'donasi';

    protected $fillable = [
        'user_id',
        'donatur',
        'jenis',
        'detail',
        'jumlah',
        'tanggal',
        'status',
        'petugas',
        'bukti',
        'status_verifikasi',
        'catatan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function laporan()
    {
        return $this->hasMany(LaporanDonasi::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status_verifikasi', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status_verifikasi', 'approved');
    }

    public function scopeByJenis($query, $jenis)
    {
        return $query->where('jenis', $jenis);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByPetugas($query, $petugas)
    {
        return $query->where('petugas', $petugas);
    }

    public function scopeByBulan($query, $bulan)
    {
        return $query->whereMonth('tanggal', $bulan);
    }

    public function scopeByTahun($query, $tahun)
    {
        return $query->whereYear('tanggal', $tahun);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('donatur', 'like', "%{$search}%")
              ->orWhere('jenis', 'like', "%{$search}%")
              ->orWhere('petugas', 'like', "%{$search}%");
        });
    }

    // Helper untuk hitung total donasi tunai
    public static function getTotalTunai($bulan = null, $tahun = null)
    {
        $query = self::where('jenis', 'Tunai')->approved();
        
        if ($bulan) {
            $query->whereMonth('tanggal', $bulan);
        }
        if ($tahun) {
            $query->whereYear('tanggal', $tahun);
        }

        return $query->sum(\DB::raw("CAST(REPLACE(REPLACE(jumlah, 'Rp ', ''), '.', '') AS UNSIGNED)"));
    }
}