(function () {
    'use strict';

    function initializeTopMenus() {
        document.querySelectorAll('[data-top-menu]').forEach((root) => {
            if (root.dataset.topMenuInitialized === 'true') { return; }
            const openButton = root.querySelector('[data-top-menu-open]');
            const closeButton = root.querySelector('[data-top-menu-close]');
            const drawer = root.querySelector('[data-top-menu-drawer]');
            const backdrop = root.querySelector('[data-top-menu-backdrop]');
            const desktop = root.querySelector('.top-menu__desktop');
            if (!openButton || !closeButton || !drawer || !backdrop || !desktop) { return; }

            root.dataset.topMenuInitialized = 'true';
            let previousBodyOverflow = '';
            let desktopPositionFrame = 0;

            const getDirectChild = (element, selector) => Array.from(element.children).find((child) => child.matches(selector)) || null;
            const positionDesktopSubmenu = (item) => {
                const submenu = getDirectChild(item, '.top-menu__submenu');
                if (!submenu || window.getComputedStyle(submenu).display === 'none') { return; }

                item.classList.remove('top-menu__item--submenu-left');
                submenu.classList.remove('top-menu__submenu--align-right', 'top-menu__submenu--viewport-positioned');
                submenu.style.removeProperty('--top-menu-submenu-top');
                submenu.style.removeProperty('--top-menu-submenu-left');

                const parentMenu = item.parentElement;
                const isNested = parentMenu && parentMenu.classList.contains('top-menu__submenu');
                if (!isNested) {
                    if (submenu.getBoundingClientRect().right > window.innerWidth - 8) {
                        submenu.classList.add('top-menu__submenu--align-right');
                    }
                    return;
                }

                const itemRect = item.getBoundingClientRect();
                const submenuWidth = submenu.offsetWidth;
                const submenuHeight = submenu.offsetHeight;
                const spaceRight = window.innerWidth - itemRect.right - 8;
                const spaceLeft = itemRect.left - 8;
                const openLeft = spaceRight < submenuWidth && spaceLeft > spaceRight;
                const submenuOverlap = 4;
                const desiredLeft = openLeft
                    ? itemRect.left - submenuWidth + submenuOverlap
                    : itemRect.right - submenuOverlap;
                const desiredTop = itemRect.top - 8;
                const left = Math.max(8, Math.min(desiredLeft, window.innerWidth - submenuWidth - 8));
                const top = Math.max(8, Math.min(desiredTop, window.innerHeight - submenuHeight - 8));

                if (openLeft) { item.classList.add('top-menu__item--submenu-left'); }
                submenu.style.setProperty('--top-menu-submenu-top', `${top}px`);
                submenu.style.setProperty('--top-menu-submenu-left', `${left}px`);
                submenu.classList.add('top-menu__submenu--viewport-positioned');

                const positionedRect = submenu.getBoundingClientRect();
                submenu.style.setProperty('--top-menu-submenu-top', `${top + top - positionedRect.top}px`);
                submenu.style.setProperty('--top-menu-submenu-left', `${left + left - positionedRect.left}px`);
            };
            const positionVisibleDesktopSubmenus = () => {
                if (desktopPositionFrame !== 0) { return; }
                desktopPositionFrame = window.requestAnimationFrame(() => {
                    desktopPositionFrame = 0;
                    desktop.querySelectorAll('.top-menu__item--parent').forEach(positionDesktopSubmenu);
                });
            };
            const closeFocusedDesktopSubmenu = () => {
                if (window.getComputedStyle(desktop).display === 'none') { return false; }
                const focused = document.activeElement;
                if (!(focused instanceof Element) || !desktop.contains(focused)) { return false; }
                let owner = focused.closest('.top-menu__item--parent');
                let submenu = null;
                while (owner && desktop.contains(owner)) {
                    submenu = getDirectChild(owner, '.top-menu__submenu');
                    if (submenu && window.getComputedStyle(submenu).display !== 'none') { break; }
                    owner = owner.parentElement ? owner.parentElement.closest('.top-menu__item--parent') : null;
                }
                if (!owner || !submenu) { return false; }
                const trigger = getDirectChild(owner, '.top-menu__link, .top-menu__text');
                if (!trigger) { return false; }
                trigger.focus({ preventScroll: true });
                owner.classList.add('top-menu__item--submenu-dismissed');
                return true;
            };

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
            desktop.querySelectorAll('.top-menu__item--parent').forEach((item) => {
                item.addEventListener('mouseenter', () => {
                    item.classList.remove('top-menu__item--submenu-dismissed');
                    positionDesktopSubmenu(item);
                });
                item.addEventListener('mouseleave', () => item.classList.remove('top-menu__item--submenu-dismissed'));
                item.addEventListener('focusin', () => {
                    item.classList.remove('top-menu__item--submenu-dismissed');
                    positionDesktopSubmenu(item);
                });
                item.addEventListener('focusout', (event) => {
                    if (!(event.relatedTarget instanceof Node) || !item.contains(event.relatedTarget)) {
                        item.classList.remove('top-menu__item--submenu-dismissed');
                    }
                });
            });
            desktop.addEventListener('scroll', positionVisibleDesktopSubmenus, true);
            root.addEventListener('top-menu:position', positionVisibleDesktopSubmenus);
            const closeForDesktopLayout = () => {
                if (window.getComputedStyle(openButton).display === 'none' && !drawer.hidden) {
                    close(false);
                }
                positionVisibleDesktopSubmenus();
            };
            if (typeof window.ResizeObserver === 'function') {
                const layoutObserver = new window.ResizeObserver(closeForDesktopLayout);
                layoutObserver.observe(root);
            } else {
                window.addEventListener('resize', closeForDesktopLayout);
            }
            root.addEventListener('keydown', (event) => {
                if (event.key !== 'Escape') { return; }
                if (!drawer.hidden) {
                    event.preventDefault();
                    close(true);
                    return;
                }
                if (closeFocusedDesktopSubmenu()) { event.preventDefault(); }
            });
        });
    }

    const documentRoot = document.documentElement;
    if (documentRoot.dataset.topMenuGlobalInitialized !== 'true') {
        documentRoot.dataset.topMenuGlobalInitialized = 'true';
        document.addEventListener('keydown', (event) => {
            if (event.key !== 'Escape' || event.defaultPrevented) { return; }
            let drawerClosed = false;
            document.querySelectorAll('[data-top-menu]').forEach((root) => {
                const drawer = root.querySelector('[data-top-menu-drawer]');
                const closeButton = root.querySelector('[data-top-menu-close]');
                if (drawer && !drawer.hidden && closeButton) {
                    closeButton.click();
                    drawerClosed = true;
                }
            });
            if (drawerClosed) {
                event.preventDefault();
                return;
            }
            document.querySelectorAll('[data-top-menu] .top-menu__desktop').forEach((desktop) => {
                const hoveredParents = Array.from(desktop.querySelectorAll('.top-menu__item--parent:hover'));
                const owner = hoveredParents.reverse().find((item) => {
                    const submenu = Array.from(item.children).find((child) => child.matches('.top-menu__submenu'));
                    return submenu && window.getComputedStyle(submenu).display !== 'none';
                });
                if (owner) { owner.classList.add('top-menu__item--submenu-dismissed'); }
            });
        });
        window.addEventListener('resize', () => {
            document.querySelectorAll('[data-top-menu]').forEach((root) => {
                root.dispatchEvent(new Event('top-menu:position'));
            });
        });
        if (document.readyState === 'loading') { document.addEventListener('DOMContentLoaded', initializeTopMenus); }
        if (window.BX && typeof window.BX.addCustomEvent === 'function') { window.BX.addCustomEvent('onFrameDataReceived', initializeTopMenus); }
    }
    if (document.readyState !== 'loading') { initializeTopMenus(); }
}());
