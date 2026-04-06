import { useEffect, useState } from 'react';
import api from '../../api/client';
import ProductCard from '../../components/storefront/ProductCard';

export default function ProductListingPage() {
  const [products, setProducts] = useState([]);
  const [sort, setSort] = useState('latest');

  useEffect(() => {
    api.get('/products', { params: { sort } })
      .then((res) => setProducts(res.data.data || []))
      .catch(() => setProducts([]));
  }, [sort]);

  return (
    <div>
      <div className="mb-6 flex flex-wrap gap-3 rounded-xl bg-white p-4 shadow-sm dark:bg-slate-900">
        <select className="rounded border px-2 py-1" onChange={(e) => setSort(e.target.value)}>
          <option value="latest">Latest</option>
          <option value="price_asc">Price Low to High</option>
          <option value="price_desc">Price High to Low</option>
          <option value="popularity">Popularity</option>
        </select>
      </div>
      <div className="grid gap-4 md:grid-cols-3 lg:grid-cols-4">
        {products.map((product) => <ProductCard key={product.id} product={product} />)}
      </div>
    </div>
  );
}
