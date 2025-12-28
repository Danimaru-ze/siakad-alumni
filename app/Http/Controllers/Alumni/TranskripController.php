<?php

namespace App\Http\Controllers\Alumni;

use App\Http\Controllers\Controller;
use App\Services\AkademikCalculationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TranskripController extends Controller
{
    protected AkademikCalculationService $calculationService;

    public function __construct(AkademikCalculationService $calculationService)
    {
        $this->calculationService = $calculationService;
    }
    
    public function index()
    {
        $mahasiswa = Auth::user()->mahasiswa;

        if (!$mahasiswa) {
            abort(403, 'Profil alumni tidak ditemukan.');
        }

        $transcriptData = $this->calculationService->getTranscript($mahasiswa);
        $ipsHistory = $this->calculationService->getIPSHistory($mahasiswa);
        $gradeDistribution = $this->calculationService->getGradeDistribution($mahasiswa);
        
        // Calculate max SKS for consistency with the view, though it might not be relevant for alumni
        $semestersWithGrades = $ipsHistory->filter(fn($s) => $s['ips'] > 0);
        $lastIps = $semestersWithGrades->last()['ips'] ?? 0;
        $maxSks = $this->calculationService->getMaxSKS($lastIps);
        
        // The view expects a variable named 'transcript'
        $transcript = $transcriptData;

        return view('mahasiswa.transkrip.index', compact(
            'transcript', 'ipsHistory', 'gradeDistribution', 'maxSks', 'mahasiswa'
        ));
    }

    public function export()
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;
        
        if (!$mahasiswa) {
            abort(403, 'Unauthorized');
        }

        $mahasiswa->load(['prodi.fakultas']);

        // Get all nilai
        $nilaiList = \App\Models\Nilai::where('mahasiswa_id', $mahasiswa->id)
            ->with('kelas.mataKuliah')
            ->get()
            ->sortBy('kelas.mataKuliah.kode_mk');

        // Calculate IPK
        $ipkData = $this->calculationService->calculateIPK($mahasiswa);

        // Get grade distribution
        $gradeDistribution = $this->calculationService->getGradeDistribution($mahasiswa);

        return view('mahasiswa.export.transkrip', compact('mahasiswa', 'nilaiList', 'ipkData', 'gradeDistribution'));
    }
}
