<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Address;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        $user = Auth::user();
        $primaryAddress = Address::where('user_id', $user->id)
            ->where('is_primary', true)
            ->first();

        if ($primaryAddress) {
            DB::table('users')->where('id', $user->id)->update([
                'alamat' => $primaryAddress->full_address
            ]);
        }

        $addresses = Address::where('user_id', $user->id)
            ->orderBy('is_primary', 'desc')
            ->get()
            ->unique('full_address');

        return view('home.profile', [
            'user' => $user,
            'addresses' => $addresses
        ]);
    }

    public function updatePhoto(Request $request)
    {
        try {
            $user = Auth::user();

            // Validasi input
            $request->validate([
                'photo' => [
                    'required',
                    'image',
                    'mimes:jpeg,png,jpg,gif,svg,webp',
                    'max:2048' // 2MB
                ]
            ], [
                'photo.required' => 'Silakan pilih foto terlebih dahulu',
                'photo.image' => 'File harus berupa gambar',
                'photo.mimes' => 'Format foto harus jpeg, png, jpg, gif, webp, atau svg',
                'photo.max' => 'Ukuran foto maksimal 2MB'
            ]);

            if ($request->hasFile('photo')) {
                try {
                    // Hapus foto lama jika ada
                    if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                        Storage::disk('public')->delete($user->photo);
                    }

                    // Upload dan simpan foto baru
                    $photo = $request->file('photo');
                    $path = $photo->store('uploads/photo_pelanggan', 'public');
                    
                    // Update data user
                    $user->photo = $path;
                    $user->save();

                    return response()->json([
                        'success' => true,
                        'message' => 'Foto profil berhasil diperbarui',
                        'photoUrl' => asset('storage/' . $path)
                    ], 200);

                } catch (\Exception $e) {
                    // Log error
                    Log::error('Error saat upload foto: ' . $e->getMessage());
                    Log::error($e->getTraceAsString());

                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal mengupload foto. Silakan coba lagi.'
                    ], 500);
                }
            }

            return response()->json([
                'success' => false,
                'message' => 'Tidak ada foto yang diupload'
            ], 400);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->errors()['photo'][0] ?? 'Validasi foto gagal'
            ], 422);

        } catch (\Exception $e) {
            // Log error
            Log::error('Error di updatePhoto: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses foto'
            ], 500);
        }
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // Validasi input dengan kondisi berbeda untuk request AJAX
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id, 'id')],
            'telepon' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string', 'max:500'],
            'selected_address' => ['nullable', 'string'],
            'tgl_lahir' => ['nullable', 'date'],
            'makanan_fav' => ['nullable', 'string', 'max:200'],
            'photo' => 'nullable|image|max:2048',
            'password' => ['nullable', 'string', 'min:8'],
        ];


        // Jika ini request AJAX untuk update foto, kurangi validasi
        if ($request->ajax() && $request->hasFile('photo')) {
            $rules = [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id, 'id')],
                'photo' => 'required|image|max:2048',
            ];
        }

        $validated = $request->validate($rules);

        // Handle address update
        if ($request->filled('selected_address')) {
            $address = Address::find($request->selected_address);
            if ($address && $address->user_id === $user->id) {
                $user->alamat = $address->full_address;

                // Update primary status
                Address::where('user_id', $user->id)->update(['is_primary' => false]);
                $address->is_primary = true;
                $address->save();
            }
        } else {
            // Gunakan alamat dari input jika tersedia
            $user->alamat = $request->input('alamat', $user->alamat);
        }

        // Proses perubahan foto profil
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($user->photo) {
                Storage::delete('public/' . $user->photo);
            }
            // Simpan foto baru
            $path = $request->file('photo')->store('uploads_photo_pelanggan', 'public');
            $user->photo = $path;
        }

        // Proses untuk request AJAX
        if ($request->ajax()) {
            try {
                // Metode query builder untuk update
                DB::table('users')->where('id', $user->id)->update([
                    'photo' => $path ?? $user->photo
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Foto profil berhasil diperbarui',
                    'photoUrl' => $user->photo ? asset('storage/' . $user->photo) : null
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui foto profil: ' . $e->getMessage()
                ], 500);
            }
        }

        // Hapus foto jika checkbox "Hapus foto profil" dicentang
        if ($request->has('remove_photo') && $request->remove_photo) {
            if ($user->photo) {
                Storage::delete('public/' . $user->photo);
            }
            $user->photo = null;
        }

        // Perbarui data pengguna
        $user->name = $validated['name'];
        // $user->email = $validated['email'];
        $user->telepon = $validated['telepon'];
        $user->tgl_lahir = $validated['tgl_lahir'];
        // $user->makanan_fav = $validated['makanan_fav']

        // Perbarui password jika ada input password baru
        if ($request->filled('password')) {
            $user->password = $validated['password'];
        }

        if ($user instanceof User) {
            $user->save();
            return back()->with('success', 'Profile Berhasil disimpan!');
        } else {
            return back()->with('error', 'Terjadi kesalahan saat menyimpan profile.');
        }
    }
}
