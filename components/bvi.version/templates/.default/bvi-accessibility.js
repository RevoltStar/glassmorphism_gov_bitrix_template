(function () {
    'use strict';

    var labels = [
        ['.bvi-fontSize-minus', 'Уменьшить размер шрифта'],
        ['.bvi-fontSize-plus', 'Увеличить размер шрифта'],
        ['.bvi-theme-white', 'Включить белую тему'],
        ['.bvi-theme-black', 'Включить черную тему'],
        ['.bvi-theme-blue', 'Включить синюю тему'],
        ['.bvi-theme-brown', 'Включить коричневую тему'],
        ['.bvi-theme-green', 'Включить зеленую тему'],
        ['.bvi-images-on', 'Показывать изображения'],
        ['.bvi-images-off', 'Скрыть изображения'],
        ['.bvi-images-grayscale', 'Показывать изображения в оттенках серого'],
        ['.bvi-speech-off', 'Выключить синтез речи'],
        ['.bvi-speech-on', 'Включить синтез речи'],
        ['[data-bvi="modal"]', 'Открыть настройки версии для слабовидящих'],
        ['[data-bvi="close"]', 'Вернуться к обычной версии сайта'],
        ['[data-bvi="panel-hide"]', 'Свернуть панель версии для слабовидящих'],
        ['[data-bvi="panel-show"]', 'Показать панель версии для слабовидящих'],
        ['[data-bvi="modal-close"]', 'Закрыть настройки версии для слабовидящих'],
        ['.bvi-letter-spacing-normal', 'Обычный интервал между буквами'],
        ['.bvi-letter-spacing-average', 'Средний интервал между буквами'],
        ['.bvi-letter-spacing-big', 'Большой интервал между буквами'],
        ['.bvi-line-height-normal', 'Обычный междустрочный интервал'],
        ['.bvi-line-height-average', 'Средний междустрочный интервал'],
        ['.bvi-line-height-big', 'Большой междустрочный интервал'],
        ['.bvi-font-family-arial', 'Шрифт Arial'],
        ['.bvi-font-family-times', 'Шрифт Times New Roman'],
        ['.bvi-built-elements-on', 'Включить встроенные элементы'],
        ['.bvi-built-elements-off', 'Выключить встроенные элементы'],
        ['.bvi-reset', 'Сбросить настройки версии для слабовидящих']
    ];

    var toggleGroups = [
        '.bvi-theme-white, .bvi-theme-black, .bvi-theme-blue, .bvi-theme-brown, .bvi-theme-green',
        '.bvi-images-on, .bvi-images-off, .bvi-images-grayscale',
        '.bvi-speech-off, .bvi-speech-on',
        '.bvi-letter-spacing-normal, .bvi-letter-spacing-average, .bvi-letter-spacing-big',
        '.bvi-line-height-normal, .bvi-line-height-average, .bvi-line-height-big',
        '.bvi-font-family-arial, .bvi-font-family-times',
        '.bvi-built-elements-on, .bvi-built-elements-off'
    ];

    function setControlName(element, label) {
        if (!element || element.getAttribute('aria-label')) {
            return;
        }

        element.setAttribute('aria-label', label);
    }

    function makeActionLikeButton(element) {
        if (!element) {
            return;
        }

        element.setAttribute('role', 'button');

        if (element.tagName === 'A' && !element.hasAttribute('href')) {
            element.setAttribute('href', '#');
        }
    }

    function updatePressedStates(root) {
        toggleGroups.forEach(function (selector) {
            root.querySelectorAll(selector).forEach(function (element) {
                element.setAttribute('aria-pressed', element.classList.contains('active') ? 'true' : 'false');
            });
        });
    }

    function updateModalState(panel) {
        var modal = panel.querySelector('.bvi-modal');
        var title = panel.querySelector('.bvi-modal-title');

        if (!modal) {
            return;
        }

        if (title && !title.id) {
            title.id = 'bvi-settings-title';
        }

        modal.setAttribute('role', 'dialog');
        modal.setAttribute('aria-modal', 'true');
        modal.setAttribute('aria-hidden', modal.classList.contains('show') ? 'false' : 'true');

        if (title) {
            modal.setAttribute('aria-labelledby', title.id);
        }
    }

    function focusFirstModalControl(panel) {
        var modal = panel.querySelector('.bvi-modal.show');
        var firstControl = modal ? modal.querySelector('a[href], button, [tabindex]:not([tabindex="-1"])') : null;

        if (firstControl) {
            firstControl.focus();
        }
    }

    function trapModalFocus(event, panel) {
        var modal = panel.querySelector('.bvi-modal.show');

        if (!modal || event.key !== 'Tab') {
            return;
        }

        var controls = Array.prototype.slice.call(
            modal.querySelectorAll('a[href], button, [tabindex]:not([tabindex="-1"])')
        ).filter(function (element) {
            return !element.classList.contains('disabled');
        });

        if (controls.length === 0) {
            return;
        }

        var first = controls[0];
        var last = controls[controls.length - 1];

        if (event.shiftKey && document.activeElement === first) {
            event.preventDefault();
            last.focus();
        } else if (!event.shiftKey && document.activeElement === last) {
            event.preventDefault();
            first.focus();
        }
    }

    function closeModal(panel) {
        var close = panel.querySelector('[data-bvi="modal-close"]');

        if (close) {
            close.click();
        }
    }

    function enhancePanel(panel) {
        if (!panel) {
            return;
        }

        panel.setAttribute('role', 'region');
        panel.setAttribute('aria-label', 'Панель версии для слабовидящих');

        labels.forEach(function (item) {
            panel.querySelectorAll(item[0]).forEach(function (element) {
                makeActionLikeButton(element);
                setControlName(element, item[1]);
            });
        });

        var fixedToggle = document.querySelector('.bvi-link-fixed-top');
        if (fixedToggle) {
            makeActionLikeButton(fixedToggle);
            setControlName(fixedToggle, 'Показать панель версии для слабовидящих');
        }

        updatePressedStates(panel);
        updateModalState(panel);

        if (!panel.dataset.a11yEnhanced) {
            panel.dataset.a11yEnhanced = 'true';

            panel.addEventListener('keydown', function (event) {
                var action = event.target.closest('.bvi-link');

                if (event.key === 'Escape') {
                    closeModal(panel);
                    return;
                }

                trapModalFocus(event, panel);

                if (!action || event.key !== ' ') {
                    return;
                }

                event.preventDefault();
                action.click();
            });

            panel.addEventListener('click', function (event) {
                window.setTimeout(function () {
                    updatePressedStates(panel);
                    updateModalState(panel);

                    if (event.target.closest('[data-bvi="modal"]')) {
                        focusFirstModalControl(panel);
                    }
                }, 0);
            });

            var modal = panel.querySelector('.bvi-modal');
            if (modal) {
                new MutationObserver(function () {
                    updateModalState(panel);
                }).observe(modal, {
                    attributes: true,
                    attributeFilter: ['class']
                });
            }
        }
    }

    function enhance() {
        var panel = document.querySelector('.bvi-panel');

        if (panel) {
            enhancePanel(panel);
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        enhance();

        new MutationObserver(enhance).observe(document.body, {
            childList: true,
            subtree: true
        });
    });
})();
