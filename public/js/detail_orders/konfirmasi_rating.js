// Fungsi untuk modal konfirmasi pesanan
function initializeRating(containerClass) {
    // Get all rating containers with the specified class
    const ratingContainers = document.querySelectorAll(`.${containerClass}`);
    
    // If no containers found, exit early
    if (!ratingContainers || ratingContainers.length === 0) {
        console.log(`No rating containers found for class: ${containerClass}`);
        return;
    }
    
    ratingContainers.forEach(ratingContainer => {
        const stars = ratingContainer.querySelectorAll('.star-label');
        const inputs = ratingContainer.querySelectorAll('input[type="radio"]');
        
        // If no stars or inputs found, skip this container
        if (!stars.length || !inputs.length) {
            console.log('Missing stars or inputs in container');
            return;
        }
        
        let currentRating = 0;

        function updateStars(rating) {
            stars.forEach((star, index) => {
                if (index < rating) {
                    star.classList.remove('text-gray-300');
                    star.classList.add('text-yellow-400');
                } else {
                    star.classList.remove('text-yellow-400');
                    star.classList.add('text-gray-300');
                }
            });
        }

        stars.forEach((star, index) => {
            star.addEventListener('mouseenter', () => {
                updateStars(index + 1);
            });

            star.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                currentRating = index + 1;
                inputs[index].checked = true;
                updateStars(currentRating);
            });
        });

        ratingContainer.addEventListener('mouseleave', () => {
            updateStars(currentRating);
        });
    });
}

// Tunggu sampai DOM sepenuhnya dimuat
document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi rating hanya jika modal konfirmasi terbuka
    function setupRatingOnModalOpen() {
        const transferBtn = document.querySelector('[onclick*="openConfirmationModal(\'Transfer\')"]');
        const codBtn = document.querySelector('[onclick*="openConfirmationModal(\'COD\')"]');

        if (transferBtn) {
            transferBtn.addEventListener('click', () => {
                // Berikan sedikit waktu untuk modal muncul
                setTimeout(() => {
                    initializeRating('rating-stars-transfer');
                }, 100);
            });
        }

        if (codBtn) {
            codBtn.addEventListener('click', () => {
                // Berikan sedikit waktu untuk modal muncul
                setTimeout(() => {
                    initializeRating('rating-stars-cod');
                }, 100);
            });
        }
    }

    setupRatingOnModalOpen();
});

// Tambahkan fungsi untuk modal
function openConfirmationModal(type) {
    const modal = document.getElementById(`confirmationModal${type}`);
    if (modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // Initialize rating setelah modal terbuka
        setTimeout(() => {
            initializeRating(`rating-stars-${type.toLowerCase()}`);
        }, 100);
    }
}

function closeConfirmationModal(type) {
    const modal = document.getElementById(`confirmationModal${type}`);
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }
}

// Close modals on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeConfirmationModal('Transfer');
        closeConfirmationModal('COD');
    }
});