<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ubah kolom type dari ENUM ke VARCHAR agar lebih fleksibel
        Schema::table('notifikasi', function (Blueprint $table) {
            $table->string('type', 50)->change();
        });
    }

    public function down(): void
    {
        // Kembalikan ke ENUM (hati-hati: data yang tidak sesuai akan error)
        Schema::table('notifikasi', function (Blueprint $table) {
            $table->enum('type', ['donasi', 'stok', 'penghuni', 'system'])->change();
        });
    }
};
