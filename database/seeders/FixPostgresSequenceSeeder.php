<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FixPostgresSequenceSeeder extends Seeder
{
    /**
     * Fix PostgreSQL sequences after data migration.
     * This is needed because when we insert data with explicit IDs,
     * the sequence doesn't update automatically.
     */
    public function run(): void
    {
        $this->command->info('🔧 Fixing PostgreSQL sequences...');

        $tables = [
            'users',
            'penghuni', 
            'donasi',
            'barang',
            'feedback',
            'notifikasi',
            'pengambilan_stok',
            'aktivitas_log',
            'laporan_donasi',
        ];

        foreach ($tables as $table) {
            try {
                // Check if table exists
                $exists = DB::select("SELECT to_regclass('public.{$table}') as exists")[0]->exists;
                
                if ($exists) {
                    // Get max ID from table
                    $maxId = DB::table($table)->max('id') ?? 0;
                    
                    // Reset sequence to max ID + 1
                    $sequenceName = "{$table}_id_seq";
                    DB::statement("SELECT setval('{$sequenceName}', ?, true)", [$maxId]);
                    
                    $this->command->info("✅ {$table}: sequence reset to {$maxId}");
                }
            } catch (\Exception $e) {
                $this->command->warn("⚠️  {$table}: " . $e->getMessage());
            }
        }

        $this->command->info('🎉 PostgreSQL sequences fixed!');
    }
}
