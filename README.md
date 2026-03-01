# Digital Product Store (Flask)

A starter project for selling digital products with:

- **User panel** (register/login, browse products, create orders, pay, unlock downloads)
- **Admin panel** (create products, review all orders)
- **SQLite database** (users, products, orders, purchases)

## Quick start

```bash
python3 -m venv .venv
source .venv/bin/activate
pip install -r requirements.txt
python app.py
```

Open `http://localhost:5000`.

## Demo admin account

- Email: `admin@example.com`
- Password: `admin123`

## Notes

- Payments are currently **simulated** with a “Pay now” button.
- Replace the payment route with Stripe/PayPal integration for production.
- Replace `file_url` with secure signed URLs or protected file delivery.
