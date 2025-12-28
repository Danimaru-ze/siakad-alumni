<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\FakultasController;
use App\Http\Controllers\Admin\ProdiController;
use App\Http\Controllers\Admin\MataKuliahController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\TahunAkademikController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KrsApprovalController;
use App\Http\Controllers\Admin\MahasiswaController as AdminMahasiswaController;
use App\Http\Controllers\Admin\DosenController;
use App\Http\Controllers\Mahasiswa\KrsController;
use App\Http\Controllers\Mahasiswa\TranskripController;
use App\Http\Controllers\Dosen\PenilaianController;
use App\Http\Controllers\HealthController;

// Job Module Controllers
use App\Http\Controllers\Superadmin\JobModerationController;
use App\Http\Controllers\AdminFakultas\JobMonitoringController;
use App\Http\Controllers\Dosen\JobReferenceController;
use App\Http\Controllers\HRD\JobController as HrdJobController;
use App\Http\Controllers\Alumni\JobBoardController;
use App\Http\Controllers\HRD\DashboardController as HrdDashboardController;
use App\Http\Controllers\Alumni\DashboardController as AlumniDashboardController;
use App\Http\Controllers\Alumni\BiodataController as AlumniBiodataController;
use App\Http\Controllers\Alumni\TranskripController as AlumniTranskripController;
use App\Http\Controllers\Alumni\KhsController as AlumniKhsController;
use App\Http\Controllers\Alumni\PresensiController as AlumniPresensiController;
use App\Http\Controllers\HRD\ProfileController as HrdProfileController;
use App\Http\Controllers\Admin\AlumniController as AdminAlumniController;
use App\Http\Controllers\HRD\AlumniController as HrdAlumniController;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\DB;

// Health check routes (no auth required)
Route::get('/health', [HealthController::class, 'index'])->name('health');
Route::get('/health/detailed', [HealthController::class, 'detailed'])->name('health.detailed');

// ======================================================================
// !! TEMPORARY ROUTE TO FIX EXISTING DATA !!
// This route will find all students with status 'lulus' but whose user role
// is not 'alumni', and update their role accordingly.
// This should be removed after being run once.
// ======================================================================
Route::get('/sync-alumni-roles-once', function () {
    if (!Auth::check() || !in_array(Auth::user()->role, ['superadmin', 'admin_fakultas'])) {
        abort(403, 'Hanya admin yang dapat mengakses halaman ini.');
    }

    $log = [];
    $updatedCount = 0;

    DB::beginTransaction();
    try {
        // Case 1: Mahasiswa is 'lulus' but User role is not 'alumni' -> FIX: set role to 'alumni'
        $graduatesToUpdate = Mahasiswa::where('status', 'lulus')->whereHas('user', function ($query) {
            $query->where('role', '!=', 'alumni');
        })->with('user')->get();

        foreach ($graduatesToUpdate as $mahasiswa) {
            $log[] = "SYNC LULUS -> ALUMNI: Updating User ID: {$mahasiswa->user->id} (NIM: {$mahasiswa->nim}). Role was '{$mahasiswa->user->role}', changing to 'alumni'.";
            $mahasiswa->user->role = 'alumni';
            $mahasiswa->user->save();
            $updatedCount++;
        }

        // Case 2: Mahasiswa is NOT 'lulus' but User role IS 'alumni' -> FIX: set role to 'mahasiswa'
        $alumniToRevert = Mahasiswa::where('status', '!=', 'lulus')->whereHas('user', function ($query) {
            $query->where('role', 'alumni');
        })->with('user')->get();

        foreach ($alumniToRevert as $mahasiswa) {
            $log[] = "SYNC ALUMNI -> MAHASISWA: Updating User ID: {$mahasiswa->user->id} (NIM: {$mahasiswa->nim}). Status is '{$mahasiswa->status}', changing role from 'alumni' to 'mahasiswa'.";
            $mahasiswa->user->role = 'mahasiswa';
            $mahasiswa->user->save();
            $updatedCount++;
        }

        DB::commit();
        
        if($updatedCount === 0) {
            $log[] = "Tidak ada data yang perlu disinkronisasi. Semua role sudah konsisten dengan status mahasiswa.";
        }

        return response()->json([
            'message' => "Proses sinkronisasi dua arah selesai. Total {$updatedCount} pengguna diperbarui.",
            'log' => $log,
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'message' => 'Terjadi kesalahan selama sinkronisasi.',
            'error' => $e->getMessage(),
        ], 500);
    }
});


Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('post-login');
    }

    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/unread-count', [\App\Http\Controllers\NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
    Route::post('/notifications/{notification}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
});

