<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AddressController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'label' => 'nullable|string|max:255',
                'receiver_name' => 'required|string|max:255',
                'phone_number' => 'required|string|max:20',
                'full_address' => 'required|string',
                'is_primary' => 'boolean'
            ]);

            // Tambahkan user_id ke data yang akan disimpan
            $validated['user_id'] = auth()->id();

            DB::transaction(function () use ($validated) {
                // Jika alamat baru adalah primary
                if (!empty($validated['is_primary'])) {
                    Address::where('user_id', auth()->id())
                        ->update(['is_primary' => false]);
                }

                // Jika ini alamat pertama, jadikan primary
                if (Address::where('user_id', auth()->id())->count() === 0) {
                    $validated['is_primary'] = true;
                }

                // Buat alamat baru
                $address = Address::create($validated);

                if (!$address) {
                    throw new \Exception('Failed to create address');
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Address saved successfully'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Address creation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to save address: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, Address $address)
    {
        try {
            // Authorize that the user owns this address
            if ($address->user_id !== auth()->id()) {
                throw new \Exception('Unauthorized access');
            }

            $validated = $request->validate([
                'label' => 'nullable|string|max:255',
                'receiver_name' => 'required|string|max:255',
                'phone_number' => 'required|string|max:20',
                'full_address' => 'required|string',
                'is_primary' => 'boolean'
            ]);

            DB::transaction(function () use ($validated, $address) {
                // Jika mengubah alamat menjadi primary
                if (!empty($validated['is_primary'])) {
                    Address::where('user_id', auth()->id())
                        ->where('id', '!=', $address->id)
                        ->update(['is_primary' => false]);
                }

                // Jika mencoba menghapus status primary pada satu-satunya alamat
                if ($address->is_primary && empty($validated['is_primary'])) {
                    if (Address::where('user_id', auth()->id())->count() === 1) {
                        $validated['is_primary'] = true;
                    }
                }

                if (!$address->update($validated)) {
                    throw new \Exception('Failed to update address');
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Address updated successfully'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Address update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update address: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Address $address)
    {
        try {
            // Authorize that the user owns this address
            if ($address->user_id !== auth()->id()) {
                throw new \Exception('Unauthorized access');
            }

            DB::transaction(function () use ($address) {
                // Jika mencoba menghapus satu-satunya alamat
                if (Address::where('user_id', auth()->id())->count() === 1) {
                    throw new \Exception('Cannot delete the only address');
                }

                // Jika menghapus alamat primary, set alamat lain sebagai primary
                if ($address->is_primary) {
                    $newPrimary = Address::where('user_id', auth()->id())
                        ->where('id', '!=', $address->id)
                        ->first();

                    if ($newPrimary) {
                        $newPrimary->update(['is_primary' => true]);
                    }
                }

                if (!$address->delete()) {
                    throw new \Exception('Failed to delete address');
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Address deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Address deletion error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Tambahkan method show untuk mendapatkan detail alamat
    public function show(Address $address)
    {
        try {
            // Authorize that the user owns this address
            if ($address->user_id !== auth()->id()) {
                throw new \Exception('Unauthorized access');
            }

            return response()->json($address);
        } catch (\Exception $e) {
            Log::error('Address fetch error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch address: ' . $e->getMessage()
            ], 500);
        }
    }
}