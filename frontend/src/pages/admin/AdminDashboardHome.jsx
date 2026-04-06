import { useEffect, useState } from 'react';
import api from '../../api/client';

export default function AdminDashboardHome() {
  const [data, setData] = useState({});

  useEffect(() => {
    api.get('/admin/dashboard').then((res) => setData(res.data));
  }, []);

  return (
    <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
      {Object.entries(data).map(([key, value]) => (
        <div key={key} className="rounded-xl bg-white p-4 shadow-sm dark:bg-slate-900">
          <p className="text-sm text-slate-500">{key.replaceAll('_', ' ')}</p>
          <p className="text-2xl font-bold">{value}</p>
        </div>
      ))}
    </div>
  );
}
