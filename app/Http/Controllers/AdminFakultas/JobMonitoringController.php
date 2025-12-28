<?php

namespace App\Http\Controllers\AdminFakultas;

use App\Http\Controllers\Controller;
use App\Models\JobVacancy;
use App\Services\JobVacancyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobMonitoringController extends Controller
{
    protected JobVacancyService $jobVacancyService;

    public function __construct(JobVacancyService $jobVacancyService)
    {
        $this->jobVacancyService = $jobVacancyService;
    }

    public function index()
    {
        $user = Auth::user();
        if ($user->role !== 'admin_fakultas') {
            abort(403);
        }

        $jobs = JobVacancy::forUser($user)->with('company')->latest()->paginate(20);

        return view('admin_fakultas.jobs.index', compact('jobs'));
    }

    public function show(JobVacancy $job)
    {
        $job->load(['company', 'hrd']);
        return view('admin.jobs.show', compact('job'));
    }

    public function flag(Request $request, JobVacancy $job)
    {
        $user = Auth::user();
        if ($user->role !== 'admin_fakultas') {
            abort(403);
        }

        $request->validate(['reason' => 'required|string|max:500']);

        $this->jobVacancyService->flag($job, $user, $request->input('reason'));

        return redirect()->route('admin_fakultas.jobs.index')->with('success', 'Job flagged successfully.');
    }
}
