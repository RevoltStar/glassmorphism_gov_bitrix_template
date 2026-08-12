document.addEventListener('DOMContentLoaded', function() {
    /*console.log('Мобильное меню: Скрипт загружен');*/
    
    // Элементы мобильного меню
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');
    const mobileMenuSidebar = document.getElementById('mobileMenuSidebar');
    const mobileMenuClose = document.getElementById('mobileMenuClose');
    
    /*console.log('Мобильное меню: Элементы найдены', {
        toggle: !!mobileMenuToggle,
        overlay: !!mobileMenuOverlay,
        sidebar: !!mobileMenuSidebar,
        close: !!mobileMenuClose
    });
    */
    // Функция открытия меню
    function openMobileMenu() {
        /*console.log('Мобильное меню: Открытие');*/
        mobileMenuToggle.classList.add('active');
        mobileMenuOverlay.classList.add('active');
        mobileMenuSidebar.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    
    // Функция закрытия меню
    function closeMobileMenu() {
        /*console.log('Мобильное меню: Закрытие');*/
        mobileMenuToggle.classList.remove('active');
        mobileMenuOverlay.classList.remove('active');
        mobileMenuSidebar.classList.remove('active');
        document.body.style.overflow = '';
        
        // Закрываем все подменю
        document.querySelectorAll('.mobile-menu-parent').forEach(parent => {
            parent.classList.remove('active');
        });
        document.querySelectorAll('.mobile-submenu').forEach(submenu => {
            submenu.style.display = 'none';
        });
    }
    
    // Обработчики для гамбургер-кнопки
    mobileMenuToggle.addEventListener('click', function() {
         /*console.log('Мобильное меню: Клик по гамбургеру');*/
        openMobileMenu();
    });

    mobileMenuOverlay.addEventListener('click', function() {
         /*console.log('Мобильное меню: Клик по оверлею');*/
        closeMobileMenu();
    });

    mobileMenuClose.addEventListener('click', function() {
         /*console.log('Мобильное меню: Клик по кнопке закрытия');*/
        closeMobileMenu();
    });
    
    // Обработчики для стрелок подменю
    document.querySelectorAll('.mobile-menu-arrow').forEach(arrow => {
        arrow.addEventListener('click', function(e) {
            e.stopPropagation();
            const parent = this.closest('.mobile-menu-parent');
            const submenu = parent.querySelector('.mobile-submenu');
            
            /*console.log('Мобильное меню: Клик по стрелке подменю', parent);*/
            
            parent.classList.toggle('active');
            
            if (parent.classList.contains('active')) {
                submenu.style.display = 'block';
                /*console.log('Мобильное меню: Подменю открыто');*/
            } else {
                submenu.style.display = 'none';
                /*console.log('Мобильное меню: Подменю закрыто');*/
                // Закрываем вложенные подменю
                submenu.querySelectorAll('.mobile-submenu').forEach(nested => {
                    nested.style.display = 'none';
                });
                submenu.querySelectorAll('.mobile-menu-parent').forEach(nestedParent => {
                    nestedParent.classList.remove('active');
                });
            }
        });
    });
    
    // Обработчики для ссылок меню (закрываем меню при клике на обычную ссылку)
    document.querySelectorAll('.mobile-menu-link[href]').forEach(link => {
        link.addEventListener('click', function() {
            if (!this.classList.contains('mobile-menu-denied')) {
                /*console.log('Мобильное меню: Клик по ссылке, закрытие меню');*/
                closeMobileMenu();
            }
        });
    });


    // Альтернативная реализация свайпа для лучшей поддержки
    let startX = 0;
    let currentX = 0;
    let isSwiping = false;
    
    document.addEventListener('touchstart', function(e) {
        startX = e.touches[0].clientX;
        currentX = startX;
        isSwiping = true;
        /*console.log('Мобильное меню: Альтернативный touch start - X:', startX);*/
    }, { passive: true });
    
    document.addEventListener('touchmove', function(e) {
        if (!isSwiping) return;
        currentX = e.touches[0].clientX;
        
        // Можно добавить визуальную обратную связь при свайпе
        const diff = currentX - startX;
        /*console.log('Мобильное меню: Альтернативный touch move - diff:', diff);*/
    }, { passive: true });
    
    document.addEventListener('touchend', function(e) {
        if (!isSwiping) return;
        
        const diff = currentX - startX;
        const swipeThreshold = 50;
        
        /*console.log('Мобильное меню: Альтернативный touch end - diff:', diff);*/
        
        // Свайп слева направо (положительный diff) - открытие
        if (diff > swipeThreshold && startX < 200) {
            /*console.log('Мобильное меню: Альтернативный - Свайп слева направо - ОТКРЫТИЕ');*/
            openMobileMenu();
        }
        // Свайп справа налево (отрицательный diff) - закрытие, только если меню открыто
        else if (diff < -swipeThreshold && mobileMenuSidebar.classList.contains('active')) {
            /*console.log('Мобильное меню: Альтернативный - Свайп справа налево - ЗАКРЫТИЕ');*/
            closeMobileMenu();
        }
        
        isSwiping = false;
    }, { passive: true });
    
    // Закрытие меню при нажатии ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            /*console.log('Мобильное меню: Нажата клавиша ESC');*/
            closeMobileMenu();
        }
    });

    /*console.log('Мобильное меню: Все обработчики установлены');*/
});