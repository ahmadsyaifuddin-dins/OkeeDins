function updateWishlistCount(count) {
    const wishlistIcons = document.querySelectorAll('.bi-heart.text-xl');

    wishlistIcons.forEach(icon => {
        const parent = icon.parentElement;
        if (!parent) return;

        const existingBadge = parent.querySelector('span.bg-custom.rounded-full');
        if (existingBadge) {
            existingBadge.remove();
        }

        if (count > 0) {
            const badge = document.createElement('span');
            badge.className = 'absolute -top-1 -right-1 bg-custom text-white text-xs rounded-full w-4 h-4 flex items-center justify-center';
            badge.textContent = count;
            parent.appendChild(badge);
        }
    });
}

document.querySelectorAll('.wishlist-form').forEach(form => {
    // Store the initial state of the form
    const initialFormState = {
        action: form.action,
        produkId: form.querySelector('input[name="produk_id"]')?.value,
        isWishlisted: form.querySelector('input[name="_method"]')?.value === 'DELETE'
    };

    form.addEventListener('submit', async function (event) {
        event.preventDefault();
        const button = form.querySelector('button');
        const originalContent = button.innerHTML;
        const isAddingToWishlist = !form.querySelector('input[name="_method"]');

        // Disable button and show loading
        button.disabled = true;
        button.innerHTML = '<i class="bi bi-arrow-repeat animate-spin"></i> <span class="text-sm">Proses...</span>';

        try {
            // Verify CSRF token exists
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            if (!csrfToken) {
                throw new Error('CSRF token tidak ditemukan');
            }

            const formData = new FormData(form);

            // Handle adding to wishlist
            if (isAddingToWishlist) {
                // Reset form to initial state for adding
                form.action = '/wishlist';

                // Ensure product ID is present
                if (!formData.get('produk_id')) {
                    const produkIdInput = document.createElement('input');
                    produkIdInput.type = 'hidden';
                    produkIdInput.name = 'produk_id';
                    produkIdInput.value = initialFormState.produkId;
                    form.appendChild(produkIdInput);
                    formData.append('produk_id', initialFormState.produkId);
                }
            }

            const response = await fetch(form.action, {
                method: isAddingToWishlist ? 'POST' : 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: formData
            });

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || 'Terjadi kesalahan pada server');
            }

            const data = await response.json();

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
                form.action = `/wishlist/${data.wishlist_id}`;

                // Remove any existing method input first
                const existingMethod = form.querySelector('input[name="_method"]');
                if (existingMethod) existingMethod.remove();

                // Add DELETE method
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);

                updateWishlistCount(data.wishlist_count);

            } else if (data.status === 'removed') {
                Toast.fire({
                    icon: 'success',
                    title: 'Berhasil dihapus dari wishlist'
                });

                // Update button appearance
                button.innerHTML = '<i class="bi bi-heart"></i> <span class="text-sm">Wishlist</span>';

                // Reset form for adding
                form.action = '/wishlist';

                // Clear all hidden inputs
                const hiddenInputs = form.querySelectorAll('input[type="hidden"]');
                hiddenInputs.forEach(input => {
                    if (input.name !== '_token') {
                        input.remove();
                    }
                });

                // Re-add the product ID input
                const produkIdInput = document.createElement('input');
                produkIdInput.type = 'hidden';
                produkIdInput.name = 'produk_id';
                produkIdInput.value = initialFormState.produkId;
                form.appendChild(produkIdInput);

                updateWishlistCount(data.wishlist_count);
            }
        } catch (error) {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: error.message || 'Terjadi kesalahan tidak terduga',
                confirmButtonColor: '#EF4444'
            });

            // Reset button to original state
            button.innerHTML = originalContent;
        } finally {
            button.disabled = false;
        }
    });
});