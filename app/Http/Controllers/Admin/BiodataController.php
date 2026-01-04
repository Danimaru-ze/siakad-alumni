<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class BiodataController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Ensure AdminProfile exists or create a default one if it doesn't
        if (!$user->adminProfile) {
            $user->adminProfile()->create();
            $user->load('adminProfile');
        }

        $adminProfile = $user->adminProfile;

        return view('admin.biodata.index', compact('user', 'adminProfile'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $adminProfile = $user->adminProfile;

        if (!$adminProfile) {
            $adminProfile = $user->adminProfile()->create();
        }

        $validatedUser = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
        ]);

        $validatedProfile = $request->validate([
            'nip' => 'nullable|string|max:50|unique:admin_profiles,nip,' . $adminProfile->id,
            'jabatan' => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ]);

        if ($request->hasFile('photo')) {
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            $validatedUser['profile_photo_path'] = $request->file('photo')->store('profile-photos', 'public');
        }

        // Update user
        $user->update($validatedUser);
        
        // Update profile
        $adminProfile->update($validatedProfile);

        return redirect()->back()->with('success', 'Biodata berhasil diperbarui');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->with('error', 'Password lama tidak sesuai');
        }

        $user->update(['password' => Hash::make($request->password)]);

        return redirect()->back()->with('success', 'Password berhasil diperbarui');
    }
}
