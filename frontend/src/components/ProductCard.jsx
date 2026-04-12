export default function ProductCard({ product, onBuy }) {
  return (
    <div className="rounded-xl border bg-white p-4 shadow-sm">
      <h3 className="text-lg font-semibold">{product.name}</h3>
      <p className="line-clamp-2 text-sm text-slate-600">{product.description}</p>
      <div className="mt-3 flex items-center justify-between">
        <span className="font-bold">${Number(product.price).toFixed(2)}</span>
        <button className="rounded bg-blue-600 px-3 py-1 text-white" onClick={() => onBuy(product.id)}>Buy</button>
      </div>
    </div>
  );
}
