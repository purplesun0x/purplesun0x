import { useState } from 'react';
import { useAuth } from '../../contexts/AuthContext';
import { useNavigate, useSearchParams } from 'react-router-dom';

export default function RegisterPage() {
  const { register } = useAuth();
  const nav = useNavigate();
  const [search] = useSearchParams();
  const [form, setForm] = useState({ name: '', email: '', password: '', password_confirmation: '', referral_code: search.get('ref') || '' });

  const submit = async (e) => {
    e.preventDefault();
    await register(form);
    nav('/dashboard');
  };

  return (
    <form onSubmit={submit} className="max-w-xl mx-auto card p-6 grid gap-3">
      <h1 className="text-2xl font-semibold">Create account</h1>
      <input className="border rounded-lg px-3 py-2" placeholder="Name" onChange={(e) => setForm({ ...form, name: e.target.value })} />
      <input className="border rounded-lg px-3 py-2" placeholder="Email" onChange={(e) => setForm({ ...form, email: e.target.value })} />
      <input className="border rounded-lg px-3 py-2" type="password" placeholder="Password" onChange={(e) => setForm({ ...form, password: e.target.value })} />
      <input className="border rounded-lg px-3 py-2" type="password" placeholder="Confirm password" onChange={(e) => setForm({ ...form, password_confirmation: e.target.value })} />
      <input className="border rounded-lg px-3 py-2" placeholder="Referral code (optional)" value={form.referral_code} onChange={(e) => setForm({ ...form, referral_code: e.target.value })} />
      <button className="bg-brand text-white rounded-lg py-2">Register</button>
    </form>
  );
}
