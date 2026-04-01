# Digital Product Store (PHP)

A lightweight PHP + SQLite application for selling digital products with:

- User registration and login
- Admin login and dashboard
- User dashboard
- Product store and checkout
- Referral program with commission tracking
- Pluggable payment gateway layer (Manual + Paystack ready)

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

## Enabling Paystack

1. Export your Paystack secret key before starting the app:

```bash
export PAYSTACK_SECRET_KEY="sk_test_xxx"
php -S localhost:8000
```

2. Go to the store and pick **PAYSTACK** in the payment selector.
3. After checkout, Paystack redirects to `payment_callback.php` where verification happens.

## Integrating other payment systems (Stripe, Flutterwave, etc.)

The app uses a small gateway interface so you can plug in any provider:

1. Create a new gateway class in `payments/` implementing `PaymentGatewayInterface`.
2. Implement:
   - `initialize(...)` → returns redirect/auth URL + reference
   - `verify(reference)` → confirms payment with the provider API
3. Register your gateway in `payments/GatewayFactory.php`:
   - add it inside `availableGatewayKeys()`
   - add a `match` case in `makeGateway()`
4. Add provider secret key(s) as environment variables.

## Notes

- Data is stored in `database.sqlite`.
- Purchased products can be downloaded from the user dashboard.
- Referral commission is credited automatically when a referred user purchases a product.
- `manual` gateway is kept for local/offline testing.
