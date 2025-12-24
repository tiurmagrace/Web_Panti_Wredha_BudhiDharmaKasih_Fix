<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penghuni', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 16)->unique();
            $table->string('nama');
            $table->string('ttl');
            $table->integer('usia');
            $table->string('kota');
            $table->text('alamat');
            $table->enum('agama', [
                'Kristen Protestan',
                'Katholik', 
                'Islam',
                'Hindu',
                'Budha',
                'Konghucu',
                'Kepercayaan Terhadap Tuhan YME',
                'Tidak Beragama'
            ]);
            $table->enum('gender', ['Pria', 'Wanita']);
            $table->enum('status', ['Belum Kawin', 'Kawin', 'Janda', 'Duda']);
            
            // Data Kontak Darurat
            $table->string('pj'); // Penanggung Jawab
            $table->string('hubungan');
            $table->string('telp', 20);
            $table->text('alamat_pj');
            
            // Data Kesehatan
            $table->string('status_sehat')->nullable();
            $table->text('penyakit')->nullable();
            $table->text('alergi')->nullable();
            $table->text('kebutuhan')->nullable();
            $table->text('obat')->nullable();
            
            // Data Panti
            $table->date('tgl_masuk');
            $table->enum('rujukan', [
                'Yang Bersangkutan Sendiri',
                'Kerabat/Tetangga',
                'Dinas Sosial',
                'Lembaga Kesehatan',
                'Komunitas sosial',
                'Pusat layanan terpadu',
                'Lembaga Keagamaan'
            ]);
            $table->enum('paviliun', [
                'ANGGREK',
                'BOUGENVILLE 1',
                'BOUGENVILLE 2',
                'MAWAR',
                'SNEEK',
                'BETHESDA',
                'DAHLIA'
            ]);
            $table->text('catatan')->nullable();
            $table->string('foto')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penghuni');
    }
};