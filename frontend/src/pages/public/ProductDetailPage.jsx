import { useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';
import api from '../../api/client';
import { useStore } from '../../context/StoreContext';

export default function ProductDetailPage() {
  const { id } = useParams();
  const [product, setProduct] = useState(null);
  const { addToCart } = useStore();

  useEffect(() => {
    api.get(`/products/${id}`).then((res) => setProduct(res.data));
  }, [id]);

  if (!product) return <p>Loading...</p>;

  return (
    <div className="grid gap-6 md:grid-cols-2">
      <img src={product.image} alt={product.name} className="w-full rounded-2xl" />
      <div>
        <h1 className="text-3xl font-semibold">{product.name}</h1>
        <p className="my-4 text-slate-600">{product.description}</p>
        <p className="mb-4 text-2xl font-bold">${product.price}</p>
        <button className="rounded-xl bg-slate-900 px-4 py-2 text-white" onClick={() => addToCart(product)}>Add to cart</button>
      </div>
    </div>
  );
}
