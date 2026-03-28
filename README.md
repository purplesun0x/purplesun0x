# Digital Product Store (PHP)

A simple PHP + SQLite website for selling digital products, with:

- User registration + login
- User dashboard with purchased products/download links
- Admin dashboard for product and user management
- Storefront listing products
- Referral program (10% commission credited to referrer wallet)

## Quick start

1. Run the built-in PHP server:
   ```bash
   php -S localhost:8000
   ```
2. Open `http://localhost:8000`.

## Default admin

- Email: `admin@example.com`
- Password: `admin123`

## Notes

- Data is stored in `database.sqlite` (auto-created).
- Referral links use `?ref=YOURCODE` and are tracked via cookie for 30 days.
