# Deployment dan operasi CRM MUTU

## Local tanpa Docker

```bash
composer install --no-interaction
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

## Local dengan Docker

```bash
docker compose up -d --build
# pertama kali, setelah container aktif:
docker compose exec app php artisan migrate --seed
docker compose exec app php artisan storage:link
```

Aplikasi tersedia di `http://127.0.0.1:8000`. Ubah `SUPERADMIN_PASSWORD` sebelum seed.

## Production checklist

1. Buat database dan user MySQL terpisah; jangan gunakan root.
2. Isi `.env.production.example` menjadi `.env` server dengan secret yang kuat.
3. Pastikan `APP_DEBUG=false`, HTTPS aktif, dan `APP_URL` benar.
4. Jalankan `php artisan migrate --force`, `php artisan config:cache`, `php artisan route:cache`, dan `php artisan view:cache`.
5. Jalankan queue worker permanen dengan Supervisor/systemd:

```ini
[program:crm-mutu-worker]
command=php /var/www/crm-mutu/artisan queue:work --sleep=3 --tries=3 --max-time=3600
directory=/var/www/crm-mutu
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
```

6. Tambahkan cron setiap menit:

```cron
* * * * * cd /var/www/crm-mutu && php artisan schedule:run >> /dev/null 2>&1
```

7. Konfigurasi disk `backups` ke S3-compatible storage, lalu uji `php artisan crm:backup --disk=backups` dan pemulihan dump secara berkala.
8. Web Push membutuhkan HTTPS, VAPID/provider push, service worker, serta queue worker; menyimpan subscription saja belum mengirim push ke browser.
9. Batasi CORS, aktifkan rate limit login, backup terenkripsi, monitoring error, dan rotasi token.

## Verifikasi

```bash
php artisan optimize:clear
php artisan migrate:status
php artisan route:list --path=api/v1
php artisan test
php artisan crm:booking-reminders
php artisan crm:backup --disk=backups
php artisan crm:prune-backups --disk=backups
```
