document.addEventListener('DOMContentLoaded', function() {
    // Добавляем классы для таблиц в контенте
    document.querySelectorAll('.news-content table').forEach(table => {
        table.classList.add('table', 'table-striped', 'table-bordered');
    });
});