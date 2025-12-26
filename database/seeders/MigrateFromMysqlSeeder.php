<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MigrateFromMysqlSeeder extends Seeder
{
    /**
     * Migrasi data dari MySQL ke PostgreSQL (Supabase)
     */
    public function run(): void
    {
        // Konfigurasi MySQL lokal
        config(['database.connections.mysql_local' => [
            'driver' => 'mysql',
            'host' => '127.0.0.1',
            'port' => '3306',
            'database' => 'panti_wredha_bdk',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
        ]]);

        echo "🔄 Memulai migrasi data dari MySQL ke Supabase...\n\n";

        // Disable foreign key checks di PostgreSQL
        DB::statement('SET session_replication_role = replica');

        // List tabel yang akan dimigrasi (urutan penting untuk foreign key)
        $tables = ['users', 'penghuni', 'donasi', 'barang', 'feedback', 'notifikasi', 'laporan_donasi', 'pengambilan_stok', 'aktivitas_log'];

        foreach ($tables as $table) {
            $this->migrateTable($table);
        }

        // Enable kembali foreign key checks
        DB::statement('SET session_replication_role = DEFAULT');

        echo "\n✅ Migrasi selesai!\n";
    }

    private function migrateTable(string $table): void
    {
        try {
            // Ambil data dari MySQL
            $data = DB::connection('mysql_local')->table($table)->get();
            
            if ($data->isEmpty()) {
                echo "⚠️  Tabel '{$table}' kosong, skip...\n";
                return;
            }

            // Hapus data lama di PostgreSQL
            DB::table($table)->truncate();

            // Insert ke PostgreSQL
            $count = 0;
            foreach ($data->chunk(100) as $chunk) {
                DB::table($table)->insert($chunk->map(fn($row) => (array) $row)->toArray());
                $count += $chunk->count();
            }

            echo "✅ Tabel '{$table}': {$count} records berhasil dimigrasi\n";

        } catch (\Exception $e) {
            echo "❌ Tabel '{$table}': Error - " . $e->getMessage() . "\n";
        }
    }
}
