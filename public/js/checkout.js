document.addEventListener('DOMContentLoaded', function () {
    const transferInfo = document.getElementById('transfer-info');
    const checkoutForm = document.getElementById('checkout-form');
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
    const addressInput = document.getElementById('address_id_input');

    // Set initial address value if one is checked
    const initialAddress = document.querySelector('input[name="selected_address"]:checked');
    if (initialAddress && addressInput) {
        addressInput.value = initialAddress.value;
    }

    // Listen for address changes
    const addressRadios = document.querySelectorAll('input[name="selected_address"]');
    addressRadios.forEach(radio => {
        radio.addEventListener('change', function () {
            if (this.checked && addressInput) {
                addressInput.value = this.value;
            }
        });
    });

    // Handle payment method changes
    paymentMethods.forEach(method => {
        method.addEventListener('change', function () {
            if (this.id === 'transfer' && this.checked && transferInfo) {
                transferInfo.style.display = 'block';
            } else if (transferInfo) {
                transferInfo.style.display = 'none';
            }
        });
    });

    // Handle form submission
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function (e) {
            e.preventDefault();

            // Validate address selection
            const selectedAddress = document.querySelector('input[name="selected_address"]:checked');
            const selectedPayment = document.querySelector('input[name="payment_method"]:checked');

            // Show errors if validation fails
            if (!selectedAddress || !selectedPayment) {
                let errorMessage = '';

                if (!selectedAddress) {
                    errorMessage = 'Silakan pilih alamat pengiriman terlebih dahulu';
                }

                if (!selectedPayment) {
                    errorMessage = errorMessage ? errorMessage + ' dan pilih metode pembayaran' : 'Silakan pilih metode pembayaran terlebih dahulu';
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: errorMessage
                });
                return;
            }

            // Update hidden address_id input before submission
            if (addressInput) {
                addressInput.value = selectedAddress.value;
            }

            // Collect form data
            const formData = new FormData(this);

            // Submit form via AJAX
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => response.json())
                .then(data => {
                    console.log('Server Response:', data);

                    if (data.success) {
                        // Show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message,
                            showConfirmButton: true,
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                if (data.payment_method === 'transfer' && data.order_id) {
                                    window.location.href = `/payment/${data.order_id}`;
                                } else if (data.order_id) {
                                    window.location.href = `/orders/${data.order_id}`;
                                }
                            }
                        });
                    } else {
                        // Show error message using the alert data from server
                        if (data.alert) {
                            Swal.fire(data.alert);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: data.message || 'Terjadi kesalahan saat memproses checkout.',
                                footer: '<a href="#">Hubungi dukungan</a>'
                            });
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan saat menghubungi server.',
                        footer: '<a href="#">Hubungi dukungan</a>'
                    });
                });
        });
    }

    // Voucher handling
    const applyVoucherBtn = document.getElementById('apply_voucher');
    const voucherInput = document.getElementById('voucher_code');
    const voucherMessage = document.getElementById('voucher_message');
    const discountRow = document.getElementById('discount_row');
    const discountAmount = document.getElementById('discount_amount');
    const totalAmount = document.getElementById('total_amount');
    const totalAmountInput = document.getElementById('total_amount_input');
    const subtotalElement = document.getElementById('subtotal');
    const shippingCostElement = document.getElementById('shipping_cost');

    // Hidden inputs
    const appliedVoucherId = document.getElementById('applied_voucher_id');
    const appliedVoucherCode = document.getElementById('applied_voucher_code');
    const appliedDiscountAmount = document.getElementById('applied_discount_amount');

    if (applyVoucherBtn && voucherInput) {
        applyVoucherBtn.addEventListener('click', function () {
            const code = voucherInput.value.trim();
            if (!code) {
                showVoucherMessage('Masukkan kode voucher terlebih dahulu', 'text-danger');
                return;
            }

            // Get the current subtotal
            const subtotal = parseInt(subtotalElement.textContent.replace(/[^0-9]/g, ''));
            const shippingCost = parseInt(shippingCostElement.textContent.replace(/[^0-9]/g, ''));

            // Call API to validate and apply voucher
            fetch('/vouchers/validate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                credentials: 'include',
                body: JSON.stringify({
                    code: code,
                    subtotal: subtotal
                })
            })
                .then(response => {
                    if (!response.ok) {
                        if (response.status === 401) {
                            throw new Error('Silakan login terlebih dahulu');
                        }
                        return response.json().then(data => {
                            throw new Error(data.message || 'Terjadi kesalahan');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        showVoucherMessage(data.message, 'text-success');

                        // Update UI with discount
                        const discount = data.discount;
                        discountRow.style.display = 'flex';
                        discountAmount.textContent = `-Rp ${formatNumber(discount)}`;

                        // Update total
                        const newTotal = subtotal + shippingCost - discount;
                        totalAmount.textContent = `Rp ${formatNumber(newTotal)}`;
                        totalAmountInput.value = newTotal;

                        // Store voucher info
                        appliedVoucherId.value = data.voucher.id;
                        appliedVoucherCode.value = code;
                        appliedDiscountAmount.value = discount;

                        // Disable input and button
                        voucherInput.disabled = true;
                        applyVoucherBtn.disabled = true;

                        // Change button to remove voucher
                        applyVoucherBtn.textContent = 'Hapus';
                        applyVoucherBtn.classList.remove('btn-outline-primary');
                        applyVoucherBtn.classList.add('btn-outline-danger');
                        applyVoucherBtn.onclick = removeVoucher;
                    } else {
                        showVoucherMessage(data.message, 'text-danger');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showVoucherMessage(error.message || 'Terjadi kesalahan saat memvalidasi voucher', 'text-danger');
                });
        });
    }

    function removeVoucher() {
        voucherInput.value = '';
        voucherInput.disabled = false;
        showVoucherMessage('', '');
        discountRow.style.display = 'none';
        discountAmount.textContent = '-Rp 0';

        // Reset hidden inputs
        appliedVoucherId.value = '';
        appliedVoucherCode.value = '';
        appliedDiscountAmount.value = '0';

        // Reset button
        applyVoucherBtn.textContent = 'Terapkan';
        applyVoucherBtn.disabled = false;
        applyVoucherBtn.classList.remove('btn-outline-danger');
        applyVoucherBtn.classList.add('btn-outline-primary');
        applyVoucherBtn.onclick = null;

        // Update total
        const subtotal = parseInt(subtotalElement.textContent.replace(/[^0-9]/g, ''));
        const shippingCost = parseInt(shippingCostElement.textContent.replace(/[^0-9]/g, ''));
        const newTotal = subtotal + shippingCost;
        totalAmount.textContent = `Rp ${formatNumber(newTotal)}`;
        totalAmountInput.value = newTotal;
    }

    function showVoucherMessage(message, className) {
        voucherMessage.textContent = message;
        voucherMessage.className = 'small mt-2 ' + className;
    }

    function formatNumber(number) {
        return new Intl.NumberFormat('id-ID').format(number);
    }
});
