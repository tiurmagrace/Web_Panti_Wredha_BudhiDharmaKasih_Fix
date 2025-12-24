<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel Feedback dari Donatur
        Schema::create('feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('nama');
            $table->string('email')->nullable();
            $table->string('telepon')->nullable();
            $table->text('pesan');
            $table->date('tanggal');
            $table->time('jam')->nullable();
            $table->enum('status', ['unread', 'read'])->default('unread');
            $table->timestamps();
            $table->softDeletes();
        });

        // Tabel Notifikasi
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('type', ['donasi', 'stok', 'penghuni', 'system']);
            $table->string('title');
            $table->text('text');
            $table->date('date');
            $table->enum('status', ['unread', 'read'])->default('unread');
            $table->json('metadata')->nullable(); // Data tambahan jika diperlukan
            $table->timestamps();
        });

        // Tabel Log Aktivitas Admin
        Schema::create('aktivitas_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('kategori'); // Penghuni, Donasi, Barang
            $table->text('text'); // Deskripsi aktivitas
            $table->timestamp('time');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aktivitas_log');
        Schema::dropIfExists('notifikasi');
        Schema::dropIfExists('feedback');
    }
};