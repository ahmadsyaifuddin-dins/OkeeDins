function updateWishlistCount(count) {
    const wishlistCountElement = document.querySelector('.wishlist-count');
    if (wishlistCountElement) {
        wishlistCountElement.textContent = count;
        
        // Show/hide badge based on count
        if (count > 0) {
            wishlistCountElement.classList.remove('d-none');
        } else {
            wishlistCountElement.classList.add('d-none');
        }
    }
}

function confirmAddToWishlist(event, form) {
    event.preventDefault();
    
    const button = form.querySelector('button');
    const icon = button.querySelector('i');
    const isInWishlist = icon.classList.contains('bi-heart-fill');

    if (!isInWishlist) {
        Swal.fire({
            title: 'Add to Wishlist?',
            text: "This product will be added to your wishlist",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, add it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading state
                button.disabled = true;
                button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
                form.submit();
            }
        });
    } else {
        Swal.fire({
            title: 'Remove from Wishlist?',
            text: "This product will be removed from your wishlist",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, remove it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading state
                button.disabled = true;
                button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
                form.submit();
            }
        });
    }
}

// Add hover effect to wishlist buttons
document.addEventListener('DOMContentLoaded', function() {
    const wishlistBtns = document.querySelectorAll('.wishlist-btn');
    wishlistBtns.forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            const icon = this.querySelector('i');
            if (icon.classList.contains('bi-heart-fill')) {
                // If already in wishlist, show blue heart with red outline
                icon.classList.remove('text-danger');
                icon.style.background = 'linear-gradient(to right, #0d6efd, #0d6efd)';
                icon.style.webkitBackgroundClip = 'text';
                icon.style.backgroundClip = 'text';
                icon.style.color = 'transparent';
                icon.style.textShadow = '0 0 1px #dc3545';
            } else {
                // If not in wishlist, show red heart
                icon.classList.add('text-danger');
            }
        });
        
        btn.addEventListener('mouseleave', function() {
            const icon = this.querySelector('i');
            if (icon.classList.contains('bi-heart-fill')) {
                // Restore filled heart color to red
                icon.classList.add('text-danger');
                icon.style.background = '';
                icon.style.webkitBackgroundClip = '';
                icon.style.backgroundClip = '';
                icon.style.color = '';
                icon.style.textShadow = '';
            } else {
                // Remove hover color if not in wishlist
                icon.classList.remove('text-danger');
            }
        });
    });
});
