<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Donasi;
use App\Models\Barang;
use App\Models\Notifikasi;
use App\Models\AktivitasLog;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DonasiController extends Controller
{
    /**
     * Get All Donasi (Admin)
     */
    public function index(Request $request)
    {
        $query = Donasi::with('user');

        if ($request->has('jenis') && $request->jenis) {
            $query->where('jenis', $request->jenis);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('status_verifikasi') && $request->status_verifikasi) {
            $query->where('status_verifikasi', $request->status_verifikasi);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('donatur', 'like', "%{$search}%")
                  ->orWhere('detail', 'like', "%{$search}%");
            });
        }

        // Filter by date range
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('tanggal', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('tanggal', '<=', $request->end_date);
        }

        $query->orderBy('created_at', 'desc');
        
        // Pagination untuk mobile
        if ($request->has('per_page')) {
            $donasi = $query->paginate($request->per_page);
            return response()->json([
                'success' => true,
                'data' => $donasi->items(),
                'pagination' => [
                    'current_page' => $donasi->currentPage(),
                    'last_page' => $donasi->lastPage(),
                    'per_page' => $donasi->perPage(),
                    'total' => $donasi->total()
                ]
            ]);
        }

        $donasi = $query->get();

        return response()->json([
            'success' => true,
            'data' => $donasi
        ]);
    }

    /**
     * Get Public Donasi List (untuk ditampilkan di mobile tanpa auth)
     */
    public function publicList(Request $request)
    {
        $query = Donasi::where('status_verifikasi', 'approved')
                       ->select('id', 'donatur', 'jenis', 'detail', 'jumlah', 'tanggal', 'created_at');

        if ($request->has('jenis') && $request->jenis) {
            $query->where('jenis', $request->jenis);
        }

        $query->orderBy('tanggal', 'desc');

        // Pagination
        $perPage = $request->get('per_page', 10);
        $donasi = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $donasi->items(),
            'pagination' => [
                'current_page' => $donasi->currentPage(),
                'last_page' => $donasi->lastPage(),
                'per_page' => $donasi->perPage(),
                'total' => $donasi->total()
            ]
        ]);
    }

    /**
     * Get Public Statistics (untuk ditampilkan di mobile tanpa auth)
     */
    public function publicStatistics()
    {
        $totalDonatur = Donasi::where('status_verifikasi', 'approved')
                              ->distinct('donatur')
                              ->count('donatur');
        
        $totalDonasiTunai = Donasi::where('jenis', 'Tunai')
                                  ->where('status_verifikasi', 'approved')
                                  ->count();
        
        $totalDonasiBarang = Donasi::where('jenis', 'Barang')
                                   ->where('status_verifikasi', 'approved')
                                   ->count();

        // Donasi bulan ini
        $donasiThisMonth = Donasi::where('status_verifikasi', 'approved')
                                 ->whereMonth('tanggal', now()->month)
                                 ->whereYear('tanggal', now()->year)
                                 ->count();

        return response()->json([
            'success' => true,
            'data' => [
                'total_donatur' => $totalDonatur,
                'total_donasi_tunai' => $totalDonasiTunai,
                'total_donasi_barang' => $totalDonasiBarang,
                'donasi_bulan_ini' => $donasiThisMonth,
                'total_donasi' => $totalDonasiTunai + $totalDonasiBarang
            ]
        ]);
    }

    /**
     * Get Donasi by User (Mobile - Riwayat donasi user)
     */
    public function myDonations(Request $request)
    {
        $query = Donasi::where('user_id', auth()->id());

        // Filter by status verifikasi
        if ($request->has('status_verifikasi') && $request->status_verifikasi) {
            $query->where('status_verifikasi', $request->status_verifikasi);
        }

        $query->orderBy('tanggal', 'desc');

        // Pagination
        if ($request->has('per_page')) {
            $donasi = $query->paginate($request->per_page);
            return response()->json([
                'success' => true,
                'data' => $donasi->items(),
                'pagination' => [
                    'current_page' => $donasi->currentPage(),
                    'last_page' => $donasi->lastPage(),
                    'per_page' => $donasi->perPage(),
                    'total' => $donasi->total()
                ]
            ]);
        }

        $donasi = $query->get();

        return response()->json([
            'success' => true,
            'data' => $donasi
        ]);
    }

    /**
     * Get Single Donasi
     */
    public function show($id)
    {
        $donasi = Donasi::with('user')->find($id);

        if (!$donasi) {
            return response()->json([
                'success' => false,
                'message' => 'Data donasi tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $donasi
        ]);
    }

    /**
     * Create Donasi dari Mobile App
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'donatur' => 'required|string|max:255',
            'jenis' => 'required|in:Barang,Tunai',
            'detail' => 'required|string|max:255',
            'jumlah' => 'required|string|max:255',
            'bukti' => 'nullable|string', // base64 image
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = $request->all();
            $data['user_id'] = auth()->id();
            $data['status'] = $data['status'] ?? 'Tidak Langsung';
            $data['petugas'] = $data['petugas'] ?? '-';
            $data['status_verifikasi'] = 'pending'; // Mobile selalu pending dulu
            $data['tanggal'] = $data['tanggal'] ?? now()->toDateString();

            // Handle base64 image upload
            if ($request->has('bukti') && $request->bukti) {
                $buktiPath = $this->saveBase64Image($request->bukti, 'donasi');
                if ($buktiPath) {
                    $data['bukti'] = $buktiPath;
                }
            }

            $donasi = Donasi::create($data);

            // Buat notifikasi untuk admin
            NotificationService::donasiMasuk($donasi);

            // Log aktivitas
            if (auth()->check()) {
                AktivitasLog::create([
                    'user_id' => auth()->id(),
                    'kategori' => 'Donasi',
                    'text' => "Donasi masuk dari {$donasi->donatur} ({$donasi->jenis})",
                    'time' => now(),
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Donasi berhasil dikirim dan menunggu verifikasi',
                'data' => $donasi
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan donasi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create Donasi dari Admin (langsung approved)
     */
    public function storeByAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'donatur' => 'required|string|max:255',
            'jenis' => 'required|in:Barang,Tunai',
            'detail' => 'required|string|max:255',
            'jumlah' => 'required|string|max:255',
            'status' => 'required|in:Langsung,Tidak Langsung',
            'petugas' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = $request->all();
            $data['user_id'] = auth()->id();
            $data['status_verifikasi'] = 'approved'; // Admin langsung approved
            $data['tanggal'] = $data['tanggal'] ?? now()->toDateString();

            // Handle base64 image upload
            if ($request->has('bukti') && $request->bukti) {
                $buktiPath = $this->saveBase64Image($request->bukti, 'donasi');
                if ($buktiPath) {
                    $data['bukti'] = $buktiPath;
                }
            }

            $donasi = Donasi::create($data);

            // Log aktivitas
            AktivitasLog::create([
                'user_id' => auth()->id(),
                'kategori' => 'Donasi',
                'text' => "Admin menambahkan donasi dari {$donasi->donatur} ({$donasi->jenis})",
                'time' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Donasi berhasil ditambahkan',
                'data' => $donasi
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan donasi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verifikasi Donasi (Admin Only)
     */
    public function verify(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status_verifikasi' => 'required|in:approved,rejected',
            'catatan' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $donasi = Donasi::find($id);

            if (!$donasi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data donasi tidak ditemukan'
                ], 404);
            }

            $updateData = [
                'status_verifikasi' => $request->status_verifikasi
            ];
            
            if ($request->has('catatan')) {
                $updateData['catatan'] = $request->catatan;
            }

            $donasi->update($updateData);

            // Jika donasi BARANG disetujui, tambahkan ke stok barang
            if ($request->status_verifikasi === 'approved' && $donasi->jenis === 'Barang') {
                $this->addToStokBarang($donasi);
            }

            // Kirim notifikasi ke user yang mendonasi
            if ($request->status_verifikasi === 'approved') {
                NotificationService::donasiDiterima($donasi);
            } else {
                NotificationService::donasiDitolak($donasi, $request->catatan);
            }

            // Log aktivitas
            AktivitasLog::create([
                'user_id' => auth()->id(),
                'kategori' => 'Donasi',
                'text' => "Verifikasi donasi dari {$donasi->donatur}: {$request->status_verifikasi}",
                'time' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status donasi berhasil diupdate',
                'data' => $donasi->fresh()
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal verifikasi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper: Tambahkan donasi barang ke stok
     */
    private function addToStokBarang(Donasi $donasi)
    {
        // Parse jumlah dari string (misal: "10 Pcs", "5 Dus", "20")
        $jumlahStr = $donasi->jumlah;
        preg_match('/(\d+)/', $jumlahStr, $matches);
        $jumlah = isset($matches[1]) ? (int) $matches[1] : 1;

        // Tentukan kategori berdasarkan detail donasi
        $detail = strtolower($donasi->detail ?? '');
        $kategori = $this->mapKategoriBarang($detail);

        // Tentukan satuan berdasarkan jumlah string
        $satuan = $this->detectSatuan($jumlahStr);

        // Cek apakah barang dengan nama sama sudah ada
        $existingBarang = Barang::where('nama', 'like', '%' . $donasi->detail . '%')
                                ->where('kategori', $kategori)
                                ->first();

        if ($existingBarang) {
            // Update stok yang sudah ada
            $existingBarang->increment('brg_masuk', $jumlah);
            $existingBarang->increment('sisa_stok', $jumlah);
            
            AktivitasLog::create([
                'user_id' => auth()->id(),
                'kategori' => 'Barang',
                'text' => "Stok {$existingBarang->nama} bertambah {$jumlah} {$existingBarang->satuan} dari donasi {$donasi->donatur}",
                'time' => now(),
            ]);
        } else {
            // Buat barang baru
            $barang = Barang::create([
                'nama' => $donasi->detail ?: 'Donasi dari ' . $donasi->donatur,
                'kategori' => $kategori,
                'satuan' => $satuan,
                'brg_masuk' => $jumlah,
                'sisa_stok' => $jumlah,
                'tgl_masuk' => $donasi->tanggal ?? now()->toDateString(),
                'kondisi' => 'Baik',
            ]);

            AktivitasLog::create([
                'user_id' => auth()->id(),
                'kategori' => 'Barang',
                'text' => "Barang baru '{$barang->nama}' ({$jumlah} {$satuan}) ditambahkan dari donasi {$donasi->donatur}",
                'time' => now(),
            ]);
        }
    }

    /**
     * Helper: Map detail donasi ke kategori barang
     */
    private function mapKategoriBarang($detail)
    {
        $mappings = [
            'sembako' => 'Sembako',
            'beras' => 'Sembako',
            'minyak' => 'Sembako',
            'gula' => 'Sembako',
            'mie' => 'Sembako',
            'pakaian' => 'Pakaian',
            'baju' => 'Pakaian',
            'celana' => 'Pakaian',
            'selimut' => 'Perlengkapan Tidur',
            'kasur' => 'Perlengkapan Tidur',
            'bantal' => 'Perlengkapan Tidur',
            'obat' => 'Alat Kesehatan',
            'vitamin' => 'Alat Kesehatan',
            'kesehatan' => 'Alat Kesehatan',
            'medis' => 'Perlengkapan Medis',
            'sabun' => 'Alat Kebersihan',
            'deterjen' => 'Alat Kebersihan',
            'kebersihan' => 'Alat Kebersihan',
            'elektronik' => 'Elektronik',
            'tv' => 'Elektronik',
            'kipas' => 'Elektronik',
        ];

        foreach ($mappings as $keyword => $kategori) {
            if (str_contains($detail, $keyword)) {
                return $kategori;
            }
        }

        return 'Lainnya';
    }

    /**
     * Helper: Detect satuan dari string jumlah
     */
    private function detectSatuan($jumlahStr)
    {
        $jumlahLower = strtolower($jumlahStr);
        
        $satuanMappings = [
            'pcs' => 'Pcs',
            'pack' => 'Pack',
            'botol' => 'Botol',
            'karung' => 'Karung',
            'dus' => 'Dus',
            'strip' => 'Strip',
            'unit' => 'Unit',
            'sachet' => 'Sachet',
            'bungkus' => 'Bungkus',
        ];

        foreach ($satuanMappings as $keyword => $satuan) {
            if (str_contains($jumlahLower, $keyword)) {
                return $satuan;
            }
        }

        return 'Pcs'; // Default
    }

    /**
     * Get Pending Donations (Admin)
     */
    public function pending(Request $request)
    {
        $query = Donasi::with('user')
                       ->where('status_verifikasi', 'pending')
                       ->orderBy('created_at', 'desc');

        if ($request->has('per_page')) {
            $donasi = $query->paginate($request->per_page);
            return response()->json([
                'success' => true,
                'data' => $donasi->items(),
                'pagination' => [
                    'current_page' => $donasi->currentPage(),
                    'last_page' => $donasi->lastPage(),
                    'per_page' => $donasi->perPage(),
                    'total' => $donasi->total()
                ]
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $query->get()
        ]);
    }

    /**
     * Update Donasi
     */
    public function update(Request $request, $id)
    {
        $donasi = Donasi::find($id);

        if (!$donasi) {
            return response()->json([
                'success' => false,
                'message' => 'Data donasi tidak ditemukan'
            ], 404);
        }

        // Filter hanya field yang tidak null/kosong
        $data = collect($request->all())->filter(function ($value, $key) {
            // Skip field yang null atau string kosong, kecuali catatan yang boleh kosong
            if ($key === 'catatan') return true;
            return $value !== null && $value !== '';
        })->toArray();

        // Handle base64 image upload
        if ($request->has('bukti') && $request->bukti && Str::startsWith($request->bukti, 'data:image')) {
            $buktiPath = $this->saveBase64Image($request->bukti, 'donasi');
            if ($buktiPath) {
                // Hapus foto lama jika ada
                if ($donasi->bukti && Storage::disk('public')->exists($donasi->bukti)) {
                    Storage::disk('public')->delete($donasi->bukti);
                }
                $data['bukti'] = $buktiPath;
            }
        }

        $donasi->update($data);

        if (auth()->check()) {
            AktivitasLog::create([
                'user_id' => auth()->id(),
                'kategori' => 'Donasi',
                'text' => "Mengupdate donasi dari {$donasi->donatur}",
                'time' => now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data donasi berhasil diupdate',
            'data' => $donasi->fresh()
        ]);
    }

    /**
     * Delete Donasi
     */
    public function destroy($id)
    {
        $donasi = Donasi::find($id);

        if (!$donasi) {
            return response()->json([
                'success' => false,
                'message' => 'Data donasi tidak ditemukan'
            ], 404);
        }

        // Hapus bukti foto jika ada
        if ($donasi->bukti && Storage::disk('public')->exists($donasi->bukti)) {
            Storage::disk('public')->delete($donasi->bukti);
        }

        $donatur = $donasi->donatur;
        $donasi->delete();

        if (auth()->check()) {
            AktivitasLog::create([
                'user_id' => auth()->id(),
                'kategori' => 'Donasi',
                'text' => "Menghapus donasi dari {$donatur}",
                'time' => now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data donasi berhasil dihapus'
        ]);
    }

    /**
     * Get Statistics (Admin)
     */
    public function statistics(Request $request)
    {
        $bulan = $request->get('bulan', now()->month);
        $tahun = $request->get('tahun', now()->year);

        // Total semua
        $totalTunai = Donasi::where('jenis', 'Tunai')
                            ->where('status_verifikasi', 'approved')
                            ->count();
        $totalBarang = Donasi::where('jenis', 'Barang')
                             ->where('status_verifikasi', 'approved')
                             ->count();

        // Bulan ini
        $tunaiThisMonth = Donasi::where('jenis', 'Tunai')
                                ->where('status_verifikasi', 'approved')
                                ->whereMonth('tanggal', $bulan)
                                ->whereYear('tanggal', $tahun)
                                ->count();
        $barangThisMonth = Donasi::where('jenis', 'Barang')
                                 ->where('status_verifikasi', 'approved')
                                 ->whereMonth('tanggal', $bulan)
                                 ->whereYear('tanggal', $tahun)
                                 ->count();

        // Pending
        $pendingCount = Donasi::where('status_verifikasi', 'pending')->count();

        // Donasi per bulan (untuk chart)
        $donasiPerBulan = [];
        for ($i = 1; $i <= 12; $i++) {
            $donasiPerBulan[] = [
                'bulan' => $i,
                'tunai' => Donasi::where('jenis', 'Tunai')
                                 ->where('status_verifikasi', 'approved')
                                 ->whereMonth('tanggal', $i)
                                 ->whereYear('tanggal', $tahun)
                                 ->count(),
                'barang' => Donasi::where('jenis', 'Barang')
                                  ->where('status_verifikasi', 'approved')
                                  ->whereMonth('tanggal', $i)
                                  ->whereYear('tanggal', $tahun)
                                  ->count()
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'total_tunai' => $totalTunai,
                'total_barang' => $totalBarang,
                'tunai_bulan_ini' => $tunaiThisMonth,
                'barang_bulan_ini' => $barangThisMonth,
                'pending' => $pendingCount,
                'donasi_per_bulan' => $donasiPerBulan
            ]
        ]);
    }

    /**
     * Helper: Save Base64 Image
     */
    private function saveBase64Image($base64String, $folder = 'uploads')
    {
        try {
            // Cek apakah string base64 valid
            if (!preg_match('/^data:image\/(\w+);base64,/', $base64String, $matches)) {
                return null;
            }

            $extension = strtolower($matches[1]);
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            
            if (!in_array($extension, $allowedExtensions)) {
                return null;
            }

            // Decode base64
            $base64Data = substr($base64String, strpos($base64String, ',') + 1);
            $imageData = base64_decode($base64Data);

            if ($imageData === false) {
                return null;
            }

            // Generate unique filename
            $filename = $folder . '/' . Str::uuid() . '.' . $extension;

            // Simpan ke storage
            Storage::disk('public')->put($filename, $imageData);

            return $filename;

        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Kirim Ucapan Terima Kasih ke Donatur (Admin Only)
     */
    public function sendThankYou(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'pesan' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $donasi = Donasi::find($id);

        if (!$donasi) {
            return response()->json([
                'success' => false,
                'message' => 'Data donasi tidak ditemukan'
            ], 404);
        }

        if (!$donasi->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'Donasi ini tidak memiliki user terkait'
            ], 400);
        }

        // Kirim notifikasi ucapan terima kasih
        NotificationService::ucapanTerimakasih($donasi, $request->pesan);

        // Log aktivitas
        AktivitasLog::create([
            'user_id' => auth()->id(),
            'kategori' => 'Donasi',
            'text' => "Mengirim ucapan terima kasih ke {$donasi->donatur}",
            'time' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ucapan terima kasih berhasil dikirim'
        ]);
    }
}
