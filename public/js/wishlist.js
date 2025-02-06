// Function to update all wishlist count displays
function updateWishlistCount(count) {
    // Find all wishlist icons that might have count indicators
    // Both in top nav and bottom nav
    const wishlistIcons = document.querySelectorAll('.bi-heart.text-xl');

    wishlistIcons.forEach(icon => {
        const parent = icon.parentElement;
        if (!parent) return;

        // Remove existing count badge if it exists
        const existingBadge = parent.querySelector('span.bg-custom.rounded-full');
        if (existingBadge) {
            existingBadge.remove();
        }

        // Add new badge if count > 0
        if (count > 0) {
            const badge = document.createElement('span');
            badge.className = 'absolute -top-1 -right-1 bg-custom text-white text-xs rounded-full w-4 h-4 flex items-center justify-center';
            badge.textContent = count;
            parent.appendChild(badge);
        }
    });
}

document.querySelectorAll('.wishlist-form').forEach(form => {
    form.addEventListener('submit', function (event) {
        event.preventDefault();
        const button = form.querySelector('button');
        const originalContent = button.innerHTML;
        const isAddingToWishlist = !form.querySelector('input[name="_method"]');

        // Disable button and show loading
        button.disabled = true;
        button.innerHTML = '<i class="bi bi-arrow-repeat animate-spin"></i> <span class="text-sm">Proses...</span>';

        fetch(form.action, {
            method: form.method,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: new FormData(form)
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Terjadi kesalahan');
                }
                return response.json();
            })
            .then(data => {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 1500,
                    timerProgressBar: true
                });

                if (data.status === 'added') {
                    Toast.fire({
                        icon: 'success',
                        title: 'Berhasil ditambahkan ke wishlist'
                    });

                    // Update button appearance
                    button.innerHTML = '<i class="bi bi-heart-fill text-custom"></i> <span class="text-sm">Wishlist</span>';

                    // Update form for delete operation
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    form.appendChild(methodInput);

                    // Update form action URL with the new wishlist ID
                    form.action = `/wishlist/${data.wishlist_id}`;

                    // Update all wishlist count indicators
                    updateWishlistCount(data.wishlist_count);

                } else if (data.status === 'removed') {
                    Toast.fire({
                        icon: 'success',
                        title: 'Berhasil dihapus dari wishlist'
                    });

                    // Update button appearance
                    button.innerHTML = '<i class="bi bi-heart"></i> <span class="text-sm">Wishlist</span>';

                    // Reset form for add operation
                    const methodInput = form.querySelector('input[name="_method"]');
                    if (methodInput) {
                        methodInput.remove();
                    }
                    form.action = '/wishlist';

                    // Update all wishlist count indicators
                    updateWishlistCount(data.wishlist_count);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: error.message,
                    confirmButtonColor: '#EF4444'
                });
            })
            .finally(() => {
                button.disabled = false;
            });
    });
});