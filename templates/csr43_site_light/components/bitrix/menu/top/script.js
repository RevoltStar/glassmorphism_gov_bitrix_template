(function () {
    'use strict';

    function initializeTopMenus() {
        document.querySelectorAll('[data-top-menu]').forEach((root) => {
            if (root.dataset.topMenuInitialized === 'true') { return; }
            const openButton = root.querySelector('[data-top-menu-open]');
            const closeButton = root.querySelector('[data-top-menu-close]');
            const drawer = root.querySelector('[data-top-menu-drawer]');
            const backdrop = root.querySelector('[data-top-menu-backdrop]');
            if (!openButton || !closeButton || !drawer || !backdrop) { return; }

            root.dataset.topMenuInitialized = 'true';
            let previousBodyOverflow = '';

            const close = (restoreFocus) => {
                if (drawer.hidden) { return; }
                drawer.hidden = true;
                backdrop.hidden = true;
                openButton.setAttribute('aria-expanded', 'false');
                document.body.style.overflow = previousBodyOverflow;
                if (restoreFocus) { openButton.focus(); }
            };
            const open = () => {
                if (!drawer.hidden) { return; }
                document.querySelectorAll('[data-top-menu-drawer]:not([hidden])').forEach((openDrawer) => {
                    if (openDrawer !== drawer) {
                        const otherRoot = openDrawer.closest('[data-top-menu]');
                        const otherClose = otherRoot && otherRoot.querySelector('[data-top-menu-close]');
                        if (otherClose) { otherClose.click(); }
                    }
                });
                previousBodyOverflow = document.body.style.overflow;
                drawer.hidden = false;
                backdrop.hidden = false;
                openButton.setAttribute('aria-expanded', 'true');
                document.body.style.overflow = 'hidden';
                closeButton.focus();
            };

            openButton.addEventListener('click', open);
            closeButton.addEventListener('click', () => close(true));
            backdrop.addEventListener('click', () => close(true));
            root.addEventListener('top-menu:close', () => close(false));
            root.querySelectorAll('[data-top-menu-submenu-toggle]').forEach((button) => {
                button.addEventListener('click', () => {
                    const submenu = document.getElementById(button.getAttribute('aria-controls') || '');
                    if (!submenu || !root.contains(submenu)) { return; }
                    const expanded = button.getAttribute('aria-expanded') === 'true';
                    button.setAttribute('aria-expanded', expanded ? 'false' : 'true');
                    submenu.hidden = expanded;
                });
            });
            root.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && !drawer.hidden) {
                    event.preventDefault();
                    close(true);
                }
            });
        });
    }

    const documentRoot = document.documentElement;
    if (documentRoot.dataset.topMenuGlobalInitialized !== 'true') {
        documentRoot.dataset.topMenuGlobalInitialized = 'true';
        document.addEventListener('keydown', (event) => {
            if (event.key !== 'Escape') { return; }
            document.querySelectorAll('[data-top-menu]').forEach((root) => {
                const drawer = root.querySelector('[data-top-menu-drawer]');
                const closeButton = root.querySelector('[data-top-menu-close]');
                if (drawer && !drawer.hidden && closeButton) { closeButton.click(); }
            });
        });
        const desktopMedia = window.matchMedia('(min-width: 62rem)');
        desktopMedia.addEventListener('change', (event) => {
            if (!event.matches) { return; }
            document.querySelectorAll('[data-top-menu]').forEach((root) => {
                const drawer = root.querySelector('[data-top-menu-drawer]');
                if (drawer && !drawer.hidden) {
                    root.dispatchEvent(new CustomEvent('top-menu:close'));
                }
            });
        });
        if (document.readyState === 'loading') { document.addEventListener('DOMContentLoaded', initializeTopMenus); }
        if (window.BX && typeof window.BX.addCustomEvent === 'function') { window.BX.addCustomEvent('onFrameDataReceived', initializeTopMenus); }
    }
    if (document.readyState !== 'loading') { initializeTopMenus(); }
}());
