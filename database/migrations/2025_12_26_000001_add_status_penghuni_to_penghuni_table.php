<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('penghuni', function (Blueprint $table) {
            $table->enum('status_penghuni', ['Aktif', 'Keluar', 'Meninggal'])->default('Aktif')->after('paviliun');
            $table->date('tgl_keluar')->nullable()->after('status_penghuni');
            $table->string('alasan_keluar')->nullable()->after('tgl_keluar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penghuni', function (Blueprint $table) {
            $table->dropColumn(['status_penghuni', 'tgl_keluar', 'alasan_keluar']);
        });
    }
};