// --- HRD DASHBOARD ROUTE ---
Route::middleware(['auth', 'role:hrd'])->prefix('hrd')->name('hrd.')->group(function () {
    Route::get('/dashboard', [HrdDashboardController::class, 'index'])->name('dashboard');

    // HRD Biodata
    Route::get('/biodata', [\App\Http\Controllers\HRD\BiodataController::class, 'index'])->name('biodata.index');
    Route::put('/biodata', [\App\Http\Controllers\HRD\BiodataController::class, 'update'])->name('biodata.update');
    Route::put('/biodata/password', [\App\Http\Controllers\HRD\BiodataController::class, 'updatePassword'])->name('biodata.password');

    // HRD Company Profile Setup & Management
    Route::get('/company-profile/setup', [\App\Http\Controllers\HRD\CompanyProfileController::class, 'create'])->name('company-profile.create');
    Route::post('/company-profile', [\App\Http\Controllers\HRD\CompanyProfileController::class, 'store'])->name('company-profile.store');
    Route::get('/company-profile', [\App\Http\Controllers\HRD\CompanyProfileController::class, 'index'])->name('company-profile.index');
    Route::put('/company-profile', [\App\Http\Controllers\HRD\CompanyProfileController::class, 'update'])->name('company-profile.update');
});

// --- ALUMNI DASHBOARD ROUTE ---
Route::middleware(['auth', 'is.alumni'])->prefix('alumni')->name('alumni.')->group(function () {
    Route::get('/dashboard', [AlumniDashboardController::class, 'index'])->name('dashboard');
    Route::get('/biodata', [AlumniBiodataController::class, 'index'])->name('biodata.index');
    Route::put('/biodata', [AlumniBiodataController::class, 'update'])->name('biodata.update');
    Route::put('/biodata/password', [AlumniBiodataController::class, 'updatePassword'])->name('biodata.password');
    Route::get('/transkrip', [AlumniTranskripController::class, 'index'])->name('transkrip.index');
    Route::get('/export/transkrip', [AlumniTranskripController::class, 'export'])->name('export.transkrip');
    Route::get('/khs', [AlumniKhsController::class, 'index'])->name('khs.index');
    Route::get('/khs/{tahunAkademik}', [AlumniKhsController::class, 'show'])->name('khs.show');
    Route::get('/presensi', [AlumniPresensiController::class, 'index'])->name('presensi.index');
    Route::get('/presensi/{kelas}', [AlumniPresensiController::class, 'show'])->name('presensi.show');
    
    // Alumni Tracer Study
    // Definisikan secara manual untuk menghindari konflik
    Route::get('tracer', [\App\Http\Controllers\Alumni\TracerStudyController::class, 'index'])->name('tracer.index');
    Route::get('tracer/{tracer}', [\App\Http\Controllers\Alumni\TracerStudyController::class, 'show'])->name('tracer.show');
    Route::post('tracer/{tracer}', [\App\Http\Controllers\Alumni\TracerStudyController::class, 'store'])->name('tracer.store.submit');
});

