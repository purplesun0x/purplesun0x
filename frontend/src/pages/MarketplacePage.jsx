import { useEffect, useState } from 'react';
import api from '../api/client';
import ProductCard from '../components/ProductCard';
import SearchFilterBar from '../components/SearchFilterBar';

export default function MarketplacePage() {
  const [products, setProducts] = useState([]);
  const [query, setQuery] = useState('');
  const [category, setCategory] = useState('');
  const [minPrice, setMinPrice] = useState('');
  const [maxPrice, setMaxPrice] = useState('');

  const loadProducts = async () => {
    const { data } = await api.get('/products', {
      params: {
        search: query || undefined,
        category: category || undefined,
        min_price: minPrice || undefined,
        max_price: maxPrice || undefined,
      },
    });
    setProducts(data.data || []);
  };

  useEffect(() => { loadProducts(); }, []);

  const handleBuy = async (productId) => {
    const { data } = await api.post('/checkout', { product_ids: [productId], provider: 'mock' });
    window.location.href = data.payment.authorization_url;
  };

  return (
    <main className="mx-auto max-w-6xl space-y-6 px-4 py-10">
      <h1 className="text-3xl font-bold">Marketplace</h1>
      <SearchFilterBar {...{ query, setQuery, category, setCategory, minPrice, setMinPrice, maxPrice, setMaxPrice }} />
      <button className="rounded bg-blue-600 px-3 py-2 text-white" onClick={loadProducts}>Apply Filters</button>
      <section className="grid gap-4 md:grid-cols-3">
        {products.map((product) => <ProductCard key={product.id} product={product} onBuy={handleBuy} />)}
      </section>
    </main>
  );
}
