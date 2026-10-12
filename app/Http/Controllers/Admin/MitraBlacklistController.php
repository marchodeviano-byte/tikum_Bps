<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MitraBlacklistController extends Controller
{
    /**
     * =========================================================
     * INDEX
     * =========================================================
     *
     * Placeholder halaman Blacklist Mitra.
     * TODO: lengkapi query data blacklist mitra di sini.
     */
    public function index(Request $request)
    {
        return view('admin.mitra_blacklist.index');
    }
}