// --- ADMIN ROUTES ---
Route::middleware(['auth', 'role:admin', 'fakultas.scope'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Users
    Route::post('/users', [UserController::class, 'store'])->name('users.store');

    // Master Data - Fakultas
    Route::get('/fakultas', [FakultasController::class, 'index'])->name('fakultas.index');
    Route::post('/fakultas', [FakultasController::class, 'store'])->name('fakultas.store');
    Route::put('/fakultas/{fakultas}', [FakultasController::class, 'update'])->name('fakultas.update');
    Route::delete('/fakultas/{fakultas}', [FakultasController::class, 'destroy'])->name('fakultas.destroy');

    // Master Data - Prodi
    Route::get('/prodi', [ProdiController::class, 'index'])->name('prodi.index');
    Route::post('/prodi', [ProdiController::class, 'store'])->name('prodi.store');
    Route::put('/prodi/{prodi}', [ProdiController::class, 'update'])->name('prodi.update');
    Route::delete('/prodi/{prodi}', [ProdiController::class, 'destroy'])->name('prodi.destroy');

    // Master Data - Mata Kuliah
    Route::get('/mata-kuliah/export', [MataKuliahController::class, 'export'])->name('mata-kuliah.export');
    Route::get('/mata-kuliah', [MataKuliahController::class, 'index'])->name('mata-kuliah.index');
    Route::post('/mata-kuliah', [MataKuliahController::class, 'store'])->name('mata-kuliah.store');
    Route::put('/mata-kuliah/{mataKuliah}', [MataKuliahController::class, 'update'])->name('mata-kuliah.update');
    Route::delete('/mata-kuliah/{mataKuliah}', [MataKuliahController::class, 'destroy'])->name('mata-kuliah.destroy');

    // Master Data - Kelas
    Route::get('/kelas', [KelasController::class, 'index'])->name('kelas.index');
    Route::post('/kelas', [KelasController::class, 'store'])->name('kelas.store');
    Route::put('/kelas/{kelas}', [KelasController::class, 'update'])->name('kelas.update');
    Route::delete('/kelas/{kelas}', [KelasController::class, 'destroy'])->name('kelas.destroy');

    // Master Data - Tahun Akademik
    Route::get('/tahun-akademik', [TahunAkademikController::class, 'index'])->name('tahun-akademik.index');
    Route::post('/tahun-akademik', [TahunAkademikController::class, 'store'])->name('tahun-akademik.store');
    Route::put('/tahun-akademik/{tahunAkademik}', [TahunAkademikController::class, 'update'])->name('tahun-akademik.update');
    Route::delete('/tahun-akademik/{tahunAkademik}', [TahunAkademikController::class, 'destroy'])->name('tahun-akademik.destroy');
    Route::get('/tahun-akademik/active', [TahunAkademikController::class, 'getActive'])->name('tahun-akademik.active');
    Route::post('/tahun-akademik/{tahunAkademik}/activate', [TahunAkademikController::class, 'activate'])->name('tahun-akademik.activate');

    // User Management
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');


    // KRS Approval (View Only - no approve/reject actions)
    Route::get('/krs-approval', [KrsApprovalController::class, 'index'])->name('krs-approval.index');
    Route::get('/krs-approval/{krs}', [KrsApprovalController::class, 'show'])->name('krs-approval.show');

    // Mahasiswa Management
    Route::get('/mahasiswa/export', [AdminMahasiswaController::class, 'export'])->name('mahasiswa.export');
    Route::get('/mahasiswa', [AdminMahasiswaController::class, 'index'])->name('mahasiswa.index');
    Route::post('/mahasiswa', [AdminMahasiswaController::class, 'store'])->name('mahasiswa.store');
    Route::get('/mahasiswa/{mahasiswa}', [AdminMahasiswaController::class, 'show'])->name('mahasiswa.show');
    Route::put('/mahasiswa/{mahasiswa}', [AdminMahasiswaController::class, 'update'])->name('mahasiswa.update');
    Route::delete('/mahasiswa/{mahasiswa}', [AdminMahasiswaController::class, 'destroy'])->name('mahasiswa.destroy');

    // Dosen Management
    Route::get('/dosen/export', [DosenController::class, 'export'])->name('dosen.export');
    Route::get('/dosen', [DosenController::class, 'index'])->name('dosen.index');
    Route::post('/dosen', [DosenController::class, 'store'])->name('dosen.store');
    Route::get('/dosen/{dosen}', [DosenController::class, 'show'])->name('dosen.show');
    Route::put('/dosen/{dosen}', [DosenController::class, 'update'])->name('dosen.update');
    Route::delete('/dosen/{dosen}', [DosenController::class, 'destroy'])->name('dosen.destroy');

    // HRD Management
    Route::resource('/hrd', \App\Http\Controllers\Admin\HrdController::class);

    // Ruangan Management
    Route::get('/ruangan', [\App\Http\Controllers\Admin\RuanganController::class, 'index'])->name('ruangan.index');
    Route::post('/ruangan', [\App\Http\Controllers\Admin\RuanganController::class, 'store'])->name('ruangan.store');
    Route::put('/ruangan/{ruangan}', [\App\Http\Controllers\Admin\RuanganController::class, 'update'])->name('ruangan.update');
    Route::delete('/ruangan/{ruangan}', [\App\Http\Controllers\Admin\RuanganController::class, 'destroy'])->name('ruangan.destroy');

    // Skripsi Management
    Route::get('/skripsi', [\App\Http\Controllers\Admin\SkripsiController::class, 'index'])->name('skripsi.index');
    Route::get('/skripsi/{skripsi}', [\App\Http\Controllers\Admin\SkripsiController::class, 'show'])->name('skripsi.show');
    Route::post('/skripsi/{skripsi}/assign-pembimbing', [\App\Http\Controllers\Admin\SkripsiController::class, 'assignPembimbing'])->name('skripsi.assign-pembimbing');
    Route::put('/skripsi/{skripsi}/status', [\App\Http\Controllers\Admin\SkripsiController::class, 'updateStatus'])->name('skripsi.update-status');
    Route::put('/skripsi/{skripsi}/nilai', [\App\Http\Controllers\Admin\SkripsiController::class, 'updateNilai'])->name('skripsi.update-nilai');

    // KP Management
    Route::get('/kp', [\App\Http\Controllers\Admin\KpController::class, 'index'])->name('kp.index');
    Route::get('/kp/{kp}', [\App\Http\Controllers\Admin\KpController::class, 'show'])->name('kp.show');
    Route::post('/kp/{kp}/assign-pembimbing', [\App\Http\Controllers\Admin\KpController::class, 'assignPembimbing'])->name('kp.assign-pembimbing');
    Route::put('/kp/{kp}/status', [\App\Http\Controllers\Admin\KpController::class, 'updateStatus'])->name('kp.update-status');
    Route::put('/kp/{kp}/nilai', [\App\Http\Controllers\Admin\KpController::class, 'updateNilai'])->name('kp.update-nilai');

    // Kehadiran Dosen
    Route::get('/kehadiran-dosen', [\App\Http\Controllers\Admin\KehadiranDosenController::class, 'index'])->name('kehadiran-dosen.index');
    Route::get('/kehadiran-dosen/{dosen}', [\App\Http\Controllers\Admin\KehadiranDosenController::class, 'show'])->name('kehadiran-dosen.show');

    // Alumni List
    Route::get('/alumni', [AdminAlumniController::class, 'index'])->name('alumni.index');

    // Tracer Study
    Route::resource('tracer', \App\Http\Controllers\Admin\TracerStudyController::class)->except(['show']);
    Route::get('/tracer/{tracer}/results', [\App\Http\Controllers\Admin\TracerStudyController::class, 'results'])->name('tracer.results');
    Route::get('/tracer/{tracer}/export', [\App\Http\Controllers\Admin\TracerStudyController::class, 'export'])->name('tracer.export');


    
});

