<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Change foto columns from VARCHAR to TEXT to support base64 images
     */
    public function up(): void
    {
        // For PostgreSQL, we need to use raw SQL to change column type
        if (DB::connection()->getDriverName() === 'pgsql') {
            // Penghuni table
            DB::statement('ALTER TABLE penghuni ALTER COLUMN foto TYPE TEXT');
            
            // Barang table  
            DB::statement('ALTER TABLE barang ALTER COLUMN foto TYPE TEXT');
            
            // Donasi table (bukti column)
            DB::statement('ALTER TABLE donasi ALTER COLUMN bukti TYPE TEXT');
        } else {
            // For MySQL
            Schema::table('penghuni', function (Blueprint $table) {
                $table->text('foto')->nullable()->change();
            });
            
            Schema::table('barang', function (Blueprint $table) {
                $table->text('foto')->nullable()->change();
            });
            
            Schema::table('donasi', function (Blueprint $table) {
                $table->text('bukti')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE penghuni ALTER COLUMN foto TYPE VARCHAR(255)');
            DB::statement('ALTER TABLE barang ALTER COLUMN foto TYPE VARCHAR(255)');
            DB::statement('ALTER TABLE donasi ALTER COLUMN bukti TYPE VARCHAR(255)');
        } else {
            Schema::table('penghuni', function (Blueprint $table) {
                $table->string('foto')->nullable()->change();
            });
            
            Schema::table('barang', function (Blueprint $table) {
                $table->string('foto')->nullable()->change();
            });
            
            Schema::table('donasi', function (Blueprint $table) {
                $table->string('bukti')->nullable()->change();
            });
        }
    }
};
