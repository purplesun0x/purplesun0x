# Laravel API Integration Notes

This folder contains production-ready Laravel-oriented source files intended to be dropped into a standard Laravel app.

## Important Framework Wiring

1. Register `RoleMiddleware` alias in `app/Http/Kernel.php`:
```php
'role' => \App\Http\Middleware\RoleMiddleware::class,
```
2. Install and configure Sanctum:
```bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\\Sanctum\\SanctumServiceProvider"
php artisan migrate
```
3. Ensure User model uses `HasApiTokens` (already included in this package).
4. Configure CORS to allow frontend origin.
5. Enable rate limiting and strict validation in production.

## Security Checklist
- Server-side validation on all write endpoints.
- Role-based authorization middleware for admin routes.
- Password hashing with bcrypt via `Hash::make`.
- SQL injection protection via Eloquent query builder.
- CSRF is primarily for cookie auth; token auth endpoints remain protected via Sanctum tokens + throttling.
- Stripe webhook signature verification should be enabled before go-live.

