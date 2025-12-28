<?php

namespace App\Http\Controllers\HRD;

use App\Http\Controllers\Controller;
use App\Models\JobVacancy;
use App\Services\JobVacancyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    protected JobVacancyService $jobVacancyService;

    public function __construct(JobVacancyService $jobVacancyService)
    {
        $this->jobVacancyService = $jobVacancyService;
    }

    public function index()
    {
        $user = Auth::user();
        if ($user->role !== 'hrd') {
            abort(403);
        }

        $jobs = JobVacancy::forUser($user)->with('company')->latest()->paginate(20);

        return view('hrd.jobs.index', compact('jobs'));
    }

    public function create()
    {
        return view('hrd.jobs.create');
    }

    public function edit(JobVacancy $job)
    {
        $user = Auth::user();
        if ($user->role !== 'hrd' || $job->hrd_user_id !== $user->id) {
            abort(403);
        }
        
        return view('hrd.jobs.edit', compact('job'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'hrd') {
            abort(403);
        }
        
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'persyaratan' => 'required|string',
            'lokasi' => 'required|string|max:255',
            'tipe_pekerjaan' => 'required|string|max:255',
            'kisaran_gaji_min' => 'nullable|integer|min:0',
            'kisaran_gaji_max' => 'nullable|integer|min:0|gte:kisaran_gaji_min',
            'deadline_lamaran' => 'nullable|date',
        ]);

        try {
            $this->jobVacancyService->create($request->all(), $user);
        } catch (\App\Exceptions\HrdProfileNotCompleteException $e) {
            return redirect()->route('hrd.profile.create')->with('error', $e->getMessage());
        }

        return redirect()->route('hrd.jobs.index')->with('success', 'Job vacancy created successfully as a draft.');
    }

    public function update(Request $request, JobVacancy $job)
    {
        $user = Auth::user();
        if ($user->role !== 'hrd' || $job->hrd_user_id !== $user->id) {
            abort(403);
        }
        if (!in_array($job->status, ['DRAFT', 'REJECTED'])) {
            abort(403, 'Can only edit jobs with DRAFT or REJECTED status.');
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'persyaratan' => 'required|string',
            'lokasi' => 'required|string|max:255',
            'tipe_pekerjaan' => 'required|string|max:255',
            'kisaran_gaji_min' => 'nullable|integer|min:0',
            'kisaran_gaji_max' => 'nullable|integer|min:0|gte:kisaran_gaji_min',
            'deadline_lamaran' => 'nullable|date',
        ]);
        
        $this->jobVacancyService->update($job, $request->all());

        return redirect()->route('hrd.jobs.index')->with('success', 'Job vacancy updated successfully.');
    }

    public function destroy(JobVacancy $job)
    {
        $user = Auth::user();
        if ($user->role !== 'hrd' || $job->hrd_user_id !== $user->id) {
            abort(403);
        }

        $this->jobVacancyService->delete($job);

        return redirect()->route('hrd.jobs.index')->with('success', 'Job vacancy deleted successfully.');
    }

    public function submit(JobVacancy $job)
    {
        $user = Auth::user();
        if ($user->role !== 'hrd' || $job->hrd_user_id !== $user->id) {
            abort(403);
        }

        $this->jobVacancyService->submitForApproval($job);

        return redirect()->route('hrd.jobs.index')->with('success', 'Job vacancy submitted for approval.');
    }
}
