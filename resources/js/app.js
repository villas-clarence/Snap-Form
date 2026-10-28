import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

const themeToggle = document.querySelector('[data-theme-toggle]');

if (themeToggle) {
    const syncThemeToggle = () => {
        const isDark = document.documentElement.classList.contains('dark-mode');
        themeToggle.setAttribute('aria-pressed', String(isDark));
        themeToggle.querySelector('[data-theme-toggle-label]').textContent = isDark ? 'Light mode' : 'Dark mode';
    };

    syncThemeToggle();
    themeToggle.addEventListener('click', () => {
        const isDark = document.documentElement.classList.toggle('dark-mode');
        localStorage.setItem('snapform-theme', isDark ? 'dark' : 'light');
        syncThemeToggle();
    });
}
