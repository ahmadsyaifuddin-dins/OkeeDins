   // Ganti script kategoriSwiper di section scripts dengan konfigurasi ini
   document.addEventListener('DOMContentLoaded', function() {
    const kategoriSwiper = new Swiper('.kategoriSwiper', {
        slidesPerView: 'auto',
        spaceBetween: 8,
        freeMode: {
            enabled: true,
            sticky: false,
        },
        grabCursor: true,
        preventClicks: false,
        preventClicksPropagation: false,
        touchReleaseOnEdges: true,
        breakpoints: {
            320: {
                spaceBetween: 8
            },
            768: {
                spaceBetween: 12
            }
        }
    });
});