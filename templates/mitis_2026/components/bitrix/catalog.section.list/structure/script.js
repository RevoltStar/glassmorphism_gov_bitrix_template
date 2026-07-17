window.addEventListener('load', function () {
    // Делегирование событий для динамически раскрываемых элементов
    document.querySelectorAll('.org-structure').forEach(function (container) {
        container.addEventListener('click', function (e) {
            const toggleBtn = e.target.closest('.org-structure__toggle');
            if (!toggleBtn) return;

            const node = toggleBtn.closest('.org-structure__node');
            if (!node) return;

            const childrenList = node.querySelector(':scope > .org-structure__children');
            if (!childrenList) return;

            const icon = toggleBtn.querySelector('i');
            const isVisible = childrenList.style.display !== 'none';

            if (isVisible) {
                childrenList.style.display = 'none';
                if (icon) {
                    icon.className = 'bi bi-plus-circle';
                }
            } else {
                childrenList.style.display = '';
                if (icon) {
                    icon.className = 'bi bi-dash-circle';
                }
            }
        });
    });
});