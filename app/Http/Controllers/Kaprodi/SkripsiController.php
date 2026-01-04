<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SkripsiController extends Controller
{
    public function index()
    {
        // TODO: Implement the logic for this controller.
        return view('kaprodi.skripsi.index');
    }

    public function show($skripsi)
    {
        // TODO: Implement the logic for this controller.
        return view('kaprodi.skripsi.show', ['skripsi' => $skripsi]);
    }

    public function approveJudul($skripsi)
    {
        // TODO: Implement the logic for this controller.
    }

    public function assignPembimbing($skripsi)
    {
        // TODO: Implement the logic for this controller.
    }
}
