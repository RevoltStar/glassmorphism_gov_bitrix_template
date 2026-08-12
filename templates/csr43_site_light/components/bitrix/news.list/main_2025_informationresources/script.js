// Функция для закрытия меню
function closeFullMenu() {
    const fullMenu = document.getElementById('full-menu');
    const gwb = document.getElementById('government_website_button');
    
    if (!fullMenu.classList.contains('d-none')) {
        fullMenu.classList.add('d-none');
        gwb.classList.remove('active');
        document.body.style.overflow = '';
    }
}

// Обработчик для кнопки открытия меню
document.getElementById('government_website_button').addEventListener('click', function(event) {
    if (event.button === 0) {
        const fullMenu = document.getElementById('full-menu');
        fullMenu.classList.toggle('d-none');
        this.classList.toggle('active');
        
        if (fullMenu.classList.contains('d-none')) {
            document.body.style.overflow = '';
        } else {
            document.body.style.overflow = 'hidden';
        }
        
        // Останавливаем всплытие события, чтобы оно не достигло document
        event.stopPropagation();
    }
});

// Обработчик для кнопки закрытия меню
document.getElementById('modal-menu-btn').addEventListener('click', function(event) {
    if (event.button === 0) {
        closeFullMenu();
        event.stopPropagation();
    }
});

// Обработчик для клика по документу (вне меню)
document.addEventListener('click', function(event) {
    const fullMenu = document.getElementById('full-menu');
    const gwb = document.getElementById('government_website_button');
    const container = fullMenu.querySelector('.container');
    
    // Проверяем, был ли клик вне контейнера меню и вне кнопки
    if (!fullMenu.classList.contains('d-none') && 
        !container.contains(event.target) && 
        !gwb.contains(event.target)) {
        closeFullMenu();
    }
});

// Обработчик для клавиши Escape
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeFullMenu();
    }
});