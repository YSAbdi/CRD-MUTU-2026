# Starter lokal cepat

```bash
cp .env.example .env
composer install
php artisan key:generate
# buat database MySQL bernama crm_mutu, lalu atur DB_* pada .env
php artisan migrate --seed
php artisan serve
```

Login API awal: `superadmin@mutu.co.id` dengan password dari `SUPERADMIN_PASSWORD`. Ganti password segera setelah login dan jangan memakai nilai contoh pada produksi.

## PWA

Aplikasi membutuhkan HTTPS pada staging/produksi agar service worker dan push notification aktif. Tambahkan ikon PNG 192x192 dan 512x512 ke `public/icons/`, lalu buka `/dashboard` dan pilih **Install aplikasi**. Untuk Android/Chrome, aplikasi dapat dipasang melalui browser; iOS memakai **Add to Home Screen**.

## Operasional produksi

- Jalankan `php artisan queue:work` untuk pekerjaan asynchronous.
- Jalankan scheduler setiap menit: `* * * * * cd /path/app && php artisan schedule:run >> /dev/null 2>&1`.
- Gunakan HTTPS, secret environment terpisah, backup database terenkripsi, dan provider Web Push/VAPID sebelum mengaktifkan push notification.
- Sanctum API sudah disiapkan; batasi CORS, rate-limit login, dan simpan token di storage yang aman pada klien.
