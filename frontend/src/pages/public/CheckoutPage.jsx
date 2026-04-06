import { useCart } from '../../contexts/CartContext';
import api from '../../api/client';

export default function CheckoutPage() {
  const { items, total, setItems } = useCart();

  const placeOrder = async () => {
    await api.post('/orders', {
      items: items.map((i) => ({ product_id: i.id, quantity: i.quantity })),
      shipping_address: { line1: '123 Main St', city: 'Austin', country: 'US' },
      payment_method: 'stripe',
      payment_status: 'pending',
    });
    setItems([]);
    alert('Order placed. Payment confirmation pending.');
  };

  return (
    <div className="max-w-3xl space-y-4">
      <h1 className="text-2xl font-semibold">Checkout</h1>
      <div className="card p-5">
        <p className="mb-3">Order total: ${total.toFixed(2)}</p>
        <button className="px-5 py-2 bg-brand text-white rounded-lg" onClick={placeOrder}>Place order</button>
      </div>
    </div>
  );
}
