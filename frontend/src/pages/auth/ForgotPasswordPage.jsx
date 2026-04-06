export default function ForgotPasswordPage() {
  return (
    <div className="mx-auto max-w-md rounded-xl bg-white p-6 shadow-sm dark:bg-slate-900">
      <h1 className="mb-3 text-2xl font-semibold">Forgot Password</h1>
      <input className="w-full rounded border p-2" placeholder="Email" />
      <button className="mt-3 w-full rounded-xl bg-slate-900 py-2 text-white">Send reset link</button>
    </div>
  );
}
