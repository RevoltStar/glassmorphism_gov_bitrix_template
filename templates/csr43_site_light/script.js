document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchButton = document.getElementById('searchButton');
    
    // Обработчик клика по кнопке
    searchButton.addEventListener('click', function() {
        performSearch();
    });
    
    // Обработчик нажатия Enter в поле ввода
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            performSearch();
        }
    });
    
    function performSearch() {
        const searchTerm = searchInput.value.trim();
        
        if (searchTerm) {
            // Кодируем поисковый запрос для URL
            const encodedQuery = encodeURIComponent(searchTerm);
            // Перенаправляем на страницу поиска
            window.location.href = `/search?q=${encodedQuery}`;
        } else {
            // Если поле пустое, можно показать сообщение или просто ничего не делать
            alert('Введите поисковый запрос');
            searchInput.focus();
        }
    }
});
document.addEventListener('DOMContentLoaded', function() {
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
