(function () {
    'use strict';

    const faceSequence = [
        'rotateX(0deg) rotateY(0deg)',
        'rotateX(0deg) rotateY(-90deg)',
        'rotateX(0deg) rotateY(-180deg)',
        'rotateX(-90deg) rotateY(0deg)',
        'rotateX(0deg) rotateY(90deg)',
        'rotateX(90deg) rotateY(0deg)'
    ];

    function initializePopularServices() {
        const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        document.querySelectorAll('[data-popular-service-cube]').forEach((cube) => {
            if (cube.dataset.popularServiceInitialized === 'true') {
                return;
            }

            const service = cube.closest('[data-popular-service]');
            if (!service) {
                return;
            }

            cube.dataset.popularServiceInitialized = 'true';
            let currentStep = 0;
            let intervalId = null;
            let pointerPaused = false;
            let focusPaused = false;

            const stop = () => {
                if (intervalId !== null) {
                    window.clearInterval(intervalId);
                    intervalId = null;
                }
            };

            const nextFace = () => {
                cube.style.transform = faceSequence[currentStep];
                currentStep = (currentStep + 1) % faceSequence.length;
            };

            const start = () => {
                if (reduceMotion || document.hidden || pointerPaused || focusPaused || intervalId !== null) {
                    return;
                }

                nextFace();
                intervalId = window.setInterval(nextFace, 2500);
            };

            service.addEventListener('mouseenter', () => {
                pointerPaused = true;
                stop();
            });
            service.addEventListener('mouseleave', () => {
                pointerPaused = false;
                start();
            });
            service.addEventListener('focusin', () => {
                focusPaused = true;
                stop();
            });
            service.addEventListener('focusout', (event) => {
                if (!service.contains(event.relatedTarget)) {
                    focusPaused = false;
                    start();
                }
            });
            document.addEventListener('visibilitychange', () => {
                if (document.hidden) {
                    stop();
                } else {
                    start();
                }
            });

            start();
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializePopularServices);
    } else {
        initializePopularServices();
    }

    if (window.BX && typeof window.BX.addCustomEvent === 'function') {
        window.BX.addCustomEvent('onFrameDataReceived', initializePopularServices);
    }
}());
