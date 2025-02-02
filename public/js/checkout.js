document.addEventListener('DOMContentLoaded', function () {
    // Inisialisasi elemen-elemen
    const transferInfo = document.getElementById('transfer-info');
    const checkoutForm = document.getElementById('checkout-form');
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
    const addressInput = document.getElementById('address_id_input');
    const addressRadios = document.querySelectorAll('input[name="selected_address"]');

    // Voucher elements
    const applyVoucherBtn = document.getElementById('apply_voucher');
    const voucherInput = document.getElementById('voucher_code');
    const voucherDiscount = document.getElementById('voucher_discount');
    const finalTotal = document.getElementById('final_total');
    const totalAmountInput = document.getElementById('total_amount_input');

    // Inisialisasi elemen voucher message
    const voucherMessage = document.createElement('div');
    voucherMessage.id = 'voucher_message';
    voucherMessage.className = 'flex items-center mt-2 text-sm';

    // Tambahkan elemen voucher message setelah div flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2
    if (voucherInput) {
        const voucherInputContainer = voucherInput.closest('.flex.flex-col.sm\\:flex-row.space-y-2.sm\\:space-y-0.sm\\:space-x-2');
        voucherInputContainer.parentNode.insertBefore(voucherMessage, voucherInputContainer.nextSibling);
    }

    // Handle address selection
    addressRadios.forEach(radio => {
        radio.addEventListener('change', function () {
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
        method.addEventListener('change', function () {
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


            // Tambahkan voucher_code ke FormData
        const voucherInput = document.getElementById('voucher_code');
        if (voucherInput && !voucherInput.disabled) {
            // Jika input voucher aktif (tidak ada voucher yang digunakan), kirim string kosong
            formData.set('voucher_code', '');
        } else if (voucherInput && voucherInput.disabled) {
            // Jika input voucher disabled (voucher sedang digunakan), kirim nilai vouchernya
            formData.set('voucher_code', voucherInput.value);
        }
        
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
                                    window.location.href = `/orders/confirmation/${data.order_id}`;
                                }
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: data.message
                        });
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

    if (applyVoucherBtn && voucherInput) {
        applyVoucherBtn.addEventListener('click', function () {
            // If button is in reset state, handle reset immediately
            if (applyVoucherBtn.classList.contains('btn-outline-danger')) {
                resetVoucherState();
                return;
            }

            const code = voucherInput.value.trim();
            if (!code) {
                showVoucherMessage('Masukkan kode voucher terlebih dahulu', 'text-danger');
                return;
            }

            // Pastikan kode dalam huruf kapital
            if (code !== code.toUpperCase()) {
                showVoucherMessage('Kode voucher harus dalam huruf kapital', 'text-danger');
                return;
            }

            // Disable button saat proses
            applyVoucherBtn.disabled = true;
            applyVoucherBtn.textContent = 'Memproses...';

            // Get the current total from finalTotal element
            const currentTotal = parseInt(finalTotal.textContent.replace(/[^0-9]/g, ''));

            // Call API to validate voucher
            fetch('/checkout/validate-voucher', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    code: code,
                    subtotal: currentTotal
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
                    if (!data.success) {
                        throw new Error(data.message);
                    }

                    // Reset button state and change to "Reset"
                    applyVoucherBtn.textContent = 'Reset';
                    applyVoucherBtn.disabled = false;
                    applyVoucherBtn.classList.remove('btn-outline-custom');
                    applyVoucherBtn.classList.add('btn-outline-danger');

                    // Disable the voucher input
                    voucherInput.disabled = true;

                    // Store original total in a data attribute
                    finalTotal.dataset.originalTotal = currentTotal;

                    // Update voucher discount display
                    voucherDiscount.textContent = '-Rp' + Math.floor(data.discount).toLocaleString('id-ID');
                    voucherDiscount.dataset.discountAmount = data.discount;

                    // Update final total
                    const newTotal = currentTotal - data.discount;
                    finalTotal.textContent = 'Rp' + Math.floor(newTotal).toLocaleString('id-ID');
                    totalAmountInput.value = Math.floor(newTotal);

                    // Tampilkan pesan sukses
                    showVoucherMessage(data.message, 'text-success');
                })
                .catch(error => {
                    // Reset button state
                    applyVoucherBtn.disabled = false;
                    applyVoucherBtn.textContent = 'Terapkan';

                    // Tampilkan pesan error
                    showVoucherMessage(error.message, 'text-danger');
                });
        });
    }

    function resetVoucherState() {
        // Reset voucher input
        voucherInput.value = '';
        voucherInput.disabled = false;

        // Reset voucher discount
        voucherDiscount.textContent = '-Rp0';

        // Reset total to original amount
        const originalTotal = parseInt(finalTotal.dataset.originalTotal);
        finalTotal.textContent = 'Rp' + Math.floor(originalTotal).toLocaleString('id-ID');
        totalAmountInput.value = Math.floor(originalTotal);

        // Reset button
        applyVoucherBtn.textContent = 'Terapkan';
        applyVoucherBtn.classList.remove('btn-outline-danger');
        applyVoucherBtn.classList.add('btn-outline-custom');

        // Clear message
        showVoucherMessage('', '');
    }

    function showVoucherMessage(message, className) {
        voucherMessage.innerHTML = '';

        // Buat icon element
        const icon = document.createElement('i');
        if (className.includes('text-danger')) {
            icon.className = 'fas fa-exclamation-circle text-red-500 mr-2';
            voucherMessage.className = 'flex items-center mt-2 text-sm text-red-500';
        } else if (className.includes('text-success')) {
            icon.className = 'fas fa-check-circle text-green-500 mr-2';
            voucherMessage.className = 'flex items-center mt-2 text-sm text-green-500';
        }

        // Buat text element
        const text = document.createElement('span');
        text.textContent = message;

        // Tambahkan icon dan text ke message container
        voucherMessage.appendChild(icon);
        voucherMessage.appendChild(text);
    }

    function formatNumber(number) {
        return new Intl.NumberFormat('id-ID').format(number);
    }

    // Fungsi untuk memperbarui bagian alamat di checkout
    // async function refreshCheckoutAddress() {
    //     try {
    //         const response = await fetch('/addresses/checkout-list', {
    //             headers: {
    //                 'Accept': 'application/json',
    //                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    //             }
    //         });

    //         const data = await response.json();
    //         if (!response.ok) {
    //             throw new Error(data.message || 'Gagal memuat daftar alamat');
    //         }

    //         // Update container dengan HTML baru
    //         const addressContainer = document.querySelector('.checkout-address-container');
    //         if (addressContainer && data.html) {
    //             addressContainer.innerHTML = data.html;

    //             // Pasang kembali event listeners untuk radio buttons
    //             const radioButtons = addressContainer.querySelectorAll('input[type="radio"]');
    //             radioButtons.forEach(radio => {
    //                 radio.addEventListener('change', function () {
    //                     document.getElementById('address_id_input').value = this.value;
    //                 });
    //             });
    //         }
    //     } catch (error) {
    //         console.error('Error refreshing checkout address:', error);
    //         Toast.fire({
    //             icon: 'error',
    //             title: 'Gagal memperbarui daftar alamat'
    //         });
    //     }
    // }
});
