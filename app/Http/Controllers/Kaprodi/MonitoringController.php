<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    public function dosen()
    {
        // TODO: Implement the logic for this controller.
        return view('kaprodi.monitoring.dosen');
    }

    public function mahasiswa()
    {
        // TODO: Implement the logic for this controller.
        return view('kaprodi.monitoring.mahasiswa');
    }

    public function nilai()
    {
        // TODO: Implement the logic for this controller.
        return view('kaprodi.monitoring.nilai');
    }
}
