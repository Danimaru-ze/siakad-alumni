<?php

namespace App\Http\Controllers\Cv;

use App\Http\Controllers\Controller;
use App\Models\CvCertification;
use App\Models\CvEducation;
use App\Models\CvExperience;
use App\Models\CvProfile;
use App\Models\CvProject;
use App\Models\CvSkill;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CvBuilderController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Eager load all CV related data
        $cvProfile = CvProfile::firstOrCreate(['user_id' => $user->id]);
        $experiences = CvExperience::where('user_id', $user->id)->orderBy('start_date', 'desc')->get();
        $educations = CvEducation::where('user_id', $user->id)->orderBy('start_date', 'desc')->get();
        $skills = CvSkill::where('user_id', $user->id)->get();
        $certifications = CvCertification::where('user_id', $user->id)->orderBy('issue_date', 'desc')->get();
        $projects = CvProject::where('user_id', $user->id)->orderBy('start_date', 'desc')->get();

        // Get Academic Data from Siakad if available
        $mahasiswa = $user->mahasiswa; // Assuming relationship exists

        return view('cv.builder', compact(
            'user', 'mahasiswa', 'cvProfile', 
            'experiences', 'educations', 'skills', 
            'certifications', 'projects'
        ));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'professional_summary' => 'nullable|string',
            'linkedin_url' => 'nullable|url',
            'phone_number' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        CvProfile::updateOrCreate(
            ['user_id' => Auth::id()],
            $request->only(['professional_summary', 'linkedin_url', 'portfolio_url', 'phone_number', 'address'])
        );

        return redirect()->back()->with('success', 'Profil CV berhasil diperbarui.');
    }

    public function storeExperience(Request $request)
    {
        $request->validate([
            'company' => 'required|string',
            'role' => 'required|string',
            'start_date' => 'required|date',
            'description' => 'required|string',
        ]);

        CvExperience::create([
            'user_id' => Auth::id(),
            'company' => $request->company,
            'role' => $request->role,
            'location' => $request->location,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_current' => $request->has('is_current'),
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Pengalaman berhasil ditambahkan.');
    }

    public function destroyExperience($id)
    {
        $exp = CvExperience::where('user_id', Auth::id())->where('id', $id)->firstOrFail();
        $exp->delete();
        return redirect()->back()->with('success', 'Pengalaman dihapus.');
    }

    public function storeSkill(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        CvSkill::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'category' => $request->category ?? 'Hard Skills',
            'level' => $request->level,
        ]);

        return redirect()->back()->with('success', 'Skill berhasil ditambahkan.');
    }

    public function destroySkill($id)
    {
        $skill = CvSkill::where('user_id', Auth::id())->where('id', $id)->firstOrFail();
        $skill->delete();
        return redirect()->back()->with('success', 'Skill dihapus.');
    }

    public function storeEducation(Request $request)
    {
        $request->validate([
            'institution' => 'required|string',
            'degree' => 'required|string',
            'start_date' => 'required|date',
        ]);

        CvEducation::create([
            'user_id' => Auth::id(),
            'institution' => $request->institution,
            'degree' => $request->degree,
            'field_of_study' => $request->field_of_study,
            'location' => $request->location,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_current' => $request->has('is_current'),
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Pendidikan berhasil ditambahkan.');
    }

    public function destroyEducation($id)
    {
        $edu = CvEducation::where('user_id', Auth::id())->where('id', $id)->firstOrFail();
        $edu->delete();
        return redirect()->back()->with('success', 'Pendidikan dihapus.');
    }

    public function generate(Request $request)
    {
        $template = $request->get('template', 'harvard'); // default harvard
        $user = Auth::user();
        
        $cvProfile = CvProfile::where('user_id', $user->id)->first();
        $experiences = CvExperience::where('user_id', $user->id)->orderBy('start_date', 'desc')->get();
        $educations = CvEducation::where('user_id', $user->id)->orderBy('start_date', 'desc')->get();
        $skills = CvSkill::where('user_id', $user->id)->get()->groupBy('category');
        $certifications = CvCertification::where('user_id', $user->id)->orderBy('issue_date', 'desc')->get();
        $projects = CvProject::where('user_id', $user->id)->orderBy('start_date', 'desc')->get();
        
        $mahasiswa = $user->mahasiswa;

        // Data array for view
        $data = compact('user', 'mahasiswa', 'cvProfile', 'experiences', 'educations', 'skills', 'certifications', 'projects');

        // Render PDF
        $pdf = Pdf::loadView('cv.templates.' . $template, $data);
        
        // Optional: Setup paper size based on template
        $pdf->setPaper('a4', 'portrait');

        return $pdf->stream('CV_' . $user->name . '.pdf');
    }
}