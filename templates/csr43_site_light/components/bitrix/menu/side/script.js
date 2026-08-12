(function () {
    'use strict';
    function initializeSideMenus() {
        document.querySelectorAll('[data-side-menu]').forEach((root) => {
            if (root.dataset.sideMenuInitialized === 'true') { return; }
            const select = root.querySelector('[data-side-menu-select]');
            if (!select) { return; }
            root.dataset.sideMenuInitialized = 'true';
            select.addEventListener('change', () => {
                const url = select.value;
                if (url) { window.location.assign(url); }
            });
        });
    }
    if (document.readyState === 'loading') { document.addEventListener('DOMContentLoaded', initializeSideMenus); } else { initializeSideMenus(); }
    if (window.BX && typeof window.BX.addCustomEvent === 'function') { window.BX.addCustomEvent('onFrameDataReceived', initializeSideMenus); }
}());
