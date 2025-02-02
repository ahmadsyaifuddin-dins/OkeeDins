@foreach ($addresses as $address)
    <div class="p-4 border rounded-lg {{ $address->is_primary ? 'border-red-500' : 'border-gray-200' }}">
        <div class="flex items-start">
            <div class="flex items-center h-5">
                <input type="radio"
                       name="selected_address"
                       id="address_{{ $address->id }}"
                       value="{{ $address->id }}"
                       {{ $address->is_primary ? 'checked' : '' }}
                       onchange="document.getElementById('address_id_input').value = this.value"
                       class="w-4 h-4 text-red-500 border-gray-300 focus:ring-red-500">
            </div>
            <div class="ml-3">
                <label for="address_{{ $address->id }}" class="font-medium text-gray-900">
                    {{ $address->label }}
                    @if ($address->is_primary)
                        <span class="ml-2 px-2 py-1 text-xs font-semibold text-red-500 bg-red-50 rounded-lg">
                            Utama
                        </span>
                    @endif
                </label>
                <div class="mt-2 text-sm text-gray-700">
                    <p class="font-medium">{{ $address->receiver_name }}</p>
                    <p>{{ $address->phone_number }}</p>
                    <p>{{ $address->full_address }}</p>
                </div>
                <div class="mt-2 space-x-4">
                    <button type="button"
                            class="text-sm text-red-500 hover:text-red-600"
                            data-modal-target="#editAddressModal"
                            data-address-id="{{ $address->id }}">
                        Edit
                    </button>
                    @if (!$address->is_primary)
                        <button type="button"
                                class="text-sm text-gray-500 hover:text-gray-600"
                                onclick="deleteAddress({{ $address->id }})">
                            Hapus
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endforeach
