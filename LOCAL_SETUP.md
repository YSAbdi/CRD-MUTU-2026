# CRM Camp Resident MUTU — Instalasi Lokal

## Persyaratan

- PHP 8.2 atau lebih baru dengan extension `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `tokenizer`, `xml`, dan `ctype`.
- Composer 2.
- MySQL 8 atau MariaDB 10.6+.
- Node.js 20+ dan npm (opsional bila frontend Vite ditambahkan).

## 1. Buat database

```sql
CREATE DATABASE crm_mutu CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

## 2. Pasang dependency

Jika source ini digunakan sebagai project Laravel:

```bash
composer install
cp .env.example .env       # Windows PowerShell: Copy-Item .env.example .env
php artisan key:generate
```

Pastikan `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` pada `.env` sesuai MySQL.

## 3. Migrasi dan akun awal

```bash
php artisan migrate:fresh --seed
php artisan storage:link
```

Akun awal dibuat oleh `DatabaseSeeder`:

- Email: `superadmin@mutu.co.id`
- Password: nilai `SUPERADMIN_PASSWORD` pada `.env`

Ganti `SUPERADMIN_PASSWORD` sebelum menjalankan seed pada server bersama.

## 4. Jalankan aplikasi

```bash
php artisan serve
```

Buka `http://127.0.0.1:8000/admin/dashboard`. Login dilakukan oleh browser melalui endpoint Sanctum token.

Untuk queue dan scheduler pada terminal terpisah:

```bash
php artisan queue:work
php artisan schedule:work
```

## Endpoint utama

Semua endpoint berikut memerlukan token Sanctum, kecuali login/register:

- `POST /api/v1/auth/login`
- `GET /api/v1/dashboard`
- `GET|POST|PUT|DELETE /api/v1/guests`
- `GET|POST|PUT /api/v1/rooms`
- `GET|POST|PUT|DELETE /api/v1/bookings`
- `PATCH /api/v1/bookings/{booking}/status`
- `GET /api/v1/calendar?start=...&end=...`
- `GET /api/v1/reports/bookings?format=pdf|xlsx`
- `GET|POST|PUT|DELETE /api/v1/users` khusus superadmin.

## PWA dan produksi

PWA memerlukan HTTPS pada staging/produksi. Letakkan ikon PNG pada `public/icons/icon-192.png` dan `public/icons/icon-512.png`. Untuk push notification, konfigurasi provider VAPID/broadcast dan jalankan queue worker. Jangan menyimpan token API di URL atau membagikan `.env`.

## Troubleshooting

### `Class ... not found` atau package tidak terbaca

```bash
composer dump-autoload
php artisan optimize:clear
```

### `No application encryption key`

```bash
php artisan key:generate
```

### `SQLSTATE[HY000] [1045]`

Periksa host, port, database, username, password MySQL pada `.env`, lalu jalankan `php artisan config:clear`.

### Tabel belum ditemukan

```bash
php artisan migrate:fresh --seed
```

Gunakan `migrate:fresh` hanya pada development karena menghapus data.

### `419 Page Expired` atau token login gagal

Pastikan URL dibuka melalui server Laravel (`php artisan serve`), bukan `file://`, hapus token lama dari browser, lalu login lagi. Bersihkan cache dengan `php artisan optimize:clear`.

### `403 Anda tidak memiliki hak akses`

Pastikan user mempunyai role `superadmin` untuk endpoint `/users`, dan middleware alias `role` terdaftar pada `bootstrap/app.php`.

### Export PDF/Excel gagal

Jalankan `composer install`, pastikan extension PHP `dom` dan `xml` aktif, lalu cek log `storage/logs/laravel.log`.

### Queue tidak berjalan

Pastikan migrasi jobs sudah dijalankan, lalu jalankan:

```bash
php artisan queue:work --tries=3
```

### Dashboard kosong

Login ulang untuk memperoleh token baru. Pastikan ada data user/tamu/kamar/booking dan request browser ke `/api/v1/dashboard` tidak berstatus 401/500.

## Checklist production

- `APP_ENV=production` dan `APP_DEBUG=false`.
- HTTPS aktif, secret tidak berada dalam repository.
- Jalankan `php artisan config:cache && php artisan route:cache && php artisan view:cache`.
- Gunakan Redis untuk cache/queue pada volume besar.
- Jadwalkan backup database terenkripsi dan uji pemulihannya.
- Gunakan worker supervisor/systemd dan health monitoring.
