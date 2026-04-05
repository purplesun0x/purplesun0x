const navToggle = document.querySelector('[data-toggle="mobile-nav"]');
const mainNav = document.getElementById('mainNav');

if (navToggle && mainNav) {
    navToggle.addEventListener('click', () => {
        mainNav.classList.toggle('show');
    });
}

document.querySelectorAll('[data-open-modal]').forEach((button) => {
    button.addEventListener('click', () => {
        const modal = document.getElementById(button.dataset.openModal);
        if (modal) {
            modal.classList.add('open');
            modal.setAttribute('aria-hidden', 'false');
        }
    });
});

document.querySelectorAll('[data-close-modal]').forEach((button) => {
    button.addEventListener('click', () => {
        const modal = document.getElementById(button.dataset.closeModal);
        if (modal) {
            modal.classList.remove('open');
            modal.setAttribute('aria-hidden', 'true');
        }
    });
});

document.querySelectorAll('[data-copy]').forEach((button) => {
    button.addEventListener('click', async () => {
        const input = document.querySelector(button.dataset.copy);
        if (!input) {
            return;
        }

        try {
            await navigator.clipboard.writeText(input.value);
            const original = button.textContent;
            button.textContent = 'Copied!';
            setTimeout(() => {
                button.textContent = original;
            }, 1200);
        } catch (error) {
            button.textContent = 'Copy Failed';
        }
    });
});

window.addEventListener('keydown', (event) => {
    if (event.key !== 'Escape') {
        return;
    }

    document.querySelectorAll('.modal.open').forEach((modal) => {
        modal.classList.remove('open');
        modal.setAttribute('aria-hidden', 'true');
    });
});
