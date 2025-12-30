# PEMBAGIAN PRESENTASI BACKEND
## Panti Wredha Budi Dharma Kasih

---

# ORANG 1 (55%) - Core Backend & Database

## 1. Arsitektur & Setup Project (10 menit)

### File yang dijelaskan:
- `.env` - Konfigurasi environment
- `config/database.php` - Konfigurasi database

### Poin presentasi:
- Struktur folder Laravel (MVC pattern)
- Koneksi database PostgreSQL (Supabase)
- Environment variables untuk keamanan

### Code yang ditunjukkan:
```env
# .env
DB_CONNECTION=pgsql
DB_HOST=aws-1-ap-southeast-1.pooler.supabase.com
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres.omlrnxdqdfxfaypaarho
```

---

## 2. Models & Migrations (15 menit)

### File yang dijelaskan:
- `app/Models/User.php`
- `app/Models/Penghuni.php`
- `app/Models/Donasi.php`
- `app/Models/Barang.php`
- `database/migrations/2025_12_20_132227_create_penghuni_table.php`
- `database/migrations/2025_12_20_132328_create_donasi_and_barang_tables.php`

### Poin presentasi:
- Eloquent ORM Laravel
- Relasi antar tabel (hasMany, belongsTo)
- Migration untuk membuat struktur database
- Fillable, casts, dan hidden attributes

### Code yang ditunjukkan:

```php
// app/Models/User.php
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = ['nama', 'email', 'password', 'no_hp', 'role'];
    
    protected $hidden = ['password', 'remember_token'];

    // Relasi: User punya banyak Donasi
    public function donasi()
    {
        return $this->hasMany(Donasi::class);
    }

    // Relasi: User punya banyak Notifikasi
    public function notifikasi()
    {
        return $this->hasMany(Notifikasi::class);
    }
}
```

```php
// app/Models/Penghuni.php
class Penghuni extends Model
{
    protected $table = 'penghuni';
    
    protected $fillable = [
        'nik', 'nama', 'ttl', 'usia', 'kota', 'alamat',
        'agama', 'gender', 'status', 'pj', 'hubungan',
        'telp', 'alamat_pj', 'status_sehat', 'penyakit',
        'alergi', 'kebutuhan', 'obat', 'tgl_masuk',
        'rujukan', 'paviliun', 'catatan', 'foto',
        'status_penghuni', 'tgl_keluar', 'alasan_keluar'
    ];
}
```

```php
// Migration penghuni
Schema::create('penghuni', function (Blueprint $table) {
    $table->id();
    $table->string('nik')->unique();
    $table->string('nama');
    $table->string('ttl')->nullable();
    $table->integer('usia')->nullable();
    $table->string('kota')->nullable();
    $table->text('alamat')->nullable();
    $table->string('agama')->nullable();
    $table->enum('gender', ['Pria', 'Wanita'])->nullable();
    $table->string('status')->nullable();
    // ... field lainnya
    $table->timestamps();
});
```

---

## 3. API Authentication (10 menit)

### File yang dijelaskan:
- `app/Http/Controllers/Api/AuthController.php`
- `routes/api.php`
- `config/sanctum.php`

### Poin presentasi:
- Laravel Sanctum untuk API authentication
- Token-based authentication
- Login, Register, Logout flow
- Middleware auth:sanctum

### Code yang ditunjukkan:

```php
// app/Http/Controllers/Api/AuthController.php

// Register
public function register(Request $request)
{
    $validated = $request->validate([
        'nama' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6',
        'no_hp' => 'nullable|string'
    ]);

    $user = User::create([
        'nama' => $validated['nama'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'no_hp' => $validated['no_hp'] ?? null,
        'role' => 'donatur'
    ]);

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'success' => true,
        'message' => 'Registrasi berhasil',
        'data' => $user,
        'token' => $token
    ], 201);
}

// Login
public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    if (!Auth::attempt($credentials)) {
        return response()->json([
            'success' => false,
            'message' => 'Email atau password salah'
        ], 401);
    }

    $user = Auth::user();
    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'success' => true,
        'message' => 'Login berhasil',
        'data' => $user,
        'token' => $token
    ]);
}
```

```php
// routes/api.php
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
});
```

---

## 4. API CRUD Utama (20 menit)

### File yang dijelaskan:
- `app/Http/Controllers/Api/PenghuniController.php`
- `app/Http/Controllers/Api/DonasiController.php`
- `app/Http/Controllers/Api/BarangController.php`

### Poin presentasi:
- RESTful API design
- CRUD operations (Create, Read, Update, Delete)
- Request validation
- Response format JSON
- Statistics endpoint

