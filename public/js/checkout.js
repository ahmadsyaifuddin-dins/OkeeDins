document.addEventListener('DOMContentLoaded', function () {
    const transferInfo = document.getElementById('transfer-info');
    const checkoutForm = document.getElementById('checkout-form');
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
    const addressInput = document.getElementById('address_id_input');
    const addressRadios = document.querySelectorAll('input[name="selected_address"]');

    // Handle address selection
    addressRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            addressInput.value = this.value;
        });
    });

    // Set initial address if one is checked
    const checkedAddress = document.querySelector('input[name="selected_address"]:checked');
    if (checkedAddress) {
        addressInput.value = checkedAddress.value;
    }

    // Handle payment method selection
    paymentMethods.forEach(method => {
        method.addEventListener('change', function() {
            if (this.value === 'transfer') {
                transferInfo.style.display = 'block';
            } else {
                transferInfo.style.display = 'none';
            }
        });
    });

    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function (e) {
            e.preventDefault();

            // Validate address
            if (!addressInput.value) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Silakan pilih alamat pengiriman',
                });
                return;
            }

            // Validate payment method
            const selectedPayment = document.querySelector('input[name="payment_method"]:checked');
            if (!selectedPayment) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Silakan pilih metode pembayaran',
                });
                return;
            }

            const btnPay = document.getElementById('btn-pay');
            if (btnPay) {
                btnPay.disabled = true;
                btnPay.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...';
            }

            // Collect form data
            const formData = new FormData(this);

            // Submit form via AJAX
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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
                    // Show error message
                    Swal.fire(data.alert);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Terjadi kesalahan saat memproses checkout',
                });
            })
            .finally(() => {
                if (btnPay) {
                    btnPay.disabled = false;
                    btnPay.innerHTML = 'Bayar Sekarang';
                }
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

            // Get the current subtotal dan total discount produk
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
                    const voucherDiscount = data.discount;
                    discountRow.style.display = 'flex';
                    discountAmount.textContent = `-Rp ${formatNumber(voucherDiscount)}`;

                    // Hitung total baru (subtotal - voucher + ongkir)
                    const newTotal = subtotal - voucherDiscount + shippingCost;
                    totalAmount.textContent = `Rp ${formatNumber(newTotal)}`;
                    totalAmountInput.value = newTotal;

                    // Store voucher info
                    appliedVoucherId.value = data.voucher.id;
                    appliedVoucherCode.value = code;
                    appliedDiscountAmount.value = voucherDiscount;

                    // Disable input and button
                    voucherInput.disabled = true;
                    applyVoucherBtn.disabled = true;

                    // Change button to remove voucher
                    applyVoucherBtn.textContent = 'Hapus';
                    applyVoucherBtn.classList.remove('btn-outline-custom');
                    applyVoucherBtn.classList.add('btn-outline-danger');
                    applyVoucherBtn.onclick = removeVoucher;
                } else {
                    showVoucherMessage(data.message, 'text-danger');
                }
            })
            .catch(error => {
                showVoucherMessage(error.message, 'text-danger');
            });
        });
    }

    // Function to remove voucher
    function removeVoucher() {
        // Get the current values
        const subtotal = parseInt(subtotalElement.textContent.replace(/[^0-9]/g, ''));
        const shippingCost = parseInt(shippingCostElement.textContent.replace(/[^0-9]/g, ''));

        // Reset voucher UI
        discountRow.style.display = 'none';
        discountAmount.textContent = '-Rp 0';
        voucherInput.value = '';
        voucherInput.disabled = false;

        // Reset voucher data
        appliedVoucherId.value = '';
        appliedVoucherCode.value = '';
        appliedDiscountAmount.value = '0';

        // Update total (subtotal + ongkir)
        const newTotal = subtotal + shippingCost;
        totalAmount.textContent = `Rp ${formatNumber(newTotal)}`;
        totalAmountInput.value = newTotal;

        // Reset button
        applyVoucherBtn.textContent = 'Terapkan';
        applyVoucherBtn.classList.remove('btn-outline-danger');
        applyVoucherBtn.classList.add('btn-outline-custom');
        applyVoucherBtn.disabled = false;
        applyVoucherBtn.onclick = null;

        // Clear message
        showVoucherMessage('', '');
    }

    function showVoucherMessage(message, className) {
        voucherMessage.textContent = message;
        voucherMessage.className = 'small mt-2 ' + className;
    }

    function formatNumber(number) {
        return new Intl.NumberFormat('id-ID').format(number);
    }
});
