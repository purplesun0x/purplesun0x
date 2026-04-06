import { useEffect, useState } from 'react';
import api from '../../api/client';

export default function ReferralProgramPage() {
  const [stats, setStats] = useState(null);
  const [link, setLink] = useState('');

  useEffect(() => {
    api.get('/referral/stats').then((res) => setStats(res.data));
    api.get('/referral/link').then((res) => setLink(res.data.link));
  }, []);

  return (
    <div className="space-y-4 rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
      <h2 className="text-xl font-semibold">Referral Program</h2>
      <p className="rounded border bg-slate-50 p-3 text-sm dark:bg-slate-800">{link}</p>
      <button className="rounded bg-slate-900 px-3 py-2 text-white" onClick={() => navigator.clipboard.writeText(link)}>Copy Link</button>
      <p>Total referrals: {stats?.total_referrals ?? 0}</p>
      <p>Earnings: ${stats?.earnings ?? 0}</p>
      <button className="rounded bg-emerald-600 px-3 py-2 text-white">Withdraw earnings</button>
    </div>
  );
}
