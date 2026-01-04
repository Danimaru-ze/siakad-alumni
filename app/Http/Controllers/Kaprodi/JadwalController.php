<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index()
    {
        // TODO: Implement the logic for this controller.
        return view('kaprodi.jadwal.index');
    }
}
