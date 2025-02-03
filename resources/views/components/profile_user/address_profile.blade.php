<div id="addressesContent" class="tab-content hidden">
    <div class="p-6">
        <!-- Add Address Button -->
        <div class="mb-4">
            <button onclick="showAddAddressModal()"
                class="inline-flex items-center px-4 py-2 bg-custom text-white rounded-lg hover:bg-custom-dark transition-colors">
                <i class="bi bi-plus-lg me-2"></i>Tambah Alamat Baru
            </button>
        </div>

        <div class="space-y-4">
            @forelse(Auth::user()->addresses as $address)
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                    <div class="flex justify-between items-center mb-2">
                        <div class="flex items-center">
                            <span class="font-medium text-gray-900">{{ $address->label }}</span>
                            @if ($address->is_primary)
                                <span
                                    class="ml-2 px-2 py-1 text-xs bg-custom-secondary text-white rounded-full">Utama</span>
                            @endif
                        </div>
                        <div>
                            @if (!$address->is_primary)
                                <form action="{{ route('addresses.make-primary', $address) }}" method="POST"
                                    class="inline">
                                    @csrf
                                    @method('POST')
                                    <button type="submit" class="text-sm text-custom hover:text-red-600">
                                        Set sebagai Utama
                                    </button>
                                </form>
                            @endif
                            <button class="ml-2 text-sm text-gray-500 hover:text-gray-700"
                                onclick="editAddressModal({{ $address->id }})">
                                Edit
                            </button>
                            @if (!$address->is_primary)
                                <button onclick="confirmDelete({{ $address->id }})"
                                    class="ml-2 text-sm text-gray-500 hover:text-gray-700">
                                    Hapus
                                </button>
                            @endif

                            <script>
                                function confirmDelete(addressId) {
                                    Swal.fire({
                                        title: 'Apakah Anda yakin?',
                                        text: "Alamat ini akan dihapus permanen!",
                                        icon: 'warning',
                                        showCancelButton: true,
                                        confirmButtonColor: '#d33',
                                        cancelButtonColor: '#3085d6',
                                        confirmButtonText: 'Ya, hapus!',
                                        cancelButtonText: 'Batal'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            document.getElementById('delete-form-' + addressId).submit();
                                        }
                                    });
                                }
                            </script>

                            <form id="delete-form-{{ $address->id }}"
                                action="{{ route('addresses.destroy', $address) }}" method="POST"
                                style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </div>
                    <div class="text-sm text-gray-600">
                        <p><i class="bi bi-person me-2"></i>{{ $address->receiver_name }}</p>
                        <p><i class="bi bi-telephone me-2"></i>{{ $address->phone_number }}</p>
                        <p class="mt-1"><i class="bi bi-geo-alt me-2"></i>{{ $address->full_address }}</p>
                    </div>
                </div>
            @empty
                <div class="text-center py-8">
                    <i class="bi bi-house-x text-4xl text-gray-400"></i>
                    <p class="mt-2 text-gray-500">Belum ada alamat tersimpan</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
