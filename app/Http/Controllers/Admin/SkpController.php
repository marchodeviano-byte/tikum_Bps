<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skp;
use App\Models\SkpEvaluasi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SkpController extends Controller
{
    /**
     * =========================================================
     * HALAMAN SKP - PENETAPAN
     * =========================================================
     *
     * View:
     * admin.skp.penetapan.index
     *
     * Menampilkan:
     * - SKP Penetapan Tahunan
     *
     * Query:
     * ?tahun=2026
     * ?jenis=bulanan
     * ?triwulan=1
     *
     */
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | TAHUN
        |--------------------------------------------------------------------------
        */

        $tahun = (int) $request->query(
            'tahun',
            now()->year
        );

        /*
        |--------------------------------------------------------------------------
        | DAFTAR PEGAWAI AKTIF
        |--------------------------------------------------------------------------
        */

        $pegawaiList = User::where(
            'status',
            true
        )
            ->orderBy('nama')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | DATA SKP PENETAPAN TAHUNAN
        |--------------------------------------------------------------------------
        */

        $skpTahunan = Skp::where(
            'tahun',
            $tahun
        )
            ->where(
                'jenis',
                'Tahunan'
            )
            ->get()
            ->keyBy('user_id');

        /*
        |--------------------------------------------------------------------------
        | GABUNGKAN DENGAN DAFTAR PEGAWAI
        |--------------------------------------------------------------------------
        */

        $dataTahunan = $pegawaiList->map(
            function ($pegawai) use ($skpTahunan) {

                return [
                    'pegawai' => $pegawai,

                    'skp' =>
                        $skpTahunan->get(
                            $pegawai->id
                        ),
                ];
            }
        );

        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.skp.penetapan.index',
            [
                'tahun' =>
                    $tahun,

                'pegawaiList' =>
                    $pegawaiList,

                'dataTahunan' =>
                    $dataTahunan,
            ]
        );
    }


    /**
     * =========================================================
     * HALAMAN SKP - EVALUASI
     * =========================================================
     *
     * View:
     * admin.skp.evaluasi.index
     *
     * Menampilkan:
     * - Dokumen Evaluasi Kinerja Pegawai
     * - Dokumen Evaluasi Kinerja Pegawai /
     *   Pendekatan Hasil Kerja Kualitatif
     *
     * Query:
     * ?tahun=2026
     *
     */
    public function evaluasi(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | JENIS EVALUASI
        |--------------------------------------------------------------------------
        | Metode evaluasi:
        | - bulanan
        | - triwulan
        | - tahunan
        |
        | Untuk Bulanan dan Triwulan, seluruh periode dalam satu tahun
        | dimuat sekaligus agar pegawai dapat upload langsung pada kolom
        | periode yang diinginkan.
        */

        $jenisAktif = strtolower(
            $request->query(
                'jenis',
                'bulanan'
            )
        );

        if (!in_array(
            $jenisAktif,
            [
                'bulanan',
                'triwulan',
                'tahunan',
            ],
            true
        )) {
            $jenisAktif = 'tahunan';
        }

        /*
        |--------------------------------------------------------------------------
        | TAHUN
        |--------------------------------------------------------------------------
        */

        $tahun = (int) $request->query(
            'tahun',
            now()->year
        );

        /*
        |--------------------------------------------------------------------------
        | BULAN / TRIWULAN UNTUK FILTER DAN HEADER
        |--------------------------------------------------------------------------
        */

        $filterBulan = $request->query('bulan', 'all');
        $filterTriwulan = $request->query('triwulan', 'all');

        if ($filterBulan !== 'all') {
            $filterBulan = (int) $filterBulan;

            if ($filterBulan < 1 || $filterBulan > 12) {
                $filterBulan = 'all';
            }
        }

        if ($filterTriwulan !== 'all') {
            $filterTriwulan = (int) $filterTriwulan;

            if ($filterTriwulan < 1 || $filterTriwulan > 4) {
                $filterTriwulan = 'all';
            }
        }

        // Nilai numerik ini tetap dipertahankan untuk kebutuhan tampilan lama.
        $bulan = is_numeric($filterBulan) ? (int) $filterBulan : now()->month;
        $triwulan = is_numeric($filterTriwulan)
            ? (int) $filterTriwulan
            : (int) ceil($bulan / 3);

        /*
        |--------------------------------------------------------------------------
        | DAFTAR PERIODE TABEL
        |--------------------------------------------------------------------------
        */

        $periodeList = [];

        if ($jenisAktif === 'bulanan') {
            $namaBulan = [
                1 => 'Januari',
                2 => 'Februari',
                3 => 'Maret',
                4 => 'April',
                5 => 'Mei',
                6 => 'Juni',
                7 => 'Juli',
                8 => 'Agustus',
                9 => 'September',
                10 => 'Oktober',
                11 => 'November',
                12 => 'Desember',
            ];

            for ($i = 1; $i <= 12; $i++) {
                if ($filterBulan !== 'all' && (int) $filterBulan !== $i) {
                    continue;
                }

                $periodeList[] = [
                    'kode' => $i,
                    'label' => $namaBulan[$i],
                ];
            }
        } elseif ($jenisAktif === 'triwulan') {
            $semuaTriwulan = [
                [
                    'kode' => 1,
                    'label' => 'Triwulan 1',
                    'rentang' => 'Jan - Mar',
                ],
                [
                    'kode' => 2,
                    'label' => 'Triwulan 2',
                    'rentang' => 'Apr - Jun',
                ],
                [
                    'kode' => 3,
                    'label' => 'Triwulan 3',
                    'rentang' => 'Jul - Sep',
                ],
                [
                    'kode' => 4,
                    'label' => 'Triwulan 4',
                    'rentang' => 'Okt - Des',
                ],
            ];

            foreach ($semuaTriwulan as $periode) {
                if ($filterTriwulan !== 'all' && (int) $filterTriwulan !== (int) $periode['kode']) {
                    continue;
                }

                $periodeList[] = $periode;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | DAFTAR PEGAWAI AKTIF
        |--------------------------------------------------------------------------
        */

        $pegawaiList = User::where(
            'status',
            true
        )
            ->orderBy('nama')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | QUERY EVALUASI
        |--------------------------------------------------------------------------
        | Untuk Bulanan: ambil bulan 1-12.
        | Untuk Triwulan: ambil triwulan 1-4.
        | Tahunan tetap satu data per pegawai.
        */

        $evaluasiQuery = SkpEvaluasi::where(
            'tahun',
            $tahun
        )
            ->where(
                'jenis',
                ucfirst($jenisAktif)
            );

        if ($jenisAktif === 'bulanan') {
            $evaluasiQuery->whereBetween(
                'bulan',
                [1, 12]
            );
        } elseif ($jenisAktif === 'triwulan') {
            $evaluasiQuery->whereBetween(
                'triwulan',
                [1, 4]
            );
        } else {
            $evaluasiQuery
                ->where(
                    'bulan',
                    0
                )
                ->where(
                    'triwulan',
                    0
                );
        }

        $evaluasiSemuaPeriode = $evaluasiQuery
            ->get();

        /*
        |--------------------------------------------------------------------------
        | GABUNGKAN PEGAWAI + EVALUASI PER PERIODE
        |--------------------------------------------------------------------------
        */

        $evaluasiByUser = $evaluasiSemuaPeriode
            ->groupBy('user_id');

        $dataEvaluasi = $pegawaiList->map(
            function ($pegawai) use (
                $evaluasiByUser,
                $jenisAktif
            ) {
                $dataPegawai = $evaluasiByUser->get(
                    $pegawai->id,
                    collect()
                );

                if ($jenisAktif === 'bulanan') {
                    $evaluasiPeriode = $dataPegawai
                        ->where('jenis', 'Bulanan')
                        ->keyBy('bulan');
                } elseif ($jenisAktif === 'triwulan') {
                    $evaluasiPeriode = $dataPegawai
                        ->where('jenis', 'Triwulan')
                        ->keyBy('triwulan');
                } else {
                    $evaluasiPeriode = $dataPegawai
                        ->where('jenis', 'Tahunan')
                        ->keyBy(function ($evaluasi) {
                            return 'tahunan';
                        });
                }

                return [
                    'pegawai' => $pegawai,
                    'evaluasi' => $jenisAktif === 'tahunan'
                        ? $evaluasiPeriode->get('tahunan')
                        : null,
                    'evaluasiPeriode' => $evaluasiPeriode,
                ];
            }
        );

        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.skp.evaluasi.index',
            [
                'tahun' =>
                    $tahun,

                'bulan' =>
                    $bulan,

                'triwulan' =>
                    $triwulan,

                'filterBulan' =>
                    $filterBulan,

                'filterTriwulan' =>
                    $filterTriwulan,

                'jenisAktif' =>
                    $jenisAktif,

                'pegawaiList' =>
                    $pegawaiList,

                'dataEvaluasi' =>
                    $dataEvaluasi,

                'periodeList' =>
                    $periodeList,

                // Dokumen yang diupload pada jenis evaluasi aktif:
                // Bulanan & Triwulan -> ['kualitatif'], Tahunan -> ['evaluasi', 'kualitatif']
                'dokumenDiperlukan' =>
                    $this->dokumenDiperlukan($jenisAktif),
            ]
        );
    }

    /**
     * =========================================================
     * UPLOAD DOKUMEN SKP PENETAPAN
     * =========================================================
     *
     * Hanya mendukung:
     * - Tahunan
     *
     */
    public function upload(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDASI SKP PENETAPAN TAHUNAN
        |--------------------------------------------------------------------------
        */

        $data = $request->validate(
            [
                'file' => [
                    'required',
                    'file',
                    'mimes:pdf',
                    'max:10240',
                ],

                'jenis' => [
                    'required',
                    'in:Tahunan',
                ],

                'user_id' => [
                    'required',
                    'integer',
                    'exists:users,id',
                ],

                'tahun' => [
                    'required',
                    'integer',
                    'min:2000',
                    'max:2100',
                ],
            ],
            [
                'file.mimes' =>
                    'Dokumen SKP harus berformat PDF.',

                'user_id.required' =>
                    'Pegawai tujuan SKP wajib dipilih.',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | PERIODE
        |--------------------------------------------------------------------------
        */

        $bulan = null;

        $triwulan = null;

        /*
        |--------------------------------------------------------------------------
        | CARI DATA LAMA
        |--------------------------------------------------------------------------
        */

        $skpLama = Skp::where(
            [
                'user_id' =>
                    $data['user_id'],

                'jenis' =>
                    'Tahunan',

                'tahun' =>
                    $data['tahun'],

                'bulan' =>
                    $bulan,

                'triwulan' =>
                    $triwulan,
            ]
        )->first();

        /*
        |--------------------------------------------------------------------------
        | FILE
        |--------------------------------------------------------------------------
        */

        $file =
            $request->file('file');

        $namaFile =
            $file->getClientOriginalName();

        /*
        |--------------------------------------------------------------------------
        | FOLDER
        |--------------------------------------------------------------------------
        */

        $folder = sprintf(
            'skp/%d/%d/tahunan',
            $data['user_id'],
            $data['tahun']
        );

        /*
        |--------------------------------------------------------------------------
        | SIMPAN FILE BARU
        |--------------------------------------------------------------------------
        */

        $pathFile =
            $file->store(
                $folder,
                'public'
            );

        /*
        |--------------------------------------------------------------------------
        | SIMPAN / UPDATE
        |--------------------------------------------------------------------------
        */

$skp = Skp::updateOrCreate(
    [
        'user_id' => $data['user_id'],
        'jenis' => 'Tahunan',
        'tahun' => $data['tahun'],
        'bulan' => $bulan,
        'triwulan' => $triwulan,
    ],
    [
        'diupload_oleh' => Auth::id(),

        'nama_file' => $namaFile,

        'path_file' => $pathFile,

        'ukuran_file' => $file->getSize(),

        // RESET APPROVAL SETIAP UPLOAD / GANTI FILE
        'status' => 'menunggu',

        'keterangan' => null,

        'diproses_oleh' => null,

        'diproses_at' => null,
    ]
);

        /*
        |--------------------------------------------------------------------------
        | HAPUS FILE LAMA
        |--------------------------------------------------------------------------
        */

        if (
            $skpLama &&
            $skpLama->path_file &&
            $skpLama->path_file !== $pathFile
        ) {

            Storage::disk(
                'public'
            )->delete(
                $skpLama->path_file
            );
        }

        /*
        |--------------------------------------------------------------------------
        | RESPONSE
        |--------------------------------------------------------------------------
        */

        return response()->json(
            [
                'success' =>
                    true,

                'message' =>
                    'Dokumen SKP Tahunan berhasil diupload.',

                'data' => [

                    'id' =>
                        $skp->id,

                    'nama_file' =>
                        $skp->nama_file,

                    'ukuran_file' =>
                        $skp->ukuran_file,

                    'label_periode' =>
                        $skp->label_periode,

                    'diupload_pada' =>
                        $skp->updated_at
                            ->format(
                                'd M Y H:i'
                            ),

                    'url_file' =>
                        $skp->url_file,

                    'status' =>
                        $skp->status,

                    'keterangan' =>
                        $skp->keterangan,
                ],
            ]
        );
    }


    /**
     * =========================================================
     * UPLOAD DOKUMEN SKP EVALUASI
     * =========================================================
     *
     * Upload dilakukan per dokumen.
     *
     * jenis_dokumen:
     * - evaluasi
     * - kualitatif
     *
     * Jika dokumen sebelumnya ditolak:
     * - file lama sudah dihapus
     * - upload baru menggantikan dokumen tersebut
     * - status dokumen kembali menjadi menunggu
     *
     */
    public function uploadEvaluasi(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */

        $data = $request->validate(
            [
                'file' => [
                    'required',
                    'file',
                    'mimes:pdf',
                    'max:10240',
                ],

                'user_id' => [
                    'required',
                    'integer',
                    'exists:users,id',
                ],

                'tahun' => [
                    'required',
                    'integer',
                    'min:2000',
                    'max:2100',
                ],

                'jenis_evaluasi' => [
                    'required',
                    'in:bulanan,triwulan,tahunan',
                ],

                'jenis_dokumen' => [
                    'required',
                    'in:evaluasi,kualitatif',
                ],

                'bulan' => [
                    'nullable',
                    'required_if:jenis_evaluasi,bulanan',
                    'integer',
                    'between:1,12',
                ],

                'triwulan' => [
                    'nullable',
                    'required_if:jenis_evaluasi,triwulan',
                    'integer',
                    'between:1,4',
                ],
            ],
            [
                'file.mimes' =>
                    'Dokumen evaluasi harus berformat PDF.',

                'user_id.required' =>
                    'Pegawai wajib dipilih.',

                'jenis_evaluasi.required' =>
                    'Metode evaluasi wajib dipilih.',

                'jenis_dokumen.required' =>
                    'Jenis dokumen evaluasi wajib dipilih.',

                'bulan.required_if' =>
                    'Bulan wajib dipilih untuk evaluasi bulanan.',

                'triwulan.required_if' =>
                    'Triwulan wajib dipilih untuk evaluasi triwulan.',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | ATURAN DOKUMEN PER JENIS EVALUASI
        |--------------------------------------------------------------------------
        | - Bulanan & Triwulan -> hanya Dokumen Evaluasi Kinerja Pendekatan
        |                         Hasil Kerja Kualitatif ("kualitatif").
        | - Tahunan            -> kedua dokumen ("evaluasi" dan "kualitatif").
        */

        if (
            $data['jenis_dokumen'] === 'evaluasi'
            && $data['jenis_evaluasi'] !== 'tahunan'
        ) {
            return response()->json(
                [
                    'success' => false,
                    'message' =>
                        'Evaluasi ' . $data['jenis_evaluasi'] .
                        ' hanya menggunakan Dokumen Evaluasi Kinerja ' .
                        'Pendekatan Hasil Kerja Kualitatif.',
                ],
                422
            );
        }

        /*
        |--------------------------------------------------------------------------
        | NORMALISASI PERIODE
        |--------------------------------------------------------------------------
        */

        $jenisEvaluasi =
            ucfirst(
                strtolower(
                    $data['jenis_evaluasi']
                )
            );

        $bulan =
            $data['jenis_evaluasi'] === 'bulanan'
                ? (int) $data['bulan']
                : 0;

        $triwulan =
            $data['jenis_evaluasi'] === 'triwulan'
                ? (int) $data['triwulan']
                : 0;

        /*
        |--------------------------------------------------------------------------
        | CARI / BUAT DATA SESUAI METODE
        |--------------------------------------------------------------------------
        |
        | Setiap kombinasi:
        | user + tahun + jenis + bulan + triwulan
        | mempunyai data evaluasi sendiri.
        |
        */

        $evaluasi = SkpEvaluasi::where(
            [
                'user_id' =>
                    $data['user_id'],

                'tahun' =>
                    $data['tahun'],

                'jenis' =>
                    $jenisEvaluasi,

                'bulan' =>
                    $bulan,

                'triwulan' =>
                    $triwulan,
            ]
        )->first();

        if (!$evaluasi) {

            $evaluasi =
                new SkpEvaluasi();

            $evaluasi->user_id =
                $data['user_id'];

            $evaluasi->tahun =
                $data['tahun'];

            $evaluasi->jenis =
                $jenisEvaluasi;

            $evaluasi->bulan =
                $bulan;

            $evaluasi->triwulan =
                $triwulan;

            $evaluasi->status =
                'belum_lengkap';
        }

        /*
        |--------------------------------------------------------------------------
        | FILE
        |--------------------------------------------------------------------------
        */

        $file =
            $request->file('file');

        $namaFile =
            $file->getClientOriginalName();

        /*
        |--------------------------------------------------------------------------
        | FOLDER
        |--------------------------------------------------------------------------
        */

        $folder = sprintf(
            'skp-evaluasi/%d/%d/%s',
            $data['user_id'],
            $data['tahun'],
            strtolower(
                $jenisEvaluasi
            )
        );

        /*
        |--------------------------------------------------------------------------
        | SIMPAN FILE BARU
        |--------------------------------------------------------------------------
        */

        $pathFile =
            $file->store(
                $folder,
                'public'
            );

        /*
        |--------------------------------------------------------------------------
        | FILE LAMA
        |--------------------------------------------------------------------------
        */

        $pathFileLama = null;

        /*
        |--------------------------------------------------------------------------
        | DOKUMEN EVALUASI
        |--------------------------------------------------------------------------
        */

        if (
            $data['jenis_dokumen'] ===
            'evaluasi'
        ) {

            $pathFileLama =
                $evaluasi
                    ->dokumen_evaluasi_path;

            $evaluasi
                ->dokumen_evaluasi_file =
                $namaFile;

            $evaluasi
                ->dokumen_evaluasi_path =
                $pathFile;

            $evaluasi
                ->dokumen_evaluasi_ukuran =
                $file->getSize();

            $evaluasi
                ->status_dokumen_evaluasi =
                'menunggu';

            $evaluasi
                ->keterangan_dokumen_evaluasi =
                null;
        }

        /*
        |--------------------------------------------------------------------------
        | DOKUMEN KUALITATIF
        |--------------------------------------------------------------------------
        */

        else {

            $pathFileLama =
                $evaluasi
                    ->dokumen_kualitatif_path;

            $evaluasi
                ->dokumen_kualitatif_file =
                $namaFile;

            $evaluasi
                ->dokumen_kualitatif_path =
                $pathFile;

            $evaluasi
                ->dokumen_kualitatif_ukuran =
                $file->getSize();

            $evaluasi
                ->status_dokumen_kualitatif =
                'menunggu';

            $evaluasi
                ->keterangan_dokumen_kualitatif =
                null;
        }

        /*
        |--------------------------------------------------------------------------
        | HITUNG STATUS UTAMA
        |--------------------------------------------------------------------------
        | Bulanan & Triwulan -> hanya dokumen kualitatif yang dihitung.
        | Tahunan            -> dokumen evaluasi + kualitatif.
        */

        $evaluasi->status =
            $this->hitungStatusUtama(
                $evaluasi
            );

        /*
        |--------------------------------------------------------------------------
        | RESET PROSES LAMA
        |--------------------------------------------------------------------------
        */

        $evaluasi->keterangan =
            null;

        $evaluasi->diproses_oleh =
            null;

        $evaluasi->diproses_at =
            null;

        /*
        |--------------------------------------------------------------------------
        | SIMPAN
        |--------------------------------------------------------------------------
        */

        $evaluasi->save();

        /*
        |--------------------------------------------------------------------------
        | HAPUS FILE LAMA
        |--------------------------------------------------------------------------
        */

        if (
            $pathFileLama &&
            $pathFileLama !== $pathFile
        ) {

            Storage::disk(
                'public'
            )->delete(
                $pathFileLama
            );
        }

        /*
        |--------------------------------------------------------------------------
        | RESPONSE
        |--------------------------------------------------------------------------
        */

        return response()->json(
            [
                'success' =>
                    true,

                'message' =>
                    'Dokumen evaluasi ' .
                    strtolower($jenisEvaluasi) .
                    ' berhasil diupload.',

                'data' => [

                    'id' =>
                        $evaluasi->id,

                    'status' =>
                        $evaluasi->status,

                    'nama_file' =>
                        $namaFile,

                    'jenis_dokumen' =>
                        $data['jenis_dokumen'],

                    'jenis_evaluasi' =>
                        strtolower(
                            $jenisEvaluasi
                        ),
                ],
            ]
        );
    }


    /**
     * =========================================================
     * REVIEW DOKUMEN SKP EVALUASI
     * =========================================================
     *
     * Approval dilakukan per dokumen.
     *
     * jenis_dokumen:
     * - evaluasi
     * - kualitatif
     *
     * aksi:
     * - setujui
     * - tolak
     *
     * Jika ditolak:
     *
     * 1. File fisik dihapus.
     * 2. Nama file di database dihapus.
     * 3. Path file di database dihapus.
     * 4. Ukuran file di database dihapus.
     * 5. Status dokumen menjadi "ditolak".
     * 6. Alasan penolakan tetap disimpan.
     *
     */
    public function reviewDokumen(
        Request $request,
        SkpEvaluasi $skpEvaluasi
    ) {
        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */

        $data = $request->validate(
            [
                'jenis_dokumen' => [
                    'required',
                    'in:evaluasi,kualitatif',
                ],

                'aksi' => [
                    'required',
                    'in:setujui,tolak',
                ],

                'keterangan' => [
                    'nullable',
                    'string',
                    'max:2000',
                ],
            ],
            [
                'jenis_dokumen.required' =>
                    'Jenis dokumen wajib dipilih.',

                'aksi.required' =>
                    'Aksi review wajib dipilih.',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | VALIDASI ALASAN PENOLAKAN
        |--------------------------------------------------------------------------
        */

        if (
            $data['aksi'] === 'tolak' &&
            empty(
                trim(
                    $data['keterangan'] ?? ''
                )
            )
        ) {

            return response()->json(
                [
                    'success' => false,

                    'message' =>
                        'Alasan penolakan wajib diisi.',
                ],
                422
            );
        }

        /*
        |--------------------------------------------------------------------------
        | DOKUMEN EVALUASI KINERJA HANYA UNTUK TAHUNAN
        |--------------------------------------------------------------------------
        */
        if (
            $data['jenis_dokumen'] === 'evaluasi'
            && $skpEvaluasi->jenis !== 'Tahunan'
        ) {
            return response()->json(
                [
                    'success' => false,
                    'message' =>
                        'Dokumen Evaluasi Kinerja tidak digunakan pada evaluasi ' .
                        strtolower($skpEvaluasi->jenis) . '.',
                ],
                422
            );
        }

        /*
        |--------------------------------------------------------------------------
        | TRANSACTION
        |--------------------------------------------------------------------------
        */

        DB::transaction(
            function () use (
                $skpEvaluasi,
                $data
            ) {

                /*
                |--------------------------------------------------------------------------
                | TENTUKAN FIELD
                |--------------------------------------------------------------------------
                */

                if (
                    $data['jenis_dokumen'] ===
                    'evaluasi'
                ) {

                    $statusField =
                        'status_dokumen_evaluasi';

                    $keteranganField =
                        'keterangan_dokumen_evaluasi';

                    $pathField =
                        'dokumen_evaluasi_path';

                    $fileField =
                        'dokumen_evaluasi_file';

                    $sizeField =
                        'dokumen_evaluasi_ukuran';
                }

                else {

                    $statusField =
                        'status_dokumen_kualitatif';

                    $keteranganField =
                        'keterangan_dokumen_kualitatif';

                    $pathField =
                        'dokumen_kualitatif_path';

                    $fileField =
                        'dokumen_kualitatif_file';

                    $sizeField =
                        'dokumen_kualitatif_ukuran';
                }

                /*
                |--------------------------------------------------------------------------
                | SETUJUI DOKUMEN
                |--------------------------------------------------------------------------
                */

                if (
                    $data['aksi'] ===
                    'setujui'
                ) {

                    if (
                        empty(
                            $skpEvaluasi->{$pathField}
                        )
                    ) {

                        abort(
                            422,
                            'Dokumen belum tersedia.'
                        );
                    }

                    $skpEvaluasi->update(
                        [
                            $statusField =>
                                'disetujui',

                            $keteranganField =>
                                null,

                            'diproses_oleh' =>
                                Auth::id(),

                            'diproses_at' =>
                                now(),
                        ]
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | TOLAK DOKUMEN
                |--------------------------------------------------------------------------
                */

                else {

                    /*
                    | Simpan path sebelum dihapus.
                    */
                    $pathLama =
                        $skpEvaluasi->{$pathField};

                    /*
                    | Hapus file fisik.
                    */
                    if (
                        $pathLama &&
                        Storage::disk('public')
                            ->exists($pathLama)
                    ) {

                        Storage::disk('public')
                            ->delete($pathLama);
                    }

                    /*
                    | Hapus data file dari DB.
                    |
                    | Alasan tetap disimpan.
                    */
                    $skpEvaluasi->update(
                        [
                            $fileField =>
                                null,

                            $pathField =>
                                null,

                            $sizeField =>
                                null,

                            $statusField =>
                                'ditolak',

                            $keteranganField =>
                                trim(
                                    $data['keterangan']
                                ),

                            'diproses_oleh' =>
                                Auth::id(),

                            'diproses_at' =>
                                now(),
                        ]
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | REFRESH DATA
                |--------------------------------------------------------------------------
                */

                $skpEvaluasi->refresh();

                /*
                |--------------------------------------------------------------------------
                | STATUS UTAMA
                |--------------------------------------------------------------------------
                | Bulanan & Triwulan -> hanya dokumen kualitatif.
                | Tahunan            -> dokumen evaluasi + kualitatif.
                */
                $skpEvaluasi->update(
                    [
                        'status' =>
                            $this->hitungStatusUtama(
                                $skpEvaluasi
                            ),
                    ]
                );
            }
        );

        /*
        |--------------------------------------------------------------------------
        | RESPONSE
        |--------------------------------------------------------------------------
        */

        return response()->json(
            [
                'success' => true,

                'message' =>
                    $data['aksi'] ===
                    'setujui'

                        ? 'Dokumen berhasil disetujui.'

                        : 'Dokumen berhasil ditolak dan dihapus. Pegawai dapat mengupload ulang.',
            ]
        );
    }


    /**
     * =========================================================
     * HAPUS DOKUMEN SKP PENETAPAN
     * =========================================================
     *
     */
    public function destroy(Skp $skp)
    {
        /*
        |--------------------------------------------------------------------------
        | HAPUS FILE FISIK
        |--------------------------------------------------------------------------
        */

        if (
            $skp->path_file
        ) {

            Storage::disk('public')->delete(
                $skp->path_file
            );
        }

        /*
        |--------------------------------------------------------------------------
        | HAPUS DATABASE
        |--------------------------------------------------------------------------
        */

        $skp->delete();

        /*
        |--------------------------------------------------------------------------
        | RESPONSE
        |--------------------------------------------------------------------------
        */

        return response()->json(
            [
                'success' => true,

                'message' =>
                    'Dokumen SKP berhasil dihapus.',
            ]
        );
    }

    /**
     * =========================================================
     * HELPER - DOKUMEN YANG DIPERLUKAN PER JENIS EVALUASI
     * =========================================================
     *
     * - Bulanan & Triwulan -> hanya "kualitatif"
     *   (Dokumen Evaluasi Kinerja Pendekatan Hasil Kerja Kualitatif)
     * - Tahunan            -> "evaluasi" dan "kualitatif"
     *
     * @return array<int, string>
     */
    private function dokumenDiperlukan(string $jenisEvaluasi): array
    {
        return strtolower($jenisEvaluasi) === 'tahunan'
            ? ['evaluasi', 'kualitatif']
            : ['kualitatif'];
    }

    /**
     * =========================================================
     * HELPER - STATUS UTAMA EVALUASI
     * =========================================================
     *
     * Hanya dokumen yang diperlukan untuk jenis evaluasi tersebut
     * yang ikut dihitung:
     *
     * - semua dokumen ada & disetujui          -> disetujui
     * - semua dokumen ada & menunggu/disetujui -> menunggu_persetujuan
     * - selain itu                             -> belum_lengkap
     */
    private function hitungStatusUtama(SkpEvaluasi $evaluasi): string
    {
        $semuaDisetujui = true;
        $semuaTerkirim = true;

        foreach ($this->dokumenDiperlukan((string) $evaluasi->jenis) as $dokumen) {

            $ada = !empty($evaluasi->{"dokumen_{$dokumen}_path"});
            $status = $evaluasi->{"status_dokumen_{$dokumen}"};

            if (!($ada && $status === 'disetujui')) {
                $semuaDisetujui = false;
            }

            if (!($ada && in_array($status, ['menunggu', 'disetujui'], true))) {
                $semuaTerkirim = false;
            }
        }

        if ($semuaDisetujui) {
            return 'disetujui';
        }

        if ($semuaTerkirim) {
            return 'menunggu_persetujuan';
        }

        return 'belum_lengkap';
    }
}