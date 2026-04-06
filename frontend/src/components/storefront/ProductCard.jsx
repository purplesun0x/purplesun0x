import { Link } from 'react-router-dom';
import { useCart } from '../../contexts/CartContext';

export default function ProductCard({ product }) {
  const { addItem } = useCart();

  return (
    <article className="card p-4 transition hover:shadow-lg hover:-translate-y-0.5">
      <img src={product.image} alt={product.name} className="h-40 w-full object-cover rounded-xl mb-4" />
      <h3 className="font-semibold mb-1">{product.name}</h3>
      <p className="text-brand font-bold mb-3">${product.price}</p>
      <div className="flex gap-2">
        <button className="flex-1 bg-brand text-white rounded-lg py-2" onClick={() => addItem(product)}>
          Add to cart
        </button>
        <Link to={`/products/${product.id}`} className="px-3 py-2 border rounded-lg">View</Link>
      </div>
    </article>
  );
}
