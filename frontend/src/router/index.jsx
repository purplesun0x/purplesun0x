import { Navigate, createBrowserRouter } from 'react-router-dom';
import PublicLayout from '../components/layout/PublicLayout';
import DashboardLayout from '../components/layout/DashboardLayout';
import AdminLayout from '../components/layout/AdminLayout';
import HomePage from '../pages/public/HomePage';
import ProductListingPage from '../pages/public/ProductListingPage';
import ProductDetailPage from '../pages/public/ProductDetailPage';
import CartPage from '../pages/public/CartPage';
import CheckoutPage from '../pages/public/CheckoutPage';
import LoginPage from '../pages/auth/LoginPage';
import RegisterPage from '../pages/auth/RegisterPage';
import ForgotPasswordPage from '../pages/auth/ForgotPasswordPage';
import UserDashboardHome from '../pages/user/UserDashboardHome';
import ReferralProgramPage from '../pages/user/ReferralProgramPage';
import AdminDashboardHome from '../pages/admin/AdminDashboardHome';
import AdminProductsPage from '../pages/admin/AdminProductsPage';

export const router = createBrowserRouter([
  {
    path: '/',
    element: <PublicLayout />,
    children: [
      { index: true, element: <HomePage /> },
      { path: 'products', element: <ProductListingPage /> },
      { path: 'products/:id', element: <ProductDetailPage /> },
      { path: 'cart', element: <CartPage /> },
      { path: 'checkout', element: <CheckoutPage /> },
      { path: 'login', element: <LoginPage /> },
      { path: 'register', element: <RegisterPage /> },
      { path: 'forgot-password', element: <ForgotPasswordPage /> },
    ],
  },
  {
    path: '/dashboard',
    element: <DashboardLayout />,
    children: [
      { index: true, element: <Navigate to="dashboard" replace /> },
      { path: 'dashboard', element: <UserDashboardHome /> },
      { path: 'referral-program', element: <ReferralProgramPage /> },
      { path: ':slug', element: <UserDashboardHome /> },
    ],
  },
  {
    path: '/admin',
    element: <AdminLayout />,
    children: [
      { index: true, element: <Navigate to="dashboard" replace /> },
      { path: 'dashboard', element: <AdminDashboardHome /> },
      { path: 'products', element: <AdminProductsPage /> },
      { path: ':slug', element: <AdminDashboardHome /> },
    ],
  },
]);
