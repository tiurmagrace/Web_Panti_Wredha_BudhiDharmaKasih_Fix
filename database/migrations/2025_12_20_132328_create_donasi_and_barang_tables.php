<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel Donasi
        Schema::create('donasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('donatur');
            $table->enum('jenis', ['Barang', 'Tunai']);
            $table->string('detail'); // Kategori untuk barang, atau metode untuk tunai
            $table->string('jumlah');
            $table->date('tanggal');
            $table->enum('status', ['Langsung', 'Tidak Langsung']);
            $table->string('petugas');
            $table->string('bukti')->nullable(); // Path foto bukti
            $table->enum('status_verifikasi', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Tabel Barang (Stok)
        Schema::create('barang', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->enum('kategori', [
                'Sembako',
                'Pakaian',
                'Alat Kebersihan',
                'Alat Kesehatan',
                'Peralatan Rumah Tangga',
                'Elektronik',
                'Perlengkapan Tidur',
                'Buku & Hiburan',
                'Perlengkapan Medis',
                'Lainnya'
            ]);
            $table->enum('satuan', [
                'Pcs',
                'Pack',
                'Botol',
                'Karung',
                'Dus',
                'Strip',
                'Unit',
                'Sachet',
                'Bungkus'
            ]);
            $table->integer('brg_masuk'); // Stok awal/masuk
            $table->integer('sisa_stok');
            $table->date('tgl_masuk');
            $table->date('expired')->nullable();
            $table->enum('kondisi', ['Baik', 'Rusak Ringan', 'Perlu Perbaikan'])->default('Baik');
            $table->string('foto')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Tabel Pengambilan Stok
        Schema::create('pengambilan_stok', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->constrained('barang')->cascadeOnDelete();
            $table->integer('jumlah');
            $table->date('tanggal');
            $table->string('keperluan');
            $table->string('petugas');
            $table->timestamps();
        });

        // Tabel Laporan Donasi
        Schema::create('laporan_donasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donasi_id')->constrained('donasi')->cascadeOnDelete();
            $table->string('email_donatur');
            $table->text('isi_laporan');
            $table->string('bukti_terima')->nullable();
            $table->enum('status', ['draft', 'sent'])->default('draft');
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_donasi');
        Schema::dropIfExists('pengambilan_stok');
        Schema::dropIfExists('barang');
        Schema::dropIfExists('donasi');
    }
};