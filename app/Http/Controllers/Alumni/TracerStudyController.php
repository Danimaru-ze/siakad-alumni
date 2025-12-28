<?php

namespace App\Http\Controllers\Alumni;

use App\Http\Controllers\Controller;
use App\Models\TracerStudy;
use App\Models\TracerResponse;
use App\Models\TracerAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TracerStudyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;

        if (!$mahasiswa) {
            abort(404, 'Data alumni tidak ditemukan.');
        }

        // Get all potentially relevant studies first
        $potentialStudies = TracerStudy::where(function ($query) use ($mahasiswa) {
            $query->where('target_angkatan', $mahasiswa->angkatan)
                  ->orWhereNull('target_angkatan');
        })
            ->where('tanggal_mulai', '<=', now())
            ->where('tanggal_selesai', '>=', now())
            ->get();

        // Filter studies at the application level to ensure correct type handling
        $studies = $potentialStudies->filter(function ($study) use ($mahasiswa) {
            // Condition 1: Target is "All Alumni" (no prodi specified)
            if (is_null($study->target_prodi_ids) || empty($study->target_prodi_ids)) {
                return true;
            }

            // Condition 2: Target is specific prodi(s)
            // in_array is used for loose comparison (e.g., 2 == "2")
            if (is_array($study->target_prodi_ids) && in_array($mahasiswa->prodi_id, $study->target_prodi_ids)) {
                return true;
            }
            
            return false;
        });
        
        // Eager load the responses for the matched studies
        $studies->load(['responses' => function ($query) use ($user) {
            $query->where('user_id', $user->id);
        }]);
        
        return view('alumni.tracer.index', compact('studies'));
    }

    /**
     * Show the form for completing the specified tracer study.
     */
    public function show(TracerStudy $tracer)
    {
        $user = Auth::user();
        
        // Ensure the user is a target of this study
        // (This logic could be improved and moved to a policy)
        if (($tracer->target_angkatan !== null && $tracer->target_angkatan != $user->mahasiswa->angkatan) || 
            ($tracer->target_prodi_ids && !in_array($user->mahasiswa->prodi_id, $tracer->target_prodi_ids))) {
            abort(403, 'Anda bukan target dari tracer study ini.');
        }

        // Check if already submitted
        $response = $tracer->responses()->where('user_id', $user->id)->first();
        if ($response && $response->status === 'SUDAH_MENGISI') {
             return redirect()->route('alumni.tracer.index')->with('success', 'Anda sudah menyelesaikan tracer study ini. Terima kasih!');
        }

        $tracer->load('questions');
        
        return view('alumni.tracer.show', compact('tracer'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $tracerId)
    {
        $tracer = TracerStudy::findOrFail($tracerId);
        $user = Auth::user();
        $questions = $tracer->questions;

        // Build validation rules dynamically
        $rules = [];
        foreach ($questions as $question) {
            if ($question->wajib_diisi) {
                $rules['answers.' . $question->id] = 'required';
            }
        }
        
        $request->validate($rules, ['answers.*.required' => 'Pertanyaan ini wajib diisi.']);

        DB::transaction(function () use ($request, $tracer, $user, $questions) {
            $response = TracerResponse::firstOrCreate(
                ['tracer_study_id' => $tracer->id, 'user_id' => $user->id]
            );

            foreach ($questions as $question) {
                if ($request->has('answers.' . $question->id)) {
                    $answerData = $request->input('answers.' . $question->id);
                    TracerAnswer::updateOrCreate(
                        ['response_id' => $response->id, 'question_id' => $question->id],
                        ['jawaban' => is_array($answerData) ? json_encode($answerData) : $answerData]
                    );
                }
            }

            $response->update([
                'status' => 'SUDAH_MENGISI',
                'submitted_at' => now(),
            ]);
        });
        
        return redirect()->route('alumni.tracer.index')->with('success', 'Terima kasih telah menyelesaikan tracer study!');
    }
}