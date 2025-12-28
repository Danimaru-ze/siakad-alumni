<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fakultas;
use App\Models\Prodi;
use App\Models\TracerStudy;
use App\Models\TracerQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Exports\TracerResultExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class TracerStudyController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = TracerStudy::with('fakultas')->withCount('responses', 'questions');

        if (!$user->isSuperAdmin()) {
            $query->where('fakultas_id', $user->fakultas_id);
        }

        $tracerStudies = $query->latest()->paginate(15);

        return view('admin.tracer.index', compact('tracerStudies'));
    }

    public function create(Request $request)
    {
        $user = $request->user();
        
        $prodiQuery = Prodi::query();
        $fakultasQuery = Fakultas::query();
        $allProdi = Prodi::select(['id', 'nama', 'fakultas_id'])->get();

        if (!$user->isSuperAdmin()) {
            $prodiQuery->where('fakultas_id', $user->fakultas_id);
            $fakultasQuery->where('id', $user->fakultas_id);
        }

        $prodi = $prodiQuery->with('fakultas')->get();
        $fakultas = $fakultasQuery->with('prodi')->get();
        
        return view('admin.tracer.create', compact('prodi', 'fakultas', 'allProdi'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'target_angkatan' => 'required_unless:target_semua_angkatan,on|nullable|numeric|digits:4',
            'target_semua_angkatan' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'target_type' => 'required|in:all,fakultas,prodi,faculty_all',
            'fakultas_id' => 'required_if:target_type,fakultas|nullable|exists:fakultas,id',
            'prodi_ids' => 'required_if:target_type,prodi|nullable|array',
            'prodi_ids.*' => 'exists:prodi,id',
            'questions' => 'required|array|min:1',
            'questions.*.pertanyaan' => 'required|string|max:1000',
            'questions.*.tipe' => 'required|in:short_text,long_text,radio,checkbox,dropdown',
            'questions.*.pilihan' => 'nullable|string',
            'questions.*.wajib_diisi' => 'boolean',
        ]);
        
        $fakultasId = null;
        $prodiIds = null;

        if ($user->isSuperAdmin()) {
            if ($validated['target_type'] === 'fakultas') {
                $fakultasId = $validated['fakultas_id'];
                $prodiIds = Prodi::where('fakultas_id', $fakultasId)->pluck('id')->toArray();
            } elseif ($validated['target_type'] === 'prodi') {
                $prodiIds = $validated['prodi_ids'];
            }
        } else { // Admin Fakultas
            $fakultasId = $user->fakultas_id;
            if ($validated['target_type'] === 'prodi') {
                $allowedProdi = Prodi::where('fakultas_id', $fakultasId)->pluck('id');
                $prodiIds = collect($validated['prodi_ids'])->intersect($allowedProdi)->toArray();
            } else { // faculty_all
                $prodiIds = Prodi::where('fakultas_id', $fakultasId)->pluck('id')->toArray();
            }
        }
        
        if (isset($validated['target_semua_angkatan']) && $validated['target_semua_angkatan'] === 'on') {
            $validated['target_angkatan'] = null;
        }

        DB::transaction(function () use ($validated, $user, $fakultasId, $prodiIds) {
            $tracerStudy = TracerStudy::create([
                'nama' => $validated['nama'],
                'deskripsi' => $validated['deskripsi'],
                'target_angkatan' => $validated['target_angkatan'],
                'tanggal_mulai' => $validated['tanggal_mulai'],
                'tanggal_selesai' => $validated['tanggal_selesai'],
                'created_by' => $user->id,
                'fakultas_id' => $fakultasId,
                'target_prodi_ids' => $prodiIds,
            ]);

            foreach ($validated['questions'] as $index => $qData) {
                $options = null;
                if (in_array($qData['tipe'], ['radio', 'checkbox', 'dropdown']) && !empty($qData['pilihan'])) {
                    $options = array_map('trim', explode(PHP_EOL, $qData['pilihan']));
                }

                TracerQuestion::create([
                    'tracer_study_id' => $tracerStudy->id,
                    'pertanyaan' => $qData['pertanyaan'],
                    'tipe' => $qData['tipe'],
                    'pilihan' => $options,
                    'wajib_diisi' => $qData['wajib_diisi'] ?? false,
                    'urutan' => $index + 1,
                ]);
            }
        });

        return redirect()->route('admin.tracer.index')->with('success', 'Tracer study berhasil dibuat.');
    }

    public function edit(Request $request, TracerStudy $tracer)
    {
        $user = $request->user();
        $tracer->load('questions');
        
        $prodiQuery = Prodi::query();
        $fakultasQuery = Fakultas::query();
        $allProdi = Prodi::select(['id', 'nama', 'fakultas_id'])->get();

        if (!$user->isSuperAdmin()) {
            $prodiQuery->where('fakultas_id', $user->fakultas_id);
            $fakultasQuery->where('id', $user->fakultas_id);
        }

        $prodi = $prodiQuery->with('fakultas')->get();
        $fakultas = $fakultasQuery->with('prodi')->get();

        return view('admin.tracer.edit', compact('tracer', 'prodi', 'fakultas', 'allProdi'));
    }

    public function update(Request $request, TracerStudy $tracer)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'target_angkatan' => 'required_unless:target_semua_angkatan,on|nullable|numeric|digits:4',
            'target_semua_angkatan' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'target_type' => 'required|in:all,fakultas,prodi,faculty_all',
            'fakultas_id' => 'required_if:target_type,fakultas|nullable|exists:fakultas,id',
            'prodi_ids' => 'required_if:target_type,prodi|nullable|array',
            'prodi_ids.*' => 'exists:prodi,id',
            'questions' => 'required|array|min:1',
            'questions.*.id' => 'nullable|exists:tracer_questions,id',
            'questions.*.pertanyaan' => 'required|string|max:1000',
            'questions.*.tipe' => 'required|in:short_text,long_text,radio,checkbox,dropdown',
            'questions.*.pilihan' => 'nullable|string',
            'questions.*.wajib_diisi' => 'boolean',
        ]);
        
        $fakultasId = null;
        $prodiIds = null;

        if ($user->isSuperAdmin()) {
             if ($validated['target_type'] === 'fakultas') {
                $fakultasId = $validated['fakultas_id'];
                $prodiIds = Prodi::where('fakultas_id', $fakultasId)->pluck('id')->toArray();
            } elseif ($validated['target_type'] === 'prodi') {
                $prodiIds = $validated['prodi_ids'];
            }
        } else { // Admin Fakultas
            $fakultasId = $user->fakultas_id;
            if ($validated['target_type'] === 'prodi') {
                $allowedProdi = Prodi::where('fakultas_id', $fakultasId)->pluck('id');
                $prodiIds = collect($validated['prodi_ids'])->intersect($allowedProdi)->toArray();
            } else { // faculty_all
                $prodiIds = Prodi::where('fakultas_id', $fakultasId)->pluck('id')->toArray();
            }
        }
        
        if (isset($validated['target_semua_angkatan']) && $validated['target_semua_angkatan'] === 'on') {
            $validated['target_angkatan'] = null;
        }

        DB::transaction(function () use ($tracer, $validated, $fakultasId, $prodiIds) {
            $tracer->update([
                'nama' => $validated['nama'],
                'deskripsi' => $validated['deskripsi'],
                'target_angkatan' => $validated['target_angkatan'],
                'tanggal_mulai' => $validated['tanggal_mulai'],
                'tanggal_selesai' => $validated['tanggal_selesai'],
                'fakultas_id' => $fakultasId,
                'target_prodi_ids' => $prodiIds,
            ]);

            $existingQuestionIds = $tracer->questions->pluck('id')->toArray();
            $submittedQuestionIds = [];

            foreach ($validated['questions'] as $index => $qData) {
                $options = null;
                if (in_array($qData['tipe'], ['radio', 'checkbox', 'dropdown']) && !empty($qData['pilihan'])) {
                    $options = array_map('trim', explode(PHP_EOL, $qData['pilihan']));
                }
                
                $question = TracerQuestion::updateOrCreate(
                    ['id' => $qData['id'] ?? null],
                    [
                        'tracer_study_id' => $tracer->id,
                        'pertanyaan' => $qData['pertanyaan'],
                        'tipe' => $qData['tipe'],
                        'pilihan' => $options,
                        'wajib_diisi' => $qData['wajib_diisi'] ?? false,
                        'urutan' => $index + 1,
                    ]
                );
                $submittedQuestionIds[] = $question->id;
            }

            $questionsToDelete = array_diff($existingQuestionIds, $submittedQuestionIds);
            TracerQuestion::whereIn('id', $questionsToDelete)->delete();
        });

        return redirect()->route('admin.tracer.index')->with('success', 'Tracer study berhasil diperbarui.');
    }

    public function destroy(TracerStudy $tracer)
    {
        $tracer->delete();
        return redirect()->route('admin.tracer.index')->with('success', 'Tracer study berhasil dihapus.');
    }

    public function results(TracerStudy $tracer)
    {
        $tracer->load([
            'questions.answers', 
            'responses.user.mahasiswa.prodi'
        ]);

        $questionResults = [];
        foreach ($tracer->questions as $question) {
            $stats = [];
            $answers = $question->answers;

            if (in_array($question->tipe, ['radio', 'dropdown'])) {
                $stats = $answers->countBy('jawaban');
            } elseif ($question->tipe == 'checkbox') {
                $allPilihan = $answers->pluck('jawaban')->map(fn($j) => json_decode($j, true))->flatten()->filter();
                $stats = $allPilihan->countBy();
            } elseif (in_array($question->tipe, ['short_text', 'long_text'])) {
                $stats = $answers->pluck('jawaban')->filter()->all();
            }
            
            $questionResults[$question->id] = [
                'pertanyaan' => $question->pertanyaan,
                'tipe' => $question->tipe,
                'pilihan' => $question->pilihan,
                'stats' => $stats,
                'total_menjawab' => $answers->count(),
            ];
        }

        $totalResponses = $tracer->responses->count();
        
        // Logic to get total targeted alumni (example, might need refinement)
        $totalTargets = \App\Models\Mahasiswa::where(function($query) use ($tracer) {
            if ($tracer->target_angkatan) {
                $query->where('angkatan', $tracer->target_angkatan);
            }
            if ($tracer->target_prodi_ids) {
                $query->whereIn('prodi_id', $tracer->target_prodi_ids);
            }
        })->where('status', 'LULUS')->count();

        $responseRate = $totalTargets > 0 ? ($totalResponses / $totalTargets) * 100 : 0;

        return view('admin.tracer.results', compact(
            'tracer', 
            'questionResults', 
            'totalResponses', 
            'totalTargets', 
            'responseRate'
        ));
    }

    public function export(TracerStudy $tracer)
    {
        $fileName = 'hasil-tracer-' . Str::slug($tracer->nama) . '.xlsx';
        return Excel::download(new TracerResultExport($tracer), $fileName);
    }
}