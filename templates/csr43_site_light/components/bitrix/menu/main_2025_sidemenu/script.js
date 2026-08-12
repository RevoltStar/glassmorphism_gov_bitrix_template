document.addEventListener('DOMContentLoaded', function() {
    const mobileSelect = document.querySelector('.side-menu-select');
    
    if (mobileSelect) {
        mobileSelect.addEventListener('change', function() {
            if (this.value) {
                window.location.href = this.value;
            }
        });
    }
});