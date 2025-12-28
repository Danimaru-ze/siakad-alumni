<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\JobVacancy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobReferenceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->role !== 'dosen') {
            abort(403);
        }

        $jobs = JobVacancy::forUser($user)->with('company')->latest()->paginate(20);

        return view('dosen.jobs.index', compact('jobs'));
    }
}