// --- MAHASISWA ROUTES ---
Route::middleware(['auth', 'mahasiswa.aktif'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\Mahasiswa\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/krs', [KrsController::class, 'index'])->name('krs.index');
    Route::post('/krs', [KrsController::class, 'store'])->middleware('throttle:krs')->name('krs.store');
    Route::delete('/krs/{detailId}', [KrsController::class, 'destroy'])->middleware('throttle:krs')->name('krs.destroy');
    Route::post('/krs/submit', [KrsController::class, 'submit'])->middleware('throttle:krs')->name('krs.submit');
    Route::post('/krs/revise', [KrsController::class, 'revise'])->middleware('throttle:krs')->name('krs.revise');
    
    // Transkrip
    Route::get('/transkrip', [TranskripController::class, 'index'])->name('transkrip.index');
    
    // Biodata
    Route::get('/biodata', [\App\Http\Controllers\Mahasiswa\BiodataController::class, 'index'])->name('biodata.index');
    Route::put('/biodata', [\App\Http\Controllers\Mahasiswa\BiodataController::class, 'update'])->name('biodata.update');
    Route::put('/biodata/password', [\App\Http\Controllers\Mahasiswa\BiodataController::class, 'updatePassword'])->name('biodata.password');

    // Presensi
    Route::get('/presensi', [\App\Http\Controllers\Mahasiswa\PresensiController::class, 'index'])->name('presensi.index');
    Route::get('/presensi/{kelas}', [\App\Http\Controllers\Mahasiswa\PresensiController::class, 'show'])->name('presensi.show');

    // Jadwal Kuliah
    Route::get('/jadwal', [\App\Http\Controllers\Mahasiswa\JadwalController::class, 'index'])->name('jadwal.index');

    // KHS (Kartu Hasil Studi)
    Route::get('/khs', [\App\Http\Controllers\Mahasiswa\KhsController::class, 'index'])->name('khs.index');
    Route::get('/khs/{tahunAkademik}', [\App\Http\Controllers\Mahasiswa\KhsController::class, 'show'])->name('khs.show');

    // Export PDF
    Route::get('/export/transkrip', [\App\Http\Controllers\Mahasiswa\ExportController::class, 'transkrip'])->name('export.transkrip');
    Route::get('/export/khs/{tahunAkademik}', [\App\Http\Controllers\Mahasiswa\ExportController::class, 'khs'])->name('export.khs');

    // AI Academic Advisor
    Route::get('/ai-advisor', [\App\Http\Controllers\Mahasiswa\AiAdvisorController::class, 'index'])->name('ai-advisor.index');
    Route::post('/ai-advisor/chat', [\App\Http\Controllers\Mahasiswa\AiAdvisorController::class, 'chat'])->middleware('throttle:ai-chat')->name('ai-advisor.chat');

    // Skripsi
    Route::get('/skripsi', [\App\Http\Controllers\Mahasiswa\SkripsiController::class, 'index'])->name('skripsi.index');
    Route::get('/skripsi/create', [\App\Http\Controllers\Mahasiswa\SkripsiController::class, 'create'])->name('skripsi.create');
    Route::post('/skripsi', [\App\Http\Controllers\Mahasiswa\SkripsiController::class, 'store'])->name('skripsi.store');
    Route::post('/skripsi/bimbingan', [\App\Http\Controllers\Mahasiswa\SkripsiController::class, 'storeBimbingan'])->name('skripsi.bimbingan.store');

    // KP
    Route::get('/kp', [\App\Http\Controllers\Mahasiswa\KpController::class, 'index'])->name('kp.index');
    Route::get('/kp/create', [\App\Http\Controllers\Mahasiswa\KpController::class, 'create'])->name('kp.create');
    Route::post('/kp', [\App\Http\Controllers\Mahasiswa\KpController::class, 'store'])->name('kp.store');
    Route::post('/kp/logbook', [\App\Http\Controllers\Mahasiswa\KpController::class, 'storeLogbook'])->name('kp.logbook.store');

    // E-Learning (LMS)
    Route::get('/lms', [\App\Http\Controllers\Mahasiswa\LmsController::class, 'index'])->name('lms.index');

    // Materi Kuliah
    Route::get('/materi/{kelas}', [\App\Http\Controllers\Mahasiswa\MateriController::class, 'index'])->name('materi.index');
    Route::get('/materi/{kelas}/download/{materi}', [\App\Http\Controllers\Mahasiswa\MateriController::class, 'download'])->name('materi.download');

    // Tugas
    Route::get('/tugas/{kelas}', [\App\Http\Controllers\Mahasiswa\TugasController::class, 'index'])->name('tugas.index');
    Route::get('/tugas/{kelas}/{tugas}', [\App\Http\Controllers\Mahasiswa\TugasController::class, 'show'])->name('tugas.show');
    Route::post('/tugas/{kelas}/{tugas}/submit', [\App\Http\Controllers\Mahasiswa\TugasController::class, 'submit'])->name('tugas.submit');
    Route::get('/tugas/{kelas}/{tugas}/download', [\App\Http\Controllers\Mahasiswa\TugasController::class, 'download'])->name('tugas.download');
});

