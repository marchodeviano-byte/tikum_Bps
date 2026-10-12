<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class HelpDeskController extends Controller
{
    public function index(): View
    {
        /*
         * Ganti nilai 'url' dengan link Google Docs yang sebenarnya.
         * Tambah / hapus item sesuai kebutuhan, tampilan akan mengikuti otomatis.
         */
        $sections = [
            'panduan' => [
                'label' => 'Panduan',
                'desc'  => 'Panduan Penggunaan web site',
                'icon'  => 'bi-file-earmark-text',
                'items' => [
                    ['title' => 'Menu Login',          'url' => 'https://docs.google.com/document/d/1i0jm60el31iBTYSn8zjQdF4OxyPGC4knKtaU0TL4p7g/edit?tab=t.0'],
                    ['title' => 'Menu Kinerja Pegawai', 'url' => 'https://docs.google.com/document/d/1MvSMO3I8WQrfQMWjNxbPTPwH7NtUjSg5Xh8P4neU3rs/edit?tab=t.acwzp8b4wr9d'],
                    ['title' => 'Menu Penilaian',      'url' => 'https://docs.google.com/document/d/1nQqv79EfQ8yzlP_SWUbYsc0D-74bHnCaX3ShjXL__DE/edit?tab=t.hlf1hocm043d'],
                    ['title' => 'Menu Pelaporan',      'url' => 'https://docs.google.com/document/d/1avTCfw3UZvwZCdcwYvehC_1sjT82N2oheDYtAFmpI_E/edit?tab=t.xe0l4mbgk27e'],
                    ['title' => 'Menu SKP',            'url' => 'https://docs.google.com/document/d/1h2HviPy1iTqMfaUR0ix0mGpZqkn77SCMnx2aX7DYWnU/edit?tab=t.gx2lrbxau01j'],
                    ['title' => 'Menu Monitoring',     'url' => 'https://docs.google.com/document/d/1kdZcIb6vY1IMw0G93NB3GsSohQackDpFTzyMw5Eh0nU/edit?tab=t.iivs0xevxb1g'],
                    ['title' => 'Menu Manajemen User', 'url' => 'https://docs.google.com/document/d/1ANWueJaQqjFb6o9QaaTsz23POEEfYPLSvYPiV33eoxc/edit?tab=t.92fn2e1kaugx'],
                ],
            ],
            'faq' => [
                'label' => 'FAQ',
                'desc'  => 'Tanyakan Sesuatu',
                'icon'  => 'bi-chat-square-text',
                'items' => [
                    ['title' => 'Lupa Password',        'url' => 'https://docs.google.com/document/d/GANTI_ID_DOKUMEN/edit'],
                    ['title' => 'Akun Tidak Bisa Login', 'url' => 'https://docs.google.com/document/d/GANTI_ID_DOKUMEN/edit'],
                ],
            ],
        ];

        return view('helpdesk.index', compact('sections'));
    }
}