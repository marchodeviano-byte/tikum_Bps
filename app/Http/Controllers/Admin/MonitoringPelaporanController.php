<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pelaporan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use ZipArchive;

class MonitoringPelaporanController extends Controller
{
    private const TIMEZONE = 'Asia/Jakarta';

    private const NAMA_BULAN = [
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

    /**
     * =========================================================
     * HALAMAN MONITORING PELAPORAN
     * =========================================================
     *
     * Query string (semua opsional):
     *
     * ?jenis=mingguan|bulanan
     * &tahun=2026
     * &bulan=10
     * &minggu=semua|1|2|...|6   (hanya relevan untuk jenis mingguan)
     */
    public function index(Request $request)
    {
        [$jenisAktif, $tahun, $bulan] = $this->bacaFilter($request);

        $mingguDalamBulan = $this->hitungMingguDalamBulan($tahun, $bulan);
        $mingguDipilih = $this->bacaMinggu($request, $mingguDalamBulan);

        $mingguTampil = $mingguDipilih === 'semua'
            ? array_keys($mingguDalamBulan)
            : [(int) $mingguDipilih];

        $jenisDb = $jenisAktif === 'bulanan' ? 'Bulanan' : 'Mingguan';

        $sekarang = now(self::TIMEZONE);

        $tahunOptions = collect(range($sekarang->year - 2, $sekarang->year + 1))
            ->push($tahun)
            ->unique()
            ->sort()
            ->values()
            ->all();

        $pegawaiList = User::where('status', true)
            ->orderBy('nama')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Data Mingguan: 1 pegawai -> status per minggu (1..N)
        |--------------------------------------------------------------------------
        */
        $pelaporanMingguan = Pelaporan::where('jenis', 'Mingguan')
            ->where('tahun', $tahun)
            ->where('bulan', $bulan)
            ->get()
            ->groupBy('user_id');

        $dataMingguan = $pegawaiList->map(function ($pegawai) use ($mingguDalamBulan, $pelaporanMingguan) {

            $rekamanPegawai = $pelaporanMingguan->get($pegawai->id, collect());

            $statusPerMinggu = [];

            foreach ($mingguDalamBulan as $noMinggu => $rentang) {
                $statusPerMinggu[$noMinggu] = $rekamanPegawai->firstWhere(
                    'minggu',
                    $noMinggu
                );
            }

            return [
                'pegawai' => $pegawai,
                'status' => $statusPerMinggu,
            ];
        });

        /*
        |--------------------------------------------------------------------------
        | Data Bulanan: 1 pegawai -> status per bulan
        |--------------------------------------------------------------------------
        |
        | - Bulan spesifik -> 1 kolom (bulan itu saja)
        | - Semua Bulan    -> 12 kolom (Januari - Desember pada tahun terpilih)
        |
        */
        $bulanTampil = $bulan === 0
            ? range(1, 12)
            : [$bulan];

        $pelaporanBulanan = Pelaporan::where('jenis', 'Bulanan')
            ->where('tahun', $tahun)
            ->when($bulan !== 0, fn($query) => $query->where('bulan', $bulan))
            ->get()
            ->groupBy('user_id');

        $dataBulanan = $pegawaiList->map(function ($pegawai) use ($pelaporanBulanan, $bulanTampil) {

            $rekamanPegawai = $pelaporanBulanan->get($pegawai->id, collect());

            $statusPerBulan = [];

            foreach ($bulanTampil as $noBulan) {
                $statusPerBulan[$noBulan] = $rekamanPegawai->firstWhere('bulan', $noBulan);
            }

            return [
                'pegawai' => $pegawai,
                'status' => $statusPerBulan,
            ];
        });

        /*
        |--------------------------------------------------------------------------
        | RINGKASAN Sudah Upload / Belum Upload
        |--------------------------------------------------------------------------
        |
        | - Bulanan + bulan spesifik      -> status bulan itu
        | - Bulanan + "Semua Bulan"      -> BULAN BERJALAN (atau Desember
        |                                    untuk tahun yang sudah lewat)
        | - Mingguan + minggu spesifik    -> status minggu itu
        | - Mingguan + "Semua Minggu"     -> MINGGU BERJALAN (atau minggu
        |                                    terakhir kalau bukan bulan ini)
        |
        */
        $mingguRingkasan = null;
        $bulanRingkasan = null;

        if ($jenisAktif === 'bulanan') {

            $bulanRingkasan = $bulan !== 0
                ? $bulan
                : $this->bulanBerjalan($tahun);

            $sudah = $dataBulanan->filter(function ($item) use ($bulanRingkasan) {
                return !empty($item['status'][$bulanRingkasan]);
            })->count();
        } else {

            $mingguRingkasan = $mingguDipilih !== 'semua'
                ? (int) $mingguDipilih
                : $this->mingguBerjalan($mingguDalamBulan, $tahun, $bulan);

            $sudah = $dataMingguan->filter(function ($item) use ($mingguRingkasan) {
                return !empty($item['status'][$mingguRingkasan]);
            })->count();
        }

        $totalPegawai = $pegawaiList->count();
        $belum = $totalPegawai - $sudah;

        /*
        |--------------------------------------------------------------------------
        | Cakupan Download Semua / Reset = jenis + periode yang sedang
        | difilter (sama persis dengan yang terlihat di layar).
        |--------------------------------------------------------------------------
        */
        $jumlahDataReset = $this
            ->queryPeriode($jenisDb, $tahun, $bulan, $mingguDipilih)
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Tombol Reset baru terbuka setelah Download All (backup) dilakukan
        | untuk periode yang sama, dan tidak ada data baru sesudah backup.
        |--------------------------------------------------------------------------
        */
        $backupSudahDilakukan = $jumlahDataReset > 0
            && $this->backupSudahDilakukan($jenisAktif, $tahun, $bulan, $mingguDipilih);

        $labelPeriode = $this->labelPeriode(
            $jenisAktif,
            $tahun,
            $bulan,
            $mingguDipilih,
            $mingguDalamBulan
        );

        return view('admin.monitoring_pelaporan.index', [
            'pegawaiList' => $pegawaiList,
            'mingguDalamBulan' => $mingguDalamBulan,
            'mingguTampil' => $mingguTampil,
            'dataMingguan' => $dataMingguan,
            'dataBulanan' => $dataBulanan,
            'jenisAktif' => $jenisAktif,
            'tahun' => $tahun,
            'bulan' => $bulan,
            'mingguDipilih' => $mingguDipilih,
            'mingguRingkasan' => $mingguRingkasan,
            'bulanRingkasan' => $bulanRingkasan,
            'bulanTampil' => $bulanTampil,
            'labelBulan' => $bulan === 0 ? 'Semua Bulan' : self::NAMA_BULAN[$bulan],
            'tahunOptions' => $tahunOptions,
            'namaBulan' => self::NAMA_BULAN,
            'totalPegawai' => $totalPegawai,
            'sudahUpload' => $sudah,
            'belumUpload' => $belum,
            'jumlahDataReset' => $jumlahDataReset,
            'backupSudahDilakukan' => $backupSudahDilakukan,
            'labelPeriode' => $labelPeriode,
        ]);
    }

    /**
     * =========================================================
     * DOWNLOAD ZIP - SATU PEGAWAI
     * =========================================================
     */
    public function downloadPegawai(User $pegawai, Request $request): BinaryFileResponse|RedirectResponse|JsonResponse
    {
        [$jenisAktif, $tahun, $bulan] = $this->bacaFilter($request);

        $mingguDalamBulan = $this->hitungMingguDalamBulan($tahun, $bulan);
        $mingguDipilih = $this->bacaMinggu($request, $mingguDalamBulan);

        $jenisDb = $jenisAktif === 'bulanan' ? 'Bulanan' : 'Mingguan';

        $laporan = $this->queryPeriode($jenisDb, $tahun, $bulan, $mingguDipilih)
            ->where('user_id', $pegawai->id)
            ->orderBy('minggu')
            ->get();

        $paramsIndex = $this->paramsIndex($jenisAktif, $tahun, $bulan, $mingguDipilih);

        if ($laporan->isEmpty()) {
            return $this->gagal(
                $request,
                'Belum ada laporan yang diupload pegawai ini untuk periode tersebut.',
                $paramsIndex
            );
        }

        $namaZip = 'Laporan_' . $this->bersihkanNama($pegawai->nama)
            . "_{$jenisDb}_{$tahun}_" . $this->kodeBulan($bulan)
            . ($jenisDb === 'Mingguan' && $mingguDipilih !== 'semua' ? "_Minggu-{$mingguDipilih}" : '')
            . '.zip';

        return $this->buatDanKirimZip($laporan, $namaZip, false, $paramsIndex, $request);
    }

    /**
     * =========================================================
     * DOWNLOAD ZIP - SEMUA PEGAWAI
     * =========================================================
     *
     * File dikelompokkan per folder nama pegawai di dalam satu ZIP.
     */
    public function downloadSemua(Request $request): BinaryFileResponse|RedirectResponse|JsonResponse
    {
        [$jenisAktif, $tahun, $bulan] = $this->bacaFilter($request);

        $mingguDalamBulan = $this->hitungMingguDalamBulan($tahun, $bulan);
        $mingguDipilih = $this->bacaMinggu($request, $mingguDalamBulan);

        $jenisDb = $jenisAktif === 'bulanan' ? 'Bulanan' : 'Mingguan';

        $laporan = $this->queryPeriode($jenisDb, $tahun, $bulan, $mingguDipilih)
            ->with('user')
            ->orderBy('user_id')
            ->orderBy('minggu')
            ->get();

        $paramsIndex = $this->paramsIndex($jenisAktif, $tahun, $bulan, $mingguDipilih);

        if ($laporan->isEmpty()) {
            return $this->gagal(
                $request,
                'Belum ada laporan yang diupload untuk periode tersebut.',
                $paramsIndex
            );
        }

        $namaZip = "Laporan_Semua_Pegawai_{$jenisDb}_{$tahun}_"
            . $this->kodeBulan($bulan)
            . ($jenisDb === 'Mingguan' && $mingguDipilih !== 'semua' ? "_Minggu-{$mingguDipilih}" : '')
            . '.zip';

        $response = $this->buatDanKirimZip($laporan, $namaZip, true, $paramsIndex, $request);

        // Backup dianggap berhasil hanya kalau ZIP benar-benar terbentuk.
        // Setelah ini tombol Reset untuk periode tersebut terbuka.
        if ($response instanceof BinaryFileResponse) {
            $this->tandaiBackup($jenisAktif, $tahun, $bulan, $mingguDipilih);
        }

        return $response;
    }

    /**
     * =========================================================
     * RESET DATA PELAPORAN
     * =========================================================
     *
     * Menghapus data laporan DAN file Excel-nya dari storage untuk
     * jenis + periode yang sedang difilter, supaya storage bersih
     * dan tidak berat. Folder yang jadi kosong ikut dihapus.
     *
     * Hanya boleh dilakukan SETELAH Download All (backup) untuk
     * periode yang sama. Dicek juga di server, bukan hanya lewat
     * tombol yang disabled di halaman.
     */
    public function reset(Request $request): JsonResponse|RedirectResponse
    {
        [$jenisAktif, $tahun, $bulan] = $this->bacaFilter($request);

        $mingguDalamBulan = $this->hitungMingguDalamBulan($tahun, $bulan);
        $mingguDipilih = $this->bacaMinggu($request, $mingguDalamBulan);

        $jenisDb = $jenisAktif === 'bulanan' ? 'Bulanan' : 'Mingguan';

        $paramsIndex = $this->paramsIndex($jenisAktif, $tahun, $bulan, $mingguDipilih);

        $laporan = $this->queryPeriode($jenisDb, $tahun, $bulan, $mingguDipilih)->get();

        if ($laporan->isEmpty()) {
            return $this->gagal(
                $request,
                'Tidak ada data laporan pada periode ini yang bisa di-reset.',
                $paramsIndex
            );
        }

        if (!$this->backupSudahDilakukan($jenisAktif, $tahun, $bulan, $mingguDipilih)) {
            return $this->gagal(
                $request,
                'Lakukan Download All terlebih dahulu sebelum melakukan reset.',
                $paramsIndex,
                403
            );
        }

        $labelPeriode = $this->labelPeriode(
            $jenisAktif,
            $tahun,
            $bulan,
            $mingguDipilih,
            $mingguDalamBulan
        );

        $jumlah = $laporan->count();
        $daftarPath = $laporan->pluck('path_file')->filter()->values()->all();
        $idList = $laporan->pluck('id')->all();

        // 1) Hapus baris database dulu (dalam transaksi)
        DB::transaction(function () use ($idList) {
            Pelaporan::whereIn('id', $idList)->delete();
        });

        // 2) Baru hapus file fisiknya
        $disk = Storage::disk('public');

        $folderTerdampak = [];

        foreach ($daftarPath as $path) {
            $disk->delete($path);
            $folderTerdampak[dirname($path)] = true;
        }

        // 3) Rapikan folder yang sudah kosong
        foreach (array_keys($folderTerdampak) as $folder) {
            $this->bersihkanFolderKosong($folder);
        }

        // 4) Tanda backup tidak berlaku lagi (datanya sudah bersih)
        $this->hapusTandaBackup($jenisAktif, $tahun, $bulan, $mingguDipilih);

        $pesan = "{$jumlah} laporan {$jenisDb} ({$labelPeriode}) berhasil direset dan file di server sudah dibersihkan.";

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $pesan,
            ]);
        }

        return redirect()
            ->route('admin.monitoring_pelaporan.index', $paramsIndex)
            ->with('success', $pesan);
    }

    /**
     * =========================================================
     * HELPER: BANGUN FILE ZIP DARI KOLEKSI PELAPORAN
     * =========================================================
     *
     * @param  \Illuminate\Support\Collection<int, Pelaporan>  $laporan
     * @param  array<string, mixed>  $paramsIndex  dipakai untuk redirect kalau gagal
     */
    private function buatDanKirimZip(
        $laporan,
        string $namaZip,
        bool $kelompokkanPerPegawai,
        array $paramsIndex,
        Request $request
    ): BinaryFileResponse|RedirectResponse|JsonResponse {

        @set_time_limit(300);

        if (!class_exists(ZipArchive::class)) {
            return $this->gagal(
                $request,
                'Ekstensi PHP "zip" belum aktif di server, download ZIP tidak dapat dibuat.',
                $paramsIndex,
                500
            );
        }

        $tempDir = storage_path('app/temp');

        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $pathZipSementara = $tempDir . '/' . uniqid('pelaporan_') . '.zip';

        $zip = new ZipArchive();

        if ($zip->open($pathZipSementara, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return $this->gagal($request, 'Gagal membuat file ZIP.', $paramsIndex, 500);
        }

        $jumlahFile = 0;

        foreach ($laporan as $item) {

            $pathAsli = storage_path('app/public/' . $item->path_file);

            if (!$item->path_file || !file_exists($pathAsli)) {
                continue;
            }

            $labelPeriode = $item->jenis === 'Mingguan'
                ? "Minggu-{$item->minggu}"
                : 'Bulan-' . str_pad((string) $item->bulan, 2, '0', STR_PAD_LEFT);

            $namaFileDalamZip = $labelPeriode . '_' . $item->nama_file;

            if ($kelompokkanPerPegawai) {

                $namaFolder = $this->bersihkanNama(
                    $item->user->nama ?? "Pegawai-{$item->user_id}"
                );

                $namaFileDalamZip = $namaFolder . '/' . $namaFileDalamZip;
            }

            $zip->addFile($pathAsli, $namaFileDalamZip);
            $jumlahFile++;
        }

        $zip->close();

        if ($jumlahFile === 0) {

            if (file_exists($pathZipSementara)) {
                @unlink($pathZipSementara);
            }

            return $this->gagal(
                $request,
                'File laporan tidak ditemukan di server (kemungkinan sudah terhapus).',
                $paramsIndex
            );
        }

        return response()
            ->download($pathZipSementara, $namaZip, [
                'Content-Type' => 'application/zip',
            ])
            ->deleteFileAfterSend(true);
    }

    /**
     * =========================================================
     * HELPER: RESPON GAGAL (JSON untuk fetch, redirect untuk link biasa)
     * =========================================================
     *
     * @param  array<string, mixed>  $paramsIndex
     */
    private function gagal(
        Request $request,
        string $pesan,
        array $paramsIndex,
        int $status = 422
    ): JsonResponse|RedirectResponse {

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => $pesan,
            ], $status);
        }

        return redirect()
            ->route('admin.monitoring_pelaporan.index', $paramsIndex)
            ->with('error', $pesan);
    }

    /**
     * =========================================================
     * HELPER: TANDA BACKUP (syarat membuka tombol Reset)
     * =========================================================
     *
     * Disimpan di cache per jenis + periode. Backup dianggap TIDAK
     * berlaku lagi kalau sesudahnya ada laporan baru/diganti pada
     * periode itu (supaya file yang belum ter-backup tidak ikut
     * terhapus). Backup "Semua Minggu" juga berlaku untuk reset
     * satu minggu tertentu.
     */
    private function kunciBackup(string $jenis, int $tahun, int $bulan, string $minggu): string
    {
        return "pelaporan_backup:{$jenis}:{$tahun}:{$bulan}:"
            . ($jenis === 'mingguan' ? $minggu : '-');
    }

    private function tandaiBackup(string $jenis, int $tahun, int $bulan, string $minggu): void
    {
        Cache::put(
            $this->kunciBackup($jenis, $tahun, $bulan, $minggu),
            now()->timestamp,
            now()->addDays(30)
        );
    }

    private function hapusTandaBackup(string $jenis, int $tahun, int $bulan, string $minggu): void
    {
        Cache::forget($this->kunciBackup($jenis, $tahun, $bulan, $minggu));
    }

    private function backupSudahDilakukan(string $jenis, int $tahun, int $bulan, string $minggu): bool
    {
        $jenisDb = $jenis === 'bulanan' ? 'Bulanan' : 'Mingguan';

        $terakhirUbah = $this->queryPeriode($jenisDb, $tahun, $bulan, $minggu)
            ->max('updated_at');

        if (!$terakhirUbah) {
            return false;
        }

        $batas = Carbon::parse($terakhirUbah)->timestamp;

        $daftarKunci = [$this->kunciBackup($jenis, $tahun, $bulan, $minggu)];

        if ($jenis === 'mingguan' && $minggu !== 'semua') {
            $daftarKunci[] = $this->kunciBackup($jenis, $tahun, $bulan, 'semua');
        }

        // Backup "Semua Bulan" juga berlaku untuk reset satu bulan tertentu
        if ($jenis === 'bulanan' && $bulan !== 0) {
            $daftarKunci[] = $this->kunciBackup($jenis, $tahun, 0, $minggu);
        }

        foreach ($daftarKunci as $kunci) {

            $waktuBackup = Cache::get($kunci);

            if ($waktuBackup !== null && (int) $waktuBackup >= $batas) {
                return true;
            }
        }

        return false;
    }

    /**
     * =========================================================
     * HELPER: FILTER & QUERY
     * =========================================================
     */

    /**
     * @return array{0: string, 1: int, 2: int} [jenis, tahun, bulan]
     */
    private function bacaFilter(Request $request): array
    {
        $sekarang = now(self::TIMEZONE);

        $jenis = $request->input('jenis') === 'bulanan'
            ? 'bulanan'
            : 'mingguan';

        $tahun = (int) $request->input('tahun', $sekarang->year);

        if ($tahun < 2000 || $tahun > 2100) {
            $tahun = $sekarang->year;
        }

        /*
        | Bulan: 1..12, atau 0 = "Semua Bulan" (hanya berlaku untuk jenis
        | Bulanan). Untuk jenis Mingguan, bulan harus spesifik karena
        | minggu dihitung per bulan.
        */
        $inputBulan = $request->input('bulan');

        if ($jenis === 'bulanan' && $inputBulan === 'semua') {

            $bulan = 0;
        } else {

            $bulan = (int) ($inputBulan ?? $sekarang->month);

            if ($bulan < 1 || $bulan > 12) {
                $bulan = $sekarang->month;
            }
        }

        return [$jenis, $tahun, $bulan];
    }

    /**
     * 'semua' atau nomor minggu yang benar-benar ada di bulan tsb.
     */
    private function bacaMinggu(Request $request, array $mingguDalamBulan): string
    {
        $minggu = $request->input('minggu', 'semua');

        if ($minggu === 'semua') {
            return 'semua';
        }

        $minggu = (int) $minggu;

        return isset($mingguDalamBulan[$minggu])
            ? (string) $minggu
            : 'semua';
    }

    private function queryPeriode(
        string $jenisDb,
        int $tahun,
        int $bulan,
        string $minggu
    ): Builder {

        $query = Pelaporan::query()
            ->where('jenis', $jenisDb)
            ->where('tahun', $tahun);

        // bulan 0 = "Semua Bulan" (seluruh tahun)
        if ($bulan !== 0) {
            $query->where('bulan', $bulan);
        }

        if ($jenisDb === 'Mingguan' && $minggu !== 'semua') {
            $query->where('minggu', (int) $minggu);
        }

        return $query;
    }

    /**
     * @return array<string, mixed>
     */
    private function paramsIndex(string $jenis, int $tahun, int $bulan, string $minggu): array
    {
        $params = [
            'jenis' => $jenis,
            'tahun' => $tahun,
            'bulan' => $bulan === 0 ? 'semua' : $bulan,
        ];

        if ($jenis === 'mingguan') {
            $params['minggu'] = $minggu;
        }

        return $params;
    }

    private function labelPeriode(
        string $jenis,
        int $tahun,
        int $bulan,
        string $minggu,
        array $mingguDalamBulan
    ): string {

        $namaBulan = self::NAMA_BULAN[$bulan] ?? 'Semua bulan';

        if ($jenis === 'bulanan') {
            return $bulan === 0
                ? "Semua bulan, {$tahun}"
                : "{$namaBulan} {$tahun}";
        }

        if ($minggu === 'semua') {
            return "Semua minggu, {$namaBulan} {$tahun}";
        }

        $rentang = $mingguDalamBulan[(int) $minggu];

        return "Minggu {$minggu}: "
            . $rentang['mulai']->format('j') . ' - '
            . $rentang['selesai']->format('j') . " {$namaBulan} {$tahun}";
    }

    /**
     * Kode bulan untuk nama file ZIP: "09" atau "SemuaBulan".
     */
    private function kodeBulan(int $bulan): string
    {
        return $bulan === 0
            ? 'SemuaBulan'
            : str_pad((string) $bulan, 2, '0', STR_PAD_LEFT);
    }

    /**
     * Bulan yang dipakai untuk ringkasan kalau "Semua Bulan" dipilih.
     */
    private function bulanBerjalan(int $tahun): int
    {
        $sekarang = now(self::TIMEZONE);

        if ($tahun === (int) $sekarang->year) {
            return (int) $sekarang->month;
        }

        return $tahun < (int) $sekarang->year ? 12 : 1;
    }

    private function bersihkanNama(string $nama): string
    {
        $nama = preg_replace('/[^A-Za-z0-9 _\-]/', '', $nama) ?? '';
        $nama = trim(preg_replace('/\s+/', '_', $nama) ?? '', '_');

        return $nama !== '' ? $nama : 'Pegawai';
    }

    /**
     * Hapus folder (dan folder induknya) kalau sudah kosong,
     * berhenti di folder "pelaporan".
     */
    private function bersihkanFolderKosong(string $folder): void
    {
        $disk = Storage::disk('public');

        while (
            $folder !== ''
            && $folder !== '.'
            && $folder !== 'pelaporan'
            && str_starts_with($folder, 'pelaporan/')
        ) {
            if (!$disk->exists($folder)) {
                $folder = dirname($folder);
                continue;
            }

            if (!empty($disk->allFiles($folder)) || !empty($disk->directories($folder))) {
                break;
            }

            $disk->deleteDirectory($folder);

            $folder = dirname($folder);
        }
    }

    /**
     * =========================================================
     * HITUNG DAFTAR MINGGU (SENIN - MINGGU) DALAM 1 BULAN
     * =========================================================
     *
     * Sama persis dengan logic di PelaporanController.
     *
     * @return array<int, array{mulai: Carbon, selesai: Carbon}>
     */
    private function hitungMingguDalamBulan(int $tahun, int $bulan): array
    {
        // bulan 0 ("Semua Bulan") tidak punya minggu; pakai Januari sebagai dummy
        $awalBulan = Carbon::createFromDate($tahun, $bulan > 0 ? $bulan : 1, 1)->startOfDay();
        $akhirBulan = $awalBulan->copy()->endOfMonth();

        $mingguList = [];
        $kursor = $awalBulan->copy();
        $index = 1;

        while ($kursor->lessThanOrEqualTo($akhirBulan)) {

            $akhirMinggu = $kursor->copy()->endOfWeek(Carbon::SUNDAY);

            if ($akhirMinggu->greaterThan($akhirBulan)) {
                $akhirMinggu = $akhirBulan->copy();
            }

            $mingguList[$index] = [
                'mulai' => $kursor->copy(),
                'selesai' => $akhirMinggu->copy(),
            ];

            $kursor = $akhirMinggu->copy()->addDay();
            $index++;
        }

        return $mingguList;
    }

    /**
     * =========================================================
     * TENTUKAN "MINGGU BERJALAN" UNTUK RINGKASAN
     * =========================================================
     */
    private function mingguBerjalan(array $mingguDalamBulan, int $tahun, int $bulan): int
    {
        $hariIni = now(self::TIMEZONE)->startOfDay();

        if ((int) $hariIni->year === $tahun && (int) $hariIni->month === $bulan) {

            foreach ($mingguDalamBulan as $noMinggu => $rentang) {

                if ($hariIni->betweenIncluded($rentang['mulai'], $rentang['selesai']->copy()->endOfDay())) {
                    return $noMinggu;
                }
            }
        }

        return max(array_keys($mingguDalamBulan));
    }
}