import { Link } from 'react-router-dom';

export default function HomePage() {
  return (
    <main className="mx-auto max-w-5xl px-4 py-16">
      <h1 className="text-4xl font-bold">Digital Marketplace for Website Scripts</h1>
      <p className="mt-4 text-lg text-slate-600">Buy premium PHP, SaaS templates, and web tools with instant license delivery.</p>
      <div className="mt-8 flex gap-3">
        <Link className="rounded bg-blue-600 px-4 py-2 text-white" to="/marketplace">Browse Marketplace</Link>
        <Link className="rounded border px-4 py-2" to="/auth">Login / Register</Link>
      </div>
    </main>
  );
}
