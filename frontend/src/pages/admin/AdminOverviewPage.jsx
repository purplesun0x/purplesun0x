export default function AdminOverviewPage() {
  return (
    <div className="space-y-4">
      <h1 className="text-2xl font-semibold">Admin Dashboard</h1>
      <div className="grid md:grid-cols-3 gap-4">
        <div className="card p-4">Sales graph widget</div>
        <div className="card p-4">Users graph widget</div>
        <div className="card p-4">Revenue graph widget</div>
      </div>
    </div>
  );
}
