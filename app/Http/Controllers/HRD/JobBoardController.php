<?php

namespace App\Http\Controllers\HRD;

use App\Http\Controllers\Controller;
use App\Models\JobVacancy;
use Illuminate\Http\Request;

class JobBoardController extends Controller
{
    /**
     * Display a listing of the published jobs for comparison.
     */
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

        $jobs = $query->latest()->paginate(10)->withQueryString();

        return view('hrd.job-board.index', compact('jobs'));
    }

    /**
     * Display the specified job vacancy.
     */
    public function show(JobVacancy $job)
    {
        if ($job->status !== 'PUBLISHED') {
            abort(404, 'Lowongan ini tidak tersedia atau belum dipublikasikan.');
        }

        $job->load(['company', 'hrd']);

        return view('hrd.job-board.show', compact('job'));
    }
}
