<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

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
        'status_penghuni',
        'tgl_keluar',
        'alasan_keluar',
        'catatan',
        'foto',
    ];

    protected $casts = [
        'tgl_masuk' => 'date',
        'tgl_keluar' => 'date',
    ];

    protected $appends = ['foto_url'];

    // Accessor untuk foto URL
    public function getFotoUrlAttribute()
    {
        if (!$this->foto) {
            return null;
        }
        
        // Jika sudah base64, return langsung
        if (str_starts_with($this->foto, 'data:image')) {
            return $this->foto;
        }
        
        // Jika sudah URL lengkap
        if (str_starts_with($this->foto, 'http')) {
            return $this->foto;
        }
        
        // Jika path file, convert ke URL
        return asset('storage/' . $this->foto);
    }

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

    public function scopeAktif($query)
    {
        return $query->where('status_penghuni', 'Aktif');
    }

    public function scopeByStatusPenghuni($query, $status)
    {
        return $query->where('status_penghuni', $status);
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