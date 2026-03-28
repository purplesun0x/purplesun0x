# Digital Product Store (PHP)

A lightweight PHP + SQLite application for selling digital products with:

- User registration and login
- Admin login and dashboard
- User dashboard
- Product store and checkout
- Referral program with commission tracking

## Quick start

1. Make sure you have PHP 8+ installed.
2. From the project folder run:

```bash
php -S localhost:8000
```

3. Open `http://localhost:8000`.
4. Default admin account:
   - Email: `admin@example.com`
   - Password: `Admin@123`

## Notes

- Data is stored in `database.sqlite`.
- Purchased products can be downloaded from the user dashboard.
- Referral commission is credited automatically when a referred user purchases a product.
