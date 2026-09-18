# Production deployment notes

The included Docker setup is intended for local development and functional verification. For production, serve Laravel through Nginx/Apache with PHP-FPM rather than `artisan serve`, terminate TLS at the reverse proxy, and run queue/scheduler as supervised processes.

Do not commit `.env`, cloud credentials, generated icons, database dumps, or access tokens.
