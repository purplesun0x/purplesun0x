import { useState } from 'react';
import { useSearchParams } from 'react-router-dom';
import { useAuth } from '../../context/AuthContext';

export default function RegisterPage() {
  const [search] = useSearchParams();
  const referral = search.get('ref') || '';
  const { register } = useAuth();
  const [form, setForm] = useState({ name: '', email: '', password: '', password_confirmation: '', referral_code: referral });

  const submit = async (e) => {
    e.preventDefault();
    await register(form);
  };

  return (
    <form onSubmit={submit} className="mx-auto max-w-md space-y-3 rounded-xl bg-white p-6 shadow-sm dark:bg-slate-900">
      <h1 className="text-2xl font-semibold">Create account</h1>
      {['name', 'email', 'password', 'password_confirmation', 'referral_code'].map((key) => (
        <input
          key={key}
          type={key.includes('password') ? 'password' : 'text'}
          className="w-full rounded border p-2"
          placeholder={key.replace('_', ' ')}
          value={form[key]}
          onChange={(e) => setForm({ ...form, [key]: e.target.value })}
        />
      ))}
      <button className="w-full rounded-xl bg-slate-900 py-2 text-white">Register</button>
    </form>
  );
}
