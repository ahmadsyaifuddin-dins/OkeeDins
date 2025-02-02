<!-- Add Address Modal -->
<div id="addAddressModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog"
    aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

        <!-- Modal panel -->
        <div
            class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white">
                <!-- Modal Header -->
                <div class="flex items-center justify-between p-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Tambah Alamat Baru
                    </h3>
                    <button type="button" class="text-gray-400 hover:text-gray-500"
                        onclick="toggleModal('addAddressModal', false)">
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6">
                    <form id="addAddressForm" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Label Alamat
                            </label>
                            <input type="text" name="label" placeholder="Rumah, Kantor, dll"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-custom focus:border-custom">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Nama Penerima
                            </label>
                            <input type="text" name="receiver_name" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-custom focus:border-custom">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Nomor Telepon
                            </label>
                            <input type="tel" name="phone_number" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-custom focus:border-custom">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Alamat Lengkap
                            </label>
                            <textarea name="full_address" rows="3" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-custom focus:border-custom"></textarea>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" name="is_primary" id="makeAddressPrimary" value="1"
                                class="w-4 h-4 text-red-500 border-gray-300 rounded focus:ring-custom">
                            <label for="makeAddressPrimary" class="ml-2 text-sm text-gray-700">
                                Jadikan Alamat Utama
                            </label>
                        </div>
                    </form>
                </div>

                <!-- Modal Footer -->
                <div class="flex items-center justify-end p-4 space-x-2 border-t">
                    <button type="button"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-custom"
                        onclick="toggleModal('addAddressModal', false)">
                        Batal
                    </button>
                    <button type="button"
                        class="px-4 py-2 text-sm font-medium text-white bg-custom rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-custom"
                        onclick="saveNewAddress()">
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Address Modal -->
<div id="editAddressModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title"
    role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

        <!-- Modal panel -->
        <div
            class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white">
                <!-- Modal Header -->
                <div class="flex items-center justify-between p-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Edit Alamat
                    </h3>
                    <button type="button" class="text-gray-400 hover:text-gray-500"
                        onclick="toggleModal('editAddressModal', false)">
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6">
                    <form id="editAddressForm" class="space-y-4">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="address_id">

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Label Alamat
                            </label>
                            <input type="text" name="label" placeholder="Rumah, Kantor, dll"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-custom focus:border-custom">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Nama Penerima
                            </label>
                            <input type="text" name="receiver_name" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-custom focus:border-custom">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Nomor Telepon
                            </label>
                            <input type="tel" name="phone_number" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-custom focus:border-custom">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Alamat Lengkap
                            </label>
                            <textarea name="full_address" rows="3" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-custom focus:border-custom"></textarea>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" name="is_primary" id="editMakeAddressPrimary" value="1"
                                class="w-4 h-4 text-custom border-gray-300 rounded focus:ring-custom">
                            <label for="editMakeAddressPrimary" class="ml-2 text-sm text-gray-700">
                                Jadikan Alamat Utama
                            </label>
                        </div>
                    </form>
                </div>

                <!-- Modal Footer -->
                <div class="flex items-center justify-end p-4 space-x-2 border-t">
                    <button type="button"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-custom"
                        onclick="toggleModal('editAddressModal', false)">
                        Batal
                    </button>
                    <button type="button"
                        class="px-4 py-2 text-sm font-medium text-white bg-custom rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-custom"
                        onclick="updateAddress()">
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
