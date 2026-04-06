import { Link, Outlet } from 'react-router-dom';

const links = ['Dashboard', 'Orders', 'Wishlist', 'Cart', 'Profile Settings', 'Addresses', 'Payment Methods', 'Referral Program', 'Notifications', 'Logout'];

export default function DashboardLayout() {
  return (
    <div className="grid min-h-screen grid-cols-1 md:grid-cols-[260px_1fr]">
      <aside className="border-r border-slate-200 bg-white p-4 dark:border-slate-800 dark:bg-slate-900">
        <h2 className="mb-4 text-lg font-bold">User Dashboard</h2>
        <nav className="space-y-2 text-sm">
          {links.map((label) => (
            <Link key={label} to={`/dashboard/${label.toLowerCase().replace(/\s+/g, '-')}`} className="block rounded-lg px-3 py-2 hover:bg-slate-100 dark:hover:bg-slate-800">{label}</Link>
          ))}
        </nav>
      </aside>
      <section className="bg-slate-50 p-6 dark:bg-slate-950">
        <header className="mb-6 flex items-center justify-between rounded-xl bg-white p-4 shadow-sm dark:bg-slate-900">
          <h1 className="font-semibold">Welcome back</h1>
          <span className="text-sm text-slate-500">Notifications · Profile</span>
        </header>
        <Outlet />
      </section>
    </div>
  );
}
