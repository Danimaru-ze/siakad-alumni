<?php

namespace App\Http\Controllers\Admin;

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
        if (!$user->isAdmin()) {
            abort(403);
        }

        $query = Mahasiswa::where('status', 'lulus')->with(['user', 'prodi']);

        // Filtering
        if ($request->filled('prodi_id')) {
            $query->where('prodi_id', $request->input('prodi_id'));
        }
        if ($request->filled('tahun_lulus')) {
            // Assuming 'tahun_lulus' is stored somewhere, for now let's filter by updated_at year
            // This logic might need refinement based on actual data structure
            $query->whereYear('updated_at', $request->input('tahun_lulus'));
        }

        $alumni = $query->latest()->paginate(25);
        $prodis = Prodi::orderBy('nama')->get();

        return view('admin.alumni.index', [
            'alumni' => $alumni,
            'prodis' => $prodis,
            'context' => 'admin',
        ]);
    }
}