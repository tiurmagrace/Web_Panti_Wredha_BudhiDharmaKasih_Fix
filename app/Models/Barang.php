<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Barang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'barang';

    protected $fillable = [
        'nama',
        'kategori',
        'satuan',
        'brg_masuk',
        'sisa_stok',
        'tgl_masuk',
        'expired',
        'kondisi',
        'foto',
    ];

    protected $casts = [
        'tgl_masuk' => 'date',
        'expired' => 'date',
    ];

    // Relationships
    public function pengambilanStok()
    {
        return $this->hasMany(PengambilanStok::class);
    }

    // Scopes
    public function scopeStokMenipis($query)
    {
        return $query->whereRaw('sisa_stok <= (brg_masuk * 0.2)');
    }

    public function scopeHampirExpired($query, $days = 30)
    {
        return $query->whereNotNull('expired')
                     ->whereBetween('expired', [
                         now()->toDateString(),
                         now()->addDays($days)->toDateString()
                     ]);
    }

    public function scopeByKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('nama', 'like', "%{$search}%")
              ->orWhere('kategori', 'like', "%{$search}%");
        });
    }

    // Helper untuk cek apakah hampir expired
    public function isNearExpiry($days = 30)
    {
        if (!$this->expired) {
            return false;
        }
        return $this->expired->diffInDays(now()) <= $days && $this->expired->isFuture();
    }
}
