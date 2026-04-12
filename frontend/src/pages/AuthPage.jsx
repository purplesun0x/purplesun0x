import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import api from '../api/client';
import { useAuth } from '../context/AuthContext';

export default function AuthPage() {
  const [isLogin, setIsLogin] = useState(true);
  const [form, setForm] = useState({ name: '', email: '', password: '', password_confirmation: '', referral_code: '' });
  const { login } = useAuth();
  const navigate = useNavigate();

  const submit = async (e) => {
    e.preventDefault();
    if (isLogin) {
      await login(form.email, form.password);
    } else {
      await api.post('/auth/register', form);
      await login(form.email, form.password);
    }
    navigate('/dashboard');
  };

  return (
    <main className="mx-auto max-w-md px-4 py-12">
      <h1 className="text-2xl font-bold">{isLogin ? 'Login' : 'Register'}</h1>
      <form onSubmit={submit} className="mt-6 space-y-3 rounded bg-white p-4 shadow">
        {!isLogin && <input className="w-full rounded border p-2" placeholder="Full name" onChange={(e) => setForm({ ...form, name: e.target.value })} />}
        <input className="w-full rounded border p-2" placeholder="Email" type="email" onChange={(e) => setForm({ ...form, email: e.target.value })} />
        <input className="w-full rounded border p-2" placeholder="Password" type="password" onChange={(e) => setForm({ ...form, password: e.target.value })} />
        {!isLogin && (
          <>
            <input className="w-full rounded border p-2" placeholder="Confirm password" type="password" onChange={(e) => setForm({ ...form, password_confirmation: e.target.value })} />
            <input className="w-full rounded border p-2" placeholder="Referral code (optional)" onChange={(e) => setForm({ ...form, referral_code: e.target.value })} />
          </>
        )}
        <button className="w-full rounded bg-blue-600 py-2 text-white" type="submit">Continue</button>
      </form>
      <button className="mt-3 text-blue-700" onClick={() => setIsLogin((s) => !s)}>
        {isLogin ? 'Need an account? Register' : 'Already have an account? Login'}
      </button>
    </main>
  );
}
