(function () {
    'use strict';

    function initializeOrgStructure(root) {
        if (!(root instanceof Element) || root.dataset.orgStructureInitialized === 'true') {
            return;
        }

        root.dataset.orgStructureInitialized = 'true';
        root.querySelectorAll('[data-org-employees-toggle]').forEach(function (button) {
            button.addEventListener('click', function () {
                var targetId = button.dataset.orgTarget || '';
                var target = targetId !== '' ? document.getElementById(targetId) : null;
                var label = button.querySelector('[data-org-toggle-label]');
                var icon = button.querySelector('.org-structure__more-icon');

                if (!target || !root.contains(target)) {
                    return;
                }

                var hiddenEmployees = target.querySelectorAll('[data-org-hidden-employee]');
                if (hiddenEmployees.length === 0) {
                    return;
                }

                var expanded = button.getAttribute('aria-expanded') === 'true';
                hiddenEmployees.forEach(function (employee) {
                    employee.hidden = expanded;
                });
                button.setAttribute('aria-expanded', expanded ? 'false' : 'true');

                if (label) {
                    label.textContent = expanded
                        ? (button.dataset.collapsedLabel || '')
                        : (button.dataset.expandedLabel || '');
                }

                if (icon) {
                    icon.classList.toggle('bi-chevron-down', expanded);
                    icon.classList.toggle('bi-chevron-up', !expanded);
                }
            });
        });
    }

    function initializeAll(context) {
        if (!(context instanceof Document || context instanceof Element)) {
            return;
        }

        if (context instanceof Element && context.matches('[data-org-structure]')) {
            initializeOrgStructure(context);
        }
        context.querySelectorAll('[data-org-structure]').forEach(initializeOrgStructure);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            initializeAll(document);
        });
    } else {
        initializeAll(document);
    }

    if (window.BX && typeof window.BX.addCustomEvent === 'function') {
        window.BX.addCustomEvent('onFrameDataReceived', function () {
            initializeAll(document);
        });
    }
}());
