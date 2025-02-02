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
                'is_primary' => 'nullable|boolean'
            ]);

            // Set is_primary berdasarkan apakah ini alamat pertama
            $isFirstAddress = Address::where('user_id', auth()->id())->count() === 0;
            $validated['is_primary'] = $isFirstAddress ? true : ($validated['is_primary'] ?? false);
            $validated['user_id'] = auth()->id();
            
            DB::beginTransaction();
            try {
                // Jika ini akan jadi alamat primary
                if ($validated['is_primary']) {
                    // Set semua alamat lain menjadi non-primary
                    Address::where('user_id', auth()->id())->update(['is_primary' => false]);
                }

                $address = Address::create($validated);
                DB::commit();

                // Ambil HTML baru untuk list alamat
                $addresses = Address::where('user_id', auth()->id())->get();
                $html = view('components.address-list', compact('addresses'))->render();

                return response()->json([
                    'success' => true,
                    'message' => 'Alamat berhasil ditambahkan',
                    'address' => $address,
                    'html' => $html
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Address creation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan alamat: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, Address $address)
    {
        try {
            if ($address->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses ke alamat ini'
                ], 403);
            }

            $validated = $request->validate([
                'label' => 'nullable|string|max:255',
                'receiver_name' => 'required|string|max:255',
                'phone_number' => 'required|string|max:20',
                'full_address' => 'required|string',
                'is_primary' => 'nullable|boolean'
            ]);

            // Convert is_primary to boolean if present
            $validated['is_primary'] = $validated['is_primary'] ?? false;

            DB::beginTransaction();
            try {
                // Jika alamat ini akan dijadikan primary
                if ($validated['is_primary']) {
                    // Set semua alamat lain menjadi non-primary
                    Address::where('user_id', auth()->id())
                          ->where('id', '!=', $address->id)
                          ->update(['is_primary' => false]);
                }
                // Jika mencoba mengubah alamat primary menjadi non-primary dan ini satu-satunya alamat
                elseif ($address->is_primary && Address::where('user_id', auth()->id())->count() === 1) {
                    $validated['is_primary'] = true; // Paksa tetap primary
                }

                $address->update($validated);
                DB::commit();

                // Ambil HTML baru untuk list alamat
                $addresses = Address::where('user_id', auth()->id())->get();
                $html = view('components.address-list', compact('addresses'))->render();

                return response()->json([
                    'success' => true,
                    'message' => 'Alamat berhasil diperbarui',
                    'address' => $address,
                    'html' => $html
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Address update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui alamat: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request, Address $address)
    {
        try {
            if ($address->user_id !== auth()->id()) {
                throw new \Exception('Anda tidak memiliki akses ke alamat ini');
            }

            if ($address->is_primary) {
                throw new \Exception('Alamat utama tidak dapat dihapus');
            }

            $address->delete();

            return response()->json([
                'success' => true,
                'message' => 'Alamat berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            Log::error('Address deletion error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus alamat: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(Address $address)
    {
        try {
            if ($address->user_id !== auth()->id()) {
                return response()->json([
                    'message' => 'Anda tidak memiliki akses ke alamat ini'
                ], 403);
            }

            return response()->json($address);

        } catch (\Exception $e) {
            Log::error('Get address data error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Gagal mengambil data alamat: ' . $e->getMessage()
            ], 500);
        }
    }

    public function setPrimary(Address $address)
    {
        try {
            // Authorize that the user owns this address
            if ($address->user_id !== auth()->id()) {
                throw new \Exception('Anda tidak memiliki akses ke alamat ini');
            }

            DB::transaction(function () use ($address) {
                // Set semua alamat user menjadi non-primary
                Address::where('user_id', auth()->id())
                    ->where('id', '!=', $address->id)
                    ->update(['is_primary' => false]);

                // Set alamat yang dipilih menjadi primary
                $address->update(['is_primary' => true]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Alamat utama berhasil diubah'
            ]);
        } catch (\Exception $e) {
            Log::error('Set primary address error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah alamat utama: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getList()
    {
        try {
            $addresses = Address::where('user_id', auth()->id())->get();
            $html = view('components.address-list', compact('addresses'))->render();
            
            return response()->json([
                'success' => true,
                'html' => $html
            ]);
        } catch (\Exception $e) {
            Log::error('Get address list error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil daftar alamat: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit(Address $address)
    {
        try {
            if ($address->user_id !== auth()->id()) {
                throw new \Exception('Anda tidak memiliki akses ke alamat ini');
            }

            return response()->json([
                'success' => true,
                'address' => $address
            ]);
        } catch (\Exception $e) {
            Log::error('Get address data error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data alamat: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getCheckoutList()
    {
        try {
            $addresses = Address::where('user_id', auth()->id())->get();
            $html = view('components.checkout-address-list', compact('addresses'))->render();
            
            return response()->json([
                'success' => true,
                'html' => $html
            ]);
        } catch (\Exception $e) {
            Log::error('Get checkout address list error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil daftar alamat: ' . $e->getMessage()
            ], 500);
        }
    }
}