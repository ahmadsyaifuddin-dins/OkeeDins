@foreach ($addresses as $address)
    <div class="address-item mb-3 p-3 border rounded @if ($address->is_primary) border-primary @endif">
        <div class="form-check">
            <input class="form-check-input radio-custom" type="radio" name="selected_address"
                id="address_{{ $address->id }}" value="{{ $address->id }}"
                @if ($address->is_primary) checked @endif>
            <label class="form-check-label" for="address_{{ $address->id }}">
                <strong>{{ $address->label }}</strong>
                @if ($address->is_primary)
                    <span class="badge bg-custom ms-2">Utama</span>
                @endif
            </label>
        </div>
        <div class="ms-4 mt-2">
            <p class="mb-1"><strong>{{ $address->receiver_name }}</strong></p>
            <p class="mb-1">{{ $address->phone_number }}</p>
            <p class="mb-0">{{ $address->full_address }}</p>
        </div>
        <div class="mt-2 ms-4">
            <button type="button" class="btn btn-link btn-sm p-0 text-primary me-3"
                data-bs-toggle="modal" data-bs-target="#editAddressModal"
                data-address-id="{{ $address->id }}">
                Edit
            </button>
            @if (!$address->is_primary)
                <button type="button" class="btn btn-link btn-sm p-0 text-danger"
                    onclick="deleteAddress({{ $address->id }})">
                    Hapus
                </button>
            @endif
        </div>
    </div>
@endforeach
