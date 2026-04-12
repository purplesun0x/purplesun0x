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

## Notes

- Storage is designed to be private (`storage/app/private/scripts`).
- Download access is authorized server-side and link-token protected.
- JWT assumed via `php-open-source-saver/jwt-auth`.
- RBAC can be wired to `spatie/laravel-permission` or custom role tables.
