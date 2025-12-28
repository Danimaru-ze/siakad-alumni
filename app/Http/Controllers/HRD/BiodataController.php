<?php

namespace App\Http\Controllers\HRD;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class BiodataController extends Controller
{
    /**
     * Show the hrd's biodata form.
     */
    public function index()
    {
        $user = Auth::user()->load('hrdProfile.company');
        
        if (!$user->hrdProfile) {
            return redirect()->route('hrd.profile.create')
                ->with('error', 'Lengkapi profil perusahaan dan HRD Anda terlebih dahulu.');
        }

        return view('hrd.biodata.index', compact('user'));
    }

    /**
     * Update the hrd's basic information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'jabatan_hrd' => 'required|string|max:255',
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
        ]);

        if ($request->hasFile('photo')) {
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            $validated['profile_photo_path'] = $request->file('photo')->store('profile-photos', 'public');
        }

        $user->update($validated);

        $user->hrdProfile()->update([
            'nama_hrd' => $validated['name'],
            'jabatan_hrd' => $validated['jabatan_hrd'],
        ]);

        return redirect()->back()->with('success', 'Biodata berhasil diperbarui.');
    }

    /**
     * Update the hrd's password.
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
