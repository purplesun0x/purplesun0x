export default function SearchFilterBar({ query, setQuery, category, setCategory, minPrice, setMinPrice, maxPrice, setMaxPrice }) {
  return (
    <div className="grid gap-3 md:grid-cols-5">
      <input className="rounded border p-2" value={query} onChange={(e) => setQuery(e.target.value)} placeholder="Search scripts" />
      <input className="rounded border p-2" value={category} onChange={(e) => setCategory(e.target.value)} placeholder="Category slug" />
      <input className="rounded border p-2" value={minPrice} onChange={(e) => setMinPrice(e.target.value)} placeholder="Min price" />
      <input className="rounded border p-2" value={maxPrice} onChange={(e) => setMaxPrice(e.target.value)} placeholder="Max price" />
      <button className="rounded bg-slate-800 px-4 py-2 text-white">Filter</button>
    </div>
  );
}
