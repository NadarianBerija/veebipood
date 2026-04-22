// Swiper
var swiper = new Swiper(".mySwiper", {
  spaceBetween: 10,
  slidesPerView: 6,
  freeMode: true,
  watchSlidesProgress: true,
});
var swiper2 = new Swiper(".mySwiper2", {
  spaceBetween: 10,
  navigation: {
    nextEl: ".swiper-button-next",
    prevEl: ".swiper-button-prev",
  },
  thumbs: {
    swiper: swiper,
  },
});

// Modal
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('[data-bs-toggle="modal"]').forEach(img => {
    img.addEventListener('click', (e) => {
      document.getElementById('modalImage').src = e.target.src;
    });
  });
});