<?php

namespace App\Http\Controllers\HRD;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlumniController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'hrd') {
            abort(403);
        }

        $query = Mahasiswa::where('status', 'lulus')->with(['user', 'prodi']);

        // Filtering
        if ($request->filled('prodi_id')) {
            $query->where('prodi_id', $request->input('prodi_id'));
        }
        if ($request->filled('tahun_lulus')) {
            $query->whereYear('updated_at', $request->input('tahun_lulus'));
        }

        $alumni = $query->latest()->paginate(25);
        $prodis = Prodi::orderBy('nama')->get();

        // Using the same view as admin
        return view('admin.alumni.index', [
            'alumni' => $alumni,
            'prodis' => $prodis,
            'context' => 'hrd',
        ]);
    }
}