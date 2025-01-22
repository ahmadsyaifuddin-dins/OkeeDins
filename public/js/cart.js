document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const itemCheckboxes = document.querySelectorAll('.item-checkbox');
    const btnCheckout = document.getElementById('btn-checkout');

    // AJAX function for updating quantity
    async function updateQuantity(cartItemId, quantity) {
        try {
            const response = await fetch(`/cart/${cartItemId}/quantity`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ quantity })
            });
            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'Gagal mengupdate quantity');
            }
            return data;
        } catch (error) {
            throw new Error(error.message || 'Gagal mengupdate quantity');
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
        btn.addEventListener('click', async function() {
            const cartItem = this.closest('.cart-item');
            const input = cartItem.querySelector('.quantity-input');
            const currentValue = parseInt(input.value);
            const action = this.dataset.action;
            const oldValue = currentValue;

            if (action === 'increase') {
                input.value = currentValue + 1;
            } else if (action === 'decrease' && currentValue > 1) {
                input.value = currentValue - 1;
            }

            try {
                updateItemPrice(cartItem);
                calculateAndUpdateSummary();
                await updateQuantity(cartItem.dataset.id, input.value);
            } catch (error) {
                input.value = oldValue;
                updateItemPrice(cartItem);
                calculateAndUpdateSummary();
                console.error('Error:', error);
                alert(error.message);
            }
        });
    });

    // Event listeners for manual quantity input
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', async function() {
            const cartItem = this.closest('.cart-item');
            const oldValue = this.defaultValue;
            const newValue = Math.max(1, parseInt(this.value) || 1);
            this.value = newValue;

            try {
                updateItemPrice(cartItem);
                calculateAndUpdateSummary();
                await updateQuantity(cartItem.dataset.id, newValue);
                this.defaultValue = newValue;
            } catch (error) {
                this.value = oldValue;
                updateItemPrice(cartItem);
                calculateAndUpdateSummary();
                console.error('Error:', error);
                alert(error.message);
            }
        });
    });

    // Event listeners for checkboxes
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            itemCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            calculateAndUpdateSummary();
        });
    }

    itemCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', calculateAndUpdateSummary);
    });

    // Checkout button click handler
    btnCheckout.addEventListener('click', function(e) {
        e.preventDefault();
        
        const selectedItems = [];
        document.querySelectorAll('.item-checkbox:checked').forEach(checkbox => {
            const cartItem = checkbox.closest('.cart-item');
            selectedItems.push(cartItem.dataset.id);
        });

        if (selectedItems.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: 'Pilih minimal satu produk untuk checkout!'
            });
            return;
        }

        // Redirect to checkout page with selected items
        window.location.href = `/checkout?items=${selectedItems.join(',')}`;
    });

    // Initial calculation
    document.querySelectorAll('.cart-item').forEach(updateItemPrice);
    calculateAndUpdateSummary();

    // Delete item functionality
    document.querySelectorAll('.delete-item').forEach(button => {
        button.addEventListener('click', async function() {
            const cartItemId = this.dataset.id;
            const cartItem = this.closest('.cart-item');

            const result = await Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah Anda yakin ingin menghapus item ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Tidak, batalkan!',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6'
            });

            if (result.isConfirmed) {
                try {
                    const response = await fetch(`/cart/${cartItemId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    });

                    if (!response.ok) {
                        throw new Error('Gagal menghapus item');
                    }

                    cartItem.remove();
                    calculateAndUpdateSummary();
                } catch (error) {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: error.message || 'Terjadi kesalahan!'
                    });
                }
            }
        });
    });
});
