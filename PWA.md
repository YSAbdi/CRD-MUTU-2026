# Panduan PWA CRM Camp Resident MUTU

1. Jalankan `composer install`, salin `.env.example` menjadi `.env`, lalu jalankan `php artisan key:generate`.
2. Konfigurasikan MySQL dan jalankan `php artisan migrate`.
3. Sajikan aplikasi melalui HTTPS pada staging/produksi (wajib untuk service worker dan push notification).
4. Buka `/dashboard` melalui Chrome/Edge/Android Safari. Pilih **Install aplikasi** atau menu browser **Add to Home Screen**.
5. Ikon PWA harus disediakan pada `public/icons/icon-192.png` dan `public/icons/icon-512.png`; gunakan aset branding organisasi.
6. Endpoint API membutuhkan Sanctum. Sambungkan frontend login Laravel Breeze/Jetstream dan provider Web Push/VAPID untuk notifikasi push produksi.

PWA offline cache hanya menyimpan shell aplikasi; data API tetap memerlukan koneksi dan autentikasi agar tidak membocorkan data sensitif.
