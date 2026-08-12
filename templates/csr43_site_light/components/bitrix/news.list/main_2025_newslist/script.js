document.addEventListener('DOMContentLoaded', function() {
	/*
    const expandButtons = document.querySelectorAll('.expand-button-wrapper');
    const fullscreenWrapper = document.getElementById('news-fullscreen-img-wrapper');
    const fullscreenImg = document.getElementById('news-fullscreen-img');
    
    // Обработчик для кнопки расширения
    expandButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Находим изображение в том же контейнере новости
            const newsContainer = this.closest('.news-image-container');
            const newsImage = newsContainer.querySelector('.news-image');
            const newsLink = newsContainer.querySelector('a').getAttribute('href');
            
            // Устанавливаем src и alt для полноэкранного изображения
            fullscreenImg.src = newsImage.src;
            fullscreenImg.alt = newsImage.alt;
            
            // Обновляем кнопку "К новости" - ИСПРАВЛЕНО: правильный селектор
            const newsButton = fullscreenWrapper.querySelector('.btn-to-news');
            newsButton.onclick = function() {
                window.location.href = newsLink;
            };
            
            // Показываем полноэкранный блок
            fullscreenWrapper.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        });
    });
    
    // Функция для скрытия полноэкранного изображения
    window.hideFullscreenImage = function() {
        fullscreenWrapper.style.display = 'none';
        document.body.style.overflow = '';
    };
    
    // Закрытие по клику на фон (вне изображения)
    fullscreenWrapper.addEventListener('click', function(e) {
        // ИСПРАВЛЕНО: закрываем только при клике на саму обертку, а не на внутренний контент
        if (e.target === fullscreenWrapper) {
            hideFullscreenImage();
        }
    });
    
    // Закрытие по клавише ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && fullscreenWrapper.style.display === 'flex') {
            hideFullscreenImage();
        }
    });
	*/
});