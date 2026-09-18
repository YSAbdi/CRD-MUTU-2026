document.addEventListener('DOMContentLoaded', () => {
    const toggleButton = document.getElementById('themeToggle');
    const body = document.body;

    const storedTheme = localStorage.getItem('crm-theme');
    if (storedTheme === 'dark') {
        body.classList.add('dark');
        toggleButton.textContent = 'Light Mode';
    }

    toggleButton.addEventListener('click', () => {
        body.classList.toggle('dark');
        const isDark = body.classList.contains('dark');
        localStorage.setItem('crm-theme', isDark ? 'dark' : 'light');
        toggleButton.textContent = isDark ? 'Light Mode' : 'Dark Mode';
    });
});
