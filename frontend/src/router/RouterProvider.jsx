import { Navigate, Route, Routes } from 'react-router-dom';
import PublicLayout from '../layouts/PublicLayout';
import SidebarLayout from '../layouts/SidebarLayout';
import HomePage from '../pages/public/HomePage';
import ProductsPage from '../pages/public/ProductsPage';
import ProductDetailPage from '../pages/public/ProductDetailPage';
import CartPage from '../pages/public/CartPage';
import CheckoutPage from '../pages/public/CheckoutPage';
import LoginPage from '../pages/auth/LoginPage';
import RegisterPage from '../pages/auth/RegisterPage';
import ForgotPasswordPage from '../pages/auth/ForgotPasswordPage';
import UserOverviewPage from '../pages/user/UserOverviewPage';
import UserOrdersPage from '../pages/user/UserOrdersPage';
import UserReferralPage from '../pages/user/UserReferralPage';
import AdminOverviewPage from '../pages/admin/AdminOverviewPage';
import AdminProductsPage from '../pages/admin/AdminProductsPage';
import AdminUsersPage from '../pages/admin/AdminUsersPage';

const userMenu = [
  ['dashboard', 'Dashboard'],
  ['orders', 'Orders'],
  ['wishlist', 'Wishlist'],
  ['cart', 'Cart'],
  ['profile', 'Profile Settings'],
  ['addresses', 'Addresses'],
  ['payments', 'Payment Methods'],
  ['referrals', 'Referral Program'],
  ['notifications', 'Notifications'],
].map(([to, label]) => ({ to: `/dashboard/${to}`, label }));

const adminMenu = [
  ['dashboard', 'Dashboard'],
  ['products', 'Products'],
  ['orders', 'Orders'],
  ['customers', 'Customers'],
  ['categories', 'Categories'],
  ['coupons', 'Coupons'],
  ['referrals', 'Referral System'],
  ['analytics', 'Analytics'],
  ['admins', 'Admin Management'],
  ['settings', 'Settings'],
].map(([to, label]) => ({ to: `/admin/${to}`, label }));

const Placeholder = ({ text }) => <div className="card p-5">{text}</div>;

export function RouterProvider() {
  return (
    <Routes>
      <Route element={<PublicLayout />}>
        <Route path="/" element={<HomePage />} />
        <Route path="/products" element={<ProductsPage />} />
        <Route path="/products/:id" element={<ProductDetailPage />} />
        <Route path="/cart" element={<CartPage />} />
        <Route path="/checkout" element={<CheckoutPage />} />
        <Route path="/login" element={<LoginPage />} />
        <Route path="/register" element={<RegisterPage />} />
        <Route path="/forgot-password" element={<ForgotPasswordPage />} />
      </Route>

      <Route path="/dashboard" element={<SidebarLayout title="User Dashboard" menu={userMenu} />}>
        <Route index element={<Navigate to="dashboard" replace />} />
        <Route path="dashboard" element={<UserOverviewPage />} />
        <Route path="orders" element={<UserOrdersPage />} />
        <Route path="referrals" element={<UserReferralPage />} />
        <Route path="wishlist" element={<Placeholder text="Wishlist module" />} />
        <Route path="cart" element={<Placeholder text="Cart module" />} />
        <Route path="profile" element={<Placeholder text="Profile and password settings" />} />
        <Route path="addresses" element={<Placeholder text="Saved addresses" />} />
        <Route path="payments" element={<Placeholder text="Payment methods" />} />
        <Route path="notifications" element={<Placeholder text="Notification preferences" />} />
      </Route>

      <Route path="/admin" element={<SidebarLayout title="Admin Panel" menu={adminMenu} />}>
        <Route index element={<Navigate to="dashboard" replace />} />
        <Route path="dashboard" element={<AdminOverviewPage />} />
        <Route path="products" element={<AdminProductsPage />} />
        <Route path="customers" element={<AdminUsersPage />} />
        <Route path="orders" element={<Placeholder text="Order management and refunds" />} />
        <Route path="categories" element={<Placeholder text="Category management" />} />
        <Route path="coupons" element={<Placeholder text="Coupon management" />} />
        <Route path="referrals" element={<Placeholder text="Referral approval and anti-abuse" />} />
        <Route path="analytics" element={<Placeholder text="KPI analytics" />} />
        <Route path="admins" element={<Placeholder text="RBAC admin management" />} />
        <Route path="settings" element={<Placeholder text="Platform settings" />} />
      </Route>
    </Routes>
  );
}
