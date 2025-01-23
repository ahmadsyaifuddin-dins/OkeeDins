function updateWishlistCount(count) {
    const wishlistCountElement = document.querySelector('.wishlist-count');
    if (wishlistCountElement) {
        wishlistCountElement.textContent = count;

        // Show/hide badge based on count
        if (count > 0) {
            wishlistCountElement.classList.remove('d-none');
        } else {
            wishlistCountElement.classList.add('d-none');
        }
    }
}

function confirmAddToWishlist(event, form) {
    event.preventDefault();

    const button = form.querySelector('button');
    const icon = button.querySelector('i');
    const isInWishlist = icon.classList.contains('bi-heart-fill');

    if (!isInWishlist) {
        Swal.fire({
            title: 'Tambahkan ke Wishlist?',
            text: "Produk ini akan ditambahkan ke wishlist anda",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, tambahkan!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Tampilkan status loading
                button.disabled = true;
                button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
                form.submit();
            }
        });
    } else {
        Swal.fire({
            title: 'Hapus dari Wishlist?',
            text: "Produk ini akan dihapus dari wishlist anda",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Tampilkan status loading
                button.disabled = true;
                button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
                form.submit();
            }
        });
    }
}

// Add hover effect to wishlist buttons
document.addEventListener('DOMContentLoaded', function () {
    const wishlistBtns = document.querySelectorAll('.wishlist-btn');
    wishlistBtns.forEach(btn => {
        // Set initial state
        const icon = btn.querySelector('i');
        if (!icon.classList.contains('bi-heart-fill')) {
            btn.style.backgroundColor = '#fff';
            icon.classList.add('text-danger');
        }

        btn.addEventListener('mouseenter', function () {
            const icon = this.querySelector('i');
            if (icon.classList.contains('bi-heart-fill')) {
                // Jika sudah di wishlist, tampilkan efek hover merah muda
                icon.classList.remove('text-danger');
                icon.style.color = '#fff';
            } else {
                // Jika belum di wishlist, ubah background jadi putih dan icon jadi merah
                this.style.backgroundColor = '#f00';
                icon.classList.remove('text-danger');
                icon.classList.add('text-light');
            }
        });

        btn.addEventListener('mouseleave', function () {
            const icon = this.querySelector('i');
            if (icon.classList.contains('bi-heart-fill')) {
                // Kembalikan ke warna merah normal
                icon.classList.add('text-danger');
                icon.style.color = '#fff';
            } else {
                // Kembalikan ke warna default (background merah, icon putih)
                this.style.backgroundColor = '#fff';
                icon.classList.add('text-danger');
                icon.classList.remove('text-light');
            }
        });
    });
});
