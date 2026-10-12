<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

use App\Models\Penilaian;
use App\Models\User;
use App\Models\PenilaianPengecualian;

class PenilaianController extends Controller
{
    /**
     * Daftar bulan berdasarkan triwulan.
     */
    private function periodeTriwulan(int $triwulan): array
    {
        return match ($triwulan) {
            1 => [
                'nama' => 'Triwulan 1',
                'bulan' => [
                    1 => 'Januari',
                    2 => 'Februari',
                    3 => 'Maret',
                ],
            ],

            2 => [
                'nama' => 'Triwulan 2',
                'bulan' => [
                    4 => 'April',
                    5 => 'Mei',
                    6 => 'Juni',
                ],
            ],

            3 => [
                'nama' => 'Triwulan 3',
                'bulan' => [
                    7 => 'Juli',
                    8 => 'Agustus',
                    9 => 'September',
                ],
            ],

            4 => [
                'nama' => 'Triwulan 4',
                'bulan' => [
                    10 => 'Oktober',
                    11 => 'November',
                    12 => 'Desember',
                ],
            ],

            default => [
                'nama' => 'Triwulan 1',
                'bulan' => [
                    1 => 'Januari',
                    2 => 'Februari',
                    3 => 'Maret',
                ],
            ],
        };
    }


    /**
     * =========================================================
     * INDEX PENILAIAN
     * =========================================================
     *
     * Sekarang halaman index langsung menjadi halaman
     * Input Nilai Kinerja.
     *
     * Tidak ada lagi halaman index lama + tombol Next.
     */
    public function index(Request $request)
    {
        /**
         * -----------------------------------------------------
         * TAHUN
         * -----------------------------------------------------
         */
        $tahunSekarang = now()->year;

        $tahun = (int) $request->input(
            'tahun',
            $tahunSekarang
        );

        if ($tahun < 2000 || $tahun > 2100) {
            $tahun = $tahunSekarang;
        }


        /**
         * -----------------------------------------------------
         * TRIWULAN
         * -----------------------------------------------------
         */
        $triwulan = (int) $request->input(
            'triwulan',
            1
        );

        if (!in_array($triwulan, [1, 2, 3, 4], true)) {
            $triwulan = 1;
        }


        /**
         * -----------------------------------------------------
         * PERIODE TRIWULAN
         * -----------------------------------------------------
         */
        $periode = $this->periodeTriwulan($triwulan);


        /**
         * -----------------------------------------------------
         * PILIHAN TAHUN
         *
         * Menampilkan:
         * - Tahun yang sudah memiliki data
         * - Tahun berjalan
         * - 1 tahun berikutnya
         * -----------------------------------------------------
         */
        $tahunDariData = Penilaian::query()
            ->select('tahun')
            ->distinct()
            ->orderBy('tahun')
            ->pluck('tahun')
            ->map(fn($tahun) => (int) $tahun);

        $tahunOptions = $tahunDariData
            ->push($tahunSekarang)
            ->push($tahunSekarang + 1)
            ->unique()
            ->sort()
            ->values();


        /**
         * -----------------------------------------------------
         * MODE INPUT DEFAULT
         *
         * Bisa dikirim melalui:
         * ?mode=bulanan
         * atau
         * ?mode_input=bulanan
         *
         * Default = bulanan
         * -----------------------------------------------------
         */
        $modeDefault = $request->input(
            'mode_input',
            $request->input('mode', 'bulanan')
        );

        if (!in_array(
            $modeDefault,
            ['bulanan', 'triwulan'],
            true
        )) {
            $modeDefault = 'bulanan';
        }


        /**
         * -----------------------------------------------------
         * DATA PEGAWAI AKTIF
         * -----------------------------------------------------
         */
        $pegawai = User::with([
            'role',
            'jabatan',
        ])
            ->where('status', true)
            ->orderBy('nama')
            ->get();


        /**
         * -----------------------------------------------------
         * AMBIL DATA PENGECUALIAN
         *
         * Pegawai yang dicentang sebagai pengecualian
         * tidak akan ditampilkan pada tabel input.
         * -----------------------------------------------------
         */
        $pengecualianIds = PenilaianPengecualian::query()
            ->where('tahun', $tahun)
            ->where('triwulan', $triwulan)
            ->pluck('user_id')
            ->map(fn($id) => (int) $id)
            ->values();


        /**
         * -----------------------------------------------------
         * FILTER PEGAWAI BERDASARKAN PENGECUALIAN
         * -----------------------------------------------------
         */
        if ($pengecualianIds->isNotEmpty()) {
            $pegawai = $pegawai
                ->whereNotIn(
                    'id',
                    $pengecualianIds->all()
                )
                ->values();
        }


        /**
         * -----------------------------------------------------
         * SEMUA PEGAWAI AKTIF
         *
         * Digunakan untuk modal "Atur Pengecualian".
         * Jadi walaupun seseorang dikecualikan,
         * dia tetap muncul di daftar pengaturan pengecualian.
         * -----------------------------------------------------
         */
        $semuaPegawai = User::with([
            'role',
            'jabatan',
        ])
            ->where('status', true)
            ->orderBy('nama')
            ->get();


        /**
         * -----------------------------------------------------
         * DATA PENILAIAN
         *
         * Ambil berdasarkan:
         * - tahun
         * - triwulan
         *
         * Kemudian keyBy user_id supaya Blade
         * mudah mengambil data masing-masing pegawai.
         * -----------------------------------------------------
         */
        $dataPenilaian = Penilaian::query()
            ->where('tahun', $tahun)
            ->where('triwulan', $triwulan)
            ->get()
            ->keyBy('user_id');


        /**
         * -----------------------------------------------------
         * PERIODE BULAN DALAM FORMAT STRING
         *
         * Contoh:
         * Januari - Februari - Maret
         * -----------------------------------------------------
         */
        $periodeTriwulan = implode(
            ' - ',
            array_values($periode['bulan'])
        );


        /**
         * -----------------------------------------------------
         * RETURN VIEW
         *
         * Sekarang index langsung menggunakan halaman
         * Input Nilai Kinerja.
         *
         * Jadi tidak perlu lagi:
         * admin.penilaian.input
         * -----------------------------------------------------
         */
        return view(
            'admin.penilaian.index',
            compact(
                'pegawai',
                'semuaPegawai',
                'dataPenilaian',
                'tahun',
                'tahunOptions',
                'triwulan',
                'periode',
                'periodeTriwulan',
                'modeDefault',
                'pengecualianIds'
            )
        );
    }


