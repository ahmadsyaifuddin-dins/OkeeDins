// Script untuk mengambil alamat tersimpan
document.addEventListener('DOMContentLoaded', function () {
    const addressSelector = document.getElementById('addressSelector');
    const alamatTextarea = document.getElementById('alamatTextarea');

    if (addressSelector) {
        // Set initial value based on primary address
        const primaryOption = Array.from(addressSelector.options)
            .find(option => option.text.includes('(Utama)'));
        if (primaryOption) {
            addressSelector.value = primaryOption.value;
            alamatTextarea.value = primaryOption.dataset.address;
            alamatTextarea.setAttribute('readonly', true);
        }

        addressSelector.addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];

            if (this.value === '') {
                alamatTextarea.value = '';
                alamatTextarea.removeAttribute('readonly');
            } else {
                alamatTextarea.value = selectedOption.dataset.address;
                alamatTextarea.setAttribute('readonly', true);
            }
        });
    }
});

// Script untuk mengupload foto profil realtime utk navbar nya
function updateNavbarProfilePhoto(photoUrl) {
    // Update foto di dropdown navbar desktop
    const desktopNavbarPhoto = document.querySelector('.navbar .dropdown img[alt="Foto Profil"]');
    if (desktopNavbarPhoto) {
        desktopNavbarPhoto.src = photoUrl;
    }

    // Update foto di dropdown navbar mobile
    const mobileNavbarPhoto = document.querySelector('.navbar .dropdown-menu img[alt="Foto Profil"]');
    if (mobileNavbarPhoto) {
        mobileNavbarPhoto.src = photoUrl;
    }
}

// Script untuk mengupload foto profil
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
                timer: 3000,
                showConfirmButton: false
            });
            // Reset input file
            input.value = '';
            return;
        }

        // Validasi tipe file
        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml'];
        if (!allowedTypes.includes(input.files[0].type)) {
            Swal.fire({
                icon: 'error',
                title: 'Tipe File Tidak Didukung',
                text: 'Hanya mendukung JPG, JPEG, PNG, SVG, dan GIF',
                timer: 3000,
                showConfirmButton: false
            });
            // Reset input file
            input.value = '';
            return;
        }

        // Kirim form secara asynchronous menggunakan AJAX
        const form = input.closest('form');
        const formData = new FormData(form);

        // Tambahkan field yang diperlukan secara manual
        formData.append('name', document.querySelector('input[name="name"]').value);
        formData.append('email', document.querySelector('input[name="email"]').value);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update foto profil di halaman profil
                    const profileImage = document.querySelector('img[alt="Profile Photo"]');
                    if (profileImage) {
                        profileImage.src = data.photoUrl;
                    }

                    // Update foto di navbar
                    updateNavbarProfilePhoto(data.photoUrl);

                    Swal.mixin({
                        toast: true,
                        position: 'top-start',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    }).fire({
                        icon: 'success',
                        title: data.message
                    });
                } else {
                    Swal.mixin({
                        toast: true,
                        position: 'top-start',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    }).fire({
                        icon: 'error',
                        title: data.message
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan!',
                    text: 'Terjadi kesalahan saat mengunggah foto',
                    timer: 3000,
                    showConfirmButton: false
                });
            });
    }
}