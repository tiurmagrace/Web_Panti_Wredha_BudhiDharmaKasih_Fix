<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

// Model LaporanDonasi
class LaporanDonasi extends Model
{
    use HasFactory;

    protected $table = 'laporan_donasi';

    protected $fillable = [
        'donasi_id',
        'email_donatur',
        'isi_laporan',
        'bukti_terima',
        'status',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function donasi()
    {
        return $this->belongsTo(Donasi::class);
    }
}
