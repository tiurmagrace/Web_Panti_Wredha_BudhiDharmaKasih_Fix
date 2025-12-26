# 📱 API Documentation - Panti Wredha Mobile App

## 🔗 Base URL & Configuration

### Development (Ngrok)
```
Base URL: https://henley-hemathermal-superbly.ngrok-free.dev/api
```

### Production (setelah deploy)
```
Base URL: https://your-domain.com/api
```

> ⚠️ **Penting**: URL Ngrok berubah setiap restart. Update `baseUrl` jika ngrok di-restart.

---

## 🔑 Authentication

Project ini menggunakan **Laravel Sanctum** (Token-based Authentication).
- **Tidak ada API Key statis**
- Token didapat setelah login
- Token digunakan di header `Authorization: Bearer {token}`

---

## 📋 Required Headers

### Semua Request (Wajib)
```
Accept: application/json
Content-Type: application/json
ngrok-skip-browser-warning: true
```

### Request yang Butuh Login (Tambahan)
```
Authorization: Bearer {token}
```

---

## 🔐 AUTH ENDPOINTS

### Register
Mendaftarkan user baru.

```
POST /auth/register
```

**Request Body:**
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "phone": "081234567890"
}
```

**Response Success (201):**
```json
{
    "success": true,
    "message": "Registrasi berhasil",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "phone": "081234567890"
        },
        "token": "1|abc123xyz..."
    }
}
```

---

### Login
Login untuk mendapatkan token.

```
POST /auth/login
```

**Request Body:**
```json
{
    "email": "john@example.com",
    "password": "password123"
}
```

**Response Success (200):**
```json
{
    "success": true,
    "message": "Login berhasil",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "role": "user"
        },
        "token": "1|abc123xyz..."
    }
}
```

---

### Logout
```
POST /auth/logout
Authorization: Bearer {token}
```

**Response Success (200):**
```json
{
    "success": true,
    "message": "Logout berhasil"
}
```

---

### Get Profile
```
GET /auth/profile
Authorization: Bearer {token}
```

**Response Success (200):**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "phone": "081234567890",
        "role": "user"
    }
}
```

---

## 💰 DONASI ENDPOINTS

### Get Public Donasi List (Tanpa Login)
```
GET /donasi/public
```

**Query Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| jenis | string | Filter: "Barang" atau "Tunai" |
| per_page | int | Jumlah item per halaman (default: 10) |
| page | int | Nomor halaman |

