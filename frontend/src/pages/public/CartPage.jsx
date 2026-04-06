import { Link } from 'react-router-dom';
import { useStore } from '../../context/StoreContext';

export default function CartPage() {
  const { cart, updateQty, removeFromCart, cartTotal } = useStore();

  return (
    <div className="space-y-4">
      {cart.map((item) => (
        <div key={item.id} className="flex items-center justify-between rounded-xl bg-white p-4 shadow-sm dark:bg-slate-900">
          <div>
            <p className="font-semibold">{item.name}</p>
            <p className="text-sm text-slate-500">${item.price}</p>
          </div>
          <input type="number" value={item.qty} min="1" className="w-20 rounded border px-2 py-1" onChange={(e) => updateQty(item.id, Number(e.target.value))} />
          <button className="text-red-500" onClick={() => removeFromCart(item.id)}>Remove</button>
        </div>
      ))}
      <div className="rounded-xl bg-white p-4 shadow-sm dark:bg-slate-900">
        <p className="mb-3 font-semibold">Total: ${cartTotal.toFixed(2)}</p>
        <Link to="/checkout" className="rounded-xl bg-slate-900 px-4 py-2 text-white">Proceed to checkout</Link>
      </div>
    </div>
  );
}
