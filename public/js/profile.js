// Fungsi untuk mengupload foto profil
function uploadProfilePhoto(input) {
    if (input.files && input.files[0]) {
        // Validasi ukuran file
        const fileSize = input.files[0].size / 1024 / 1024; // dalam MB
        const maxSize = 2; // 2MB

        if (fileSize > maxSize) {
            Swal.fire({
                icon: 'error',
                title: 'Ukuran File Terlalu Besar',
                text: `Maksimal ukuran file adalah ${maxSize}MB`,
                showConfirmButton: true,
                confirmButtonColor: '#EF4444'
            });
            input.value = '';
            return;
        }

        // Validasi tipe file
        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml', 'image/webp'];
        if (!allowedTypes.includes(input.files[0].type)) {
            Swal.fire({
                icon: 'error',
                title: 'Tipe File Tidak Didukung',
                text: 'Hanya mendukung format: JPG, JPEG, PNG, SVG, WEBP, dan GIF',
                showConfirmButton: true,
                confirmButtonColor: '#EF4444'
            });
            input.value = '';
            return;
        }

        // Tampilkan loading state
        Swal.fire({
            title: 'Mengupload foto...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Kirim form menggunakan fetch API
        const form = document.getElementById('profilePhotoForm');
        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Terjadi kesalahan saat mengupload foto');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Update foto profil di halaman
                const profileImages = document.querySelectorAll('img[alt="Profile Photo"]');
                profileImages.forEach(img => {
                    img.src = data.photoUrl;
                });

                // Update foto di navbar jika ada
                updateNavbarProfilePhoto(data.photoUrl);

                // Tampilkan notifikasi sukses
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: data.message || 'Foto profil berhasil diperbarui',
                    showConfirmButton: false,
                    timer: 1500
                });
            } else {
                throw new Error(data.message || 'Terjadi kesalahan saat mengupload foto');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: error.message || 'Terjadi kesalahan saat mengupload foto',
                showConfirmButton: true,
                confirmButtonColor: '#EF4444'
            });
        });
    }
}

// Fungsi untuk mengupdate foto profil di navbar
function updateNavbarProfilePhoto(photoUrl) {
    // Update foto di navbar (termasuk dropdown)
    const navbarPhotos = document.querySelectorAll('.navbar img[alt="' + document.querySelector('img[alt="Profile Photo"]').alt + '"]');
    navbarPhotos.forEach(photo => {
        photo.src = photoUrl;
    });

    // Update foto di dropdown mobile jika ada
    const mobileDropdownPhotos = document.querySelectorAll('.mobile-menu img[alt="' + document.querySelector('img[alt="Profile Photo"]').alt + '"]');
    mobileDropdownPhotos.forEach(photo => {
        photo.src = photoUrl;
    });

    // Update foto di sidebar jika ada
    const sidebarPhotos = document.querySelectorAll('.sidebar img[alt="' + document.querySelector('img[alt="Profile Photo"]').alt + '"]');
    sidebarPhotos.forEach(photo => {
        photo.src = photoUrl;
    });
}

// Event listener untuk form profil
document.addEventListener('DOMContentLoaded', function() {
    const profileForm = document.querySelector('form[action*="profile/update"]');
    if (profileForm) {
        profileForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitButton = this.querySelector('button[type="submit"]');
            const originalButtonText = submitButton.innerHTML;
            
            // Disable button dan tampilkan loading
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="bi bi-arrow-repeat animate-spin"></i> Menyimpan...';

            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message || 'Profil berhasil diperbarui',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    throw new Error(data.message || 'Terjadi kesalahan saat memperbarui profil');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: error.message || 'Terjadi kesalahan saat memperbarui profil',
                    showConfirmButton: true,
                    confirmButtonColor: '#EF4444'
                });
            })
            .finally(() => {
                // Reset button state
                submitButton.disabled = false;
                submitButton.innerHTML = originalButtonText;
            });
        });
    }
});
