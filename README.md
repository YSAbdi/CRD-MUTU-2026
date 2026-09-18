# CRM Camp Resident MUTU

Fondasi Laravel 11 untuk CRM booking camp dengan MySQL, API Sanctum, PWA installable, dashboard responsif, tema gelap berbasis preferensi perangkat, dan service worker push-notification ready.

## Menjalankan aplikasi

```bash
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate
php artisan serve
```

Untuk PWA dan push notification, gunakan HTTPS pada lingkungan non-local. Tambahkan aset ikon pada `public/icons/`. Jalankan queue worker untuk pekerjaan notifikasi: `php artisan queue:work`.

Fitur tambahan seperti autentikasi UI (Breeze/Jetstream), role/permission, export PDF/XLSX, cloud backup, broadcasting realtime, dan scheduler dapat ditambahkan melalui provider deployment yang dipilih organisasi. Jangan menonaktifkan CSRF, rate limiting, atau validasi API pada produksi.
