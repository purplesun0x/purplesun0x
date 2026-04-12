import { useEffect, useState } from 'react';
import api from '../api/client';

export default function DashboardPage() {
  const [dashboard, setDashboard] = useState(null);

  useEffect(() => {
    api.get('/dashboard').then((res) => setDashboard(res.data));
  }, []);

  const createDownloadLink = async (orderItemId) => {
    const { data } = await api.post(`/downloads/${orderItemId}/token`);
    window.open(data.download_url, '_blank');
  };

  if (!dashboard) return <p className="p-6">Loading dashboard...</p>;

  return (
    <main className="mx-auto max-w-6xl px-4 py-10">
      <h1 className="text-3xl font-bold">User Dashboard</h1>
      <p className="mt-2">Referral Balance: ${Number(dashboard.commission_balance).toFixed(2)}</p>
      <h2 className="mt-6 text-xl font-semibold">Purchased Products</h2>
      <ul className="space-y-3">
        {dashboard.orders.flatMap((order) => order.items).map((item) => (
          <li key={item.id} className="rounded border bg-white p-3">
            <div className="flex items-center justify-between">
              <span>{item.product.name}</span>
              <button className="rounded bg-emerald-600 px-3 py-1 text-white" onClick={() => createDownloadLink(item.id)}>
                Download
              </button>
            </div>
          </li>
        ))}
      </ul>
      <h2 className="mt-6 text-xl font-semibold">License Keys</h2>
      <ul className="list-disc pl-5">
        {dashboard.licenses.map((license) => <li key={license.id}>{license.product.name}: {license.license_key}</li>)}
      </ul>
    </main>
  );
}
