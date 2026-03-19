/**
 * Dark Mode Toggle Script for Midone Admin Dashboard
 * Handles dynamic dark mode switching with localStorage persistence
 */

(function () {
    'use strict';

    // Get the HTML element
    const htmlElement = document.documentElement;
    const darkModeSwitcher = document.querySelector('.dark-mode-switcher');
    const darkModeToggle = document.querySelector('.dark-mode-switcher__toggle');

    /**
     * Initialize dark mode on page load
     */
    const initDarkMode = function () {
        const mode = localStorage.getItem('darkMode');
        const isSystemDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
        const shouldDark = mode === 'active' || (mode === 'system' && isSystemDark);

        // Apply dark mode
        htmlElement.classList.toggle('dark', shouldDark);
        updateToggleUI(shouldDark);
    };

    /**
     * Update the toggle UI based on dark mode state
     */
    const updateToggleUI = function (isDark) {
        if (darkModeToggle) {
            if (isDark) {
                darkModeToggle.classList.add('dark-mode-switcher__toggle--active');
            } else {
                darkModeToggle.classList.remove('dark-mode-switcher__toggle--active');
            }
        }
        
        // Update aria-checked attribute
        if (darkModeSwitcher) {
            darkModeSwitcher.setAttribute('aria-checked', isDark ? 'true' : 'false');
        }
    };

    /**
     * Toggle dark mode
     */
    const toggleDarkMode = function (e) {
        // Prevent any default behavior or propagation
        if (e) {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
        }

        const isDarkNow = htmlElement.classList.contains('dark');
        const newState = !isDarkNow;

        // Update HTML class
        htmlElement.classList.toggle('dark', newState);

        // Update localStorage
        localStorage.setItem('darkMode', newState ? 'active' : 'inactive');

        // Update UI
        updateToggleUI(newState);

        // Dispatch custom event for other scripts to listen
        window.dispatchEvent(new CustomEvent('darkModeToggled', {
            detail: { isDark: newState }
        }));

        return false;
    };

    /**
     * Listen for system dark mode preference changes
     */
    const listenSystemPreference = function () {
        if (window.matchMedia) {
            const darkModeQuery = window.matchMedia('(prefers-color-scheme: dark)');

            darkModeQuery.addEventListener('change', function (e) {
                const mode = localStorage.getItem('darkMode');
                // Only apply system preference if user hasn't manually set a preference
                if (mode === 'system' || !mode) {
                    htmlElement.classList.toggle('dark', e.matches);
                    updateToggleUI(e.matches);
                }
            });
        }
    };

    /**
     * Setup click handler with capturing phase
     */
    const setupClickHandler = function () {
        if (darkModeSwitcher) {
            // Use capturing phase to intercept before other handlers
            darkModeSwitcher.addEventListener('click', toggleDarkMode, true);
            
            // Also handle keyboard access
            darkModeSwitcher.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    toggleDarkMode(e);
                }
            }, true);
        }
    };

    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            // Small delay to ensure our handler is registered first
            setTimeout(function () {
                initDarkMode();
                listenSystemPreference();
                setupClickHandler();
            }, 0);
        });
    } else {
        initDarkMode();
        listenSystemPreference();
        setupClickHandler();
    }

    // Expose to global scope for manual control
    window.DarkMode = {
        toggle: function () {
            toggleDarkMode();
        },
        enable: function () {
            htmlElement.classList.add('dark');
            localStorage.setItem('darkMode', 'active');
            updateToggleUI(true);
        },
        disable: function () {
            htmlElement.classList.remove('dark');
            localStorage.setItem('darkMode', 'inactive');
            updateToggleUI(false);
        },
        isEnabled: function () {
            return htmlElement.classList.contains('dark');
        }
    };
})();
