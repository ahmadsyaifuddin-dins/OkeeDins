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

    public function update(Request $request)
    {
        $user = Auth::user();

        // Validasi input
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id, 'id')],
            'telepon' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string', 'max:500'],
            'selected_address' => ['nullable', 'string'],
            'tgl_lahir' => ['nullable', 'date'],
            'makanan_fav' => ['nullable', 'string', 'max:200'],
            'photo' => 'nullable|image|max:2048',
            'password' => ['nullable', 'string', 'min:8'],
        ]);

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
            // If no address is selected, use the input from textarea
            $user->alamat = $validated['alamat'];
        }

        // Proses perubahan foto profil
        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::delete('public/' . $user->photo);
            }
            $path = $request->file('photo')->store('uploads_photo_pelanggan', 'public');
            $user->photo = $path;
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
        $user->email = $validated['email'];
        $user->telepon = $validated['telepon'];
        $user->tgl_lahir = $validated['tgl_lahir'];
        $user->makanan_fav = $validated['makanan_fav'];

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

    // Method untuk mengubah alamat menjadi primary
    public function setPrimaryAddress(Request $request, Address $address)
    {
        if ($address->user_id !== Auth::id()) {
            return back()->with('error', 'Anda tidak memiliki akses ke alamat ini.');
        }

        // Update semua alamat user menjadi non-primary
        Address::where('user_id', Auth::id())
            ->update(['is_primary' => false]);

        // Set alamat yang dipilih menjadi primary
        $address->is_primary = true;
        $address->save();

        return back()->with('success', 'Alamat utama berhasil diubah!');
    }
}