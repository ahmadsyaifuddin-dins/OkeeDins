document.addEventListener('DOMContentLoaded', function() {
    const voucherInput = document.getElementById('voucher-code');
    const btnApplyVoucher = document.getElementById('btn-apply-voucher');
    const btnPay = document.getElementById('btn-pay');
    const transferRadio = document.getElementById('transfer');
    const codRadio = document.getElementById('cod');
    const transferInfo = document.getElementById('transfer-info');

    // Get selected items from URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    const itemsParam = urlParams.get('items');
    const directBuy = urlParams.get('direct_buy');
    const produkId = urlParams.get('produk_id');
    const quantity = urlParams.get('quantity') || 1;

    console.log('URL Parameters:', window.location.search);
    console.log('Direct buy:', directBuy);
    console.log('Produk ID:', produkId);

    if (!directBuy && !itemsParam) {
        console.error('No items parameter found in URL');
        Swal.fire({
            icon: 'warning',
            title: 'Oops...',
            text: 'No items selected for checkout!'
        }).then(() => {
            window.location.href = '/cart';
        });
        return;
    }

    const selectedItems = directBuy ? [`direct_${produkId}`] : itemsParam.split(',').filter(Boolean);
    console.log('Selected items:', selectedItems);

    if (selectedItems.length === 0) {
        console.error('No valid items found in URL parameter');
        Swal.fire({
            icon: 'warning',
            title: 'Oops...',
            text: 'No items selected for checkout!'
        }).then(() => {
            window.location.href = '/cart';
        });
        return;
    }

    // Toggle transfer info when payment method changes
    transferRadio?.addEventListener('change', function() {
        transferInfo.style.display = this.checked ? 'block' : 'none';
    });

    codRadio?.addEventListener('change', function() {
        transferInfo.style.display = 'none';
    });

    // Handle voucher application
    btnApplyVoucher?.addEventListener('click', async function() {
        const code = voucherInput.value.trim();
        if (!code) {
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: 'Please enter a voucher code!'
            });
            return;
        }

        try {
            const response = await fetch('/vouchers/validate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ code })
            });

            const data = await response.json();
            if (response.ok) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: data.message
                });
                // Update UI with voucher discount
                updateTotalWithVoucher(data.discount);
            } else {
                throw new Error(data.message || 'Failed to apply voucher');
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.message
            });
        }
    });

    // Handle payment
    btnPay?.addEventListener('click', async function() {
        // Validate required fields
        const selectedAddress = document.querySelector('input[name="selected_address"]:checked');
        const selectedPayment = document.querySelector('input[name="payment_method"]:checked');
        const notes = document.getElementById('notes').value;
        const voucherCode = document.getElementById('voucher-code').value;
        const finalTotal = document.getElementById('final-total').textContent.replace(/[^0-9]/g, '');

        if (!selectedAddress) {
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: 'Silakan pilih alamat pengiriman!'
            });
            return;
        }

        if (!selectedPayment) {
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: 'Silakan pilih metode pembayaran!'
            });
            return;
        }

        try {
            const response = await fetch('/checkout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    address_id: selectedAddress.value,
                    payment_method: selectedPayment.value,
                    notes: notes,
                    voucher_code: voucherCode,
                    total_amount: parseInt(finalTotal),
                    selected_items: selectedItems,
                    quantity: directBuy ? parseInt(quantity) : undefined
                })
            });

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || 'Terjadi kesalahan saat memproses pembayaran');
            }

            const data = await response.json();
            
            if (selectedPayment.value === 'transfer') {
                // Redirect ke halaman pembayaran untuk transfer
                window.location.href = `/payment/${data.order_id}`;
            } else {
                // Untuk COD, tampilkan pesan sukses dan redirect ke halaman konfirmasi
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Pesanan Anda berhasil dibuat!',
                    showConfirmButton: false,
                    timer: 2000
                }).then(() => {
                    window.location.href = `/orders/${data.order_id}/confirmation`;
                });
            }

        } catch (error) {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: error.message || 'Terjadi kesalahan saat memproses pembayaran'
            });
        }
    });

    function updateTotalWithVoucher(voucherDiscount) {
        const subtotalElement = document.getElementById('subtotal');
        const productDiscountElement = document.getElementById('product-discount');
        const discountRow = document.getElementById('discount-row');
        const discountAmount = document.getElementById('discount-amount');
        const finalTotal = document.getElementById('final-total');

        const subtotal = parseInt(subtotalElement.textContent.replace(/[^0-9]/g, ''));
        const productDiscount = productDiscountElement ? 
            parseInt(productDiscountElement.textContent.replace(/[^0-9]/g, '')) : 0;

        const voucherDiscountAmount = Math.floor((subtotal - productDiscount) * (voucherDiscount / 100));
        
        discountRow.style.display = 'flex';
        discountAmount.textContent = numberFormat(voucherDiscountAmount);
        
        const total = subtotal - productDiscount - voucherDiscountAmount;
        finalTotal.textContent = numberFormat(total);
    }

    function numberFormat(number) {
        return new Intl.NumberFormat('id-ID', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(number);
    }
});
