import { useEffect, useState } from 'react';
import api from '../../api/client';
import ProductCard from '../../components/storefront/ProductCard';

export default function ProductsPage() {
  const [products, setProducts] = useState([]);
  const [sortBy, setSortBy] = useState('latest');

  useEffect(() => {
    api.get('/products', { params: { sort_by: sortBy } }).then((res) => setProducts(res.data.data || []));
  }, [sortBy]);

  return (
    <div className="space-y-6">
      <div className="flex flex-wrap gap-3 items-center justify-between">
        <h1 className="text-2xl font-semibold">Products</h1>
        <select className="border rounded-lg px-3 py-2" onChange={(e) => setSortBy(e.target.value)}>
          <option value="latest">Latest</option>
          <option value="price_asc">Price: Low to High</option>
          <option value="price_desc">Price: High to Low</option>
          <option value="popularity">Popularity</option>
        </select>
      </div>
      <div className="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {products.map((product) => <ProductCard key={product.id} product={product} />)}
      </div>
    </div>
  );
}
