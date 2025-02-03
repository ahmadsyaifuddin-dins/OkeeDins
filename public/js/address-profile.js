// Konfigurasi SweetAlert2 default
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 1500,
    timerProgressBar: true
});

// Fungsi untuk refresh container alamat
async function refreshAddressContainer() {
    try {
        const response = await fetch('/addresses/list', {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        const data = await response.json();
        if (!response.ok) {
            throw new Error(data.message || 'Gagal memuat daftar alamat');
        }

        // Update container dengan HTML baru
        const addressListContainer = document.getElementById('addressList');
        if (addressListContainer && data.html) {
            addressListContainer.innerHTML = data.html;
            attachEventListeners(); // Reattach event listeners setelah update HTML
        }
    } catch (error) {
        console.error('Error refreshing address container:', error);
        Toast.fire({
            icon: 'error',
            title: 'Gagal memperbarui daftar alamat'
        });
    }
}

// Fungsi untuk menangani penambahan alamat baru
async function saveNewAddress() {
    const form = document.getElementById('addAddressForm');
    const formData = new FormData(form);

    try {
        const response = await fetch('/addresses/store', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: formData
        });

        const data = await response.json();
        if (!response.ok) {
            if (data.errors) {
                const errorMessages = Object.values(data.errors).flat().join('\n');
                throw new Error(errorMessages);
            }
            throw new Error(data.message || 'Gagal menambahkan alamat');
        }

        // Tutup modal dan reset form
        toggleModal('addAddressModal', false);
        form.reset();

        // Refresh container alamat
        await refreshAddressContainer();
        
        // Refresh checkout address jika ada
        if (typeof refreshCheckoutAddress === 'function') {
            await refreshCheckoutAddress();
        }

        Toast.fire({
            icon: 'success',
            title: 'Alamat berhasil ditambahkan'
        });

        // Reload halaman setelah notifikasi selesai
        setTimeout(() => {
            window.location.reload();
        }, 1500);
    } catch (error) {
        console.error('Error adding address:', error);
        Toast.fire({
            icon: 'error',
            title: error.message
        });
    }
}

// Fungsi untuk menampilkan modal edit dan mengisi data
async function editAddressModal(addressId) {
    try {
        const response = await fetch(`/addresses/${addressId}/edit`, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        const data = await response.json();
        if (!response.ok) {
            throw new Error(data.message || 'Gagal mengambil data alamat');
        }

        // Isi form dengan data yang ada
        const form = document.getElementById('editAddressForm');
        form.querySelector('[name="address_id"]').value = data.id;
        form.querySelector('[name="label"]').value = data.label || '';
        form.querySelector('[name="receiver_name"]').value = data.receiver_name;
        form.querySelector('[name="phone_number"]').value = data.phone_number;
        form.querySelector('[name="full_address"]').value = data.full_address;
        form.querySelector('[name="is_primary"]').checked = data.is_primary;

        // Tampilkan modal
        toggleModal('editAddressModal', true);
    } catch (error) {
        console.error('Error getting address:', error);
        Toast.fire({
            icon: 'error',
            title: error.message
        });
    }
}

// Fungsi untuk menyimpan perubahan alamat
async function updateAddress(event) {
    event.preventDefault();
    
    const form = document.getElementById('editAddressForm');
    const addressId = form.querySelector('[name="address_id"]').value;
    const formData = new FormData(form);
    formData.append('_method', 'PUT'); // Tambahkan method PUT

    try {
        const response = await fetch(`/addresses/${addressId}`, {
            method: 'POST', // Tetap gunakan POST karena ada file upload
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: formData
        });

        const data = await response.json();
        if (!response.ok) {
            if (data.errors) {
                const errorMessages = Object.values(data.errors).flat().join('\n');
                throw new Error(errorMessages);
            }
            throw new Error(data.message || 'Gagal memperbarui alamat');
        }

        // Tutup modal
        toggleModal('editAddressModal', false);

        // Refresh container alamat
        await refreshAddressContainer();

        Toast.fire({
            icon: 'success',
            title: 'Alamat berhasil diperbarui'
        });

        // Reload halaman setelah notifikasi selesai
        setTimeout(() => {
            window.location.reload();
        }, 1500);
    } catch (error) {
        console.error('Error updating address:', error);
        Toast.fire({
            icon: 'error',
            title: error.message
        });
    }
}

// Fungsi untuk menghapus alamat
async function deleteAddress(addressId) {
    try {
        const confirmed = await Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Alamat ini akan dihapus secara permanen",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        });

        if (confirmed.isConfirmed) {
            const response = await fetch(`/addresses/${addressId}/destroy`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'Gagal menghapus alamat');
            }

            // Refresh container alamat
            await refreshAddressContainer();

            Toast.fire({
                icon: 'success',
                title: 'Alamat berhasil dihapus'
            });

            // Reload halaman setelah notifikasi selesai
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        }
    } catch (error) {
        console.error('Error deleting address:', error);
        Toast.fire({
            icon: 'error',
            title: error.message
        });
    }
}

// Fungsi untuk toggle modal
function toggleModal(modalId, show = true) {
    const modal = document.getElementById(modalId);
    if (show) {
        modal.classList.remove('hidden');
    } else {
        modal.classList.add('hidden');
    }
}

// Fungsi untuk memasang semua event listeners
function attachEventListeners() {
    // Event listener untuk tombol tambah alamat
    document.querySelectorAll('[data-modal-target="#addAddressModal"]').forEach(button => {
        button.addEventListener('click', () => toggleModal('addAddressModal', true));
    });

    // Event listener untuk tombol edit alamat
    document.querySelectorAll('[data-modal-target="#editAddressModal"]').forEach(button => {
        button.addEventListener('click', () => {
            const addressId = button.getAttribute('data-address-id');
            editAddressModal(addressId);
        });
    });

    // Event listener untuk menutup modal ketika mengklik overlay
    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) {
                const modalId = overlay.closest('[role="dialog"]').id;
                toggleModal(modalId, false);
            }
        });
    });

    // Event listener untuk tombol tutup modal
    document.querySelectorAll('[data-modal-close]').forEach(button => {
        button.addEventListener('click', () => {
            const modalId = button.closest('[role="dialog"]').id;
            toggleModal(modalId, false);
        });
    });

    // Event listener untuk form submit
    const addAddressForm = document.getElementById('addAddressForm');
    if (addAddressForm) {
        addAddressForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            await saveNewAddress();
        });
    }

    const editAddressForm = document.getElementById('editAddressForm');
    if (editAddressForm) {
        editAddressForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            await updateAddress(e);
        });
    }

    // Event listener untuk tombol hapus alamat
    document.querySelectorAll('[data-delete-address]').forEach(button => {
        button.addEventListener('click', () => {
            const addressId = button.getAttribute('data-address-id');
            deleteAddress(addressId);
        });
    });
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    attachEventListeners();
});