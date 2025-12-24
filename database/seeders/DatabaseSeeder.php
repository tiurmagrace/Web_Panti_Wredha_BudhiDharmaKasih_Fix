<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Penghuni;
use App\Models\Donasi;
use App\Models\Barang;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ============================================
        // 1. HAPUS SEMUA DATA LAMA TERLEBIH DAHULU
        // ============================================
        echo "🗑️  Menghapus data lama...\n";
        
        // Nonaktifkan foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        
        // Kosongkan semua tabel
        User::truncate();
        Penghuni::truncate();
        Donasi::truncate();
        Barang::truncate();
        
        // Aktifkan kembali foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        
        echo "✅ Data lama berhasil dihapus!\n\n";
        
        // ============================================
        // 2. BUAT DATA ADMIN
        // ============================================
        echo "👑 Membuat admin...\n";
        
        User::create([
            'nama' => 'Administrator',
            'email' => 'admin@pantibdk.com',
            'password' => 'password123', // Auto-hash oleh Model User
            'role' => 'admin',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        echo "✅ Admin berhasil dibuat!\n\n";
        
        // ============================================
        // 3. BUAT DATA DONATUR
        // ============================================
        echo "🙋 Membuat data donatur...\n";
        
        $donatur1 = User::create([
            'nama' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'no_hp' => '081234567890',
            'password' => 'password123', // Auto-hash oleh Model User
            'role' => 'donatur',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $donatur2 = User::create([
            'nama' => 'Siti Rahayu',
            'email' => 'siti@example.com',
            'no_hp' => '082345678901',
            'password' => 'password123', // Auto-hash oleh Model User
            'role' => 'donatur',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        echo "✅ 2 donatur berhasil dibuat!\n\n";
        
        // ============================================
        // 4. BUAT DATA PENGHUNI
        // ============================================
        echo "🏠 Membuat data penghuni...\n";
        
        // Penghuni 1 - Suparman
        Penghuni::create([
            'nik' => '3303012345670001',
            'nama' => 'Suparman',
            'ttl' => 'Purbalingga, 15 Mei 1950',
            'usia' => 74,
            'kota' => 'Purbalingga',
            'alamat' => 'Jl. Mawar No. 123, Purbalingga',
            'agama' => 'Kristen Protestan',
            'gender' => 'Pria',
            'status' => 'Duda',
            'pj' => 'Bambang Suparman',
            'hubungan' => 'Anak',
            'telp' => '081234567890',
            'alamat_pj' => 'Jakarta Selatan',
            'status_sehat' => 'Cukup Baik',
            'penyakit' => 'Diabetes',
            'alergi' => 'Tidak ada',
            'kebutuhan' => 'Kursi roda',
            'obat' => 'Metformin',
            'tgl_masuk' => '2020-01-15',
            'rujukan' => 'Kerabat/Tetangga',
            'paviliun' => 'BOUGENVILLE 1',
            'catatan' => 'Penghuni yang ramah',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Penghuni 2 - Siti Aminah
        Penghuni::create([
            'nik' => '3303012345670002',
            'nama' => 'Siti Aminah',
            'ttl' => 'Purbalingga, 20 Juni 1948',
            'usia' => 76,
            'kota' => 'Purbalingga',
            'alamat' => 'Jl. Melati No. 45, Purbalingga',
            'agama' => 'Islam',
            'gender' => 'Wanita',
            'status' => 'Janda',
            'pj' => 'Rina Aminah',
            'hubungan' => 'Anak',
            'telp' => '082345678901',
            'alamat_pj' => 'Bandung',
            'status_sehat' => 'Baik',
            'penyakit' => 'Hipertensi',
            'alergi' => 'Seafood',
            'kebutuhan' => 'Tongkat',
            'obat' => 'Amlodipine',
            'tgl_masuk' => '2019-06-10',
            'rujukan' => 'Dinas Sosial',
            'paviliun' => 'SNEEK',
            'catatan' => 'Aktif dalam kegiatan rohani',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        echo "✅ 2 penghuni berhasil dibuat!\n\n";
        
        // ============================================
        // 5. BUAT DATA DONASI
        // ============================================
        echo "💰 Membuat data donasi...\n";
        
        // Donasi 1 - Budi Santoso (Tunai)
        Donasi::create([
            'user_id' => $donatur1->id,
            'donatur' => 'Budi Santoso',
            'jenis' => 'Tunai',
            'detail' => 'Transfer Bank BCA',
            'jumlah' => 'Rp 2.000.000',
            'tanggal' => now()->subDays(5),
            'status' => 'Langsung',
            'petugas' => 'Admin BDK',
            'status_verifikasi' => 'approved',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Donasi 2 - Siti Rahayu (Barang)
        Donasi::create([
            'user_id' => $donatur2->id,
            'donatur' => 'Siti Rahayu',
            'jenis' => 'Barang',
            'detail' => 'Sembako',
            'jumlah' => '5 Karung Beras',
            'tanggal' => now()->subDays(3),
            'status' => 'Tidak Langsung',
            'petugas' => 'Admin BDK',
            'status_verifikasi' => 'approved',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Donasi 3 - Yayasan ABC (Anonim)
        Donasi::create([
            'user_id' => null,
            'donatur' => 'Yayasan ABC',
            'jenis' => 'Barang',
            'detail' => 'Pakaian',
            'jumlah' => '100 Pcs',
            'tanggal' => now()->subDays(1),
            'status' => 'Langsung',
            'petugas' => 'Admin BDK',
            'status_verifikasi' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        echo "✅ 3 donasi berhasil dibuat!\n\n";
        
        // ============================================
        // 6. BUAT DATA BARANG (STOK) - DENGAN SATUAN YANG VALID
        // ============================================
        echo "📦 Membuat data stok barang...\n";
        
        // Barang 1 - Beras Premium (Karung)
        Barang::create([
            'nama' => 'Beras Premium',
            'kategori' => 'Sembako',
            'satuan' => 'Karung', // ✅ VALID
            'brg_masuk' => 50,
            'sisa_stok' => 45,
            'tgl_masuk' => now()->subDays(10),
            'kondisi' => 'Baik',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Barang 2 - Minyak Goreng (Botol, bukan Liter)
        Barang::create([
            'nama' => 'Minyak Goreng',
            'kategori' => 'Sembako',
            'satuan' => 'Botol', // ✅ VALID (ganti dari 'Liter')
            'brg_masuk' => 100,
            'sisa_stok' => 85,
            'tgl_masuk' => now()->subDays(15),
            'expired' => now()->addMonths(6),
            'kondisi' => 'Baik',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Barang 3 - Susu UHT (Dus)
        Barang::create([
            'nama' => 'Susu UHT',
            'kategori' => 'Sembako',
            'satuan' => 'Dus', // ✅ VALID
            'brg_masuk' => 30,
            'sisa_stok' => 28,
            'tgl_masuk' => now()->subDays(5),
            'expired' => now()->addDays(25),
            'kondisi' => 'Baik',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Barang 4 - Sabun Mandi (Pcs)
        Barang::create([
            'nama' => 'Sabun Mandi',
            'kategori' => 'Alat Kebersihan',
            'satuan' => 'Pcs', // ✅ VALID
            'brg_masuk' => 200,
            'sisa_stok' => 180,
            'tgl_masuk' => now()->subDays(20),
            'kondisi' => 'Baik',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Barang 5 - Paracetamol (Strip)
        Barang::create([
            'nama' => 'Paracetamol',
            'kategori' => 'Perlengkapan Medis',
            'satuan' => 'Strip', // ✅ VALID
            'brg_masuk' => 100,
            'sisa_stok' => 95,
            'tgl_masuk' => now()->subDays(30),
            'expired' => now()->addYears(2),
            'kondisi' => 'Baik',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        echo "✅ 5 barang stok berhasil dibuat!\n\n";
        
        // ============================================
        // 7. TAMPILKAN RINGKASAN
        // ============================================
        echo str_repeat("=", 50) . "\n";
        echo "🎉 DATABASE SEEDING BERHASIL!\n";
        echo str_repeat("=", 50) . "\n\n";
        
        echo "📊 RINGKASAN DATA:\n";
        echo "──────────────────\n";
        echo "• 👤 User: 3 (1 Admin, 2 Donatur)\n";
        echo "• 🏠 Penghuni: 2\n";
        echo "• 💰 Donasi: 3\n";
        echo "• 📦 Barang: 5\n\n";
        
        echo "🔐 LOGIN CREDENTIALS:\n";
        echo "─────────────────────\n";
        echo "👑 ADMIN:\n";
        echo "   Email: admin@pantibdk.com\n";
        echo "   Password: password123\n\n";
        
        echo "🙋 DONATUR 1:\n";
        echo "   Email: budi@example.com\n";
        echo "   Password: password123\n";
        echo "   Telepon: 081234567890\n\n";
        
        echo "🙋 DONATUR 2:\n";
        echo "   Email: siti@example.com\n";
        echo "   Password: password123\n";
        echo "   Telepon: 082345678901\n\n";
        
        echo "📦 SATUAN BARANG YANG DIGUNAKAN:\n";
        echo "───────────────────────────────\n";
        echo "• Beras Premium: Karung\n";
        echo "• Minyak Goreng: Botol (bukan Liter)\n";
        echo "• Susu UHT: Dus\n";
        echo "• Sabun Mandi: Pcs\n";
        echo "• Paracetamol: Strip\n\n";
        
        echo "🚀 NEXT STEP:\n";
        echo "─────────────\n";
        echo "1. Jalankan: php artisan serve\n";
        echo "2. Buka: http://localhost:8000\n";
        echo "3. Login dengan credential di atas\n";
        echo str_repeat("=", 50) . "\n";
    }
}