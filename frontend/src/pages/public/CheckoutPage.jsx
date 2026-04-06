export default function CheckoutPage() {
  return (
    <div className="grid gap-6 md:grid-cols-2">
      <form className="space-y-3 rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
        <h2 className="text-xl font-semibold">Shipping Details</h2>
        <input className="w-full rounded border p-2" placeholder="Full name" />
        <input className="w-full rounded border p-2" placeholder="Address" />
        <select className="w-full rounded border p-2"><option>Stripe</option><option>Paystack</option></select>
      </form>
      <div className="rounded-xl bg-white p-5 shadow-sm dark:bg-slate-900">
        <h2 className="text-xl font-semibold">Order Summary</h2>
        <button className="mt-4 rounded-xl bg-slate-900 px-4 py-2 text-white">Place order</button>
      </div>
    </div>
  );
}
