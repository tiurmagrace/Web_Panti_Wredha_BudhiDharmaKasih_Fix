<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Penghuni extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'penghuni';

    protected $fillable = [
        'nik',
        'nama',
        'ttl',
        'usia',
        'kota',
        'alamat',
        'agama',
        'gender',
        'status',
        'pj',
        'hubungan',
        'telp',
        'alamat_pj',
        'status_sehat',
        'penyakit',
        'alergi',
        'kebutuhan',
        'obat',
        'tgl_masuk',
        'rujukan',
        'paviliun',
        'catatan',
        'foto',
    ];

    protected $casts = [
        'tgl_masuk' => 'date',
    ];

    // Accessor untuk tahun masuk
    public function getTahunMasukAttribute()
    {
        return $this->tgl_masuk ? $this->tgl_masuk->format('Y') : null;
    }

    // Scope untuk filter
    public function scopeByPaviliun($query, $paviliun)
    {
        return $query->where('paviliun', $paviliun);
    }

    public function scopeByTahun($query, $tahun)
    {
        return $query->whereYear('tgl_masuk', $tahun);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('nama', 'like', "%{$search}%")
              ->orWhere('nik', 'like', "%{$search}%")
              ->orWhere('kota', 'like', "%{$search}%")
              ->orWhere('paviliun', 'like', "%{$search}%");
        });
    }
}