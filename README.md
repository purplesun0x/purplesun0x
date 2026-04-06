# PurpleSun Full-Stack E-Commerce Platform

Production-oriented starter architecture for a decoupled React + Laravel commerce system with referral and multi-admin support.

## Monorepo Structure

- `frontend/` — React + Vite + Tailwind storefront, customer dashboard, and admin dashboard.
- `backend/` — Laravel REST API with Sanctum auth, RBAC middleware, referral logic, and normalized migrations.

## Backend Highlights (Laravel API)

- Auth endpoints: register/login/logout/current user.
- Product CRUD with role restrictions.
- Order and order-item persistence with referral commission crediting.
- Referral APIs for link, stats, and withdraw request.
- Admin APIs for dashboard, users, analytics, and product management.
- Webhook handlers for Stripe/Paystack event processing.

### Important Integration Steps

1. Install dependencies and create a Laravel app shell if needed.
2. Register `RoleMiddleware` alias (e.g., `role`) in your HTTP kernel/bootstrap routing middleware aliases.
3. Enable Sanctum middleware in API stack and publish Sanctum config/migrations.
4. Configure `.env` values:
   - `APP_URL`, `FRONTEND_URL`
   - `DB_*`
   - `SANCTUM_STATEFUL_DOMAINS`
   - `REFERRAL_COMMISSION_PERCENTAGE`
   - `STRIPE_*` and/or `PAYSTACK_*`
5. Run migrations and seed admin roles.

## Frontend Highlights (React)

- Premium, card-based responsive UI with Tailwind.
- Public pages: home, products, product details, cart, checkout.
- Auth pages: register/login/forgot password.
- User dashboard with referral program view.
- Multi-admin dashboard shell with analytics summary and management sections.
- Axios API client with bearer token interception.
- Context-based state management for auth and commerce cart/wishlist.

## Deployment

- Backend: deploy `backend/` to Nginx/Apache with PHP-FPM.
- Frontend: deploy `frontend/` on Vercel or Netlify.
- Ensure CORS and Sanctum cookie/token strategy are configured for your deployment domains.