### Code yang ditunjukkan:

```php
// app/Http/Controllers/Api/PenghuniController.php

// GET semua penghuni
public function index()
{
    $penghuni = Penghuni::orderBy('created_at', 'desc')->get();
    
    return response()->json([
        'success' => true,
        'data' => $penghuni
    ]);
}

// POST tambah penghuni
public function store(Request $request)
{
    $validated = $request->validate([
        'nik' => 'required|unique:penghuni',
        'nama' => 'required|string',
        'ttl' => 'nullable|string',
        // ... validasi lainnya
    ]);

    $penghuni = Penghuni::create($validated);

    // Log aktivitas
    AktivitasLog::create([
        'user_id' => auth()->id(),
        'kategori' => 'Penghuni',
        'text' => "Menambahkan penghuni baru: {$penghuni->nama}",
        'time' => now()
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Penghuni berhasil ditambahkan',
        'data' => $penghuni
    ], 201);
}

// PUT update penghuni
public function update(Request $request, $id)
{
    $penghuni = Penghuni::findOrFail($id);
    $penghuni->update($request->all());

    return response()->json([
        'success' => true,
        'message' => 'Data penghuni berhasil diupdate',
        'data' => $penghuni
    ]);
}

// DELETE hapus penghuni
public function destroy($id)
{
    $penghuni = Penghuni::findOrFail($id);
    $penghuni->delete();

    return response()->json([
        'success' => true,
        'message' => 'Penghuni berhasil dihapus'
    ]);
}

// GET statistics
public function statistics()
{
    return response()->json([
        'success' => true,
        'data' => [
            'total' => Penghuni::where('status_penghuni', 'Aktif')->count(),
            'total_semua' => Penghuni::count(),
            'keluar' => Penghuni::where('status_penghuni', 'Keluar')->count(),
            'meninggal' => Penghuni::where('status_penghuni', 'Meninggal')->count()
        ]
    ]);
}
```

```php
// app/Http/Controllers/Api/DonasiController.php

// GET statistics untuk dashboard
public function adminStatistics()
{
    $now = now();
    
    return response()->json([
        'success' => true,
        'data' => [
            'total_tunai' => Donasi::where('jenis', 'Tunai')
                                   ->where('status_verifikasi', 'approved')
                                   ->count(),
            'total_barang' => Donasi::where('jenis', 'Barang')
                                    ->where('status_verifikasi', 'approved')
                                    ->count(),
            'tunai_bulan_ini' => Donasi::where('jenis', 'Tunai')
                                       ->whereMonth('tanggal', $now->month)
                                       ->whereYear('tanggal', $now->year)
                                       ->count(),
            'pending' => Donasi::where('status_verifikasi', 'pending')->count()
        ]
    ]);
}
```

---
---

# ORANG 2 (45%) - Fitur Tambahan & Integrasi

## 1. API Pendukung (10 menit)

### File yang dijelaskan:
- `app/Http/Controllers/Api/NotifikasiController.php`
- `app/Http/Controllers/Api/FeedbackController.php`
- `app/Http/Controllers/Api/AktivitasLogController.php`
- `app/Models/Notifikasi.php`

### Poin presentasi:
- Sistem notifikasi untuk donatur
- Feedback dari pengunjung
- Activity logging untuk admin

### Code yang ditunjukkan:

```php
// app/Http/Controllers/Api/NotifikasiController.php

public function index(Request $request)
{
    $user = $request->user();
    
    // Admin: lihat semua notifikasi
    // Donatur: hanya notifikasi miliknya
    if ($user->role === 'admin') {
        $notifikasi = Notifikasi::orderBy('created_at', 'desc')->get();
    } else {
        $notifikasi = Notifikasi::where('user_id', $user->id)
                                ->orderBy('created_at', 'desc')
                                ->get();
    }

    return response()->json([
        'success' => true,
        'data' => $notifikasi
    ]);
}

public function markAsRead($id)
{
    $notifikasi = Notifikasi::findOrFail($id);
    $notifikasi->update(['status' => 'read']);

    return response()->json([
        'success' => true,
        'message' => 'Notifikasi ditandai sudah dibaca'
    ]);
}

public function markAllAsRead(Request $request)
{
    Notifikasi::where('user_id', $request->user()->id)
              ->where('status', 'unread')
              ->update(['status' => 'read']);

    return response()->json([
        'success' => true,
        'message' => 'Semua notifikasi ditandai sudah dibaca'
    ]);
}
```

```php
// app/Models/Notifikasi.php
class Notifikasi extends Model
{
    protected $table = 'notifikasi';
    
    protected $fillable = [
        'user_id', 'type', 'title', 'text', 
        'date', 'status', 'metadata'
    ];

    protected $casts = [
        'metadata' => 'array',
        'date' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
```

