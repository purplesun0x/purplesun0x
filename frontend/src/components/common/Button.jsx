export default function Button({ children, className = '', ...props }) {
  return (
    <button
      className={`rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:-translate-y-0.5 hover:bg-slate-700 ${className}`}
      {...props}
    >
      {children}
    </button>
  );
}
