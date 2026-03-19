<script>
(function () {
    const mode = localStorage.getItem('darkMode');
    const isSystemDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
    const shouldDark = mode === 'active' || (mode === 'system' && isSystemDark);
    document.documentElement.classList.toggle('dark', shouldDark);
})();
</script>
<?php /**PATH /media/bot/7E246BE4246B9DC1/laragon/www/parking/resources/views/visitor/auth/partials/dark-init.blade.php ENDPATH**/ ?>