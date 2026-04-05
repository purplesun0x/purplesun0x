const root = document.documentElement;
const toggle = document.querySelector('[data-theme-toggle]');
const modal = document.querySelector('[data-modal]');

toggle?.addEventListener('click', () => {
  root.classList.toggle('dark');
});

document.querySelector('[data-modal-open]')?.addEventListener('click', () => {
  modal?.classList.add('open');
});

document.querySelector('[data-modal-close]')?.addEventListener('click', () => {
  modal?.classList.remove('open');
});

window.addEventListener('click', (e) => {
  if (e.target === modal) {
    modal.classList.remove('open');
  }
});

document.querySelectorAll('[data-copy-target]').forEach((btn) => {
  btn.addEventListener('click', async () => {
    const target = document.querySelector(btn.dataset.copyTarget);
    if (!(target instanceof HTMLInputElement)) return;

    await navigator.clipboard.writeText(target.value);
    const previous = btn.textContent;
    btn.textContent = 'Copied';
    setTimeout(() => {
      btn.textContent = previous;
    }, 1200);
  });
});
