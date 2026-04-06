import ProductCard from '../../components/storefront/ProductCard';

const featured = [
  { id: 1, name: 'Air Pro Headphones', price: 299, image: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?q=80&w=1200&auto=format&fit=crop' },
  { id: 2, name: 'Smartwatch X', price: 199, image: 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?q=80&w=1200&auto=format&fit=crop' },
  { id: 3, name: 'Premium Keyboard', price: 129, image: 'https://images.unsplash.com/photo-1517336714739-489689fd1ca8?q=80&w=1200&auto=format&fit=crop' },
];

export default function HomePage() {
  return (
    <div className="space-y-12">
      <section className="card p-10 bg-gradient-to-r from-slate-900 to-brand text-white">
        <h1 className="text-4xl font-bold mb-4">Modern shopping for modern teams.</h1>
        <p className="max-w-2xl mb-6 text-slate-200">Curated products, secure checkout, and a referral engine that rewards your growth.</p>
        <div className="flex gap-3">
          <button className="px-5 py-3 bg-white text-slate-900 rounded-xl">Shop Now</button>
          <button className="px-5 py-3 border border-white/60 rounded-xl">Explore Deals</button>
        </div>
      </section>

      <section>
        <h2 className="text-2xl font-semibold mb-5">Featured Products</h2>
        <div className="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
          {featured.map((product) => <ProductCard key={product.id} product={product} />)}
        </div>
      </section>
    </div>
  );
}
