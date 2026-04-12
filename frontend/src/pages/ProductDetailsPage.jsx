import { useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';
import api from '../api/client';

export default function ProductDetailsPage() {
  const { slug } = useParams();
  const [data, setData] = useState(null);

  useEffect(() => {
    api.get(`/products/${slug}`).then((res) => setData(res.data));
  }, [slug]);

  if (!data) return <p className="p-6">Loading...</p>;

  return (
    <main className="mx-auto max-w-5xl px-4 py-10">
      <h1 className="text-3xl font-bold">{data.product.name}</h1>
      <p className="mt-2 text-slate-700">{data.product.description}</p>
      <p className="mt-4 font-semibold">Price: ${Number(data.product.price).toFixed(2)}</p>
      <h2 className="mt-8 text-xl font-semibold">Related Products</h2>
      <ul className="mt-2 list-disc pl-5">
        {data.related.map((item) => <li key={item.id}>{item.name}</li>)}
      </ul>
    </main>
  );
}
