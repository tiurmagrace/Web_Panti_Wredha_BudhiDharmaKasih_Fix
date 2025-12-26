# 🚀 Tutorial Deploy Laravel ke Railway

Railway adalah platform hosting gratis yang mendukung Laravel + MySQL. Dalam 15 menit, project kamu akan live dengan URL tetap.

---

## 📋 Prasyarat

1. **GitHub Account** - Untuk push code
2. **Railway Account** - Gratis di https://railway.app
3. **Git installed** - Untuk push ke GitHub

---

## Step 1: Siapkan Project di GitHub

### 1.1 Buat Repository di GitHub

1. Buka https://github.com/new
2. Nama repo: `panti-wredha-api`
3. Pilih **Public** (agar Railway bisa akses)
4. Klik **Create repository**

### 1.2 Push Code ke GitHub

```bash
# Di folder project kamu
git init
git add .
git commit -m "Initial commit"
git branch -M main
git remote add origin https://github.com/USERNAME/panti-wredha-api.git
git push -u origin main
```

> Ganti `USERNAME` dengan username GitHub kamu

---

## Step 2: Setup Railway

### 2.1 Login ke Railway

1. Buka https://railway.app
2. Klik **Login** → **GitHub**
3. Authorize Railway

### 2.2 Buat Project Baru

1. Klik **+ New Project**
2. Pilih **Deploy from GitHub repo**
3. Cari repo `panti-wredha-api`
4. Klik **Deploy**

Railway akan mulai build project kamu.

---

## Step 3: Setup Database MySQL

### 3.1 Tambah MySQL Service

1. Di Railway dashboard, klik **+ Add Service**
2. Pilih **MySQL**
3. Railway akan membuat database otomatis

### 3.2 Konfigurasi Environment Variables

Railway akan auto-generate variable database. Tapi kita perlu set beberapa variable tambahan:

1. Klik **Variables** di Railway dashboard
2. Tambahkan:

```
APP_NAME=Panti Wredha
APP_ENV=production
APP_DEBUG=false
APP_URL=https://panti-wredha-api.railway.app
APP_KEY=base64:xxxxx (generate dengan php artisan key:generate)
DB_CONNECTION=mysql
DB_HOST=${{ Mysql.MYSQL_HOST }}
DB_PORT=${{ Mysql.MYSQL_PORT }}
DB_DATABASE=${{ Mysql.MYSQL_DATABASE }}
DB_USERNAME=${{ Mysql.MYSQL_USER }}
DB_PASSWORD=${{ Mysql.MYSQL_PASSWORD }}
```

---

## Step 4: Setup Laravel

### 4.1 Generate APP_KEY

Jalankan di local:

```bash
php artisan key:generate
```

Copy value dari `.env` file:
```
APP_KEY=base64:xxxxxxxxxxxxx
```

Paste ke Railway Variables.

### 4.2 Buat Procfile

Buat file `Procfile` di root project:

```
web: vendor/bin/heroku-php-apache2 public/
```

### 4.3 Buat .railwayignore (Optional)

Buat file `.railwayignore`:

```
node_modules/
.git/
storage/logs/
bootstrap/cache/
```

---

## Step 5: Deploy

### 5.1 Push ke GitHub

```bash
git add .
git commit -m "Add Railway deployment files"
git push origin main
```

Railway akan auto-deploy saat ada push ke GitHub.

### 5.2 Monitor Deployment

1. Buka Railway dashboard
2. Lihat **Deployments** tab
3. Tunggu sampai status **Success** (hijau)

---

## Step 6: Run Migrations

Setelah deployment sukses, jalankan migrations:

### 6.1 Via Railway CLI

```bash
# Install Railway CLI
npm i -g @railway/cli

# Login
railway login

# Link project
railway link

# Run migrations
railway run php artisan migrate --force
```

### 6.2 Via Railway Dashboard

1. Buka Railway dashboard
2. Klik **Deployments**
3. Klik **View Logs**
4. Jalankan command di terminal:

```bash
railway run php artisan migrate --force
```

---

## Step 7: Verifikasi Deployment

### 7.1 Cek URL

Railway akan memberikan URL seperti:
```
https://panti-wredha-api-production.up.railway.app
```

Atau custom domain jika sudah setup.

### 7.2 Test API

Buka di browser:
```
https://panti-wredha-api-production.up.railway.app/api/donasi/public
```

Jika muncul JSON, berarti berhasil! ✅

---

## Step 8: Setup Custom Domain (Optional)

Jika ingin URL lebih bagus:

1. Beli domain di Namecheap/Hostinger
2. Di Railway, klik **Settings** → **Domain**
3. Tambah custom domain
4. Update DNS sesuai instruksi Railway

---

## Troubleshooting

### Error: "SQLSTATE[HY000]: General error: 1030"
**Solusi:** Database belum siap. Tunggu 1-2 menit, lalu refresh.

### Error: "Class not found"
**Solusi:** Jalankan:
```bash
railway run php artisan config:cache
railway run php artisan route:cache
```

### Error: "Permission denied"
**Solusi:** Jalankan:
```bash
railway run php artisan storage:link
```

---

## Update Code

Setiap kali update code:

```bash
git add .
git commit -m "Update message"
git push origin main
```

Railway akan auto-deploy dalam 2-5 menit.

---

## Monitoring & Logs

### Lihat Logs

```bash
railway logs
```

### Lihat Database

Railway menyediakan MySQL client di dashboard:
1. Klik **MySQL** service
2. Klik **Data** tab
3. Lihat database

---

## Base URL untuk Flutter

Setelah deploy, update `API_DOCUMENTATION.md`:

```dart
// Ganti dari:
static const String baseUrl = 'https://henley-hemathermal-superbly.ngrok-free.dev/api';

// Menjadi:
static const String baseUrl = 'https://panti-wredha-api-production.up.railway.app/api';
```

---

## Checklist Deployment

- [ ] Push code ke GitHub
- [ ] Connect Railway ke GitHub repo
- [ ] Setup MySQL di Railway
- [ ] Set environment variables
- [ ] Buat Procfile
- [ ] Deploy sukses (status hijau)
- [ ] Run migrations
- [ ] Test API di browser
- [ ] Update Flutter base URL
- [ ] Share URL ke tim mobile

---

## Selesai! 🎉

Project kamu sekarang live di:
```
https://panti-wredha-api-production.up.railway.app/api
```

Tim mobile bisa mulai development dengan URL yang tetap dan database yang real.

---

## Support

Jika ada error, cek:
1. Railway logs
2. Laravel error log
3. Database connection
4. Environment variables

Atau hubungi Railway support di https://railway.app/support
