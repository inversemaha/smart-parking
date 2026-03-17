<script>
(function () {
    const mode = localStorage.getItem('darkMode');
    const isSystemDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
    const shouldDark = mode === 'active' || (mode === 'system' && isSystemDark);
    document.documentElement.classList.toggle('dark', shouldDark);
})();
</script>
