(function () {
    'use strict';

    const selector = '[data-gallery-item]';

    function initFancybox() {
        if (
            typeof window.Fancybox === 'undefined'
            || typeof window.Fancybox.bind !== 'function'
        ) {
            return;
        }

        const items = document.querySelectorAll(selector);
        if (items.length === 0) {
            return;
        }

        if (typeof window.Fancybox.unbind === 'function') {
            window.Fancybox.unbind(selector);
        }
        window.Fancybox.bind(selector, {
            groupAttr: 'data-fancybox',
            Toolbar: {
                display: {
                    left: ['infobar'],
                    middle: ['zoomIn', 'zoomOut', 'toggle1to1'],
                    right: ['slideshow', 'thumbs', 'close'],
                },
            },
            Thumbs: {
                type: 'modern',
            },
            Html: {
                video: {
                    autoplay: false,
                    preload: 'metadata',
                },
            },
            Images: {
                initialSize: 'fit',
                zoom: true,
                Panzoom: {
                    maxScale: 10,
                },
            },
            caption: (fancybox, slide) => {
                const caption = slide.triggerEl?.dataset.galleryCaption || '';

                if (!caption) {
                    return '';
                }

                const captionElement = document.createElement('div');
                const total = fancybox.carousel?.slides.length || 0;
                const position = total ? ` (${slide.index + 1}/${total})` : '';

                captionElement.textContent = `${caption}${position}`;

                return captionElement;
            },
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initFancybox, {once: true});
    } else {
        initFancybox();
    }

    if (window.BX && typeof window.BX.addCustomEvent === 'function') {
        window.BX.addCustomEvent('onFrameDataReceived', initFancybox);
    }
})();
