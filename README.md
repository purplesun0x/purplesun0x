# NovaCart: Full-Stack E-Commerce Platform

Production-oriented full-stack starter architecture using a decoupled React frontend and Laravel REST API backend.

## Stack
- **Frontend:** React + Vite, Tailwind CSS, Axios, React Router, Context API.
- **Backend:** Laravel (API-only), Sanctum auth, RESTful controllers, middleware-based RBAC.
- **Database:** MySQL with normalized migrations (users, products, orders, referrals, admin roles, withdrawals).
- **Payments:** Stripe webhook-ready flow (extendable to Paystack).

## Repository Layout
- `frontend/` — customer storefront, user dashboard, and admin dashboard UI.
- `backend/` — Laravel API code structure (models, controllers, middleware, routes, migrations, services).

## Backend Highlights
- Auth endpoints: register/login/logout/current user.
- Product catalog APIs with filtering/sorting + admin write endpoints.
- Order placement with stock locking and transactional persistence.
- Referral logic:
  - Unique referral code per user.
  - Commission issuance after successful payment.
  - Wallet-based withdrawal request flow.
- Multi-admin architecture:
  - Role middleware (`super_admin`, `manager`, `support`).
  - Dedicated admin analytics and management endpoints.

## Frontend Highlights
- Premium-inspired UI structure with reusable cards and responsive grids.
- Public pages:
  - Home, product listing, product detail, cart, checkout.
  - Auth pages (register/login/forgot password scaffold).
- User dashboard:
  - Overview, orders, referral page, and navigation placeholders.
- Admin dashboard:
  - Analytics and core modules scaffolding with RBAC-oriented layout.
- Light/dark mode toggle.

## API Endpoints Included
- `POST /api/register`
- `POST /api/login`
- `POST /api/logout`
- `GET /api/user`
- `GET /api/products`
- `GET /api/products/{id}`
- `POST /api/products`
- `PUT /api/products/{id}`
- `DELETE /api/products/{id}`
- `POST /api/orders`
- `GET /api/orders`
- `GET /api/orders/{id}`
- `GET /api/referral/link`
- `GET /api/referral/stats`
- `POST /api/referral/withdraw`
- `GET /api/admin/dashboard`
- `GET /api/admin/users`
- `POST /api/admin/products`
- `GET /api/admin/analytics`

## Next Production Steps
1. Create actual Laravel app shell and copy `backend/` files into proper Laravel app folders.
2. Add FormRequest classes and API Resources for strict contracts.
3. Configure Sanctum, CORS, and token expiration policy.
4. Harden Stripe webhook signature verification and idempotency.
5. Add queue workers for emails, payout approval, and analytics aggregation.
6. Add Redis cache + Horizon for high throughput.
7. Add tests:
   - PHPUnit feature tests for auth/orders/referrals/admin RBAC.
   - Frontend component/integration tests with Vitest + Testing Library.
8. Provision deployment:
   - Backend on Nginx/Apache + PHP-FPM.
   - Frontend on Vercel/Netlify.
   - MySQL managed host.

