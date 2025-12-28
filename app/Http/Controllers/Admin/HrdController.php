<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HrdProfile;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Str;

class HrdController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hrdProfiles = HrdProfile::with(['user', 'company'])->latest()->paginate(10);
        $companies = Company::orderBy('nama_perusahaan')->get();
        return view('admin.hrd.index', compact('hrdProfiles', 'companies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_hrd' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'min:8'],
            'nama_perusahaan' => ['required', 'string', 'max:255'],
            'jabatan_hrd' => ['nullable', 'string', 'max:255'],
        ]);

        $company = Company::firstOrCreate(
            ['nama_perusahaan' => $request->nama_perusahaan],
            ['email' => Str::slug($request->nama_perusahaan) . '-' . time() . '@placeholder.com']
        );

        $user = User::create([
            'name' => $request->nama_hrd,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'hrd',
        ]);

        HrdProfile::create([
            'user_id' => $user->id,
            'company_id' => $company->id,
            'nama_hrd' => $request->nama_hrd,
            'jabatan_hrd' => $request->jabatan_hrd,
            'email_hrd' => $request->email,
        ]);

        return redirect()->route('admin.hrd.index')->with('success', 'HRD berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HrdProfile $hrd)
    {
        $request->validate([
            'nama_hrd' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $hrd->user_id],
            'password' => ['nullable', 'min:8'],
            'nama_perusahaan' => ['required', 'string', 'max:255'],
            'jabatan_hrd' => ['nullable', 'string', 'max:255'],
        ]);

        $company = Company::firstOrCreate(
            ['nama_perusahaan' => $request->nama_perusahaan],
            ['email' => Str::slug($request->nama_perusahaan) . '-' . time() . '@placeholder.com']
        );
        
        $user = $hrd->user;
        $user->name = $request->nama_hrd;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        $hrd->update([
            'company_id' => $company->id,
            'nama_hrd' => $request->nama_hrd,
            'jabatan_hrd' => $request->jabatan_hrd,
            'email_hrd' => $request->email,
        ]);

        return redirect()->route('admin.hrd.index')->with('success', 'HRD berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HrdProfile $hrd)
    {
        // User akan terhapus secara otomatis karena onDelete('cascade')
        $hrd->user()->delete();
        return redirect()->route('admin.hrd.index')->with('success', 'HRD berhasil dihapus.');
    }
}
