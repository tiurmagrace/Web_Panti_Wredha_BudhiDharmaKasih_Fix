<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Hapus constraint check pada kolom type di PostgreSQL
        DB::statement('ALTER TABLE notifikasi DROP CONSTRAINT IF EXISTS notifikasi_type_check');
    }

    public function down(): void
    {
        // Tidak perlu restore constraint karena sudah tidak diperlukan
    }
};
