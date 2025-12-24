<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

// Model PengambilanStok
class PengambilanStok extends Model
{
    use HasFactory;

    protected $table = 'pengambilan_stok';

    protected $fillable = [
        'barang_id',
        'jumlah',
        'tanggal',
        'keperluan',
        'petugas',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
