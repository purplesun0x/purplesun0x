import Button from '../common/Button';
import { useStore } from '../../context/StoreContext';

export default function ProductCard({ product }) {
  const { addToCart, toggleWishlist } = useStore();

  return (
    <div className="group rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:-translate-y-1 hover:shadow-xl dark:border-slate-700 dark:bg-slate-900">
      <img src={product.image} alt={product.name} className="mb-4 h-48 w-full rounded-xl object-cover" />
      <h3 className="font-semibold">{product.name}</h3>
      <p className="mb-4 text-sm text-slate-500">${product.price}</p>
      <div className="flex gap-2">
        <Button className="flex-1" onClick={() => addToCart(product)}>Add to cart</Button>
        <Button className="bg-amber-500 hover:bg-amber-400" onClick={() => toggleWishlist(product)}>♥</Button>
      </div>
    </div>
  );
}
