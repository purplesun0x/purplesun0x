import { Link, Outlet } from 'react-router-dom';

const links = ['Dashboard', 'Products', 'Orders', 'Customers', 'Categories', 'Coupons', 'Referral System', 'Analytics', 'Admin Management', 'Settings', 'Logout'];

export default function AdminLayout() {
  return (
    <div className="grid min-h-screen grid-cols-1 md:grid-cols-[280px_1fr]">
      <aside className="border-r border-slate-200 bg-slate-900 p-4 text-white">
        <h2 className="mb-4 text-lg font-bold">Admin Panel</h2>
        <nav className="space-y-2 text-sm">
          {links.map((label) => (
            <Link key={label} to={`/admin/${label.toLowerCase().replace(/\s+/g, '-')}`} className="block rounded-lg px-3 py-2 hover:bg-slate-800">{label}</Link>
          ))}
        </nav>
      </aside>
      <section className="bg-slate-100 p-6 dark:bg-slate-950">
        <header className="mb-6 rounded-xl bg-white p-4 shadow-sm dark:bg-slate-900">
          <h1 className="font-semibold">Enterprise Control Center</h1>
        </header>
        <Outlet />
      </section>
    </div>
  );
}
