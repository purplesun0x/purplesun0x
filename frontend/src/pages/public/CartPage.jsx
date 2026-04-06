import { useCart } from '../../contexts/CartContext';
import { Link } from 'react-router-dom';

export default function CartPage() {
  const { items, updateQty, removeItem, total } = useCart();

  return (
    <div className="space-y-6">
      <h1 className="text-2xl font-semibold">Your Cart</h1>
      <div className="space-y-3">
        {items.map((item) => (
          <div key={item.id} className="card p-4 flex flex-wrap gap-4 items-center justify-between">
            <div>
              <h3 className="font-medium">{item.name}</h3>
              <p>${item.price}</p>
            </div>
            <input type="number" min="1" value={item.quantity} className="w-20 border rounded px-2 py-1" onChange={(e) => updateQty(item.id, Number(e.target.value))} />
            <button onClick={() => removeItem(item.id)} className="text-red-500">Remove</button>
          </div>
        ))}
      </div>
      <div className="card p-5 flex justify-between items-center">
        <p className="font-semibold">Total: ${total.toFixed(2)}</p>
        <Link to="/checkout" className="px-5 py-2 bg-brand text-white rounded-lg">Proceed to checkout</Link>
      </div>
    </div>
  );
}
