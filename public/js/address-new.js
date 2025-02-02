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
        const response = await fetch('/addresses', {
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

// Fungsi untuk menangani edit alamat
async function updateAddress() {
    const form = document.getElementById('editAddressForm');
    const formData = new FormData(form);
    const addressId = formData.get('address_id');

    try {
        const response = await fetch(`/addresses/${addressId}`, {
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
            throw new Error(data.message || 'Gagal mengupdate alamat');
        }

        // Tutup modal
        toggleModal('editAddressModal', false);

        // Refresh container alamat
        await refreshAddressContainer();
        
        // Refresh checkout address jika ada
        if (typeof refreshCheckoutAddress === 'function') {
            await refreshCheckoutAddress();
        }

        Toast.fire({
            icon: 'success',
            title: 'Alamat berhasil diperbarui'
        });
        // Reload halaman setelah notifikasi selesai
        setTimeout(() => {
            window.location.reload();
        } , 1500);
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
        const result = await Swal.fire({
            title: 'Hapus Alamat?',
            text: "Alamat yang dihapus tidak dapat dikembalikan",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        });

        if (result.isConfirmed) {
            const response = await fetch(`/addresses/${addressId}`, {
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

// Fungsi untuk mengisi form edit dengan data alamat yang ada
async function fillEditForm(addressId) {
    try {
        const response = await fetch(`/addresses/${addressId}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        const data = await response.json();
        
        if (!response.ok) {
            throw new Error(data.message || 'Gagal mengambil data alamat');
        }

        const form = document.getElementById('editAddressForm');
        
        // Mengisi form dengan data yang ada
        form.querySelector('[name="address_id"]').value = data.id;
        form.querySelector('[name="label"]').value = data.label || '';
        form.querySelector('[name="receiver_name"]').value = data.receiver_name;
        form.querySelector('[name="phone_number"]').value = data.phone_number;
        form.querySelector('[name="full_address"]').value = data.full_address;
        form.querySelector('[name="is_primary"]').checked = data.is_primary;

        // Tampilkan modal setelah data terisi
        toggleModal('editAddressModal', true);
    } catch (error) {
        console.error('Error filling edit form:', error);
        Toast.fire({
            icon: 'error',
            title: error.message
        });
    }
}

// Fungsi untuk toggle modal dengan Tailwind
function toggleModal(modalId, show = true) {
    const modal = document.getElementById(modalId);
    const overlay = modal.querySelector('.fixed.inset-0.bg-gray-500');
    
    if (show) {
        // Tampilkan modal
        modal.classList.remove('hidden');
        // Tambahkan animasi fade in
        setTimeout(() => {
            overlay.classList.remove('opacity-0');
            modal.querySelector('.relative').classList.remove('opacity-0', 'translate-y-4');
            modal.querySelector('.relative').classList.add('opacity-100', 'translate-y-0');
        }, 50);
        // Prevent scroll pada body
        document.body.style.overflow = 'hidden';
    } else {
        // Animasi fade out
        overlay.classList.add('opacity-0');
        modal.querySelector('.relative').classList.add('opacity-0', 'translate-y-4');
        modal.querySelector('.relative').classList.remove('opacity-100', 'translate-y-0');
        // Tunggu animasi selesai sebelum hide modal
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }, 300);
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
            fillEditForm(addressId);
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
            await updateAddress();
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