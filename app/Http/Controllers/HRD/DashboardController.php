<?php

namespace App\Http\Controllers\HRD;

use App\Http\Controllers\Controller;
use App\Models\AlumniProfile;
use App\Models\Company;
use App\Models\JobVacancy;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->role !== 'hrd') {
            abort(403);
        }
        
        // Eager load company profile
        $hrdProfile = $user->hrdProfile()->with('company')->first();
        if (!$hrdProfile) {
             return redirect()->route('hrd.company-profile.create')
                ->with('error', 'Lengkapi profil perusahaan dan HRD Anda terlebih dahulu.');
        }

        // Stats for the current HRD's company
        $companyId = $hrdProfile->company_id;
        $stats = [
            'total_jobs' => JobVacancy::where('company_id', $companyId)->count(),
            'active_jobs' => JobVacancy::where('company_id', $companyId)->where('status', 'approved')->count(),
            'pending_jobs' => JobVacancy::where('company_id', $companyId)->where('status', 'pending')->count(),
            'total_alumni' => AlumniProfile::count(),
        ];

        // Chart Data
        $jobsByStatus = JobVacancy::where('company_id', $companyId)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
        
        $alumniByProdi = Prodi::withCount(['mahasiswa' => function ($query) {
                $query->where('status', 'lulus');
            }])
            ->having('mahasiswa_count', '>', 0)
            ->orderBy('mahasiswa_count', 'desc')
            ->take(10)
            ->get(['nama', 'mahasiswa_count'])
            ->pluck('mahasiswa_count', 'nama')
            ->toArray();


        return view('hrd.dashboard.index', compact('user', 'stats', 'jobsByStatus', 'alumniByProdi'));
    }
}