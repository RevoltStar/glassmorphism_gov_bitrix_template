document.addEventListener('DOMContentLoaded', function() {
    if (typeof Fancybox === 'undefined' || typeof Fancybox.bind !== 'function') {
        return;
    }

    Fancybox.bind('[data-gallery-item]', {
        groupAttr: 'data-fancybox',
        Toolbar: {
            display: {
                left: ["infobar"],
                middle: ["zoomIn", "zoomOut", "toggle1to1"],
                right: ["slideshow", "thumbs", "close"],
            },
        },
        Thumbs: {
            type: "modern",
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
});
