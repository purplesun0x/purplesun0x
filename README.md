# PurpleSun Store Platform (PHP + MySQL)

A lightweight e-commerce platform you can run on **XAMPP** with:

- User registration/login
- User dashboard
- Referral tracking
- Product listing and cart
- Checkout simulation with payment reference
- Admin panel for product and platform control

## 1) XAMPP setup

1. Copy this project folder into your XAMPP `htdocs` directory.
2. Start **Apache** and **MySQL** from the XAMPP Control Panel.
3. Open phpMyAdmin and import `sql/schema.sql`.
4. Confirm your DB config in `includes/config.php`:
   - host: `127.0.0.1`
   - database: `purplesun_store`
   - user: `root`
   - password: `` (empty by default on local XAMPP)

## 2) Access URLs

- Storefront: `http://localhost/purplesun0x/public/index.php`
- Register: `http://localhost/purplesun0x/public/register.php`
- Login: `http://localhost/purplesun0x/public/login.php`
- Admin panel: `http://localhost/purplesun0x/admin/index.php`

## 3) Default admin account

- Email: `admin@purplesun.local`
- Password: `admin123`

## 4) Referral flow

- Every user gets a unique referral code.
- New users can register with a referral code.
- Referrals are visible in the referrer dashboard.

## 5) Payment/cart flow

- Logged-in users add products to cart.
- Checkout creates an order and marks it as `paid` with a generated payment reference.
- Orders show on the user dashboard.

## File structure

- `public/` user-facing pages
- `admin/` admin panel
- `includes/` configuration, DB, and helper/layout functions
- `sql/schema.sql` database schema + seed data
