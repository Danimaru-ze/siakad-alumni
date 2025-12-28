<?php

namespace App\Http\Controllers\Alumni;

use App\Http\Controllers\Controller;
use App\Models\JobVacancy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobBoardController extends Controller
{
    public function index(Request $request)
    {
        $query = JobVacancy::where('status', 'PUBLISHED')->with(['company', 'hrd']);

        // Filter by search keyword
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhereHas('company', function ($q_company) use ($search) {
                      $q_company->where('nama_perusahaan', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by location
        if ($request->filled('lokasi')) {
            $query->where('lokasi', 'like', '%' . $request->input('lokasi') . '%');
        }

        // Filter by minimum salary
        if ($request->filled('gaji_min')) {
            $query->where('kisaran_gaji_max', '>=', $request->input('gaji_min'));
        }

        // Filter by maximum salary
        if ($request->filled('gaji_max')) {
            $query->where('kisaran_gaji_min', '<=', $request->input('gaji_max'));
        }

        $jobs = $query->latest()->paginate(9)->withQueryString();

        return view('alumni.jobs.index', compact('jobs'));
    }

    /**
     * Display the specified resource.
     */
    public function show(JobVacancy $job)
    {
        // Pastikan hanya lowongan yang sudah di-publish yang bisa dilihat
        if ($job->status !== 'PUBLISHED') {
            abort(404);
        }

        // Eager load relasi untuk efisiensi
        $job->load(['company', 'hrd']);

        return view('alumni.jobs.show', compact('job'));
    }
}
