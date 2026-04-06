import { Link } from 'react-router-dom';
import ProductCard from '../../components/storefront/ProductCard';

const mockProducts = Array.from({ length: 8 }).map((_, i) => ({
  id: i + 1,
  name: `Premium Product ${i + 1}`,
  price: (29 + i * 3).toFixed(2),
  image: `https://picsum.photos/seed/product-${i + 1}/600/400`,
}));

export default function HomePage() {
  return (
    <div className="space-y-12">
      <section className="rounded-3xl bg-gradient-to-r from-slate-900 to-indigo-900 p-10 text-white">
        <h1 className="mb-3 text-4xl font-bold">Premium commerce experiences, beautifully delivered.</h1>
        <p className="mb-6 max-w-2xl text-slate-200">Build trust with modern shopping flows, fast checkout, and transparent referral rewards.</p>
        <div className="flex gap-3">
          <Link to="/products" className="rounded-xl bg-white px-5 py-3 font-semibold text-slate-900">Shop Now</Link>
          <Link to="/products?sort=popularity" className="rounded-xl border border-white px-5 py-3">Explore Deals</Link>
        </div>
      </section>

      <section>
        <h2 className="mb-4 text-2xl font-semibold">Featured Products</h2>
        <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
          {mockProducts.map((product) => <ProductCard key={product.id} product={product} />)}
        </div>
      </section>

      <section className="grid gap-4 rounded-2xl bg-white p-6 shadow-sm md:grid-cols-3 dark:bg-slate-900">
        {['Secure Payments', 'Fast Delivery', 'Global Support'].map((item) => <div key={item} className="rounded-xl bg-slate-100 p-4 dark:bg-slate-800">{item}</div>)}
      </section>

      <section className="rounded-2xl border border-dashed border-slate-300 p-8 text-center">
        <h3 className="mb-2 text-xl font-semibold">Stay in the loop</h3>
        <p className="mb-4 text-sm text-slate-500">Get newsletters for drops, offers, and referral boosts.</p>
        <input placeholder="you@example.com" className="mr-2 rounded-xl border px-3 py-2" />
        <button className="rounded-xl bg-slate-900 px-4 py-2 text-white">Subscribe</button>
      </section>
    </div>
  );
}
