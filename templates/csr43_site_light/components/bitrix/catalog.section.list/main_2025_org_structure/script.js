document.addEventListener('DOMContentLoaded', function() {
    // Обработчики для кнопок "Ещё"
    document.querySelectorAll('.show-more-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            const sectionId = this.getAttribute('data-section');
            const hiddenContainer = document.getElementById('hidden-employees-' + sectionId);
            const isHidden = this.getAttribute('data-state') === 'hidden';
            
            if (hiddenContainer) {
                if (isHidden) {
                    // Показываем скрытых сотрудников
                    hiddenContainer.style.display = 'contents';
                    this.setAttribute('data-state', 'visible');
                    this.querySelector('.btn-text').textContent = 'Скрыть';
                    this.querySelector('i').className = 'bi bi-chevron-up ms-1';
                } else {
                    // Скрываем сотрудников
                    hiddenContainer.style.display = 'none';
                    this.setAttribute('data-state', 'hidden');
                    this.querySelector('.btn-text').textContent = 'Ещё ' + hiddenContainer.children.length + ' сотрудников';
                    this.querySelector('i').className = 'bi bi-chevron-down ms-1';
                }
            }
        });
    });
});