// --- DOSEN ROUTES ---
Route::middleware(['auth', 'role:dosen'])->prefix('dosen')->name('dosen.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\Dosen\DashboardController::class, 'index'])->name('dashboard');

    // Biodata
    Route::get('/biodata', [\App\Http\Controllers\Dosen\BiodataController::class, 'index'])->name('biodata.index');
    Route::put('/biodata', [\App\Http\Controllers\Dosen\BiodataController::class, 'update'])->name('biodata.update');
    Route::put('/biodata/password', [\App\Http\Controllers\Dosen\BiodataController::class, 'updatePassword'])->name('biodata.password');

    // E-Learning (LMS)
    Route::get('/lms', [\App\Http\Controllers\Dosen\LmsController::class, 'index'])->name('lms.index');

    // Bimbingan (Dosen PA)
    Route::get('/bimbingan', [\App\Http\Controllers\Dosen\BimbinganController::class, 'index'])->name('bimbingan.index');
    Route::get('/bimbingan/krs-approval', [\App\Http\Controllers\Dosen\BimbinganController::class, 'krsApproval'])->name('bimbingan.krs-approval');
    Route::get('/bimbingan/krs/{krs}', [\App\Http\Controllers\Dosen\BimbinganController::class, 'showKrs'])->name('bimbingan.krs-show');
    Route::post('/bimbingan/krs/{krs}/approve', [\App\Http\Controllers\Dosen\BimbinganController::class, 'approveKrs'])->name('bimbingan.krs-approve');
    Route::post('/bimbingan/krs/{krs}/reject', [\App\Http\Controllers\Dosen\BimbinganController::class, 'rejectKrs'])->name('bimbingan.krs-reject');

    // Presensi
    Route::get('/presensi', [\App\Http\Controllers\Dosen\PresensiController::class, 'index'])->name('presensi.index');
    Route::get('/presensi/kelas/{kelas}', [\App\Http\Controllers\Dosen\PresensiController::class, 'showKelas'])->name('presensi.kelas');
    Route::get('/presensi/kelas/{kelas}/pertemuan/create', [\App\Http\Controllers\Dosen\PresensiController::class, 'createPertemuan'])->name('presensi.pertemuan.create');
    Route::post('/presensi/kelas/{kelas}/pertemuan', [\App\Http\Controllers\Dosen\PresensiController::class, 'storePertemuan'])->name('presensi.pertemuan.store');
    Route::get('/presensi/pertemuan/{pertemuan}/input', [\App\Http\Controllers\Dosen\PresensiController::class, 'inputPresensi'])->name('presensi.input');
    Route::post('/presensi/pertemuan/{pertemuan}', [\App\Http\Controllers\Dosen\PresensiController::class, 'storePresensi'])->name('presensi.store');

    // Skripsi Bimbingan
    Route::get('/skripsi', [\App\Http\Controllers\Dosen\SkripsiController::class, 'index'])->name('skripsi.index');
    Route::get('/skripsi/{skripsi}', [\App\Http\Controllers\Dosen\SkripsiController::class, 'show'])->name('skripsi.show');
    Route::post('/skripsi/bimbingan/{bimbingan}/review', [\App\Http\Controllers\Dosen\SkripsiController::class, 'reviewBimbingan'])->name('skripsi.bimbingan.review');
    Route::put('/skripsi/{skripsi}/status', [\App\Http\Controllers\Dosen\SkripsiController::class, 'updateStatus'])->name('skripsi.update-status');

    // KP Bimbingan
    Route::get('/kp', [\App\Http\Controllers\Dosen\KpController::class, 'index'])->name('kp.index');
    Route::get('/kp/{kp}', [\App\Http\Controllers\Dosen\KpController::class, 'show'])->name('kp.show');
    Route::post('/kp/logbook/{logbook}/review', [\App\Http\Controllers\Dosen\KpController::class, 'reviewLogbook'])->name('kp.logbook.review');
    Route::put('/kp/{kp}/status', [\App\Http\Controllers\Dosen\KpController::class, 'updateStatus'])->name('kp.update-status');

    // Kehadiran
    Route::get('/kehadiran', [\App\Http\Controllers\Dosen\KehadiranController::class, 'index'])->name('kehadiran.index');
    Route::post('/kehadiran', [\App\Http\Controllers\Dosen\KehadiranController::class, 'store'])->name('kehadiran.store');
    Route::post('/kehadiran/{kehadiran}/checkout', [\App\Http\Controllers\Dosen\KehadiranController::class, 'checkout'])->name('kehadiran.checkout');
    // Penilaian
    Route::get('/penilaian', [\App\Http\Controllers\Dosen\PenilaianController::class, 'index'])->name('penilaian.index');
    Route::get('/penilaian/{kelas}', [\App\Http\Controllers\Dosen\PenilaianController::class, 'show'])->name('penilaian.show');
    Route::post('/penilaian/{kelas}', [\App\Http\Controllers\Dosen\PenilaianController::class, 'store'])->middleware('throttle:penilaian')->name('penilaian.store');

    // Materi Kuliah
    Route::get('/materi/{kelas}', [\App\Http\Controllers\Dosen\MateriController::class, 'index'])->name('materi.index');
    Route::post('/materi/{kelas}', [\App\Http\Controllers\Dosen\MateriController::class, 'store'])->name('materi.store');
    Route::put('/materi/{kelas}/{materi}', [\App\Http\Controllers\Dosen\MateriController::class, 'update'])->name('materi.update');
    Route::delete('/materi/{kelas}/{materi}', [\App\Http\Controllers\Dosen\MateriController::class, 'destroy'])->name('materi.destroy');
    Route::get('/materi/{kelas}/download/{materi}', [\App\Http\Controllers\Dosen\MateriController::class, 'download'])->name('materi.download');

    // Tugas
    Route::get('/tugas/{kelas}', [\App\Http\Controllers\Dosen\TugasController::class, 'index'])->name('tugas.index');
    Route::post('/tugas/{kelas}', [\App\Http\Controllers\Dosen\TugasController::class, 'store'])->name('tugas.store');
    Route::get('/tugas/{kelas}/{tugas}', [\App\Http\Controllers\Dosen\TugasController::class, 'show'])->name('tugas.show');
    Route::post('/tugas/{kelas}/submission/{submission}/grade', [\App\Http\Controllers\Dosen\TugasController::class, 'grade'])->name('tugas.grade');
    Route::post('/tugas/{kelas}/{tugas}/toggle', [\App\Http\Controllers\Dosen\TugasController::class, 'toggle'])->name('tugas.toggle');
    Route::delete('/tugas/{kelas}/{tugas}', [\App\Http\Controllers\Dosen\TugasController::class, 'destroy'])->name('tugas.destroy');
    Route::get('/tugas/{kelas}/submission/{submission}/download', [\App\Http\Controllers\Dosen\TugasController::class, 'downloadSubmission'])->name('tugas.submission.download');
});

