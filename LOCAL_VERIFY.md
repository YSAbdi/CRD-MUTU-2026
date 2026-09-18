# Quick verification

Run these commands after copying the files:

```bash
php artisan optimize:clear
php artisan migrate:fresh --seed
php artisan test
php artisan route:list --path=api/v1
```

Expected local demo data: one Superadmin, three operator users, twelve rooms, twenty guests, and ten bookings. Demo records are only generated when `APP_ENV=local` or `testing`.

If `User::factory()` or another factory is not found, confirm that the model uses `HasFactory`, then run `composer dump-autoload`.
