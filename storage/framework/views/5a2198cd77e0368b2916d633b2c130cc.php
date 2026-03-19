<script>
(function () {
    const switcher = document.querySelector('.dark-mode-switcher');
    if (!switcher) return;

    const toggle = switcher.querySelector('.dark-mode-switcher__toggle');

    const getMode = () => {
        const mode = localStorage.getItem('darkMode');
        if (mode === 'active' || mode === 'inactive') return mode;
        if (mode === 'system') {
            return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'active' : 'inactive';
        }
        return document.documentElement.classList.contains('dark') ? 'active' : 'inactive';
    };

    const applyMode = (mode) => {
        document.documentElement.classList.toggle('dark', mode === 'active');
        if (toggle) {
            toggle.classList.toggle('dark-mode-switcher__toggle--active', mode === 'active');
        }
    };

    applyMode(getMode());

    switcher.addEventListener('click', function (event) {
        event.preventDefault();
        event.stopPropagation();
        event.stopImmediatePropagation();

        const nextMode = document.documentElement.classList.contains('dark') ? 'inactive' : 'active';
        localStorage.setItem('darkMode', nextMode);
        applyMode(nextMode);
    }, true);
})();
</script>
<?php /**PATH /media/bot/7E246BE4246B9DC1/laragon/www/parking/resources/views/visitor/auth/partials/theme-toggle-script.blade.php ENDPATH**/ ?>