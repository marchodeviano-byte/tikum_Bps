<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SuratkeputusanhasilController extends Controller
{
    /**
     * =========================================================
     * INDEX
     * =========================================================
     *
     * Placeholder halaman Surat Keputusan - Hasil.
     * TODO: lengkapi query data hasil SK di sini.
     */
    public function index(Request $request)
    {
        return view('admin.sipeter.surat-keputusan.hasil.index');
    }
}