**Response Success (200):**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "donatur": "Budi Santoso",
            "jenis": "Tunai",
            "detail": "Transfer Bank BCA",
            "jumlah": "Rp 500.000",
            "tanggal": "2025-12-25"
        }
    ],
    "pagination": {
        "current_page": 1,
        "last_page": 5,
        "per_page": 10,
        "total": 50
    }
}
```

---

### Get Public Statistics (Tanpa Login)
```
GET /donasi/public/statistics
```

**Response Success (200):**
```json
{
    "success": true,
    "data": {
        "total_donatur": 150,
        "total_donasi_tunai": 75,
        "total_donasi_barang": 80,
        "donasi_bulan_ini": 25,
        "total_donasi": 155
    }
}
```

---

### Submit Donasi (Perlu Login)
```
POST /donasi
Authorization: Bearer {token}
```

**Request Body (Donasi Tunai):**
```json
{
    "donatur": "John Doe",
    "jenis": "Tunai",
    "detail": "Transfer Bank BCA",
    "jumlah": "Rp 500.000",
    "bukti": "data:image/jpeg;base64,/9j/4AAQ..."
}
```

**Request Body (Donasi Barang):**
```json
{
    "donatur": "John Doe",
    "jenis": "Barang",
    "detail": "Sembako",
    "jumlah": "10 Pcs",
    "bukti": "data:image/jpeg;base64,/9j/4AAQ..."
}
```

**Detail Options untuk Barang:**
- Sembako
- Pakaian
- Alat Kebersihan
- Alat Kesehatan
- Peralatan Rumah Tangga
- Elektronik
- Perlengkapan Tidur
- Buku & Hiburan
- Perlengkapan Medis
- Lainnya

**Response Success (201):**
```json
{
    "success": true,
    "message": "Donasi berhasil dikirim dan menunggu verifikasi",
    "data": {
        "id": 10,
        "donatur": "John Doe",
        "jenis": "Tunai",
        "detail": "Transfer Bank BCA",
        "jumlah": "Rp 500.000",
        "status_verifikasi": "pending",
        "tanggal": "2025-12-26"
    }
}
```

---

### Get My Donations (Perlu Login)
Riwayat donasi user yang login.

```
GET /donasi/my-donations
Authorization: Bearer {token}
```

**Query Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| status_verifikasi | string | Filter: "pending", "approved", "rejected" |

**Response Success (200):**
```json
{
    "success": true,
    "data": [
        {
            "id": 10,
            "donatur": "John Doe",
            "jenis": "Tunai",
            "detail": "Transfer Bank BCA",
            "jumlah": "Rp 500.000",
            "tanggal": "2025-12-26",
            "status_verifikasi": "approved",
            "bukti": "donasi/uuid-123.jpg"
        }
    ]
}
```

**Status Verifikasi:**
- `pending` - Menunggu verifikasi admin
- `approved` - Sudah disetujui
- `rejected` - Ditolak

---

### Get Single Donasi
```
GET /donasi/{id}
Authorization: Bearer {token}
```

---

## 🔔 NOTIFIKASI ENDPOINTS

### Get Notifications (Perlu Login)
```
GET /notifikasi
Authorization: Bearer {token}
```

**Response Success (200):**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "type": "donasi_diterima",
            "title": "Donasi Diterima",
            "text": "Donasi Tunai Anda sebesar Rp 500.000 telah diterima.",
            "date": "2025-12-26T10:00:00Z",
            "status": "unread"
        }
    ]
}
```

**Notification Types:**
- `donasi_diterima` - Donasi sudah diverifikasi
- `donasi_ditolak` - Donasi ditolak
- `ucapan_terimakasih` - Ucapan terima kasih dari admin

---

### Get Unread Count
```
GET /notifikasi/unread-count
Authorization: Bearer {token}
```

---

### Mark as Read
```
PATCH /notifikasi/{id}/mark-as-read
Authorization: Bearer {token}
```

---

### Mark All as Read
```
PATCH /notifikasi/mark-all-as-read
Authorization: Bearer {token}
```

---

## 📝 FEEDBACK ENDPOINT

### Submit Feedback (Tanpa Login)
```
POST /feedback
```

**Request Body:**
```json
{
    "nama": "John Doe",
    "email": "john@example.com",
    "telepon": "081234567890",
    "pesan": "Terima kasih atas pelayanannya..."
}
```

---

## ⚠️ ERROR RESPONSES

### 401 Unauthorized
```json
{
    "success": false,
    "message": "Unauthenticated"
}
```

### 422 Validation Error
```json
{
    "success": false,
    "message": "Validasi gagal",
    "errors": {
        "email": ["Email sudah terdaftar"]
    }
}
```

### 500 Server Error
```json
{
    "success": false,
    "message": "Terjadi kesalahan server"
}
```

---

## 🖼️ IMAGE HANDLING

### Upload (Base64)
```json
{
    "bukti": "data:image/jpeg;base64,/9j/4AAQSkZJRg..."
}
```

### Display Image
```
https://henley-hemathermal-superbly.ngrok-free.dev/storage/{path}
```

---

## 📱 FLUTTER IMPLEMENTATION

### pubspec.yaml
```yaml
dependencies:
  http: ^1.1.0
  shared_preferences: ^2.2.2
```

### lib/config/api_config.dart
```dart
class ApiConfig {
  // Ganti URL jika ngrok restart
  static const String baseUrl = 'https://henley-hemathermal-superbly.ngrok-free.dev/api';
  
  static Map<String, String> get headers => {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'ngrok-skip-browser-warning': 'true',
  };
  
  static Map<String, String> authHeaders(String token) => {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'Authorization': 'Bearer $token',
    'ngrok-skip-browser-warning': 'true',
  };
}
```