---

## 2. Fitur Khusus (15 menit)

### File yang dijelaskan:
- `app/Http/Controllers/Api/DonasiController.php` (method verify & thankYou)
- `app/Helpers/FileUploadHelper.php`

### Poin presentasi:
- Verifikasi donasi (approve/reject)
- Kirim notifikasi ke donatur
- Upload foto dengan Base64

### Code yang ditunjukkan:

```php
// Verifikasi Donasi
public function verify(Request $request, $id)
{
    $donasi = Donasi::findOrFail($id);
    
    $validated = $request->validate([
        'status_verifikasi' => 'required|in:approved,rejected',
        'catatan' => 'nullable|string'
    ]);

    $donasi->update([
        'status_verifikasi' => $validated['status_verifikasi']
    ]);

    // Kirim notifikasi ke donatur
    if ($donasi->user_id) {
        $isApproved = $validated['status_verifikasi'] === 'approved';
        
        Notifikasi::create([
            'user_id' => $donasi->user_id,
            'type' => $isApproved ? 'donasi_diterima' : 'donasi_ditolak',
            'title' => $isApproved ? 'Donasi Anda Telah Diterima' : 'Status Donasi',
            'text' => $isApproved 
                ? "Terima kasih! Donasi {$donasi->jenis} Anda telah diverifikasi."
                : "Mohon maaf, donasi Anda tidak dapat diverifikasi. Alasan: {$validated['catatan']}",
            'date' => now(),
            'status' => 'unread',
            'metadata' => ['donasi_id' => $donasi->id]
        ]);
    }

    return response()->json([
        'success' => true,
        'message' => 'Donasi berhasil diverifikasi',
        'data' => $donasi
    ]);
}

// Kirim Ucapan Terima Kasih
public function sendThankYou(Request $request, $id)
{
    $donasi = Donasi::findOrFail($id);

    if (!$donasi->user_id) {
        return response()->json([
            'success' => false,
            'message' => 'Donasi ini tidak memiliki user terdaftar'
        ], 400);
    }

    $pesan = $request->pesan ?? 'Kami mengucapkan terima kasih yang sebesar-besarnya...';

    Notifikasi::create([
        'user_id' => $donasi->user_id,
        'type' => 'ucapan_terimakasih',
        'title' => 'Ucapan Terima Kasih',
        'text' => $pesan,
        'date' => now(),
        'status' => 'unread',
        'metadata' => ['donasi_id' => $donasi->id]
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Ucapan terima kasih berhasil dikirim'
    ]);
}
```

```php
// app/Helpers/FileUploadHelper.php
class FileUploadHelper
{
    public static function uploadBase64Image($base64String, $folder = 'uploads')
    {
        // Decode base64
        if (preg_match('/^data:image\/(\w+);base64,/', $base64String, $type)) {
            $base64String = substr($base64String, strpos($base64String, ',') + 1);
            $type = strtolower($type[1]);
            
            $base64String = base64_decode($base64String);
            
            // Generate filename
            $filename = uniqid() . '.' . $type;
            $path = public_path("uploads/{$folder}");
            
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }
            
            file_put_contents("{$path}/{$filename}", $base64String);
            
            return "/uploads/{$folder}/{$filename}";
        }
        
        return null;
    }
}
```

---

## 3. Database Seeder (10 menit)

### File yang dijelaskan:
- `database/seeders/DatabaseSeeder.php`
- `database/seeders/MigrateFromMysqlSeeder.php`

### Poin presentasi:
- Seeder untuk data awal (admin, donatur, penghuni, dll)
- Migrasi data dari MySQL ke PostgreSQL
- Perbedaan syntax MySQL vs PostgreSQL

### Code yang ditunjukkan:

```php
// database/seeders/DatabaseSeeder.php

public function run(): void
{
    // Disable foreign key untuk PostgreSQL
    DB::statement('SET session_replication_role = replica');
    
    // Truncate tables
    DB::table('donasi')->truncate();
    DB::table('barang')->truncate();
    DB::table('penghuni')->truncate();
    DB::table('users')->truncate();
    
    // Enable kembali
    DB::statement('SET session_replication_role = DEFAULT');

    // Buat Admin
    User::create([
        'nama' => 'Administrator',
        'email' => 'admin@pantibdk.com',
        'password' => 'password123',
        'role' => 'admin'
    ]);

    // Buat Donatur
    User::create([
        'nama' => 'Budi Santoso',
        'email' => 'budi@example.com',
        'no_hp' => '081234567890',
        'password' => 'password123',
        'role' => 'donatur'
    ]);

    // Buat Penghuni
    Penghuni::create([
        'nik' => '3303012345670001',
        'nama' => 'Suparman',
        'ttl' => 'Purbalingga, 15 Mei 1950',
        'usia' => 74,
        'paviliun' => 'BOUGENVILLE 1',
        // ... data lainnya
    ]);
}
```