// --- JOB VACANCY ROUTES ---

// Superadmin: Job Moderation
Route::middleware(['auth', 'role:superadmin'])->prefix('admin/jobs')->name('superadmin.jobs.')->group(function() {
    Route::get('/', [JobModerationController::class, 'index'])->name('index');
    Route::get('/{job}', [JobModerationController::class, 'show'])->name('show');
    Route::post('/{job}/approve', [JobModerationController::class, 'approve'])->name('approve');
    Route::post('/{job}/reject', [JobModerationController::class, 'reject'])->name('reject');
    Route::delete('/{job}', [JobModerationController::class, 'destroy'])->name('destroy');
});

// Admin Fakultas: Job Monitoring
Route::middleware(['auth', 'role:admin'])->prefix('admin/jobs-monitoring')->name('admin_fakultas.jobs.')->group(function() {
    Route::get('/', [JobMonitoringController::class, 'index'])->name('index');
    Route::get('/{job}', [JobMonitoringController::class, 'show'])->name('show');
    Route::post('/{job}/flag', [JobMonitoringController::class, 'flag'])->name('flag');
});



// Dosen: Job Reference
Route::middleware(['auth', 'role:dosen'])->prefix('dosen/jobs')->name('dosen.jobs.')->group(function() {
    Route::get('/', [JobReferenceController::class, 'index'])->name('index');
});

    // HRD: Job Management & Alumni
    Route::middleware(['auth', 'role:hrd', 'hrd.profile.complete'])->prefix('hrd')->name('hrd.')->group(function() {
        Route::get('/jobs', [HrdJobController::class, 'index'])->name('jobs.index');
        Route::get('/jobs/create', [HrdJobController::class, 'create'])->name('jobs.create');
        Route::post('/jobs', [HrdJobController::class, 'store'])->name('jobs.store');
        Route::get('/jobs/{job}/edit', [HrdJobController::class, 'edit'])->name('jobs.edit');
        Route::put('/jobs/{job}', [HrdJobController::class, 'update'])->name('jobs.update');
        Route::delete('/jobs/{job}', [HrdJobController::class, 'destroy'])->name('jobs.destroy');
        Route::post('/jobs/{job}/submit', [HrdJobController::class, 'submit'])->name('jobs.submit');

        // HRD Job Board
        Route::get('/job-board', [\App\Http\Controllers\HRD\JobBoardController::class, 'index'])->name('job-board.index');
        Route::get('/job-board/{job}', [\App\Http\Controllers\HRD\JobBoardController::class, 'show'])->name('job-board.show');

        // Alumni List
        Route::get('/alumni', [HrdAlumniController::class, 'index'])->name('alumni.index');
    });
// Alumni: Job Board
Route::middleware(['auth', 'is.alumni'])->prefix('alumni/jobs')->name('alumni.jobs.')->group(function() {
    Route::get('/', [JobBoardController::class, 'index'])->name('index');
    Route::get('/{job}', [JobBoardController::class, 'show'])->name('show');
});

Route::get('/post-login', function () {
    $user = auth()->user();

    return match ($user->role) {
        'superadmin', 'admin_fakultas' => redirect()->route('admin.dashboard'),
        'hrd' => redirect()->route('hrd.dashboard'),
        'dosen' => redirect()->route('dosen.dashboard'),
        'mahasiswa' => redirect()->route('mahasiswa.dashboard'),
        'alumni' => redirect()->route('alumni.dashboard'),
        default => redirect()->route('login'), // Fallback aman
    };
})->middleware('auth')->name('post-login');

require __DIR__.'/auth.php';