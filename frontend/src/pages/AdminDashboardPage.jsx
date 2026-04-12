import { useEffect, useState } from 'react';
import api from '../api/client';

export default function AdminDashboardPage() {
  const [orders, setOrders] = useState([]);

  useEffect(() => {
    api.get('/admin/orders').then((res) => setOrders(res.data.data || []));
  }, []);

  return (
    <main className="mx-auto max-w-6xl px-4 py-10">
      <h1 className="text-3xl font-bold">Admin Dashboard</h1>
      <p className="text-slate-600">Monitor transactions and order statuses.</p>
      <table className="mt-6 w-full border-collapse border bg-white">
        <thead>
          <tr>
            <th className="border p-2 text-left">Order #</th>
            <th className="border p-2 text-left">Status</th>
            <th className="border p-2 text-left">Total</th>
          </tr>
        </thead>
        <tbody>
          {orders.map((order) => (
            <tr key={order.id}>
              <td className="border p-2">{order.order_number}</td>
              <td className="border p-2">{order.status}</td>
              <td className="border p-2">${Number(order.total).toFixed(2)}</td>
            </tr>
          ))}
        </tbody>
      </table>
    </main>
  );
}
