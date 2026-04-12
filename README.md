# Digital Marketplace for Website Scripts

This repository contains a production-oriented blueprint for a **full-stack digital marketplace**:

- **Backend**: Laravel REST API + JWT auth + MySQL
- **Frontend**: React + Tailwind CSS
- **Payments**: Paystack and Flutterwave (webhook verified)
- **Delivery**: private file storage + expiring download tokens
- **Admin**: multi-admin RBAC

## Repository Layout

- `backend/` Laravel API source files (models, controllers, middleware, routes, services)
- `database/schema.sql` complete SQL schema for required entities
- `frontend/` React app (pages, components, API integration)

## Can I test with XAMPP before deployment?

**Yes — absolutely.**

Use XAMPP for Apache + MySQL, and run Laravel + React side-by-side:

- XAMPP provides: `Apache`, `PHP`, `MySQL`.
- Laravel API runs from `backend/` (typically via `php artisan serve` during development).
- React frontend runs from `frontend/` with Vite (`npm run dev`).

### Recommended local setup (Windows + XAMPP)

1. Install and start **Apache** + **MySQL** in XAMPP.
2. Create database in phpMyAdmin:
   - Name: `script_marketplace`
   - Import file: `database/schema.sql`
3. In `backend/`:
   - Create `.env` from `.env.example`
   - Set DB config:
     - `DB_HOST=127.0.0.1`
     - `DB_PORT=3306`
     - `DB_DATABASE=script_marketplace`
     - `DB_USERNAME=root`
     - `DB_PASSWORD=` (empty by default on XAMPP)
   - Set app URL:
     - `APP_URL=http://127.0.0.1:8000`
     - `FRONTEND_URL=http://localhost:5173`
   - Install dependencies and key material:
     - `composer install`
     - `php artisan key:generate`
     - `php artisan jwt:secret`
   - Serve API:
     - `php artisan serve`
4. In `frontend/`:
   - `npm install`
   - Add `.env` with:
     - `VITE_API_BASE_URL=http://127.0.0.1:8000/api`
   - Run: `npm run dev`
5. Open frontend at `http://localhost:5173`.

### Notes for XAMPP users

- Keep script ZIPs in **private storage** (`storage/app/private/scripts`) and never expose them directly via Apache public paths.
- Webhooks (Paystack/Flutterwave) need a public callback URL. For local testing, use tunnel tools like `ngrok` or use the included `mock` provider flow.
- For production-like Apache hosting, point Apache virtual host to Laravel's `public/` folder and keep `storage/` private.

## Security Notes

- Storage is designed to be private (`storage/app/private/scripts`).
- Download access is authorized server-side and link-token protected.
- JWT assumed via `php-open-source-saver/jwt-auth`.
- RBAC can be wired to `spatie/laravel-permission` or custom role tables.
