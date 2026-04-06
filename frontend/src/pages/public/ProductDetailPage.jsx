import { useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';
import api from '../../api/client';
import { useCart } from '../../contexts/CartContext';

export default function ProductDetailPage() {
  const { id } = useParams();
  const [product, setProduct] = useState(null);
  const { addItem } = useCart();

  useEffect(() => {
    api.get(`/products/${id}`).then((res) => setProduct(res.data));
  }, [id]);

  if (!product) return <p>Loading product...</p>;

  return (
    <div className="grid md:grid-cols-2 gap-8">
      <img src={product.image} alt={product.name} className="w-full rounded-2xl" />
      <div>
        <h1 className="text-3xl font-semibold mb-2">{product.name}</h1>
        <p className="text-brand text-2xl font-bold mb-4">${product.price}</p>
        <p className="text-slate-600 dark:text-slate-300 mb-5">{product.description}</p>
        <button className="px-5 py-3 bg-brand text-white rounded-xl" onClick={() => addItem(product)}>Add to cart</button>
      </div>
    </div>
  );
}
