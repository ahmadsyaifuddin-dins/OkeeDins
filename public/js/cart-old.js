document.addEventListener('DOMContentLoaded', function () {
    const selectAllCheckbox = document.getElementById('selectAll');
    const itemCheckboxes = document.querySelectorAll('.item-checkbox');
    const btnCheckout = document.getElementById('btn-checkout');

    // Fungsi validasi quantity
    function validateQuantity(input) {
        const maxStock = parseInt(input.dataset.stock);
        let value = parseInt(input.value);
        
        if (isNaN(value) || value < 1) {
            value = 1;
        } else if (value > maxStock) {
            value = maxStock;
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian',
                text: 'Jumlah melebihi stok yang tersedia!',
                confirmButtonColor: '#EF4444'
            });
        }
        
        input.value = value;
        return value;
    }

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

    // Calculate and update summary (unchanged)
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

        btnCheckout.disabled = selectedCount === 0;
        btnCheckout.textContent = `Checkout (${selectedCount})`;
    }

    // Helper function for number formatting (unchanged)
    function numberFormat(number) {
        return new Intl.NumberFormat('id-ID', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(number);
    }

    // Improved event listeners for quantity buttons
    document.querySelectorAll('.quantity-btn').forEach(btn => {
        btn.addEventListener('click', async function () {
            const cartItem = this.closest('.cart-item');
            const input = cartItem.querySelector('.quantity-input');
            const currentValue = parseInt(input.value);
            const maxStock = parseInt(input.dataset.stock);
            const action = this.dataset.action;
            const oldValue = currentValue;

            // Validasi perubahan step-by-step
            if (action === 'increase') {
                if (currentValue < maxStock) {
                    input.value = currentValue + 1;
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian',
                        text: 'Jumlah melebihi stok yang tersedia!',
                        confirmButtonColor: '#EF4444'
                    });
                    return;
                }
            } else if (action === 'decrease' && currentValue > 1) {
                input.value = currentValue - 1;
            }

            try {
                const validatedValue = validateQuantity(input);
                updateItemPrice(cartItem);
                calculateAndUpdateSummary();
                await updateQuantity(cartItem.dataset.id, validatedValue);
            } catch (error) {
                input.value = oldValue;
                updateItemPrice(cartItem);
                calculateAndUpdateSummary();
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message
                });
            }
        });
    });

    // Improved event listeners for manual quantity input
    document.querySelectorAll('.quantity-input').forEach(input => {
        // Handle direct input
        input.addEventListener('input', function() {
            const cartItem = this.closest('.cart-item');
            validateQuantity(this);
            updateItemPrice(cartItem);
        });

        // Handle complete change with AJAX update
        input.addEventListener('change', async function () {
            const cartItem = this.closest('.cart-item');
            const oldValue = this.defaultValue;
            const validatedValue = validateQuantity(this);

            try {
                updateItemPrice(cartItem);
                calculateAndUpdateSummary();
                await updateQuantity(cartItem.dataset.id, validatedValue);
                this.defaultValue = validatedValue;
            } catch (error) {
                this.value = oldValue;
                updateItemPrice(cartItem);
                calculateAndUpdateSummary();
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message
                });
            }
        });

        // Handle paste event
        input.addEventListener('paste', function(e) {
            setTimeout(() => {
                const cartItem = this.closest('.cart-item');
                validateQuantity(this);
                updateItemPrice(cartItem);
            }, 0);
        });
    });

    // Rest of the code remains unchanged...
    // Checkbox handlers
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function () {
            itemCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            calculateAndUpdateSummary();
        });
    }

    itemCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', calculateAndUpdateSummary);
    });

    // Checkout handler
    btnCheckout.addEventListener('click', function (e) {
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

        window.location.href = `/checkout?items=${selectedItems.join(',')}`;
    });

    // Initial calculations
    document.querySelectorAll('.cart-item').forEach(updateItemPrice);
    calculateAndUpdateSummary();

    // Delete functionality
    document.querySelectorAll('.delete-item').forEach(button => {
        button.addEventListener('click', async function () {
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