<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class BantuanController extends Controller
{
    /**
     * =========================================================
     * HALAMAN BANTUAN (HELP DESK)
     * =========================================================
     */
    public function index()
    {
        return view('admin.bantuan.index', [
            'sections' => $this->daftarBantuan(),
        ]);
    }

    /**
     * =========================================================
     * ISI HALAMAN BANTUAN
     * =========================================================
     *
     * Struktur:
     *
     * 'kunci' => [
     *     'label' => judul kartu di beranda,
     *     'icon'  => kelas Bootstrap Icons (tanpa awalan "bi "),
     *     'desc'  => keterangan singkat di bawah judul kartu,
     *     'items' => [
     *         ['title' => 'Judul', 'url' => 'alamat tujuan'],
     *     ],
     * ]
     *
     * PENTING:
     * - Kunci "panduan" dipakai pencarian di beranda, jangan diganti.
     * - Isi 'url' dengan alamat file/halaman yang sebenarnya. Contoh:
     *     asset('panduan/cara-upload-laporan.pdf')   (file di public/panduan/)
     *     'https://contoh.com/panduan'               (alamat luar)
     *   Tanda '#' di bawah hanyalah penanda sementara.
     *
     * @return array<string, array<string, mixed>>
     */
    private function daftarBantuan(): array
    {
        return [

            'panduan' => [
                'label' => 'Panduan',
                'icon' => 'bi-book',
                'desc' => 'Langkah penggunaan setiap menu aplikasi',
                'items' => [
                    ['title' => 'Cara Mengelola User', 'url' => '#'],
                    ['title' => 'Cara Upload Laporan Pelaporan', 'url' => '#'],
                    ['title' => 'Cara Upload SKP Penetapan dan Evaluasi', 'url' => '#'],
                    ['title' => 'Cara Mengisi Angka Kredit', 'url' => '#'],
                    ['title' => 'Cara Melakukan Penilaian Tahap 1', 'url' => '#'],
                    ['title' => 'Cara Melakukan Penilaian Tahap 2 (Panitia)', 'url' => '#'],
                    ['title' => 'Cara Download dan Reset Data Monitoring', 'url' => '#'],
                ],
            ],

            'faq' => [
                'label' => 'FAQ',
                'icon' => 'bi-question-circle',
                'desc' => 'Pertanyaan yang sering diajukan',
                'items' => [
                    ['title' => 'Format file laporan apa yang diterima?', 'url' => '#'],
                    ['title' => 'Apa beda evaluasi Bulanan, Triwulan, dan Tahunan?', 'url' => '#'],
                    ['title' => 'Mengapa tombol Reset belum bisa diklik?', 'url' => '#'],
                    ['title' => 'Bagaimana angka kredit tahun berikutnya dihitung?', 'url' => '#'],
                    ['title' => 'Akun pegawai dinonaktifkan, apa yang terjadi?', 'url' => '#'],
                ],
            ],

        ];
    }
}