### lib/services/api_service.dart
```dart
import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import '../config/api_config.dart';

class ApiService {
  // Token Management
  Future<void> saveToken(String token) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString('auth_token', token);
  }
  
  Future<String?> getToken() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString('auth_token');
  }
  
  Future<void> removeToken() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove('auth_token');
  }
  
  // LOGIN
  Future<Map<String, dynamic>> login(String email, String password) async {
    final response = await http.post(
      Uri.parse('${ApiConfig.baseUrl}/auth/login'),
      headers: ApiConfig.headers,
      body: jsonEncode({'email': email, 'password': password}),
    );
    
    final data = jsonDecode(response.body);
    if (data['success'] == true) {
      await saveToken(data['data']['token']);
    }
    return data;
  }
  
  // REGISTER
  Future<Map<String, dynamic>> register({
    required String name,
    required String email,
    required String password,
    String? phone,
  }) async {
    final response = await http.post(
      Uri.parse('${ApiConfig.baseUrl}/auth/register'),
      headers: ApiConfig.headers,
      body: jsonEncode({
        'name': name,
        'email': email,
        'password': password,
        'password_confirmation': password,
        'phone': phone,
      }),
    );
    return jsonDecode(response.body);
  }
  
  // GET PUBLIC DONASI
  Future<List<dynamic>> getPublicDonasi({String? jenis, int page = 1}) async {
    String url = '${ApiConfig.baseUrl}/donasi/public?page=$page';
    if (jenis != null) url += '&jenis=$jenis';
    
    final response = await http.get(Uri.parse(url), headers: ApiConfig.headers);
    final data = jsonDecode(response.body);
    return data['success'] ? data['data'] : [];
  }
  
  // GET PUBLIC STATISTICS
  Future<Map<String, dynamic>> getPublicStatistics() async {
    final response = await http.get(
      Uri.parse('${ApiConfig.baseUrl}/donasi/public/statistics'),
      headers: ApiConfig.headers,
    );
    return jsonDecode(response.body);
  }
  
  // SUBMIT DONASI
  Future<Map<String, dynamic>> submitDonasi({
    required String donatur,
    required String jenis,
    required String detail,
    required String jumlah,
    String? buktiBase64,
  }) async {
    final token = await getToken();
    if (token == null) {
      return {'success': false, 'message': 'Silakan login terlebih dahulu'};
    }
    
    final body = {
      'donatur': donatur,
      'jenis': jenis,
      'detail': detail,
      'jumlah': jumlah,
    };
    if (buktiBase64 != null) body['bukti'] = buktiBase64;
    
    final response = await http.post(
      Uri.parse('${ApiConfig.baseUrl}/donasi'),
      headers: ApiConfig.authHeaders(token),
      body: jsonEncode(body),
    );
    return jsonDecode(response.body);
  }
  
  // GET MY DONATIONS
  Future<List<dynamic>> getMyDonations({String? status}) async {
    final token = await getToken();
    if (token == null) return [];
    
    String url = '${ApiConfig.baseUrl}/donasi/my-donations';
    if (status != null) url += '?status_verifikasi=$status';
    
    final response = await http.get(
      Uri.parse(url),
      headers: ApiConfig.authHeaders(token),
    );
    final data = jsonDecode(response.body);
    return data['success'] ? data['data'] : [];
  }
  
  // GET NOTIFICATIONS
  Future<List<dynamic>> getNotifications() async {
    final token = await getToken();
    if (token == null) return [];
    
    final response = await http.get(
      Uri.parse('${ApiConfig.baseUrl}/notifikasi'),
      headers: ApiConfig.authHeaders(token),
    );
    final data = jsonDecode(response.body);
    return data['success'] ? data['data'] : [];
  }
  
  // LOGOUT
  Future<void> logout() async {
    final token = await getToken();
    if (token != null) {
      await http.post(
        Uri.parse('${ApiConfig.baseUrl}/auth/logout'),
        headers: ApiConfig.authHeaders(token),
      );
    }
    await removeToken();
  }
}
```

---

## 📞 CONTACT

Jika ada pertanyaan tentang API, hubungi tim backend.

---

## 📝 CHANGELOG

- **v1.0** - Initial API documentation
- **v1.1** - Added ngrok URL and Flutter implementation
