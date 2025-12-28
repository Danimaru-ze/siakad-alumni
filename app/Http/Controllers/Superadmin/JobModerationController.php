<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\JobVacancy;
use App\Services\JobVacancyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobModerationController extends Controller
{
    protected JobVacancyService $jobVacancyService;

    public function __construct(JobVacancyService $jobVacancyService)
    {
        $this->jobVacancyService = $jobVacancyService;
    }

    public function index()
    {
        $user = Auth::user();
        if ($user->role !== 'superadmin') {
            abort(403);
        }
        
        $jobs = JobVacancy::forUser($user)->with(['company', 'hrd'])->latest()->paginate(20);
        
        return view('superadmin.jobs.index', compact('jobs'));
    }

    public function show(JobVacancy $job)
    {
        $job->load(['company', 'hrd']);
        return view('admin.jobs.show', compact('job'));
    }

    public function approve(JobVacancy $job)
    {
        $user = Auth::user();
        if ($user->role !== 'superadmin') {
            abort(403);
        }

        $this->jobVacancyService->approve($job, $user);

        return redirect()->route('superadmin.jobs.index')->with('success', 'Job approved successfully.');
    }

    public function reject(Request $request, JobVacancy $job)
    {
        $user = Auth::user();
        if ($user->role !== 'superadmin') {
            abort(403);
        }

        $request->validate(['reason' => 'nullable|string|max:500']);
        
        $this->jobVacancyService->reject($job, $user, $request->input('reason'));

        return redirect()->route('superadmin.jobs.index')->with('success', 'Job rejected successfully.');
    }
    
    public function destroy(JobVacancy $job)
    {
        $user = Auth::user();
        if ($user->role !== 'superadmin') {
            abort(403);
        }

        $this->jobVacancyService->delete($job);

        return redirect()->route('superadmin.jobs.index')->with('success', 'Job deleted successfully.');
    }
}