    /**
     * =========================================================
     * HALAMAN INPUT NILAI
     * =========================================================
     *
     * Method ini dipertahankan untuk kompatibilitas
     * dengan route lama:
     *
     * admin.penilaian.input
     *
     * Sekarang langsung menggunakan index().
     */
    public function inputnilai(Request $request)
    {
        return $this->index($request);
    }


    /**
     * =========================================================
     * SIMPAN NILAI
     * =========================================================
     */
    public function simpan(Request $request)
    {
        /**
         * -----------------------------------------------------
         * VALIDASI DASAR
         * -----------------------------------------------------
         */
        $request->validate([
            'tahun' => [
                'required',
                'integer',
                'min:2000',
                'max:2100',
            ],

            'triwulan' => [
                'required',
                'integer',
                'in:1,2,3,4',
            ],

            'mode_input' => [
                'required',
                'in:bulanan,triwulan',
            ],

            'mode_edit' => [
                'nullable',
                'in:0,1',
            ],
        ]);


        /**
         * -----------------------------------------------------
         * DATA REQUEST
         * -----------------------------------------------------
         */
        $tahun = (int) $request->input('tahun');

        $triwulan = (int) $request->input('triwulan');

        $modeInput = $request->input('mode_input');

        $modeEdit = $request->input(
            'mode_edit',
            '0'
        );

        $modeEdit = $modeEdit === '1';


        /**
         * -----------------------------------------------------
         * PEGAWAI AKTIF
         * -----------------------------------------------------
         */
        $pegawai = User::query()
            ->where('status', true)
            ->orderBy('nama')
            ->get();


        /**
         * -----------------------------------------------------
         * CEK PEGAWAI
         * -----------------------------------------------------
         */
        if ($pegawai->isEmpty()) {
            return redirect()
                ->route(
                    'admin.penilaian.index',
                    [
                        'tahun' => $tahun,
                        'triwulan' => $triwulan,
                    ]
                )
                ->with(
                    'error',
                    'Belum ada pegawai aktif yang dapat dinilai.'
                );
        }


        /**
         * -----------------------------------------------------
         * TEMPAT DATA INPUT
         * -----------------------------------------------------
         */
        $pegawaiInput = collect();


        /**
         * =====================================================
         * LOOP PEGAWAI
         * =====================================================
         */
        foreach ($pegawai as $user) {

            $userId = $user->id;


            /**
             * =================================================
             * MODE BULANAN
             * =================================================
             */
            if ($modeInput === 'bulanan') {

                $nilai1 = $request->input(
                    "nilai_bulan_1.$userId"
                );

                $nilai2 = $request->input(
                    "nilai_bulan_2.$userId"
                );

                $nilai3 = $request->input(
                    "nilai_bulan_3.$userId"
                );


                /**
                 * -------------------------------------------------
                 * SEMUA KOSONG
                 *
                 * Berarti pegawai tersebut tidak diinput.
                 * -------------------------------------------------
                 */
                if (
                    ($nilai1 === null || $nilai1 === '') &&
                    ($nilai2 === null || $nilai2 === '') &&
                    ($nilai3 === null || $nilai3 === '')
                ) {
                    continue;
                }


                /**
                 * -------------------------------------------------
                 * HARUS LENGKAP 3 BULAN
                 * -------------------------------------------------
                 */
                if (
                    $nilai1 === null ||
                    $nilai1 === '' ||
                    $nilai2 === null ||
                    $nilai2 === '' ||
                    $nilai3 === null ||
                    $nilai3 === ''
                ) {
                    throw ValidationException::withMessages([
                        "nilai_bulan_1.$userId" =>
                        "Nilai {$user->nama} harus diisi lengkap untuk 3 bulan.",
                    ]);
                }


                /**
                 * -------------------------------------------------
                 * NORMALISASI NILAI
                 *
                 * Contoh:
                 * 95,50 -> 95.50
                 * -------------------------------------------------
                 */
                $nilaiNormal1 = str_replace(
                    ',',
                    '.',
                    (string) $nilai1
                );

                $nilaiNormal2 = str_replace(
                    ',',
                    '.',
                    (string) $nilai2
                );

                $nilaiNormal3 = str_replace(
                    ',',
                    '.',
                    (string) $nilai3
                );


                /**
                 * -------------------------------------------------
                 * VALIDASI 0 - 100
                 * -------------------------------------------------
                 */
                foreach (
                    [
                        $nilaiNormal1,
                        $nilaiNormal2,
                        $nilaiNormal3,
                    ] as $nilaiNormal
                ) {

                    if (
                        !is_numeric($nilaiNormal) ||
                        (float) $nilaiNormal < 0 ||
                        (float) $nilaiNormal > 100
                    ) {
                        throw ValidationException::withMessages([
                            "nilai_bulan_1.$userId" =>
                            "Nilai {$user->nama} harus berada di antara 0 sampai 100.",
                        ]);
                    }
                }


                /**
                 * -------------------------------------------------
                 * MASUKKAN KE COLLECTION
                 * -------------------------------------------------
                 */
                $pegawaiInput->push([
                    'user_id' => $userId,

                    'nilai_bulan_1' =>
                    (float) $nilaiNormal1,

                    'nilai_bulan_2' =>
                    (float) $nilaiNormal2,

                    'nilai_bulan_3' =>
                    (float) $nilaiNormal3,
                ]);
            }


            /**
             * =================================================
             * MODE TRIWULAN
             * =================================================
             */
            else {

                $nilai = $request->input(
                    "nilai_triwulan.$userId"
                );


                /**
                 * -------------------------------------------------
                 * KOSONG = TIDAK DIPROSES
                 * -------------------------------------------------
                 */
                if (
                    $nilai === null ||
                    $nilai === ''
                ) {
                    continue;
                }


                /**
                 * -------------------------------------------------
                 * NORMALISASI KOMA
                 * -------------------------------------------------
                 */
                $nilaiNormal = str_replace(
                    ',',
                    '.',
                    (string) $nilai
                );


                /**
                 * -------------------------------------------------
                 * VALIDASI 0 - 100
                 * -------------------------------------------------
                 */
                if (
                    !is_numeric($nilaiNormal) ||
                    (float) $nilaiNormal < 0 ||
                    (float) $nilaiNormal > 100
                ) {
                    throw ValidationException::withMessages([
                        "nilai_triwulan.$userId" =>
                        "Nilai {$user->nama} harus berada di antara 0 sampai 100.",
                    ]);
                }


                /**
                 * -------------------------------------------------
                 * MASUKKAN KE COLLECTION
                 * -------------------------------------------------
                 */
                $pegawaiInput->push([
                    'user_id' => $userId,

                    'nilai_triwulan' =>
                    (float) $nilaiNormal,
                ]);
            }
        }


        /**
         * =====================================================
         * TIDAK ADA DATA
         * =====================================================
         */
        if ($pegawaiInput->isEmpty()) {
            return redirect()
                ->route(
                    'admin.penilaian.index',
                    [
                        'tahun' => $tahun,
                        'triwulan' => $triwulan,
                    ]
                )
                ->with(
                    'error',
                    'Belum ada nilai yang diinput.'
                );
        }


        /**
         * =====================================================
         * PROSES DATABASE
         * =====================================================
         */
        DB::transaction(
            function () use (
                $pegawaiInput,
                $tahun,
                $triwulan,
                $modeInput,
                $modeEdit
            ) {

                foreach ($pegawaiInput as $data) {

                    /**
                     * -------------------------------------------------
                     * CARI DATA LAMA
                     * -------------------------------------------------
                     */
                    $penilaian = Penilaian::query()
                        ->where(
                            'user_id',
                            $data['user_id']
                        )
                        ->where(
                            'tahun',
                            $tahun
                        )
                        ->where(
                            'triwulan',
                            $triwulan
                        )
                        ->first();


                    /**
                     * =================================================
                     * DATA SUDAH ADA
                     * =================================================
                     */
                    if ($penilaian) {

                        /**
                         * -------------------------------------------------
                         * JIKA BUKAN MODE EDIT
                         * -------------------------------------------------
                         */
                        if (!$modeEdit) {

                            throw ValidationException::withMessages([
                                'nilai' =>
                                "Penilaian sudah pernah diinput untuk pegawai tersebut. Gunakan Edit Semua atau Reset terlebih dahulu.",
                            ]);
                        }


                        /**
                         * =================================================
                         * UPDATE MODE BULANAN
                         * =================================================
                         */
                        if ($modeInput === 'bulanan') {

                            $penilaian->update([
                                'metode_input' =>
                                'bulanan',

                                'nilai_bulan_1' =>
                                $data['nilai_bulan_1'],

                                'nilai_bulan_2' =>
                                $data['nilai_bulan_2'],

                                'nilai_bulan_3' =>
                                $data['nilai_bulan_3'],

                                /**
                                 * Mode bulanan tidak memakai
                                 * nilai_triwulan.
                                 */
                                'nilai_triwulan' => null,
                            ]);
                        }


                        /**
                         * =================================================
                         * UPDATE MODE TRIWULAN
                         * =================================================
                         */
                        else {

                            $nilai =
                                $data['nilai_triwulan'];

                            $penilaian->update([
                                'metode_input' =>
                                'triwulan',

                                /**
                                 * Sesuai struktur database saat ini,
                                 * nilai triwulan juga dimasukkan
                                 * ke ketiga kolom bulan.
                                 */
                                'nilai_bulan_1' =>
                                $nilai,

                                'nilai_bulan_2' =>
                                $nilai,

                                'nilai_bulan_3' =>
                                $nilai,

                                'nilai_triwulan' =>
                                $nilai,
                            ]);
                        }


                        continue;
                    }


                    /**
                     * =================================================
                     * DATA BELUM ADA → CREATE
                     * =================================================
                     */
                    if ($modeInput === 'bulanan') {

                        Penilaian::create([
                            'user_id' =>
                            $data['user_id'],

                            'tahun' =>
                            $tahun,

                            'triwulan' =>
                            $triwulan,

                            'metode_input' =>
                            'bulanan',

                            'nilai_bulan_1' =>
                            $data['nilai_bulan_1'],

                            'nilai_bulan_2' =>
                            $data['nilai_bulan_2'],

                            'nilai_bulan_3' =>
                            $data['nilai_bulan_3'],

                            'nilai_triwulan' =>
                            null,
                        ]);
                    }


                    /**
                     * =================================================
                     * CREATE MODE TRIWULAN
                     * =================================================
                     */
                    else {

                        $nilai =
                            $data['nilai_triwulan'];

                        Penilaian::create([
                            'user_id' =>
                            $data['user_id'],

                            'tahun' =>
                            $tahun,

                            'triwulan' =>
                            $triwulan,

                            'metode_input' =>
                            'triwulan',

                            /**
                             * Nilai triwulan disalin ke
                             * ketiga kolom bulan.
                             */
                            'nilai_bulan_1' =>
                            $nilai,

                            'nilai_bulan_2' =>
                            $nilai,

                            'nilai_bulan_3' =>
                            $nilai,

                            'nilai_triwulan' =>
                            $nilai,
                        ]);
                    }
                }
            }
        );


        /**
         * =====================================================
         * REDIRECT KEMBALI KE MENU PENILAIAN
         * =====================================================
         *
         * Karena sekarang index adalah halaman input,
         * setelah simpan user tetap berada di halaman
         * Penilaian yang sama.
         */
        return redirect()
            ->route(
                'admin.penilaian.index',
                [
                    'tahun' => $tahun,
                    'triwulan' => $triwulan,
                ]
            )
            ->with(
                'success',
                $modeEdit
                    ? 'Nilai kinerja berhasil diperbarui.'
                    : 'Nilai kinerja berhasil disimpan.'
            );
    }


