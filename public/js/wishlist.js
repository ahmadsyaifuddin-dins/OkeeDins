// Fungsi untuk wishlist
document.querySelectorAll('.wishlist-form').forEach(form => {
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        const button = form.querySelector('button');
        const originalContent = button.innerHTML;
        const isAddingToWishlist = !form.querySelector('input[name="_method"]'); // Jika tidak ada _method=DELETE, berarti sedang menambahkan

        // Disable button dan tampilkan loading
        button.disabled = true;
        button.innerHTML = '<i class="bi bi-arrow-repeat animate-spin"></i> <span class="text-sm">Proses...</span>';

        // Submit form menggunakan fetch
        fetch(form.action, {
                method: form.method,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: new FormData(form)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Terjadi kesalahan');
                }
                // Tampilkan toast notification
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 1500,
                    timerProgressBar: true
                });

                Toast.fire({
                    icon: 'success',
                    title: isAddingToWishlist ? 'Berhasil ditambahkan ke wishlist' : 'Berhasil dihapus dari wishlist'
                });

                // Reload halaman setelah notifikasi selesai
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
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
                // Reset button state
                button.disabled = false;
                button.innerHTML = originalContent;
            });
    });
});