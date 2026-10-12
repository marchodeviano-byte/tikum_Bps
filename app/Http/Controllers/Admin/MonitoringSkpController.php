<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skp;
use App\Models\SkpEvaluasi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class MonitoringSkpController extends Controller
{
    /**
     * =========================================================
     * MONITORING SKP - PENETAPAN
     * =========================================================
     *
     * Menampilkan:
     * - SKP Tahunan
     *
     * Sumber data:
     * - tabel skps
     *
     */
    public function index(Request $request)
    {
        /**
         * --------------------------------------------------------------------------
         * MONITORING SKP PENETAPAN - KHUSUS TAHUNAN
         * --------------------------------------------------------------------------
         */
        $tahun = (int) $request->query(
            'tahun',
            now()->year
        );

        $jenisAktif = 'tahunan';

        /**
         * --------------------------------------------------------------------------
         * DAFTAR PEGAWAI AKTIF
         * --------------------------------------------------------------------------
         */
        $pegawaiList = User::where('status', true)
            ->with('role')
            ->orderBy('nama')
            ->get();

        /**
         * --------------------------------------------------------------------------
         * DATA SKP PENETAPAN TAHUNAN
         * --------------------------------------------------------------------------
         *
         * Upload SKP Penetapan disimpan dengan:
         * jenis = Tahunan
         * bulan = 0
         * triwulan = 0
         *
         */
        $skpTahunanTahunIni = Skp::where(
            'jenis',
            'Tahunan'
        )
            ->where(
                'tahun',
                $tahun
            )
            ->get()
            ->keyBy('user_id');

        /**
         * --------------------------------------------------------------------------
         * GABUNGKAN PEGAWAI + SKP TAHUNAN
         * --------------------------------------------------------------------------
         */
        $dataTahunan = $pegawaiList->map(
            function ($pegawai) use (
                $skpTahunanTahunIni
            ) {
                return [
                    'pegawai' => $pegawai,
                    'skp' => $skpTahunanTahunIni->get(
                        $pegawai->id
                    ),
                ];
            }
        );

        /**
         * --------------------------------------------------------------------------
         * STATISTIK
         * --------------------------------------------------------------------------
         */
        $totalPegawai = $pegawaiList->count();

        // Statistik "Sudah Upload" dihitung hanya dari dokumen
        // yang SUDAH DISETUJUI.
        // File yang masih menunggu persetujuan atau ditolak
        // tidak masuk ke kategori Sudah Upload.
        $sudahUpload = $dataTahunan
            ->filter(
                fn ($item) =>
                $item['skp'] !== null
                && !empty($item['skp']->path_file)
                && ($item['skp']->status ?? 'menunggu') === 'disetujui'
            )
            ->count();

        $belumUpload =
            $totalPegawai - $sudahUpload;

        $menungguPersetujuan = $dataTahunan
            ->filter(
                fn ($item) =>
                $item['skp']
                && $item['skp']->status === 'menunggu'
            )
            ->count();

        $sudahDisetujui = $dataTahunan
            ->filter(
                fn ($item) =>
                $item['skp']
                && $item['skp']->status === 'disetujui'
            )
            ->count();

        $perluPerbaikan = $dataTahunan
            ->filter(
                fn ($item) =>
                $item['skp']
                && $item['skp']->status === 'ditolak'
            )
            ->count();

        /**
         * --------------------------------------------------------------------------
         * VIEW
         * --------------------------------------------------------------------------
         */
        return view(
            'admin.monitoring_skp.penetapan.index',
            [
                'tahun' => $tahun,
                'jenisAktif' => $jenisAktif,
                'pegawaiList' => $pegawaiList,
                'dataTahunan' => $dataTahunan,
                'totalPegawai' => $totalPegawai,
                'sudahUpload' => $sudahUpload,
                'belumUpload' => $belumUpload,
                'sudahDisetujui' => $sudahDisetujui,
                'menungguPersetujuan' => $menungguPersetujuan,
                'perluPerbaikan' => $perluPerbaikan,
                'backupSudahDilakukan' => session()->get(
                    'skp_penetapan_backup_' . $tahun,
                    false
                ),
            ]
        );
    }

    /**
     * =========================================================
     * REVIEW SKP PENETAPAN
     * =========================================================
     *
     * Digunakan untuk:
     * - Setujui
     * - Tolak
     *
     */
    public function reviewPenetapan(Request $request, Skp $skp)
    {
        $data = $request->validate([
            'aksi' => ['required', 'in:setujui,tolak'],
            'keterangan' => ['nullable', 'string', 'max:2000'],
        ]);

        /**
         * --------------------------------------------------------------------------
         * VALIDASI JENIS
         * --------------------------------------------------------------------------
         */
        if ($skp->jenis !== 'Tahunan') {
            return response()->json([
                'success' => false,
                'message' => 'Dokumen yang direview bukan SKP Penetapan Tahunan.',
            ], 422);
        }

        /**
         * --------------------------------------------------------------------------
         * VALIDASI FILE
         * --------------------------------------------------------------------------
         */
        if (!$skp->path_file) {
            return response()->json([
                'success' => false,
                'message' => 'Dokumen SKP Penetapan belum tersedia untuk direview.',
            ], 422);
        }

        /**
         * --------------------------------------------------------------------------
         * VALIDASI ALASAN PENOLAKAN
         * --------------------------------------------------------------------------
         */
        if (
            $data['aksi'] === 'tolak'
            && trim($data['keterangan'] ?? '') === ''
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Alasan penolakan wajib diisi.',
            ], 422);
        }

        /**
         * --------------------------------------------------------------------------
         * SETUJUI
         * --------------------------------------------------------------------------
         *
         * Jika disetujui:
         * - status = disetujui
         * - keterangan = null
         * - diproses_oleh = user yang menyetujui
         * - diproses_at = waktu persetujuan
         *
         */
        if ($data['aksi'] === 'setujui') {
            $skp->update([
                'status' => 'disetujui',
                'keterangan' => null,
                'diproses_oleh' => auth()->id(),
                'diproses_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'SKP Penetapan berhasil disetujui.',
            ]);
        }

        /**
         * --------------------------------------------------------------------------
         * TOLAK
         * --------------------------------------------------------------------------
         *
         * PENTING:
         * File TIDAK dihapus ketika ditolak.
         *
         * Data berikut tetap dipertahankan:
         * - nama_file
         * - path_file
         * - ukuran_file
         *
         * Yang diubah hanya status/proses approval.
         *
         */
        $skp->update([
            'status' => 'ditolak',
            'keterangan' => trim($data['keterangan']),
            'diproses_oleh' => auth()->id(),
            'diproses_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'SKP Penetapan ditolak. Pegawai dapat mengupload ulang dokumen.',
        ]);
    }

    /**
     * =========================================================
     * DOWNLOAD ALL SKP PENETAPAN
     * =========================================================
     */
    public function downloadAllPenetapan(Request $request)
    {
        $tahun = (int) $request->query(
            'tahun',
            now()->year
        );

        $skpList = Skp::with('user')
            ->where('tahun', $tahun)
            ->where('jenis', 'Tahunan')
            ->orderBy('user_id')
            ->orderBy('bulan')
            ->get();

        $jumlahFile = 0;

        foreach ($skpList as $skp) {
            if (
                $skp->path_file
                && Storage::disk('public')->exists($skp->path_file)
            ) {
                $jumlahFile++;
            }
        }

        if ($jumlahFile === 0) {
            return back()->with(
                'error',
                'Tidak ada file SKP Penetapan yang dapat dibackup untuk tahun ' . $tahun . '.'
            );
        }

        $namaZip =
            'Backup_SKP_Penetapan_' .
            $tahun .
            '_' .
            now()->format('Ymd_His') .
            '.zip';

        $zipPath = storage_path(
            'app/temp/' . $namaZip
        );

        $tempDirectory = dirname($zipPath);

        if (!is_dir($tempDirectory)) {
            mkdir($tempDirectory, 0755, true);
        }

        $zip = new ZipArchive();

        $hasilOpen = $zip->open(
            $zipPath,
            ZipArchive::CREATE | ZipArchive::OVERWRITE
        );

        if ($hasilOpen !== true) {
            return back()->with(
                'error',
                'Backup gagal dibuat. ZIP tidak dapat dibuat.'
            );
        }

        $rootFolder =
            'Backup_SKP_Penetapan_' . $tahun;

        $zip->addEmptyDir($rootFolder);

        foreach ($skpList as $skp) {
            if (
                !$skp->user
                || !$skp->path_file
                || !Storage::disk('public')->exists($skp->path_file)
            ) {
                continue;
            }

            $namaPegawai = trim(
                (string) $skp->user->nama
            );

            if ($namaPegawai === '') {
                $namaPegawai =
                    'Pegawai_' . $skp->user_id;
            }

            $namaFolderPegawai = Str::of($namaPegawai)
                ->replace(
                    ['/', '\\', ':', '*', '?', '"', '<', '>', '|'],
                    '-'
                )
                ->trim()
                ->toString();

            if ($namaFolderPegawai === '') {
                $namaFolderPegawai =
                    'Pegawai_' . $skp->user_id;
            }

            $folderPegawai =
                $rootFolder . '/' . $namaFolderPegawai;

            $zip->addEmptyDir(
                $folderPegawai
            );

            $pathAsli = storage_path(
                'app/public/' . $skp->path_file
            );

            if (!is_file($pathAsli)) {
                continue;
            }

            $namaFile =
                $skp->nama_file
                ?: 'dokumen-skp-tahunan.pdf';

            $namaFile = Str::of($namaFile)
                ->replace(
                    ['/', '\\'],
                    '-'
                )
                ->trim()
                ->toString();

            $zip->addFile(
                $pathAsli,
                $folderPegawai . '/' . $namaFile
            );
        }

        if (!$zip->close()) {
            if (file_exists($zipPath)) {
                @unlink($zipPath);
            }

            return back()->with(
                'error',
                'Backup gagal diselesaikan.'
            );
        }

        session()->put(
            'skp_penetapan_backup_' . $tahun,
            true
        );

        return response()
            ->download(
                $zipPath,
                $namaZip
            )
            ->deleteFileAfterSend(true);
    }

    /**
     * =========================================================
     * RESET SKP PENETAPAN
     * =========================================================
     *
     * Menghapus seluruh SKP Penetapan Tahunan
     * pada tahun yang dipilih.
     *
     * Data user dan data SKP Evaluasi tidak ikut dihapus.
     *
     */
    public function resetPenetapan(Request $request)
    {
        $tahun = (int) $request->input(
            'tahun',
            now()->year
        );

        $backupSessionKey =
            'skp_penetapan_backup_' . $tahun;

        if (!session()->get(
            $backupSessionKey,
            false
        )) {
            return response()->json([
                'success' => false,
                'message' =>
                'Reset tidak dapat dilakukan. Silakan Download All terlebih dahulu.',
            ], 422);
        }

        $skpList = Skp::where(
            'tahun',
            $tahun
        )
            ->where(
                'jenis',
                'Tahunan'
            )
            ->get();

        DB::transaction(
            function () use ($skpList) {
                foreach ($skpList as $skp) {

                    if (
                        $skp->path_file
                        && Storage::disk('public')->exists(
                            $skp->path_file
                        )
                    ) {
                        Storage::disk('public')->delete(
                            $skp->path_file
                        );
                    }

                    $skp->delete();
                }
            }
        );

        session()->forget(
            $backupSessionKey
        );

        return response()->json([
            'success' => true,
            'message' =>
            'Data SKP Penetapan tahun ' .
                $tahun .
                ' berhasil direset.',
        ]);
    }

    /**
     * =========================================================
     * MONITORING SKP - EVALUASI
     * =========================================================
     *
     * Menampilkan:
     * - Evaluasi Bulanan
     * - Evaluasi Triwulan
     * - Evaluasi Tahunan
     *
     * Sumber data:
     * - tabel skp_evaluasis
     *
     */
    public function evaluasi(Request $request)
    {
        /**
         * --------------------------------------------------------------------------
         * FILTER
         * --------------------------------------------------------------------------
         */
        $tahun = (int) $request->query(
            'tahun',
            now()->year
        );

        $jenisAktif = strtolower(
            (string) $request->query(
                'jenis',
                'bulanan'
            )
        );

        if (!in_array(
            $jenisAktif,
            ['bulanan', 'triwulan', 'tahunan'],
            true
        )) {
            $jenisAktif = 'bulanan';
        }

        /**
         * --------------------------------------------------------------------------
         * FILTER PERIODE
         * --------------------------------------------------------------------------
         */
        $bulanQuery = $request->query(
            'bulan',
            'all'
        );

        $triwulanQuery = $request->query(
            'triwulan',
            'all'
        );

        $bulan = $bulanQuery === 'all'
            ? 'all'
            : (int) $bulanQuery;

        $triwulan = $triwulanQuery === 'all'
            ? 'all'
            : (int) $triwulanQuery;

        if (
            $bulan !== 'all'
            && ($bulan < 1 || $bulan > 12)
        ) {
            $bulan = 'all';
        }

        if (
            $triwulan !== 'all'
            && ($triwulan < 1 || $triwulan > 4)
        ) {
            $triwulan = 'all';
        }

        /**
         * --------------------------------------------------------------------------
         * DAFTAR BULAN / TRIWULAN
         * --------------------------------------------------------------------------
         */
        $daftarBulan = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember',
        ];

        $daftarTriwulan = [
            '1' => 'Jan - Mar',
            '2' => 'Apr - Jun',
            '3' => 'Jul - Sep',
            '4' => 'Okt - Des',
        ];

        /**
         * --------------------------------------------------------------------------
         * PERIODE YANG DITAMPILKAN
         * --------------------------------------------------------------------------
         */
        if ($jenisAktif === 'bulanan') {
            $periodeList = $bulan === 'all'
                ? range(1, 12)
                : [$bulan];
        } elseif ($jenisAktif === 'triwulan') {
            $periodeList = $triwulan === 'all'
                ? range(1, 4)
                : [$triwulan];
        } else {
            $periodeList = ['tahunan'];
        }

        /**
         * --------------------------------------------------------------------------
         * DAFTAR PEGAWAI
         * --------------------------------------------------------------------------
         */
        $pegawaiList = User::where('status', true)
            ->with('role')
            ->orderBy('nama')
            ->get();

        /**
         * --------------------------------------------------------------------------
         * SEMUA DATA EVALUASI TAHUN YANG DIPILIH
         * --------------------------------------------------------------------------
         *
         * Penting:
         * Jangan keyBy user_id saja karena satu pegawai dapat mempunyai:
         * - banyak evaluasi Bulanan
         * - banyak evaluasi Triwulan
         * - satu evaluasi Tahunan
         *
         */
        $evaluasiTahunIni = SkpEvaluasi::where(
            'tahun',
            $tahun
        )
            ->get()
            ->groupBy('user_id');

        /**
         * --------------------------------------------------------------------------
         * GABUNGKAN PEGAWAI + DATA PER METODE
         * --------------------------------------------------------------------------
         */
        $dataEvaluasi = $pegawaiList->map(
            function ($pegawai) use (
                $evaluasiTahunIni
            ) {
                $rekaman = $evaluasiTahunIni->get(
                    $pegawai->id,
                    collect()
                );

                return [
                    'pegawai' => $pegawai,

                    'bulanan' => $rekaman
                        ->where('jenis', 'Bulanan')
                        ->keyBy(
                            fn ($item) =>
                            (int) $item->bulan
                        ),

                    'triwulan' => $rekaman
                        ->where('jenis', 'Triwulan')
                        ->keyBy(
                            fn ($item) =>
                            (int) $item->triwulan
                        ),

                    'tahunan' => $rekaman
                        ->first(
                            fn ($item) =>
                            $item->jenis === 'Tahunan'
                        ),
                ];
            }
        );

        /**
         * --------------------------------------------------------------------------
         * REKAMAN PERIODE YANG SEDANG DITAMPILKAN
         * --------------------------------------------------------------------------
         */
        $ambilRekaman = function ($item) use (
            $jenisAktif,
            $periodeList
        ) {
            if ($jenisAktif === 'bulanan') {
                return collect($periodeList)
                    ->map(
                        fn ($periode) =>
                        $item['bulanan'][(int) $periode] ?? null
                    );
            }

            if ($jenisAktif === 'triwulan') {
                return collect($periodeList)
                    ->map(
                        fn ($periode) =>
                        $item['triwulan'][(int) $periode] ?? null
                    );
            }

            return collect([
                $item['tahunan'] ?? null
            ]);
        };

        /**
         * --------------------------------------------------------------------------
         * STATISTIK
         * --------------------------------------------------------------------------
         *
         * Dokumen yang dihitung mengikuti jenis evaluasi:
         * - Bulanan & Triwulan -> hanya Dokumen Evaluasi Kinerja
         *                         Pendekatan Hasil Kerja Kualitatif
         * - Tahunan            -> Dokumen Evaluasi Kinerja + Kualitatif
         *
         */
        $totalPegawai =
            $pegawaiList->count();

        $sudahUpload = $dataEvaluasi
            ->filter(
                function ($item) use (
                    $ambilRekaman,
                    $jenisAktif
                ) {
                    $records =
                        $ambilRekaman($item);

                    if ($records->isEmpty()) {
                        return false;
                    }

                    return $records->every(
                        fn ($evaluasi) =>
                        $this->evaluasiDisetujui(
                            $evaluasi,
                            $jenisAktif
                        )
                    );
                }
            )
            ->count();

        $belumUpload =
            $totalPegawai - $sudahUpload;

        $menungguPersetujuan = $dataEvaluasi
            ->filter(
                function ($item) use (
                    $ambilRekaman,
                    $jenisAktif
                ) {
                    return $ambilRekaman($item)
                        ->contains(
                            fn ($evaluasi) =>
                            $this->evaluasiMenunggu(
                                $evaluasi,
                                $jenisAktif
                            )
                        );
                }
            )
            ->count();

        $perluPerbaikan = $dataEvaluasi
            ->filter(
                function ($item) use (
                    $ambilRekaman,
                    $jenisAktif
                ) {
                    return $ambilRekaman($item)
                        ->contains(
                            fn ($evaluasi) =>
                            $this->evaluasiDitolak(
                                $evaluasi,
                                $jenisAktif
                            )
                        );
                }
            )
            ->count();

        /**
         * --------------------------------------------------------------------------
         * BACKUP STATUS
         * --------------------------------------------------------------------------
         */
        $backupSessionKey =
            'skp_evaluasi_backup_' . $tahun;

        $backupSudahDilakukan =
            session()->get(
                $backupSessionKey,
                false
            );

        /**
         * --------------------------------------------------------------------------
         * VIEW
         * --------------------------------------------------------------------------
         */
        return view(
            'admin.monitoring_skp.evaluasi.index',
            [
                'tahun' => $tahun,
                'jenisAktif' => $jenisAktif,
                'bulan' => $bulan,
                'triwulan' => $triwulan,
                'daftarBulan' => $daftarBulan,
                'daftarTriwulan' => $daftarTriwulan,
                'periodeList' => $periodeList,
                'pegawaiList' => $pegawaiList,
                'dataEvaluasi' => $dataEvaluasi,
                'totalPegawai' => $totalPegawai,
                'sudahUpload' => $sudahUpload,
                'belumUpload' => $belumUpload,
                'sudahDisetujui' => $sudahUpload,
                'menungguPersetujuan' =>
                $menungguPersetujuan,
                'perluPerbaikan' =>
                $perluPerbaikan,
                'backupSudahDilakukan' =>
                $backupSudahDilakukan,
                'dokumenDiperlukan' =>
                $this->dokumenDiperlukan($jenisAktif),
            ]
        );
    }

    /**
     * =========================================================
     * DOWNLOAD ALL SKP EVALUASI
     * =========================================================
     */
    public function downloadAllEvaluasi(Request $request)
    {
        $tahun = (int) $request->query(
            'tahun',
            now()->year
        );

        /**
         * --------------------------------------------------------------------------
         * AMBIL SEMUA DATA EVALUASI PADA TAHUN TERPILIH
         * --------------------------------------------------------------------------
         *
         * Download All tidak mengikuti filter metode yang sedang aktif di halaman.
         *
         * Semua metode ikut dibackup:
         * - Bulanan
         * - Triwulan
         * - Tahunan
         *
         */
        $evaluasiList = SkpEvaluasi::with('user')
            ->where('tahun', $tahun)
            ->orderBy('user_id')
            ->orderBy('jenis')
            ->orderBy('bulan')
            ->orderBy('triwulan')
            ->get();

        $jumlahFile = 0;

        foreach ($evaluasiList as $evaluasi) {
            if (
                $evaluasi->dokumen_evaluasi_path
                && Storage::disk('public')->exists(
                    $evaluasi->dokumen_evaluasi_path
                )
            ) {
                $jumlahFile++;
            }

            if (
                $evaluasi->dokumen_kualitatif_path
                && Storage::disk('public')->exists(
                    $evaluasi->dokumen_kualitatif_path
                )
            ) {
                $jumlahFile++;
            }
        }

        if ($jumlahFile === 0) {
            return back()->with(
                'error',
                'Tidak ada file SKP Evaluasi yang dapat dibackup untuk tahun ' . $tahun . '.'
            );
        }

        $namaZip =
            'Backup_SKP_Evaluasi_' .
            $tahun .
            '_' .
            now()->format('Ymd_His') .
            '.zip';

        $zipPath = storage_path(
            'app/temp/' . $namaZip
        );

        $tempDirectory = dirname($zipPath);

        if (!is_dir($tempDirectory)) {
            mkdir($tempDirectory, 0755, true);
        }

        $zip = new ZipArchive();

        $hasilOpen = $zip->open(
            $zipPath,
            ZipArchive::CREATE | ZipArchive::OVERWRITE
        );

        if ($hasilOpen !== true) {
            return back()->with(
                'error',
                'Backup gagal dibuat. ZIP tidak dapat dibuat.'
            );
        }

        $rootFolder =
            'Backup_SKP_Evaluasi_' . $tahun;

        $zip->addEmptyDir($rootFolder);

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

        $pegawaiFolderDibuat = [];

        foreach ($evaluasiList as $evaluasi) {
            if (!$evaluasi->user) {
                continue;
            }

            $namaPegawai = trim(
                (string) $evaluasi->user->nama
            );

            if ($namaPegawai === '') {
                $namaPegawai =
                    'Pegawai_' . $evaluasi->user_id;
            }

            $namaFolderPegawai = Str::of($namaPegawai)
                ->replace(
                    ['/', '\\', ':', '*', '?', '"', '<', '>', '|'],
                    '-'
                )
                ->trim()
                ->toString();

            if ($namaFolderPegawai === '') {
                $namaFolderPegawai =
                    'Pegawai_' . $evaluasi->user_id;
            }

            $folderPegawai =
                $rootFolder . '/' . $namaFolderPegawai;

            $folderBulanan =
                $folderPegawai . '/Bulanan';

            $folderTriwulan =
                $folderPegawai . '/Triwulan';

            $folderTahunan =
                $folderPegawai . '/Tahunan';

            /**
             * Selalu buat ketiga folder metode untuk setiap pegawai.
             */
            if (!isset(
                $pegawaiFolderDibuat[$evaluasi->user_id]
            )) {
                $zip->addEmptyDir(
                    $folderPegawai
                );

                $zip->addEmptyDir(
                    $folderBulanan
                );

                $zip->addEmptyDir(
                    $folderTriwulan
                );

                $zip->addEmptyDir(
                    $folderTahunan
                );

                $pegawaiFolderDibuat[
                    $evaluasi->user_id
                ] = true;
            }

            if ($evaluasi->jenis === 'Bulanan') {
                $bulanEvaluasi =
                    (int) $evaluasi->bulan;

                $namaPeriode =
                    ($namaBulan[$bulanEvaluasi]
                        ?? 'Bulan ' . $bulanEvaluasi)
                    . ' '
                    . $tahun;

                $folderMetode =
                    $folderBulanan;
            } elseif ($evaluasi->jenis === 'Triwulan') {
                $triwulanEvaluasi =
                    (int) $evaluasi->triwulan;

                $namaPeriode =
                    'Triwulan '
                    . $triwulanEvaluasi
                    . ' '
                    . $tahun;

                $folderMetode =
                    $folderTriwulan;
            } else {
                $namaPeriode =
                    'Tahunan ' . $tahun;

                $folderMetode =
                    $folderTahunan;
            }

            /**
             * --------------------------------------------------------------------------
             * DOKUMEN EVALUASI KINERJA
             * --------------------------------------------------------------------------
             */
            if (
                $evaluasi->dokumen_evaluasi_path
                && Storage::disk('public')->exists(
                    $evaluasi->dokumen_evaluasi_path
                )
            ) {
                $pathAsli = storage_path(
                    'app/public/' .
                        $evaluasi->dokumen_evaluasi_path
                );

                $namaFile =
                    $evaluasi->dokumen_evaluasi_file
                    ?: 'dokumen-evaluasi.pdf';

                $namaFile = Str::of($namaFile)
                    ->replace(
                        ['/', '\\'],
                        '-'
                    )
                    ->toString();

                $zip->addFile(
                    $pathAsli,
                    $folderMetode .
                        '/' .
                        $namaPeriode .
                        ' - Evaluasi Kinerja - ' .
                        $namaFile
                );
            }

            /**
             * --------------------------------------------------------------------------
             * DOKUMEN PENDEKATAN HASIL KERJA KUALITATIF
             * --------------------------------------------------------------------------
             */
            if (
                $evaluasi->dokumen_kualitatif_path
                && Storage::disk('public')->exists(
                    $evaluasi->dokumen_kualitatif_path
                )
            ) {
                $pathAsli = storage_path(
                    'app/public/' .
                        $evaluasi->dokumen_kualitatif_path
                );

                $namaFile =
                    $evaluasi->dokumen_kualitatif_file
                    ?: 'dokumen-kualitatif.pdf';

                $namaFile = Str::of($namaFile)
                    ->replace(
                        ['/', '\\'],
                        '-'
                    )
                    ->toString();

                $zip->addFile(
                    $pathAsli,
                    $folderMetode .
                        '/' .
                        $namaPeriode .
                        ' - Kualitatif - ' .
                        $namaFile
                );
            }
        }

        if (!$zip->close()) {
            if (file_exists($zipPath)) {
                @unlink($zipPath);
            }

            return back()->with(
                'error',
                'Backup gagal diselesaikan.'
            );
        }

        session()->put(
            'skp_evaluasi_backup_' . $tahun,
            true
        );

        return response()
            ->download(
                $zipPath,
                $namaZip
            )
            ->deleteFileAfterSend(true);
    }

    /**
     * =========================================================
     * RESET SKP EVALUASI
     * =========================================================
     */
    public function resetEvaluasi(Request $request)
    {
        /**
         * --------------------------------------------------------------------------
         * TAHUN
         * --------------------------------------------------------------------------
         */
        $tahun = (int) $request->input(
            'tahun',
            now()->year
        );

        /**
         * --------------------------------------------------------------------------
         * CEK BACKUP
         * --------------------------------------------------------------------------
         */
        $backupSessionKey =
            'skp_evaluasi_backup_' . $tahun;

        if (!session()->get(
            $backupSessionKey,
            false
        )) {
            return response()->json([
                'success' => false,
                'message' =>
                'Reset tidak dapat dilakukan. Silakan Download All terlebih dahulu.',
            ], 422);
        }

        /**
         * --------------------------------------------------------------------------
         * AMBIL DATA EVALUASI
         * --------------------------------------------------------------------------
         */
        $evaluasiList =
            SkpEvaluasi::where(
                'tahun',
                $tahun
            )->get();

        /**
         * --------------------------------------------------------------------------
         * HAPUS DATA + FILE
         * --------------------------------------------------------------------------
         */
        DB::transaction(
            function () use (
                $evaluasiList
            ) {
                foreach ($evaluasiList as $evaluasi) {

                    /**
                     * --------------------------------------------------------------------------
                     * HAPUS DOKUMEN EVALUASI
                     * --------------------------------------------------------------------------
                     */
                    if (
                        $evaluasi->dokumen_evaluasi_path
                        && Storage::disk('public')->exists(
                            $evaluasi->dokumen_evaluasi_path
                        )
                    ) {
                        Storage::disk('public')->delete(
                            $evaluasi->dokumen_evaluasi_path
                        );
                    }

                    /**
                     * --------------------------------------------------------------------------
                     * HAPUS DOKUMEN KUALITATIF
                     * --------------------------------------------------------------------------
                     */
                    if (
                        $evaluasi->dokumen_kualitatif_path
                        && Storage::disk('public')->exists(
                            $evaluasi->dokumen_kualitatif_path
                        )
                    ) {
                        Storage::disk('public')->delete(
                            $evaluasi->dokumen_kualitatif_path
                        );
                    }

                    /**
                     * --------------------------------------------------------------------------
                     * HAPUS RECORD
                     * --------------------------------------------------------------------------
                     */
                    $evaluasi->delete();
                }
            }
        );

        /**
         * --------------------------------------------------------------------------
         * KUNCI KEMBALI RESET
         * --------------------------------------------------------------------------
         */
        session()->forget(
            $backupSessionKey
        );

        /**
         * --------------------------------------------------------------------------
         * RESPONSE
         * --------------------------------------------------------------------------
         */
        return response()->json([
            'success' => true,
            'message' =>
            'Data SKP Evaluasi tahun ' .
                $tahun .
                ' berhasil direset.',
        ]);
    }

    /**
     * =========================================================
     * HELPER - BULAN DALAM TRIWULAN
     * =========================================================
     */
    private function bulanDalamTriwulan(
        int $triwulan
    ): array {
        $awal =
            (($triwulan - 1) * 3) + 1;

        return [
            $awal,
            $awal + 1,
            $awal + 2,
        ];
    }

    /**
     * =========================================================
     * HELPER - DOKUMEN YANG DIPERLUKAN PER JENIS EVALUASI
     * =========================================================
     *
     * - Bulanan & Triwulan -> hanya "kualitatif"
     * - Tahunan            -> "evaluasi" dan "kualitatif"
     *
     * @return array<int, string>
     */
    private function dokumenDiperlukan(string $jenisAktif): array
    {
        return $jenisAktif === 'tahunan'
            ? ['evaluasi', 'kualitatif']
            : ['kualitatif'];
    }

    /**
     * Semua dokumen yang diperlukan sudah ada dan disetujui.
     */
    private function evaluasiDisetujui(?SkpEvaluasi $evaluasi, string $jenisAktif): bool
    {
        if (!$evaluasi) {
            return false;
        }

        foreach ($this->dokumenDiperlukan($jenisAktif) as $dokumen) {

            if (
                empty($evaluasi->{"dokumen_{$dokumen}_path"})
                || $evaluasi->{"status_dokumen_{$dokumen}"} !== 'disetujui'
            ) {
                return false;
            }
        }

        return true;
    }

    /**
     * Ada dokumen (yang diperlukan) berstatus ditolak.
     */
    private function evaluasiDitolak(?SkpEvaluasi $evaluasi, string $jenisAktif): bool
    {
        if (!$evaluasi) {
            return false;
        }

        foreach ($this->dokumenDiperlukan($jenisAktif) as $dokumen) {

            if ($evaluasi->{"status_dokumen_{$dokumen}"} === 'ditolak') {
                return true;
            }
        }

        return false;
    }

    /**
     * Tidak ada yang ditolak, dan ada dokumen (yang diperlukan)
     * berstatus menunggu.
     */
    private function evaluasiMenunggu(?SkpEvaluasi $evaluasi, string $jenisAktif): bool
    {
        if (!$evaluasi || $this->evaluasiDitolak($evaluasi, $jenisAktif)) {
            return false;
        }

        foreach ($this->dokumenDiperlukan($jenisAktif) as $dokumen) {

            if ($evaluasi->{"status_dokumen_{$dokumen}"} === 'menunggu') {
                return true;
            }
        }

        return false;
    }
}