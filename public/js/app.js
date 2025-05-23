//dark mode
const darkModeToggle = document.getElementById('darkModeToggle');
darkModeToggle.addEventListener('change', function () {
    const isDark = this.checked;
    const moonIcon = document.getElementById('moon-icon');
    const sunIcon = document.getElementById('sun-icon');

    if (isDark) {
        console.log('dark mode');
        document.body.classList.add('bg-dark', 'text-light');
        moonIcon.style.display = 'inline';
        sunIcon.style.display = 'none';
        // menentukan tema
    } else {
        console.log('light mode');
        document.body.classList.remove('bg-dark', 'text-light');
        moonIcon.style.display = 'none';
        sunIcon.style.display = 'inline';
    }
});