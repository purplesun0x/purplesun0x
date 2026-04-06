import { Link } from 'react-router-dom';
import { useTheme } from '../../contexts/ThemeContext';

export default function Navbar() {
  const { theme, setTheme } = useTheme();

  return (
    <header className="border-b border-slate-200 dark:border-slate-800 bg-white/90 dark:bg-slate-950/90 backdrop-blur sticky top-0 z-50">
      <div className="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
        <Link to="/" className="text-xl font-bold text-brand">NovaCart</Link>
        <nav className="flex items-center gap-4 text-sm">
          <Link to="/products">Shop</Link>
          <Link to="/dashboard">Dashboard</Link>
          <Link to="/admin">Admin</Link>
          <button
            className="px-3 py-1 rounded-full border border-slate-300 dark:border-slate-700"
            onClick={() => setTheme(theme === 'light' ? 'dark' : 'light')}
          >
            {theme === 'light' ? 'Dark' : 'Light'}
          </button>
        </nav>
      </div>
    </header>
  );
}
