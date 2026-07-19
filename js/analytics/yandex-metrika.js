(function (window, document) {
    const counterId = Number(window.YANDEX_METRIKA_ID);

    if (!Number.isSafeInteger(counterId) || counterId <= 0) {
        return;
    }

    const tagUrl = `https://mc.yandex.ru/metrika/tag.js?id=${encodeURIComponent(counterId)}`;

    (function (m, e, t, r, i, k, a) {
        m[i] = m[i] || function () {
            (m[i].a = m[i].a || []).push(arguments);
        };
        m[i].l = 1 * new Date();

        for (let j = 0; j < document.scripts.length; j++) {
            if (document.scripts[j].src === r) {
                return;
            }
        }

        k = e.createElement(t);
        a = e.getElementsByTagName(t)[0];
        k.async = 1;
        k.src = r;
        a.parentNode.insertBefore(k, a);
    })(window, document, 'script', tagUrl, 'ym');

    window.ym(counterId, 'init', {
        ssr: true,
        webvisor: true,
        clickmap: true,
        ecommerce: 'dataLayer',
        accurateTrackBounce: true,
        trackLinks: true
    });
})(window, document);
