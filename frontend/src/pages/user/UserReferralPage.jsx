import { useEffect, useState } from 'react';
import api from '../../api/client';

export default function UserReferralPage() {
  const [data, setData] = useState(null);

  useEffect(() => {
    Promise.all([api.get('/referral/link'), api.get('/referral/stats')]).then(([link, stats]) => {
      setData({ ...link.data, ...stats.data });
    });
  }, []);

  if (!data) return <p>Loading referral stats...</p>;

  return (
    <div className="card p-5 space-y-3">
      <h2 className="text-xl font-semibold">Referral Program</h2>
      <p className="text-sm break-all">{data.referral_link}</p>
      <p>Total referrals: {data.total_referrals}</p>
      <p>Earnings: ${data.referral_earnings}</p>
      <button className="px-4 py-2 bg-brand text-white rounded-lg">Withdraw earnings</button>
    </div>
  );
}
