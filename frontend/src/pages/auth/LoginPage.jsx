import { useState } from 'react';
import { useAuth } from '../../context/AuthContext';

export default function LoginPage() {
  const { login } = useAuth();
  const [form, setForm] = useState({ email: '', password: '' });

  const submit = async (e) => {
    e.preventDefault();
    await login(form);
  };

  return (
    <form onSubmit={submit} className="mx-auto max-w-md space-y-3 rounded-xl bg-white p-6 shadow-sm dark:bg-slate-900">
      <h1 className="text-2xl font-semibold">Login</h1>
      <input className="w-full rounded border p-2" placeholder="Email" onChange={(e) => setForm({ ...form, email: e.target.value })} />
      <input className="w-full rounded border p-2" type="password" placeholder="Password" onChange={(e) => setForm({ ...form, password: e.target.value })} />
      <button className="w-full rounded-xl bg-slate-900 py-2 text-white">Sign in</button>
    </form>
  );
}
