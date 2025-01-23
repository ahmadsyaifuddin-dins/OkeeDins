// Konfigurasi SweetAlert2 default
// const Toast = Swal.mixin({
//     toast: true,
//     position: 'top-start',
//     showConfirmButton: false,
//     timer: 3000,
//     timerProgressBar: true
// });

// Fungsi untuk refresh container alamat
async function refreshAddressContainer() {
    try {
        const response = await fetch('/addresses/list', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (!response.ok) throw new Error('Gagal memuat daftar alamat');

        const result = await response.json();
        if (!result.success) {
            throw new Error(result.message || 'Gagal memuat daftar alamat');
        }

        const container = document.querySelector('.addresses-container');
        if (container) {
            container.innerHTML = result.html;
            attachEditModalListeners();
        }
    } catch (error) {
        console.error('Error refreshing addresses:', error);
        Toast.fire({
            icon: 'error',
            title: error.message
        });
    }
}

// Fungsi untuk menangani penambahan alamat baru
async function saveNewAddress() {
    const form = document.getElementById('addAddressForm');
    const formData = new FormData(form);

    // Handle checkbox value
    formData.set('is_primary', form.querySelector('[name="is_primary"]').checked ? '1' : '0');

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
        bootstrap.Modal.getInstance(document.getElementById('addAddressModal')).hide();
        form.reset();

        // Refresh container alamat
        await refreshAddressContainer();

        Toast.fire({
            icon: 'success',
            title: 'Alamat berhasil ditambahkan'
        });
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
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify(Object.fromEntries(formData))
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
        bootstrap.Modal.getInstance(document.getElementById('editAddressModal')).hide();

        // Refresh container alamat
        await refreshAddressContainer();

        Toast.fire({
            icon: 'success',
            title: 'Alamat berhasil diperbarui'
        });
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
            text: "Alamat yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
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
            if (!response.ok) throw new Error(data.message || 'Gagal menghapus alamat');

            // Refresh container alamat
            await refreshAddressContainer();

            Toast.fire({
                icon: 'success',
                title: 'Alamat berhasil dihapus'
            });
        }
    } catch (error) {
        console.error('Error deleting address:', error);
        Toast.fire({
            icon: 'error',
            title: error.message
        });
    }
}

// Event handler untuk modal edit
function attachEditModalListeners() {
    const editModal = document.getElementById('editAddressModal');
    if (!editModal) return;

    editModal.addEventListener('show.bs.modal', async function (event) {
        const button = event.relatedTarget;
        const addressId = button.getAttribute('data-address-id');

        try {
            const response = await fetch(`/addresses/${addressId}`);
            const data = await response.json();

            if (!response.ok) throw new Error(data.message || 'Gagal mengambil data alamat');

            const form = this.querySelector('#editAddressForm');
            form.querySelector('[name="address_id"]').value = data.id;
            form.querySelector('[name="label"]').value = data.label;
            form.querySelector('[name="receiver_name"]').value = data.receiver_name;
            form.querySelector('[name="phone_number"]').value = data.phone_number;
            form.querySelector('[name="full_address"]').value = data.full_address;
            form.querySelector('[name="is_primary"]').checked = data.is_primary;
        } catch (error) {
            console.error('Error fetching address:', error);
            Toast.fire({
                icon: 'error',
                title: error.message
            });
            bootstrap.Modal.getInstance(editModal).hide();
        }
    });
}

// Event listener untuk form tambah alamat
document.getElementById('addAddressForm')?.addEventListener('submit', async function (e) {
    e.preventDefault();
    await saveNewAddress();
});

// Event listener untuk form edit alamat
document.getElementById('editAddressForm')?.addEventListener('submit', async function (e) {
    e.preventDefault();
    await updateAddress();
});

