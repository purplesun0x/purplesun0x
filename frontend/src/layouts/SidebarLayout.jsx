import { Link, Outlet } from 'react-router-dom';

export default function SidebarLayout({ title, menu }) {
  return (
    <div className="min-h-screen grid grid-cols-1 md:grid-cols-[260px_1fr]">
      <aside className="border-r border-slate-200 dark:border-slate-800 p-5 bg-white dark:bg-slate-950">
        <h2 className="font-bold text-lg mb-5">{title}</h2>
        <nav className="space-y-2 text-sm">
          {menu.map((item) => (
            <Link key={item.to} to={item.to} className="block px-3 py-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800">
              {item.label}
            </Link>
          ))}
        </nav>
      </aside>
      <section className="p-6">
        <Outlet />
      </section>
    </div>
  );
}
