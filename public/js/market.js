document.addEventListener('DOMContentLoaded', function () {
    new Swiper(".main-swiper", {
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
    });
});




document.addEventListener('DOMContentLoaded', function () {
    // Quantity controls
    const quantityButtons = document.querySelectorAll('.btn-number');

    quantityButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();

            const type = this.getAttribute('data-type');
            const input = this.closest('.product-qty').querySelector('.input-number');
            const currentValue = parseInt(input.value);

            if (type === 'minus') {
                if (currentValue > 1) {
                    input.value = currentValue - 1;
                }
            } else if (type === 'plus') {
                input.value = currentValue + 1;
            }
        });
    });
});