<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MitraPenilaianController extends Controller
{
    /**
     * =========================================================
     * INDEX
     * =========================================================
     *
     * Placeholder halaman Penilaian Mitra.
     * TODO: lengkapi query data penilaian mitra di sini.
     */
    public function index(Request $request)
    {
        return view('admin.mitra_penilaian.index');
    }
}
