export default function UserDashboardHome() {
  const cards = [
    { label: 'Total orders', value: '128' },
    { label: 'Recent activity', value: '12 events' },
    { label: 'Referral earnings', value: '$2,184' },
    { label: 'Wallet balance', value: '$326.00' },
  ];

  return <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-4">{cards.map((c) => <div key={c.label} className="rounded-xl bg-white p-4 shadow-sm dark:bg-slate-900"><p className="text-sm text-slate-500">{c.label}</p><p className="text-2xl font-bold">{c.value}</p></div>)}</div>;
}
