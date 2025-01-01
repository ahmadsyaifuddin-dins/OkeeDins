// Toast configuration
const Toast = Swal.mixin({
    toast: true,
    position: 'top-start',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
    }
});

// Show toast alert if success or error messages exist
function showToast() {
    const successMessage = document.querySelector('meta[name="success-message"]')?.content;
    const errorMessage = document.querySelector('meta[name="error-message"]')?.content;

    if (successMessage) {
        return Toast.fire({
            icon: 'success',
            title: successMessage
        });
    }

    if (errorMessage) {
        return Toast.fire({
            icon: 'error',
            title: errorMessage
        });
    }
}

// Initialize Swiper
function initSwiper() {
    new Swiper(".main-swiper", {
        loop: true,
        autoplay: {
            delay: 3000,
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
}

// Initialize components
document.addEventListener('DOMContentLoaded', async () => {
    await showToast();
    initSwiper();
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