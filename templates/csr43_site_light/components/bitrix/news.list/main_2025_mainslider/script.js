document.addEventListener('DOMContentLoaded', function() {
    const slider = document.getElementById('mainSlider');
    const carousel = new bootstrap.Carousel(slider);
    
    slider.addEventListener('mouseenter', function() {
        carousel.pause();
    });
    
    slider.addEventListener('mouseleave', function() {
        carousel.cycle();
    });
});