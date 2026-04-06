import { Outlet } from 'react-router-dom';
import Navbar from '../components/common/Navbar';

export default function PublicLayout() {
  return (
    <div>
      <Navbar />
      <main className="max-w-7xl mx-auto px-4 py-8">
        <Outlet />
      </main>
      <footer className="border-t border-slate-200 dark:border-slate-800 py-8 mt-16 text-center text-sm text-slate-500">
        © {new Date().getFullYear()} NovaCart · Premium commerce experience.
      </footer>
    </div>
  );
}
