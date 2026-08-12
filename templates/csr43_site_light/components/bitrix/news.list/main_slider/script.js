(function () {
    'use strict';

    const getCarousel = (slider) => {
        const Carousel = window.bootstrap && window.bootstrap.Carousel;
        if (!Carousel) {
            return null;
        }

        if (typeof Carousel.getOrCreateInstance === 'function') {
            return Carousel.getOrCreateInstance(slider, { interval: 5000, ride: false, pause: false });
        }

        const existing = typeof Carousel.getInstance === 'function' ? Carousel.getInstance(slider) : null;
        return existing || new Carousel(slider, { interval: 5000, ride: false, pause: false });
    };

    const shouldCycle = (slider) => !window.matchMedia('(prefers-reduced-motion: reduce)').matches
        && !document.hidden
        && slider.dataset.mainSliderPointerPaused !== 'true'
        && slider.dataset.mainSliderFocusPaused !== 'true';

    const updateCycle = (slider) => {
        const carousel = getCarousel(slider);
        if (!carousel) {
            return;
        }

        if (shouldCycle(slider)) {
            carousel.cycle();
        } else {
            carousel.pause();
        }
    };

    function initializeMainSliders() {
        document.querySelectorAll('[data-main-slider]').forEach((slider) => {
            if (slider.dataset.mainSliderInitialized === 'true' || !getCarousel(slider)) {
                return;
            }

            slider.dataset.mainSliderInitialized = 'true';
            slider.addEventListener('mouseenter', () => {
                slider.dataset.mainSliderPointerPaused = 'true';
                updateCycle(slider);
            });
            slider.addEventListener('mouseleave', () => {
                slider.dataset.mainSliderPointerPaused = 'false';
                updateCycle(slider);
            });
            slider.addEventListener('focusin', () => {
                slider.dataset.mainSliderFocusPaused = 'true';
                updateCycle(slider);
            });
            slider.addEventListener('focusout', (event) => {
                if (!slider.contains(event.relatedTarget)) {
                    slider.dataset.mainSliderFocusPaused = 'false';
                    updateCycle(slider);
                }
            });
            updateCycle(slider);
        });
    }

    const documentRoot = document.documentElement;
    if (documentRoot.dataset.mainSliderGlobalInitialized !== 'true') {
        documentRoot.dataset.mainSliderGlobalInitialized = 'true';
        document.addEventListener('visibilitychange', () => {
            document.querySelectorAll('[data-main-slider]').forEach(updateCycle);
        });

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initializeMainSliders);
        }
        window.addEventListener('load', initializeMainSliders, { once: true });

        if (window.BX && typeof window.BX.addCustomEvent === 'function') {
            window.BX.addCustomEvent('onFrameDataReceived', initializeMainSliders);
        }
    }

    if (document.readyState !== 'loading') {
        initializeMainSliders();
    }
}());