```php
// database/seeders/MigrateFromMysqlSeeder.php

public function run(): void
{
    // Konfigurasi MySQL lokal
    config(['database.connections.mysql_local' => [
        'driver' => 'mysql',
        'host' => '127.0.0.1',
        'database' => 'panti_wredha_bdk',
        'username' => 'root',
        'password' => '',
    ]]);

    // Disable foreign key di PostgreSQL
    DB::statement('SET session_replication_role = replica');

    // Migrasi setiap tabel
    $tables = ['users', 'penghuni', 'donasi', 'barang', 'notifikasi'];

    foreach ($tables as $table) {
        $data = DB::connection('mysql_local')->table($table)->get();
        
        if ($data->isNotEmpty()) {
            DB::table($table)->truncate();
            DB::table($table)->insert(
                $data->map(fn($row) => (array) $row)->toArray()
            );
        }
    }

    DB::statement('SET session_replication_role = DEFAULT');
}
```

---

## 4. Integrasi & Deployment (10 menit)

### File yang dijelaskan:
- `.env` (konfigurasi Supabase)
- `API_DOCUMENTATION.md`
- `routes/api.php`

### Poin presentasi:
- Koneksi ke Supabase PostgreSQL
- Session Pooler vs Direct Connection
- Dokumentasi API untuk tim mobile
- Testing API dengan browser/Postman

### Code yang ditunjukkan:

```env
# .env - Konfigurasi Supabase
DB_CONNECTION=pgsql
DB_HOST=aws-1-ap-southeast-1.pooler.supabase.com
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres.omlrnxdqdfxfaypaarho
DB_PASSWORD=pantiwredapbg
DB_SSLMODE=require
```

```php
// routes/api.php - Semua endpoint API

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/admin/login', [AuthController::class, 'adminLogin']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    
    // Penghuni
    Route::apiResource('penghuni', PenghuniController::class);
    Route::get('/penghuni/statistics', [PenghuniController::class, 'statistics']);
    
    // Donasi
    Route::apiResource('donasi', DonasiController::class);
    Route::patch('/donasi/{id}/verify', [DonasiController::class, 'verify']);
    Route::post('/donasi/{id}/thank-you', [DonasiController::class, 'sendThankYou']);
    Route::get('/donasi/admin/statistics', [DonasiController::class, 'adminStatistics']);
    
    // Barang
    Route::apiResource('barang', BarangController::class);
    Route::get('/barang/statistics', [BarangController::class, 'statistics']);
    
    // Notifikasi
    Route::get('/notifikasi', [NotifikasiController::class, 'index']);
    Route::patch('/notifikasi/{id}/mark-as-read', [NotifikasiController::class, 'markAsRead']);
    Route::patch('/notifikasi/mark-all-as-read', [NotifikasiController::class, 'markAllAsRead']);
    
    // Feedback & Log
    Route::apiResource('feedback', FeedbackController::class);
    Route::get('/aktivitas-log', [AktivitasLogController::class, 'index']);
});
```

---

## DEMO API (Untuk Kedua Presenter)

### Endpoint yang bisa di-demo:

1. **Login Admin**
   ```
   POST /api/admin/login
   Body: { "email": "admin@pantibdk.com", "password": "password123" }
   ```

2. **Get Penghuni**
   ```
   GET /api/penghuni
   Header: Authorization: Bearer {token}
   ```

3. **Tambah Donasi**
   ```
   POST /api/donasi
   Body: { "donatur": "Test", "jenis": "Tunai", "jumlah": "Rp 100.000" }
   ```

4. **Verifikasi Donasi**
   ```
   PATCH /api/donasi/1/verify
   Body: { "status_verifikasi": "approved" }
   ```

5. **Get Statistics**
   ```
   GET /api/donasi/admin/statistics
   ```

---

## Tips Presentasi

1. **Buka 2 tab browser:**
   - Tab 1: Web admin (http://localhost:8000/admin)
   - Tab 2: API testing (Postman atau browser)

2. **Tunjukkan flow lengkap:**
   - Login → Get data → Create → Update → Delete

3. **Jelaskan response JSON:**
   - success: true/false
   - message: pesan untuk user
   - data: data yang diminta

4. **Highlight keamanan:**
   - Password di-hash
   - Token authentication
   - Validasi input
