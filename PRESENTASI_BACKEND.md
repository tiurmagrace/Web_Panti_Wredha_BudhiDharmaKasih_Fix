# PRESENTASI BACKEND - 10 MENIT (2 ORANG)
## Sistem Informasi Panti Wredha Budi Dharma Kasih

---

# 👤 ORANG 1 - ARSITEKTUR, DATABASE & API (55% = 5.5 menit)

---

## 1. TEKNOLOGI YANG DIGUNAKAN (45 detik)

| Komponen | Teknologi | Fungsi |
|----------|-----------|--------|
| Framework | **Laravel 10** | Framework PHP untuk membangun API dan web admin |
| Database | **PostgreSQL** | Database relasional untuk menyimpan semua data |
| Cloud | **Supabase** | Hosting database di cloud (AWS Singapore) |
| Auth | **Laravel Sanctum** | Sistem login dengan token untuk keamanan API |
| Frontend | **Blade + Vue.js** | Template engine + reactive UI untuk web admin |

### Kenapa Pilih Laravel?
- Framework PHP paling populer dengan dokumentasi lengkap
- Fitur bawaan: Authentication, Validation, ORM
- Struktur rapi dengan pola MVC

---

## 2. ARSITEKTUR SISTEM (1.5 menit)

### Diagram Arsitektur:

```
┌─────────────────────────────────────────────────────────────────┐
│                         INTERNET                                 │
└─────────────────────────────────────────────────────────────────┘
         │                                    │
         ▼                                    ▼
┌─────────────────┐                  ┌─────────────────┐
│   Mobile App    │                  │   Web Browser   │
│   (Flutter)     │                  │   (Chrome/dll)  │
│                 │                  │                 │
│   Donatur       │                  │   Admin Panti   │
└────────┬────────┘                  └────────┬────────┘
         │                                    │
         │         HTTP Request (JSON)        │
         └──────────────┬─────────────────────┘
                        ▼
              ┌─────────────────┐
              │   BACKEND       │
              │   Laravel 10    │
              │                 │
              │  • REST API     │
              │  • Web Admin    │
              │  • Auth System  │
              └────────┬────────┘
                       │
                       │  SQL Query
                       ▼
              ┌─────────────────┐
              │   DATABASE      │
              │   PostgreSQL    │
              │   (Supabase)    │
              └─────────────────┘
```

### Penjelasan Flow:
1. **Donatur** buka mobile app → Request ke Backend API
2. **Admin** buka web browser → Request ke Backend
3. **Backend Laravel** proses request → Query database
4. **Database Supabase** simpan/ambil data
5. Data **SINKRON** antara mobile dan web (database sama)

### Pola MVC:
- **Model** → Representasi tabel (User, Penghuni, Donasi, Barang)
- **View** → Tampilan web admin (Blade + Vue.js)
- **Controller** → Logic bisnis dan API endpoints

---

## 3. STRUKTUR DATABASE (1.5 menit)

### Tabel-Tabel Utama:

| No | Tabel | Fungsi | Field Penting |
|----|-------|--------|---------------|
| 1 | `users` | Data user (admin & donatur) | nama, email, password, role |
| 2 | `penghuni` | Data lansia di panti | nik, nama, usia, paviliun, foto |
| 3 | `donasi` | Data donasi masuk | donatur, jenis, jumlah, status_verifikasi |
| 4 | `barang` | Stok barang di gudang | nama, kategori, sisa_stok, expired |
| 5 | `notifikasi` | Notifikasi untuk user | type, title, text, status |
| 6 | `feedback` | Pesan dari pengunjung | nama, email, pesan |
| 7 | `aktivitas_log` | Log aktivitas admin | kategori, text, time |

### Relasi Antar Tabel:

```
users ──┬──▶ donasi        (1 user bisa punya BANYAK donasi)
        └──▶ notifikasi    (1 user bisa punya BANYAK notifikasi)

barang ────▶ pengambilan_stok (1 barang punya RIWAYAT pengambilan)
```

### Koneksi Database Cloud:

```env
DB_CONNECTION=pgsql
DB_HOST=aws-1-ap-southeast-1.pooler.supabase.com
DB_PORT=5432
DB_SSLMODE=require   # Koneksi aman dengan SSL
```

**Keuntungan:** Database bisa diakses dari mana saja, backup otomatis, koneksi aman.

---

## 4. DAFTAR API ENDPOINTS (1.5 menit)

### API Authentication:

| Method | Endpoint | Fungsi |
|--------|----------|--------|
| POST | `/api/auth/register` | Registrasi donatur baru |
| POST | `/api/auth/login` | Login user/admin |
| POST | `/api/auth/logout` | Logout (hapus token) |

### API Penghuni (Admin Only):

| Method | Endpoint | Fungsi |
|--------|----------|--------|
| GET | `/api/penghuni` | Ambil semua data penghuni |
| POST | `/api/penghuni` | Tambah penghuni baru |
| PUT | `/api/penghuni/{id}` | Update data penghuni |
| DELETE | `/api/penghuni/{id}` | Hapus penghuni |
| GET | `/api/penghuni/statistics` | Statistik jumlah penghuni |

### API Donasi:

| Method | Endpoint | Fungsi |
|--------|----------|--------|
| GET | `/api/donasi` | Ambil semua donasi |
| POST | `/api/donasi` | Submit donasi baru |
| PATCH | `/api/donasi/{id}/verify` | Verifikasi donasi (admin) |
| GET | `/api/donasi/admin/statistics` | Statistik donasi |

### API Barang:

| Method | Endpoint | Fungsi |
|--------|----------|--------|
| GET | `/api/barang` | Ambil semua stok barang |
| POST | `/api/barang` | Tambah barang baru |
| POST | `/api/barang/ambil-stok` | Catat pengambilan stok |
| GET | `/api/barang/statistics` | Statistik stok gudang |

