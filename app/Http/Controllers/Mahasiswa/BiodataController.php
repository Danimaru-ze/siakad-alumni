<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class BiodataController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;
        
        if (!$mahasiswa) {
            abort(403, 'Unauthorized');
        }

        $mahasiswa->load(['prodi.fakultas', 'dosenPa.user', 'krs']);

        return view('mahasiswa.biodata.index', compact('user', 'mahasiswa'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;

        if (!$mahasiswa) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
        ]);

        if ($request->hasFile('photo')) {
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            $validated['profile_photo_path'] = $request->file('photo')->store('profile-photos', 'public');
        }

        // Update user
        $user->update($validated);

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
