# Final local run checklist

```bash
composer install
cp .env.example .env
php artisan key:generate
# buat database crm_mutu dan isi DB_* di .env
php artisan migrate:fresh --seed
php artisan storage:link
php artisan optimize:clear
php artisan serve
```

Open `http://127.0.0.1:8000/admin/dashboard`.

Default local account:

- Email: `superadmin@mutu.co.id`
- Password: value of `SUPERADMIN_PASSWORD` in `.env`

For a quick API check after login:

```bash
php artisan route:list --path=api/v1
php artisan test
```

The dashboard reads `/api/v1/dashboard`; CRUD pages use `/admin/guests`, `/admin/rooms`, `/admin/bookings`, and `/admin/users`. Replace the sample password before any shared or production deployment.