    /**
     * =========================================================
     * SIMPAN PENGECUALIAN
     * =========================================================
     */
    public function simpanPengecualian(Request $request)
    {
        /**
         * -----------------------------------------------------
         * VALIDASI
         * -----------------------------------------------------
         */
        $validated = $request->validate([
            'tahun' => [
                'required',
                'integer',
                'min:2000',
                'max:2100',
            ],

            'triwulan' => [
                'required',
                'integer',
                'in:1,2,3,4',
            ],

            'pengecualian' => [
                'nullable',
                'array',
            ],

            'pengecualian.*' => [
                'integer',
                'distinct',
            ],
        ]);


        /**
         * -----------------------------------------------------
         * DATA PERIODE
         * -----------------------------------------------------
         */
        $tahun = (int) $validated['tahun'];

        $triwulan = (int) $validated['triwulan'];


        /**
         * -----------------------------------------------------
         * USER ID PENGECUALIAN
         * -----------------------------------------------------
         */
        $userIds = collect(
            $validated['pengecualian'] ?? []
        )
            ->map(fn($id) => (int) $id)
            ->unique()
            ->values();


        /**
         * -----------------------------------------------------
         * PASTIKAN HANYA PEGAWAI AKTIF
         * -----------------------------------------------------
         */
        $userIdsAktif = User::query()
            ->where('status', true)
            ->whereIn(
                'id',
                $userIds
            )
            ->pluck('id')
            ->map(fn($id) => (int) $id)
            ->values();


        /**
         * =====================================================
         * DATABASE TRANSACTION
         * =====================================================
         */
        DB::transaction(
            function () use (
                $tahun,
                $triwulan,
                $userIdsAktif
            ) {

                /**
                 * -------------------------------------------------
                 * HAPUS PENGECUALIAN LAMA
                 * -------------------------------------------------
                 */
                PenilaianPengecualian::query()
                    ->where(
                        'tahun',
                        $tahun
                    )
                    ->where(
                        'triwulan',
                        $triwulan
                    )
                    ->delete();


                /**
                 * -------------------------------------------------
                 * JIKA TIDAK ADA PENGECUALIAN
                 * -------------------------------------------------
                 */
                if ($userIdsAktif->isEmpty()) {
                    return;
                }


                /**
                 * -------------------------------------------------
                 * SIAPKAN DATA INSERT
                 * -------------------------------------------------
                 */
                $now = now();

                $rows = $userIdsAktif
                    ->map(
                        fn($userId) => [
                            'user_id' =>
                            $userId,

                            'tahun' =>
                            $tahun,

                            'triwulan' =>
                            $triwulan,

                            'created_at' =>
                            $now,

                            'updated_at' =>
                            $now,
                        ]
                    )
                    ->all();


                /**
                 * -------------------------------------------------
                 * INSERT
                 * -------------------------------------------------
                 */
                PenilaianPengecualian::insert(
                    $rows
                );
            }
        );


        /**
         * =====================================================
         * REDIRECT
         * =====================================================
         */
        return redirect()
            ->route(
                'admin.penilaian.index',
                [
                    'tahun' => $tahun,
                    'triwulan' => $triwulan,
                ]
            )
            ->with(
                'success',
                'Pengecualian penilaian berhasil disimpan.'
            );
    }
}