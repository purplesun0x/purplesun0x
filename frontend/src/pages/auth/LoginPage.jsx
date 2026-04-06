import { useState } from 'react';
import { useAuth } from '../../contexts/AuthContext';
import { Link, useNavigate } from 'react-router-dom';

export default function LoginPage() {
  const { login } = useAuth();
  const nav = useNavigate();
  const [form, setForm] = useState({ email: '', password: '' });

  const submit = async (e) => {
    e.preventDefault();
    await login(form);
    nav('/dashboard');
  };

  return (
    <form onSubmit={submit} className="max-w-md mx-auto card p-6 space-y-4">
      <h1 className="text-2xl font-semibold">Login</h1>
      <input className="w-full border rounded-lg px-3 py-2" placeholder="Email" onChange={(e) => setForm({ ...form, email: e.target.value })} />
      <input className="w-full border rounded-lg px-3 py-2" type="password" placeholder="Password" onChange={(e) => setForm({ ...form, password: e.target.value })} />
      <button className="w-full bg-brand text-white rounded-lg py-2">Sign in</button>
      <Link to="/forgot-password" className="text-sm text-brand block">Forgot password?</Link>
    </form>
  );
}
