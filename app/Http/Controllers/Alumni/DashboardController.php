<?php

namespace App\Http\Controllers\Alumni;

use App\Http\Controllers\Controller;
use App\Services\AkademikCalculationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected AkademikCalculationService $calculationService;

    public function __construct(AkademikCalculationService $calculationService)
    {
        $this->calculationService = $calculationService;
    }

    public function index()
    {
        $user = Auth::user();
        $alumni = $user->mahasiswa;

        if (!$alumni) {
            abort(403, 'Profil alumni tidak ditemukan.');
        }

        // =================================================================
        // !! MANUAL IPK CALCULATION & SAVE (BRUTE FORCE FIX) !!
        // =================================================================
        $nilaiList = \App\Models\Nilai::where('mahasiswa_id', $alumni->id)
            ->with('kelas.mataKuliah')
            ->get();

        if ($nilaiList->isEmpty()) {
            $alumni->ipk = 0;
            $alumni->total_sks = 0;
        } else {
            $totalSks = 0;
            $totalBobot = 0;
            $konversi = config('siakad.nilai_konversi');

            foreach ($nilaiList as $nilai) {
                if (!$nilai->kelas || !$nilai->kelas->mataKuliah) {
                    continue;
                }
                $sks = $nilai->kelas->mataKuliah->sks;
                $bobot = 0;
                foreach ($konversi as $k) {
                    if ($k['huruf'] === $nilai->nilai_huruf) {
                        $bobot = $k['bobot'];
                        break;
                    }
                }
                $totalSks += $sks;
                $totalBobot += ($sks * $bobot);
            }
            $ipk = $totalSks > 0 ? round($totalBobot / $totalSks, 2) : 0;

            $alumni->ipk = $ipk;
            $alumni->total_sks = $totalSks;
        }
        
        $alumni->save();
        // =================================================================

        $alumni->load(['prodi.fakultas']);
        
        // Data untuk view, sekarang menggunakan nilai yang baru disimpan
        $ipkData = ['ipk' => $alumni->ipk, 'total_sks' => $alumni->total_sks];
        $gradeDistribution = $this->calculationService->getGradeDistribution($alumni);

        // Greeting
        $hour = now()->hour;
        if ($hour < 11) { $greeting = 'Selamat Pagi'; }
        elseif ($hour < 15) { $greeting = 'Selamat Siang'; }
        elseif ($hour < 18) { $greeting = 'Selamat Sore'; }
        else { $greeting = 'Selamat Malam'; }

        return view('alumni.dashboard.index', compact(
            'user', 'alumni', 'ipkData', 'gradeDistribution', 'greeting'
        ));
    }
}
