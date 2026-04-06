const stats = [
  { label: 'Total Orders', value: '42' },
  { label: 'Recent Activity', value: '8 updates' },
  { label: 'Referral Earnings', value: '$340.00' },
  { label: 'Wallet Balance', value: '$120.00' },
];

export default function UserOverviewPage() {
  return (
    <div className="space-y-5">
      <h1 className="text-2xl font-semibold">Dashboard Overview</h1>
      <div className="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {stats.map((s) => (
          <div className="card p-4" key={s.label}><p className="text-sm text-slate-500">{s.label}</p><p className="text-xl font-semibold">{s.value}</p></div>
        ))}
      </div>
    </div>
  );
}
