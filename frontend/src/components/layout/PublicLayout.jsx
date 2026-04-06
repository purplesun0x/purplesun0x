import { Link, Outlet } from 'react-router-dom';

export default function PublicLayout() {
  return (
    <div className="min-h-screen bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-white">
      <header className="sticky top-0 z-50 border-b border-slate-200 bg-white/90 backdrop-blur dark:border-slate-800 dark:bg-slate-900/90">
        <nav className="mx-auto flex max-w-7xl items-center justify-between px-4 py-4">
          <Link to="/" className="text-xl font-bold">PurpleSun</Link>
          <div className="space-x-4 text-sm">
            <Link to="/products">Shop</Link>
            <Link to="/cart">Cart</Link>
            <Link to="/login">Login</Link>
          </div>
        </nav>
      </header>
      <main className="mx-auto max-w-7xl px-4 py-8"><Outlet /></main>
      <footer className="mt-10 border-t border-slate-200 px-4 py-10 text-center text-sm text-slate-500 dark:border-slate-800">
        © {new Date().getFullYear()} PurpleSun Commerce · Privacy · Terms · Support
      </footer>
    </div>
  );
}
