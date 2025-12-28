<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class BiodataController extends Controller
{
    /**
     * Show the lecturer's biodata form.
     */
    public function index()
    {
        $user = Auth::user();
        $dosen = $user->dosen;

        if (!$dosen) {
            // This should not happen for a user with the 'dosen' role
            abort(403, 'Profil dosen tidak ditemukan.');
        }

        // Load related data for display
        $dosen->load(['prodi.fakultas']);

        return view('dosen.biodata.index', compact('user', 'dosen'));
    }

    /**
     * Update the lecturer's basic information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
        ]);

        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            // Simpan foto baru dan perbarui path
            $validated['profile_photo_path'] = $request->file('photo')->store('profile-photos', 'public');
        }

        $user->update($validated);

        return redirect()->back()->with('success', 'Biodata berhasil diperbarui.');
    }

    /**
     * Update the lecturer's password.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }
}
