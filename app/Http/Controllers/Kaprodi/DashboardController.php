<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TahunAkademik;
use App\Models\Prodi;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Krs;
use App\Models\Nilai;
use App\Models\Pertemuan;
use App\Models\Presensi;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $dosen = $user->dosen;
        
        // Ensure the user is a dosen and is a kaprodi
        if (!$dosen || $dosen->prodi?->kaprodi_id !== $dosen->id) {
            // Using view() instead of abort() to provide a more graceful error page within the app layout
            return view('errors.kaprodi-not-assigned');
        }

        $prodi = $dosen->prodi;
        $activeTa = TahunAkademik::where('is_active', true)->first();

        // =====================================================================
        // SECTION 1: DATA UNTUK KAPRODI
        // =====================================================================

        // Initialize stats array
        $prodiStats = [
            'total_mahasiswa' => 0,
            'total_dosen' => 0,
            'rata_ipk' => 0,
            'kelas_aktif' => 0,
        ];
        
        if ($prodi) {
            $prodiStats['total_mahasiswa'] = Mahasiswa::where('prodi_id', $prodi->id)->where('status', 'aktif')->count();
            $prodiStats['total_dosen'] = Dosen::where('prodi_id', $prodi->id)->count();
            $prodiStats['rata_ipk'] = Mahasiswa::where('prodi_id', $prodi->id)->where('status', 'aktif')->avg('ipk') ?? 0;
            if ($activeTa) {
                $prodiStats['kelas_aktif'] = Kelas::where('tahun_akademik_id', $activeTa->id)
                                                  ->whereHas('mataKuliah', function ($query) use ($prodi) {
                                                      $query->where('prodi_id', $prodi->id);
                                                  })->count();
            }
        }

        $topMahasiswa = Mahasiswa::where('prodi_id', $prodi->id)->orderBy('ipk', 'desc')->take(5)->get();
        $warningMahasiswa = Mahasiswa::where('prodi_id', $prodi->id)->where('ipk', '<', 2.5)->orderBy('ipk', 'asc')->take(5)->get();
        $mahasiswaPerAngkatan = Mahasiswa::where('prodi_id', $prodi->id)
            ->select('angkatan', \DB::raw('count(*) as total'))
            ->groupBy('angkatan')
            ->orderBy('angkatan', 'desc')
            ->pluck('total', 'angkatan');
        
        $sebaranNilai = [];
        if($activeTa){
            $sebaranNilai = Nilai::join('kelas', 'nilai.kelas_id', '=', 'kelas.id')
                ->join('mata_kuliah', 'kelas.mata_kuliah_id', '=', 'mata_kuliah.id')
                ->where('mata_kuliah.prodi_id', $prodi->id)
                ->where('kelas.tahun_akademik_id', $activeTa->id)
                ->select('nilai_huruf', \DB::raw('count(*) as total'))
                ->groupBy('nilai_huruf')
                ->pluck('total', 'nilai_huruf');
        }
        

        // =====================================================================
        // SECTION 2: DATA UNTUK DOSEN (DARI DOSEN DASHBOARD)
        // =====================================================================
        
        $kelasListQuery = Kelas::where('dosen_id', $dosen->id)
            ->with(['mataKuliah', 'krsDetail.krs.mahasiswa', 'jadwal']);
        
        if ($activeTa) {
            $kelasListQuery->where(function($q) use ($activeTa) {
                $q->where('tahun_akademik_id', $activeTa->id)
                  ->orWhereNull('tahun_akademik_id');
            });
        }
        
        $kelasList = $kelasListQuery->get();

        // Stats
        $totalKelas = $kelasList->count();
        $totalMahasiswa = $kelasList->sum(fn($k) => $k->krsDetail->count());
        
        // Get total nilai yang sudah diinput
        $nilaiDiinput = Nilai::whereIn('kelas_id', $kelasList->pluck('id'))
            ->whereNotNull('nilai_angka')
            ->count();
        $totalNilai = $kelasList->sum(fn($k) => $k->krsDetail->count());
        $persentaseNilai = $totalNilai > 0 ? round(($nilaiDiinput / $totalNilai) * 100) : 0;

        // Presensi stats
        $pertemuanList = Pertemuan::whereHas('jadwalKuliah', fn($q) => $q->whereIn('kelas_id', $kelasList->pluck('id')))->get();
        $totalPertemuan = $pertemuanList->count();
        
        $presensiStats = Presensi::whereIn('pertemuan_id', $pertemuanList->pluck('id'))
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Mahasiswa bimbingan
        $mahasiswaBimbingan = $dosen->mahasiswaBimbingan()->with('user')->get();
        $pendingKrs = $mahasiswaBimbingan->isNotEmpty() 
            ? Krs::whereIn('mahasiswa_id', $mahasiswaBimbingan->pluck('id'))
                ->where('status', 'pending')
                ->count()
            : 0;

        // Recent activities (recent nilai input)
        $recentNilai = Nilai::whereIn('kelas_id', $kelasList->pluck('id'))
            ->whereNotNull('nilai_angka')
            ->with(['kelas.mataKuliah', 'mahasiswa.user'])
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        // Kelas dengan jadwal hari ini
        $hariMapping = [
            'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu', 'Sunday' => 'Minggu'
        ];
        $hariIni = $hariMapping[now()->format('l')] ?? now()->format('l');
        
        $kelasHariIni = $kelasList->filter(function ($kelas) use ($hariIni) {
            return $kelas->jadwal->contains('hari', $hariIni);
        });

        // Menggabungkan variabel mahasiswaBimbinganCount dari kaprodi dengan data yang lebih lengkap
        $mahasiswaBimbinganCount = $mahasiswaBimbingan->count();

        return view('kaprodi.dashboard.index', compact(
            // Data Kaprodi
            'prodi', 
            'activeTa', 
            'prodiStats',
            'topMahasiswa',
            'warningMahasiswa',
            'mahasiswaPerAngkatan',
            'sebaranNilai',
            
            // Data Dosen
            'dosen',
            'kelasList', 
            'totalKelas', 
            'totalMahasiswa',
            'nilaiDiinput', 
            'totalNilai', 
            'persentaseNilai', 
            'totalPertemuan',
            'presensiStats', 
            'mahasiswaBimbingan', 
            'pendingKrs', 
            'recentNilai',
            'kelasHariIni',
            'hariIni',
            'mahasiswaBimbinganCount'
        ));
    }
}