**Total: 30+ endpoint API** untuk semua fitur aplikasi.

---

## 5. CONTOH RESPONSE API (30 detik)

```json
// GET /api/donasi/admin/statistics
{
    "success": true,
    "data": {
        "total_tunai": 45,
        "total_barang": 32,
        "total_donasi_bulan_ini": 8,
        "kategori_bulan_ini": {
            "Tunai": 3,
            "Sembako": 4,
            "Pakaian": 1
        },
        "pending": 2
    }
}
```

**Format standar:** `success` (true/false), `message`, `data`

---
---

# 👤 ORANG 2 - AUTHENTICATION, FITUR & KEAMANAN (45% = 4.5 menit)

---

## 1. SISTEM AUTHENTICATION (1.5 menit)

### Cara Kerja Login dengan Token:

```
┌──────────────┐                      ┌──────────────┐
│    USER      │                      │   BACKEND    │
└──────┬───────┘                      └──────┬───────┘
       │                                     │
       │  1. Kirim email + password          │
       │────────────────────────────────────▶│
       │                                     │
       │                          2. Cek ke database
       │                          3. Jika cocok, buat TOKEN
       │                                     │
       │  4. Kirim token ke user             │
       │◀────────────────────────────────────│
       │                                     │
       │  5. Simpan token di device          │
       │                                     │
       │  6. Request berikutnya pakai token  │
       │────────────────────────────────────▶│
       │                                     │
       │                          7. Validasi token
       │                          8. Proses request
```

### Contoh Response Login:

```json
{
    "success": true,
    "message": "Login berhasil",
    "data": {
        "id": 1,
        "nama": "Administrator",
        "email": "admin@pantibdk.com",
        "role": "admin"
    },
    "token": "1|abc123xyz789..."
}
```

**Token disimpan di mobile app dan dipakai untuk semua request selanjutnya.**

### Keamanan Password:

```php
// Password di-HASH, tidak disimpan plain text
$user->password = Hash::make('password123');
// Hasil: $2y$10$92IXUNpkjO0rOQ5byMi... (tidak bisa dibaca)
```

---

## 2. FITUR NOTIFIKASI OTOMATIS (1.5 menit)

### Kapan Notifikasi Dikirim:

| Event | Dikirim ke | Contoh Pesan |
|-------|------------|--------------|
| Donasi masuk | Admin | "Donasi Tunai dari Budi sebesar Rp 500.000 menunggu verifikasi" |
| Donasi diterima | Donatur | "Terima kasih! Donasi Anda telah diterima" |
| Donasi ditolak | Donatur | "Mohon maaf, donasi tidak dapat diverifikasi" |
| Stok menipis | Admin | "Stok Beras tinggal 5 Karung" |
| Barang hampir expired | Admin | "Susu UHT akan kadaluarsa dalam 18 hari" |

### Cara Kerja di Backend:

```php
// Saat donasi masuk → otomatis kirim notifikasi ke admin
public function store(Request $request)
{
    $donasi = Donasi::create($request->all());
    
    // Kirim notifikasi otomatis
    NotificationService::notifyAdmin(
        'donasi_masuk',
        'Donasi Baru Masuk',
        "Donasi dari {$donasi->donatur} menunggu verifikasi"
    );
    
    return response()->json(['success' => true]);
}
```

```php
// Saat admin verifikasi → kirim notifikasi ke donatur
public function verify($id)
{
    $donasi = Donasi::find($id);
    $donasi->update(['status_verifikasi' => 'approved']);
    
    NotificationService::notifyUser(
        $donasi->user_id,
        'donasi_diterima',
        'Donasi Anda Telah Diterima',
        'Terima kasih atas donasi Anda!'
    );
}
```

---

## 3. KEAMANAN SISTEM (1 menit)

| Aspek | Implementasi | Penjelasan |
|-------|--------------|------------|
| Password | **Bcrypt Hash** | Password tidak disimpan plain text |
| API Access | **Token Sanctum** | Setiap request harus ada token valid |
| Validasi | **Request Validation** | Semua input dicek sebelum diproses |
| Role | **Admin vs Donatur** | Akses berbeda sesuai role |
| Database | **SSL Connection** | Koneksi ke Supabase terenkripsi |

### Contoh Validasi Input:

```php
$request->validate([
    'nama' => 'required|string|max:255',
    'email' => 'required|email|unique:users',
    'password' => 'required|min:6'
]);
// Jika tidak valid → return error, tidak diproses
```

---

## 4. KESIMPULAN (30 detik)

### Backend Laravel Menyediakan:

| Fitur | Keterangan |
|-------|------------|
| ✅ REST API | 30+ endpoint untuk mobile app Flutter |
| ✅ Web Admin | Dashboard untuk pengelola panti |
| ✅ Database Cloud | Data tersimpan aman di Supabase |
| ✅ Notifikasi Otomatis | Admin dan donatur dapat info realtime |
| ✅ Keamanan | Token auth, password hash, validasi input |

```
Mobile App  ──┐
              ├──▶  Backend Laravel  ──▶  Database Supabase
Web Admin   ──┘

Data SINKRON karena pakai database yang SAMA
```

**Terima kasih!**

---
---

# RINGKASAN PEMBAGIAN

| Orang | Materi | Persentase | Durasi |
|-------|--------|------------|--------|
| **Orang 1** | Teknologi, Arsitektur, Database, API Endpoints | **55%** | **5.5 menit** |
| **Orang 2** | Authentication, Notifikasi, Keamanan, Kesimpulan | **45%** | **4.5 menit** |
| **Total** | | **100%** | **10 menit** |
