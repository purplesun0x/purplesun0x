# Multi-Vendor Script Marketplace (Core PHP)

Lightweight marketplace for buying/selling digital scripts, built with **PHP 8+**, **MySQL**, and **Bootstrap**.

## Features
- Session-based registration/login with hashed passwords.
- User browsing, cart, checkout, and secure purchased downloads.
- Vendor role upgrade, product upload, product management, and earnings view.
- Admin dashboard for users, product approvals, and transactions.
- Payment integration stubs for Paystack and Flutterwave.

## Project Structure
```
/config
/includes
/uploads/products
/vendor
/admin
/public
/auth
/api
schema.sql
```

## Local Setup (XAMPP)
1. Copy this project into your XAMPP `htdocs/` directory.
2. Create MySQL database `script_marketplace`.
3. Import `schema.sql` via phpMyAdmin.
4. Update DB credentials in `config/database.php`.
5. Update app URL and payment keys in `config/config.php`.
6. Run in browser:
   - `http://localhost/purplesun0x/public`

## Test Credentials
- Admin:
  - Email: `admin@example.com`
  - Password: `admin123`

## Notes on Payments
`api/paystack.php` and `api/flutterwave.php` currently include local **stub flows** (init + callback + DB write). For production:
- replace stubs with cURL requests to provider APIs,
- verify signatures and callbacks,
- keep secret keys in server environment variables.

## Security Included
- Prepared statements for DB writes/reads.
- Password hashing using `password_hash`/`password_verify`.
- File upload extension + size validation (`zip`, `rar`, `pdf`).
- No direct public file links: downloads are served through `public/download.php` only after purchase verification.
