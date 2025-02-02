{{-- @if($addresses->isEmpty())
    <div class="text-center py-8">
        <div class="mb-4">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <p class="text-gray-500">Belum ada alamat tersimpan</p>
    </div>
@else
    <div class="space-y-4">
        @foreach($addresses as $address)
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow duration-200">
                <div class="p-4">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-2">
                                <h3 class="text-gray-900 font-medium">{{ $address->label }}</h3>
                                @if($address->is_primary)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Utama
                                    </span>
                                @endif
                            </div>
                            
                            <div class="mt-2 space-y-1">
                                <p class="text-gray-900">{{ $address->receiver_name }}</p>
                                <p class="text-gray-600">{{ $address->phone_number }}</p>
                                <p class="text-gray-600 whitespace-pre-line">{{ $address->full_address }}</p>
                            </div>
                        </div>

                        <div class="flex flex-col items-end space-y-2">
                            <div class="flex items-center space-x-2">
                                <button type="button"
                                        class="text-sm text-gray-600 hover:text-red-500 transition-colors duration-200"
                                        data-modal-target="#editAddressModal"
                                        data-address-id="{{ $address->id }}">
                                    <span class="sr-only">Edit alamat</span>
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </button>
                                @unless($address->is_primary)
                                    <button type="button"
                                            class="text-sm text-gray-600 hover:text-red-500 transition-colors duration-200"
                                            onclick="deleteAddress({{ $address->id }})">
                                        <span class="sr-only">Hapus alamat</span>
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                @endunless
                            </div>
                            
                            @if(!$address->is_primary)
                                <button type="button"
                                        class="text-sm text-red-600 hover:text-red-700 font-medium"
                                        onclick="makeAddressPrimary({{ $address->id }})">
                                    Jadikan Utama
                                </button>
                            @endif
                        </div>
                    </div>

                    @if(isset($showRadio) && $showRadio)
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <div class="flex items-center">
                                <input type="radio"
                                       name="selected_address"
                                       value="{{ $address->id }}"
                                       {{ $address->is_primary ? 'checked' : '' }}
                                       class="h-4 w-4 text-red-600 border-gray-300 focus:ring-red-500"
                                       onchange="updateSelectedAddress(this.value)">
                                <label class="ml-2 text-sm text-gray-700">
                                    Pilih alamat ini
                                </label>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endif --}}
