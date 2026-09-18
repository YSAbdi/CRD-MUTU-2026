# CRM Camp Resident MUTU

Sistem informasi manajemen camp resident berbasis Laravel + MySQL. Repository ini sebelumnya hanya berisi dokumentasi; scaffold berikut menyediakan fondasi backend yang siap dikembangkan dan dijalankan.

## Fitur inti

- Autentikasi Laravel Sanctum dan password hashing Argon2id/bcrypt.
- Role-based access: `superadmin`, `admin`, `operator`, `viewer`.
- Manajemen tamu/resident, kamar, dan booking check-in/check-out.
- Dashboard ringkasan operasional dan kalender booking melalui API.
- Ekspor laporan PDF dan Excel melalui service yang dapat diperluas.
- Endpoint API versioned untuk integrasi sistem eksternal.
- Queue-ready untuk notifikasi, reminder booking, dan pekerjaan berat.
- Soft delete dan indeks database untuk data besar.

## Instalasi lokal

```bash
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Atur `DB_*` di `.env` ke database MySQL. Untuk notifikasi real-time, isi konfigurasi broadcasting (Pusher/Ably) dan jalankan worker:

```bash
php artisan queue:work
```

Untuk scheduler reminder dan backup cloud, tambahkan cron berikut:

```cron
* * * * * cd /path/ke/CRD-MUTU-2026 && php artisan schedule:run >> /dev/null 2>&1
```

## Endpoint API

Semua endpoint menggunakan prefix `/api/v1` dan membutuhkan token Sanctum, kecuali endpoint login/register yang ditambahkan oleh starter kit pilihan proyek. Endpoint utama:

- `GET /dashboard`
- `GET|POST /guests`
- `GET|POST /rooms`
- `GET|POST /bookings`
- `PATCH /bookings/{booking}/status`
- `GET /reports/bookings?format=pdf|xlsx`

Frontend produksi disarankan menggunakan Laravel Breeze + Blade/Livewire atau Inertia. Tema gelap, onboarding, PWA push notification, dan provider cloud backup sebaiknya diaktifkan melalui konfigurasi deployment sesuai provider organisasi.