// Initialize event listeners when document is ready
document.addEventListener('DOMContentLoaded', function () {
    // Konfigurasi SweetAlert2 default
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });

    // Fungsi untuk refresh container alamat
    async function refreshAddressContainer() {
        try {
            const response = await fetch('/addresses/list', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) throw new Error('Gagal memuat daftar alamat');

            const result = await response.json();
            if (!result.success) {
                throw new Error(result.message || 'Gagal memuat daftar alamat');
            }

            const container = document.querySelector('.addresses-container');
            if (container) {
                container.innerHTML = result.html;
                attachEditModalListeners();
            }
        } catch (error) {
            console.error('Error refreshing addresses:', error);
            Toast.fire({
                icon: 'error',
                title: error.message
            });
        }
    }

    // Event handler untuk form tambah alamat
    window.saveNewAddress = async function () {
        const form = document.getElementById('addAddressForm');
        const formData = new FormData(form);

        // Handle checkbox value
        formData.set('is_primary', form.querySelector('[name="is_primary"]').checked ? '1' : '0');

        try {
            const response = await fetch('/addresses', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: formData
            });

            if (!response.ok) {
                const result = await response.json();
                throw new Error(result.message || 'Gagal menambahkan alamat');
            }

            // Tutup modal dan reset form
            const modal = bootstrap.Modal.getInstance(document.getElementById('addAddressModal'));
            if (modal) {
                modal.hide();
                form.reset();
            }

            // Refresh daftar alamat
            await refreshAddressContainer();

            Toast.fire({
                icon: 'success',
                title: 'Alamat berhasil ditambahkan'
            });
        } catch (error) {
            console.error('Error adding address:', error);
            Toast.fire({
                icon: 'error',
                title: error.message
            });
        }
    };

    // Event handler untuk form edit alamat
    window.updateAddress = async function () {
        const form = document.getElementById('editAddressForm');
        const formData = new FormData(form);
        const addressId = formData.get('address_id');

        // Handle checkbox value
        formData.set('is_primary', form.querySelector('[name="is_primary"]').checked ? '1' : '0');

        // Tambahkan method spoofing
        formData.append('_method', 'PUT');

        try {
            const response = await fetch(`/addresses/${addressId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: formData
            });

            if (!response.ok) {
                const result = await response.json();
                throw new Error(result.message || 'Gagal memperbarui alamat');
            }

            // Tutup modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('editAddressModal'));
            if (modal) {
                modal.hide();
            }

            // Refresh daftar alamat
            await refreshAddressContainer();

            Toast.fire({
                icon: 'success',
                title: 'Alamat berhasil diperbarui'
            });
        } catch (error) {
            console.error('Error updating address:', error);
            Toast.fire({
                icon: 'error',
                title: error.message
            });
        }
    };

    // Event handler untuk hapus alamat
    window.deleteAddress = async function (addressId) {
        try {
            const result = await Swal.fire({
                title: 'Hapus Alamat?',
                text: "Alamat yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
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

                // Refresh daftar alamat
                await refreshAddressContainer();

                Toast.fire({
                    icon: 'success',
                    title: 'Alamat berhasil dihapus'
                });
            }
        } catch (error) {
            console.error('Error deleting address:', error);
            Toast.fire({
                icon: 'error',
                title: error.message
            });
        }
    };

    // Event handler untuk modal edit
    function attachEditModalListeners() {
        const editModal = document.getElementById('editAddressModal');
        if (!editModal) return;

        editModal.addEventListener('show.bs.modal', async function (event) {
            const button = event.relatedTarget;
            const addressId = button.getAttribute('data-address-id');

            try {
                const response = await fetch(`/addresses/${addressId}`, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Gagal mengambil data alamat');
                }

                const form = this.querySelector('#editAddressForm');
                form.querySelector('[name="address_id"]').value = data.id;
                form.querySelector('[name="label"]').value = data.label || '';
                form.querySelector('[name="receiver_name"]').value = data.receiver_name;
                form.querySelector('[name="phone_number"]').value = data.phone_number;
                form.querySelector('[name="full_address"]').value = data.full_address;
                form.querySelector('[name="is_primary"]').checked = data.is_primary;
            } catch (error) {
                console.error('Error fetching address:', error);
                Toast.fire({
                    icon: 'error',
                    title: error.message
                });
                bootstrap.Modal.getInstance(editModal).hide();
            }
        });
    }

    // Initialize
    attachEditModalListeners();
    refreshAddressContainer();
});
