document.addEventListener('DOMContentLoaded', () => {
    const slides = document.querySelectorAll('#hero-slider .slide');

    slides.forEach(slide => {
        const bg = slide.getAttribute('data-bg');
        slide.style.backgroundImage = `url(${bg})`;
    });

    if (slides.length > 0) {
        let current = 0;
        const interval = 30000;

        setInterval(() => {
            slides[current].classList.remove('active');
            current = (current + 1) % slides.length;
            slides[current].classList.add('active');
        }, interval);
    }
}); 