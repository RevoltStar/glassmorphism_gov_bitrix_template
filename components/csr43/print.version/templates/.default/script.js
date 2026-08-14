(function () {
    'use strict';

    const stateKey = 'csr43PrintVersionState';
    const state = window[stateKey] || {
        eventsBound: false,
        printRequested: false,
    };
    window[stateKey] = state;

    function waitForDocumentLoad() {
        if (document.readyState === 'complete') {
            return Promise.resolve();
        }

        return new Promise(function (resolve) {
            window.addEventListener('load', resolve, { once: true });
        });
    }

    function waitForImage(image) {
        image.loading = 'eager';

        if (image.complete) {
            if (typeof image.decode === 'function') {
                return image.decode().catch(function () {});
            }

            return Promise.resolve();
        }

        return new Promise(function (resolve) {
            image.addEventListener('load', resolve, { once: true });
            image.addEventListener('error', resolve, { once: true });
        }).then(function () {
            if (typeof image.decode === 'function') {
                return image.decode().catch(function () {});
            }

            return undefined;
        });
    }

    function waitForLayout() {
        return new Promise(function (resolve) {
            window.requestAnimationFrame(function () {
                window.requestAnimationFrame(resolve);
            });
        });
    }

    function waitForFonts() {
        if (document.fonts && document.fonts.ready) {
            return document.fonts.ready.catch(function () {});
        }

        return Promise.resolve();
    }

    function initPrintVersion() {
        const control = document.querySelector('[data-print-version][data-print-mode="Y"]');
        if (!control || state.printRequested) {
            return;
        }

        state.printRequested = true;
        waitForDocumentLoad()
            .then(function () {
                const content = document.getElementById('main-content') || document.body;
                const images = Array.from(content.querySelectorAll('img'));

                return Promise.all([
                    Promise.all(images.map(waitForImage)),
                    waitForFonts(),
                ]);
            })
            .then(waitForLayout)
            .then(function () {
                window.print();
            });
    }

    function bindEvents() {
        if (state.eventsBound) {
            return;
        }

        state.eventsBound = true;
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initPrintVersion, { once: true });
        } else {
            initPrintVersion();
        }

        if (window.BX && typeof window.BX.addCustomEvent === 'function') {
            window.BX.addCustomEvent('onFrameDataReceived', initPrintVersion);
        }
    }

    bindEvents();
    initPrintVersion();
}());
