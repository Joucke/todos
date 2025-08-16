@props([
    'class' => 'flex items-center space-x-1 p-1 bg-gray-100 dark:bg-gray-700 rounded-lg transition-colors duration-200'
])

<div
    id="theme-toggle"
    class="{{ $class }}"
>
    <!-- Light Mode Button -->
    <button
        data-theme="light"
        class="theme-btn flex items-center justify-center w-8 h-8 rounded-md transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300"
        title="Light mode"
    >
        <x-icon type="light-mode" />
    </button>

    <!-- System Mode Button -->
    <button
        data-theme="system"
        class="theme-btn flex items-center justify-center w-8 h-8 rounded-md transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300"
        title="System preference"
    >
        <x-icon type="system-mode" />
    </button>

    <!-- Dark Mode Button -->
    <button
        data-theme="dark"
        class="theme-btn flex items-center justify-center w-8 h-8 rounded-md transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300"
        title="Dark mode"
    >
        <x-icon type="dark-mode" />
    </button>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const buttons = document.querySelectorAll('.theme-btn');

    // Get current theme from cookie or default to system
    function getCurrentTheme() {
        const cookies = document.cookie.split(';');
        for (let cookie of cookies) {
            const [name, value] = cookie.trim().split('=');
            if (name === 'theme') {
                return value;
            }
        }
        return 'system'; // default
    }

    // Set theme cookie
    function setTheme(theme) {
        document.cookie = `theme=${theme}; path=/; max-age=31536000`; // 1 year
    }

    // Apply theme to document
    function applyTheme(theme) {
        if (theme === 'dark') {
            document.documentElement.classList.add('dark');
        } else if (theme === 'light') {
            document.documentElement.classList.remove('dark');
        } else { // system
            // Check system preference
            if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }
    }

    // Update button states
    function updateButtonStates(activeTheme) {
        buttons.forEach(button => {
            const theme = button.getAttribute('data-theme');
            if (theme === activeTheme) {
                button.classList.add('bg-white', 'dark:bg-gray-600', 'shadow-sm');
                button.classList.remove('text-gray-500', 'dark:text-gray-400');
                button.classList.add('text-gray-900', 'dark:text-white');
            } else {
                button.classList.remove('bg-white', 'dark:bg-gray-600', 'shadow-sm');
                button.classList.add('text-gray-500', 'dark:text-gray-400');
                button.classList.remove('text-gray-900', 'dark:text-white');
            }
        });
    }

    // Initialize theme
    const currentTheme = getCurrentTheme();
    applyTheme(currentTheme);
    updateButtonStates(currentTheme);

    // Add click handlers
    buttons.forEach(button => {
        button.addEventListener('click', function() {
            const theme = this.getAttribute('data-theme');
            setTheme(theme);
            applyTheme(theme);
            updateButtonStates(theme);
        });
    });

    // Listen for system theme changes
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function() {
        const currentTheme = getCurrentTheme();
        if (currentTheme === 'system') {
            applyTheme('system');
        }
    });
});
</script>
