<?php

namespace App\Http\Controllers\HRD;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\HrdProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CompanyProfileController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('hrd.company-profile.setup');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_hrd' => 'required|string|max:255',
            'jabatan_hrd' => 'required|string|max:255',
            'nama_perusahaan' => 'required|string|max:255',
            'email_perusahaan' => 'required|email|max:255|unique:companies,email',
            'website' => 'nullable|url|max:255',
            'alamat' => 'required|string|max:1000',
        ]);

        $user = Auth::user();

        try {
            DB::transaction(function () use ($request, $user) {
                // Create company
                $company = Company::create([
                    'nama_perusahaan' => $request->input('nama_perusahaan'),
                    'email' => $request->input('email_perusahaan'),
                    'website' => $request->input('website'),
                    'alamat' => $request->input('alamat'),
                ]);

                // Create HRD Profile
                HrdProfile::create([
                    'user_id' => $user->id,
                    'company_id' => $company->id,
                    'nama_hrd' => $request->input('nama_hrd'),
                    'jabatan_hrd' => $request->input('jabatan_hrd'),
                    'email_hrd' => $user->email, // Use user's email for HRD email
                ]);

                // Also update the user's name to match the HRD name given at setup
                $user->update(['name' => $request->input('nama_hrd')]);
            });
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyimpan profil. Silakan coba lagi. Error: ' . $e->getMessage())->withInput();
        }

        return redirect()->route('hrd.dashboard')->with('success', 'Profil perusahaan berhasil disimpan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function index()
    {
        $user = Auth::user();
        $hrdProfile = $user->hrdProfile()->with('company')->firstOrFail();

        return view('hrd.company-profile.index', compact('hrdProfile'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $hrdProfile = $user->hrdProfile()->firstOrFail();
        $company = $hrdProfile->company;

        $validated = $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:companies,email,' . $company->id,
            'website' => 'nullable|url|max:255',
            'alamat' => 'required|string|max:1000',
            'telepon' => 'nullable|string|max:20',
            'deskripsi' => 'nullable|string|max:5000',
        ]);

        try {
            $company->update($validated);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui profil perusahaan. Silakan coba lagi.')->withInput();
        }

        return redirect()->route('hrd.company-profile.index')->with('success', 'Profil perusahaan berhasil diperbarui.');
    }
}
