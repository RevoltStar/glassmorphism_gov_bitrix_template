(function () {
    'use strict';

    const getDialog = () => document.querySelector('[data-government-sites-dialog]');
    const getToggle = () => document.querySelector('[data-government-sites-toggle]');

    const closeDialog = (dialog, toggle, restoreFocus) => {
        if (!dialog || !toggle || dialog.hidden) {
            return;
        }

        dialog.hidden = true;
        toggle.classList.remove('active');
        toggle.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = dialog.dataset.previousBodyOverflow || '';
        delete dialog.dataset.previousBodyOverflow;

        if (restoreFocus) {
            toggle.focus();
        }
    };

    const openDialog = (dialog, toggle) => {
        if (!dialog || !toggle || !dialog.hidden) {
            return;
        }

        const closeButton = dialog.querySelector('[data-government-sites-close]');
        dialog.dataset.previousBodyOverflow = document.body.style.overflow;
        dialog.hidden = false;
        toggle.classList.add('active');
        toggle.setAttribute('aria-expanded', 'true');
        document.body.style.overflow = 'hidden';
        (closeButton || dialog).focus();
    };

    function initializeGovernmentSites() {
        const dialog = getDialog();
        const toggle = getToggle();

        if (!dialog || !toggle) {
            return;
        }

        if (toggle.dataset.governmentSitesInitialized !== 'true') {
            toggle.dataset.governmentSitesInitialized = 'true';
            toggle.addEventListener('click', () => {
                const currentDialog = getDialog();
                const currentToggle = getToggle();
                if (!currentDialog || !currentToggle) {
                    return;
                }

                if (currentDialog.hidden) {
                    openDialog(currentDialog, currentToggle);
                } else {
                    closeDialog(currentDialog, currentToggle, true);
                }
            });
        }

        if (dialog.dataset.governmentSitesInitialized === 'true') {
            return;
        }

        const closeButton = dialog.querySelector('[data-government-sites-close]');
        const panel = dialog.querySelector('.government-sites__panel');
        if (!closeButton || !panel) {
            return;
        }

        dialog.dataset.governmentSitesInitialized = 'true';
        closeButton.addEventListener('click', () => closeDialog(dialog, getToggle(), true));

        dialog.addEventListener('click', (event) => {
            if (event.target === dialog || !panel.contains(event.target)) {
                closeDialog(dialog, getToggle(), true);
            }
        });

        dialog.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                event.preventDefault();
                closeDialog(dialog, getToggle(), true);
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeGovernmentSites);
    } else {
        initializeGovernmentSites();
    }

    if (window.BX && typeof window.BX.addCustomEvent === 'function') {
        window.BX.addCustomEvent('onFrameDataReceived', initializeGovernmentSites);
    }
}());
