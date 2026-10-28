import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

const themeToggles = document.querySelectorAll('[data-theme-toggle]');

if (themeToggles.length > 0) {
    const syncThemeToggle = () => {
        const isDark = document.documentElement.classList.contains('dark-mode');
        themeToggles.forEach((themeToggle) => {
            themeToggle.setAttribute('aria-pressed', String(isDark));
            themeToggle.querySelector('[data-theme-toggle-label]').textContent = isDark ? 'Light mode' : 'Dark mode';
        });
    };

    syncThemeToggle();
    themeToggles.forEach((themeToggle) => {
        themeToggle.addEventListener('click', () => {
            const isDark = document.documentElement.classList.toggle('dark-mode');
            localStorage.setItem('snapform-theme', isDark ? 'dark' : 'light');
            syncThemeToggle();
        });
    });
}
