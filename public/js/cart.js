document.addEventListener('DOMContentLoaded', function () {
    const selectAllCheckbox = document.getElementById('selectAll');
    const itemCheckboxes = document.querySelectorAll('.item-checkbox');
    const btnCheckout = document.getElementById('btn-checkout');

    // Setup CSRF token for all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // AJAX function for updating quantity
    async function updateQuantity(cartItemId, quantity) {
        try {
            const response = await fetch(`/cart/${cartItemId}/quantity`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                body: JSON.stringify({ quantity })
            });
            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'Gagal mengupdate quantity');
            }
            return data;
        } catch (error) {
            Swal.fire({
                title: 'Error!',
                text: error.message,
                icon: 'error',
                confirmButtonColor: '#EF4444'
            });
        }
    }

    // Update price display for individual items
    function updateItemPrice(cartItem) {
        const quantityInput = cartItem.querySelector('.quantity-input');
        const quantity = parseInt(quantityInput.value);
        const checkbox = cartItem.querySelector('.item-checkbox');
        const basePrice = parseFloat(checkbox.dataset.price);
        const priceDisplay = cartItem.querySelector('.text-danger');
        const totalItemPrice = basePrice * quantity;
        priceDisplay.textContent = `Rp${numberFormat(totalItemPrice)}`;
    }

    // Calculate and update summary
    function calculateAndUpdateSummary() {
        let totalPrice = 0;
        let totalDiscount = 0;
        let selectedCount = 0;

        document.querySelectorAll('.item-checkbox:checked').forEach(checkbox => {
            const cartItem = checkbox.closest('.cart-item');
            const quantity = parseInt(cartItem.querySelector('.quantity-input').value);
            const price = parseFloat(checkbox.dataset.price);
            const discount = parseFloat(checkbox.dataset.discount) || 0;

            selectedCount += quantity;
            const itemTotalPrice = price * quantity;
            totalPrice += itemTotalPrice;
            totalDiscount += (itemTotalPrice * discount) / 100;
        });

        // Update displays
        document.querySelector('#total-harga').innerHTML = `
            <span>Total Harga (${selectedCount} barang)</span>
            <span>Rp${numberFormat(totalPrice)}</span>
        `;

        document.querySelector('#total-diskon').innerHTML = `
            <span>Total Diskon</span>
            <span class="text-danger">-Rp${numberFormat(totalDiscount)}</span>
        `;

        const grandTotal = totalPrice - totalDiscount;
        document.querySelector('#total-belanja').innerHTML = `
            <strong>Total Belanja</strong>
            <strong class="text-danger">Rp${numberFormat(grandTotal)}</strong>
        `;

        // Update checkout button
        btnCheckout.disabled = selectedCount === 0;
        btnCheckout.textContent = `Checkout (${selectedCount})`;
    }

    // Helper function for number formatting
    function numberFormat(number) {
        return new Intl.NumberFormat('id-ID', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(number);
    }

    // Event listeners for quantity buttons
    document.querySelectorAll('.quantity-btn').forEach(btn => {
        btn.addEventListener('click', async function () {
            const cartItem = this.closest('.cart-item');
            const input = cartItem.querySelector('.quantity-input');
            const currentValue = parseInt(input.value);
            const action = this.dataset.action;
            const cartItemId = cartItem.id.replace('cart-item-', '');
            const oldValue = currentValue;

            if (action === 'increase') {
                input.value = currentValue + 1;
            } else if (action === 'decrease' && currentValue > 1) {
                input.value = currentValue - 1;
            }

            const newValue = parseInt(input.value);
            if (newValue !== oldValue) {
                try {
                    await updateQuantity(cartItemId, newValue);
                    updateItemPrice(cartItem);
                    calculateAndUpdateSummary();
                } catch (error) {
                    input.value = oldValue;
                }
            }
        });
    });

    // Event listener for select all checkbox
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function () {
            itemCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            calculateAndUpdateSummary();
        });
    }

    // Event listeners for item checkboxes
    itemCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            if (selectAllCheckbox) {
                selectAllCheckbox.checked = Array.from(itemCheckboxes).every(cb => cb.checked);
            }
            calculateAndUpdateSummary();
        });
    });

    // Event listener for delete buttons
    document.querySelectorAll('.delete-item').forEach(button => {
        button.addEventListener('click', async function () {
            const cartItemId = this.dataset.id;
            const cartItem = this.closest('.cart-item');

            Swal.fire({
                title: 'Hapus Produk?',
                text: 'Produk akan dihapus dari keranjang',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#6B7280'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const response = await fetch(`/cart/${cartItemId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                'Accept': 'application/json'
                            }
                        });

                        if (!response.ok) {
                            throw new Error('Gagal menghapus item');
                        }

                        cartItem.remove();
                        calculateAndUpdateSummary();

                        // Update cart badge
                        const cartBadges = document.querySelectorAll('.cart-badge');
                        cartBadges.forEach(badge => {
                            const currentCount = parseInt(badge.textContent);
                            badge.textContent = currentCount - 1;
                            if (currentCount - 1 <= 0) {
                                badge.classList.add('hidden');
                            }
                        });

                        // Reload if cart is empty
                        if (document.querySelectorAll('.cart-item').length === 0) {
                            location.reload();
                        }

                    } catch (error) {
                        Swal.fire({
                            title: 'Error!',
                            text: error.message,
                            icon: 'error',
                            confirmButtonColor: '#EF4444'
                        });
                    }
                }
            });
        });
    });

    // Event listener for checkout button
    if (btnCheckout) {
        btnCheckout.addEventListener('click', function (e) {
            e.preventDefault();

            // Get selected items
            const selectedItems = [];
            document.querySelectorAll('.item-checkbox:checked').forEach(checkbox => {
                selectedItems.push(checkbox.value);
            });

            if (selectedItems.length === 0) {
                Swal.fire({
                    title: 'Peringatan!',
                    text: 'Pilih minimal satu produk untuk checkout',
                    icon: 'warning',
                    confirmButtonColor: '#EF4444'
                });
                return;
            }

            // Log untuk debugging
            console.log('Selected items:', selectedItems);

            // Convert array to comma-separated string
            const itemIdsString = selectedItems.join(',');
            console.log('Items string:', itemIdsString);

            // Redirect to checkout page with item IDs as query parameter
            window.location.href = `/checkout?items=${itemIdsString}`;
        });
    }

    // Initial calculation
    calculateAndUpdateSummary();
});
