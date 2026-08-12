document.addEventListener('DOMContentLoaded', function() {
    const cubes = document.querySelectorAll('.cube-3d');
    
    const faceSequence = [
        { transform: "rotateX(0deg) rotateY(0deg)" },
        { transform: "rotateX(0deg) rotateY(-90deg)" },
        { transform: "rotateX(0deg) rotateY(-180deg)" },
        { transform: "rotateX(-90deg) rotateY(0deg)" },
        { transform: "rotateX(0deg) rotateY(90deg)" },
        { transform: "rotateX(90deg) rotateY(0deg)" }
    ];
    
    cubes.forEach((cube) => {
    let currentStep = 0;
    let animationInterval = null;
    let isAnimating = false;

    function startAnimation() {
        if (isAnimating) return;
        
        isAnimating = true;
        // Сначала выполним один шаг немедленно
        nextFace();
        // Затем установим интервал
        animationInterval = setInterval(nextFace, 2500);
    }

    function stopAnimation() {
        isAnimating = false;
        if (animationInterval) {
            clearInterval(animationInterval);
            animationInterval = null;
        }
    }

    function nextFace() {
        const step = faceSequence[currentStep];
        cube.style.transform = step.transform;
        currentStep = (currentStep + 1) % faceSequence.length;
    }
    
    // Автоматическое вращение
    startAnimation();
    
    // Останавливаем анимацию при hover
    cube.addEventListener('mouseenter', stopAnimation);
    cube.addEventListener('mouseleave', startAnimation);
});
});