<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pelaporan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class PelaporanController extends Controller
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
     * HALAMAN PELAPORAN (Upload)
     * =========================================================
     *
     * Query string (semua opsional, default = periode berjalan):
     *
     * ?jenis=mingguan|bulanan
     * &tahun=2026
     * &bulan=10
     *
     * - Mingguan -> tabel menampilkan minggu-minggu pada bulan/tahun terpilih
     * - Bulanan  -> tabel menampilkan Januari - Desember pada tahun terpilih
     */
    public function index(Request $request)
    {
        $sekarang = now(self::TIMEZONE);

        $jenis = $request->query('jenis') === 'bulanan'
            ? 'bulanan'
            : 'mingguan';

        $tahun = (int) $request->query('tahun', $sekarang->year);
        $bulan = (int) $request->query('bulan', $sekarang->month);

        // Nilai di luar jangkauan dikembalikan ke periode berjalan
        // (mencegah error Carbon kalau query string diutak-atik).
        if ($tahun < 2000 || $tahun > 2100) {
            $tahun = $sekarang->year;
        }

        if ($bulan < 1 || $bulan > 12) {
            $bulan = $sekarang->month;
        }

        $tahunOptions = collect(range($sekarang->year - 2, $sekarang->year + 1))
            ->push($tahun)
            ->unique()
            ->sort()
            ->values()
            ->all();

        $pegawaiList = User::where('status', true)
            ->orderBy('nama')
            ->get();

        $mingguDalamBulan = $this->hitungMingguDalamBulan($tahun, $bulan);

        /*
        | Laporan MINGGUAN pada bulan/tahun terpilih
        */
        $pelaporanPeriode = Pelaporan::where('jenis', 'Mingguan')
            ->where('tahun', $tahun)
            ->where('bulan', $bulan)
            ->get()
            ->groupBy('user_id');

        /*
        | Laporan BULANAN untuk 1 tahun penuh (Januari - Desember)
        */
        $pelaporanTahun = Pelaporan::where('jenis', 'Bulanan')
            ->where('tahun', $tahun)
            ->get()
            ->groupBy('user_id');

        return view('admin.pelaporan.index', [
            'pegawaiList' => $pegawaiList,
            'jenis' => $jenis,
            'tahun' => $tahun,
            'bulan' => $bulan,
            'tahunOptions' => $tahunOptions,
            'namaBulan' => self::NAMA_BULAN,
            'mingguDalamBulan' => $mingguDalamBulan,
            'pelaporanPeriode' => $pelaporanPeriode,
            'pelaporanTahun' => $pelaporanTahun,
        ]);
    }

    /**
     * =========================================================
     * UPLOAD LAPORAN (dipanggil via AJAX dari tombol Upload)
     * =========================================================
     */
    public function upload(Request $request)
    {
        $data = $request->validate([
            'file' => ['required', 'file', 'mimes:xls,xlsx', 'max:10240'],
            'jenis' => ['required', 'in:Mingguan,Bulanan'],
            'tahun' => ['required', 'integer', 'min:2000', 'max:2100'],
            'bulan' => ['required', 'integer', 'between:1,12'],
            // Satu bulan bisa punya sampai 6 minggu kalender
            // (contoh: Agustus 2026 -> 1-2, 3-9, 10-16, 17-23, 24-30, 31).
            'minggu' => ['nullable', 'required_if:jenis,Mingguan', 'integer', 'between:1,6'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
        ], [
            'file.required' => 'File laporan wajib dipilih.',
            'file.mimes' => 'File laporan harus berformat Excel (.xls / .xlsx).',
            'file.max' => 'Ukuran file laporan maksimal 10 MB.',
            'minggu.required_if' => 'Minggu wajib dipilih untuk laporan Mingguan.',
        ]);

        $userId = $data['user_id'] ?? Auth::id();

        if (!$userId) {
            throw ValidationException::withMessages([
                'user_id' => 'Pegawai tujuan laporan tidak ditemukan.',
            ]);
        }

        $minggu = $data['jenis'] === 'Mingguan'
            ? (int) $data['minggu']
            : null;

        /*
        |--------------------------------------------------------------------------
        | Hitung tanggal nyata sesuai jenis + periode yang dipilih.
        |--------------------------------------------------------------------------
        */
        try {
            [$tanggalMulai, $tanggalSelesai] = $this->hitungRentangTanggal(
                $data['jenis'],
                (int) $data['tahun'],
                (int) $data['bulan'],
                $minggu
            );
        } catch (\InvalidArgumentException $e) {
            throw ValidationException::withMessages([
                'minggu' => $e->getMessage(),
            ]);
        }

        $kunci = [
            'user_id' => $userId,
            'jenis' => $data['jenis'],
            'tahun' => $data['tahun'],
            'bulan' => $data['bulan'],
            'minggu' => $minggu,
        ];

        // Laporan lama pada periode yang sama (kalau ada) -> akan diganti
        $laporanLama = Pelaporan::where($kunci)->first();
        $pathLama = $laporanLama?->path_file;

        $file = $request->file('file');

        $namaFile = $file->getClientOriginalName();

        $folder = sprintf(
            'pelaporan/%d/%02d',
            $data['tahun'],
            $data['bulan']
        );

        $pathFile = $file->store($folder, 'public');

        $pelaporan = Pelaporan::updateOrCreate(
            $kunci,
            [
                'diupload_oleh' => Auth::id(),
                'tanggal_mulai' => $tanggalMulai,
                'tanggal_selesai' => $tanggalSelesai,
                'nama_file' => $namaFile,
                'path_file' => $pathFile,
                'ukuran_file' => $file->getSize(),
            ]
        );

        // Hapus file lama dari storage supaya tidak menumpuk
        // (sebelumnya file lama tertinggal setelah "Ganti").
        if ($pathLama && $pathLama !== $pathFile) {
            Storage::disk('public')->delete($pathLama);
        }

        return response()->json([
            'success' => true,
            'message' => $laporanLama
                ? 'Laporan berhasil diganti.'
                : 'Laporan berhasil diupload.',
            'data' => [
                'id' => $pelaporan->id,
                'nama_file' => $pelaporan->nama_file,
                'ukuran_file' => $pelaporan->ukuran_file,
                'label_periode' => $pelaporan->label_periode,
                'tanggal_mulai' => $pelaporan->tanggal_mulai->format('d M Y'),
                'tanggal_selesai' => $pelaporan->tanggal_selesai->format('d M Y'),
                'diupload_pada' => $pelaporan->updated_at
                    ->copy()
                    ->timezone(self::TIMEZONE)
                    ->format('d M Y H:i') . ' WIB',
                'url_file' => $pelaporan->url_file,
            ],
        ]);
    }

    /**
     * =========================================================
     * HAPUS LAPORAN YANG SUDAH DIUPLOAD
     * =========================================================
     */
    public function destroy(Pelaporan $pelaporan)
    {
        if ($pelaporan->path_file) {
            Storage::disk('public')->delete($pelaporan->path_file);
        }

        $pelaporan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil dihapus.',
        ]);
    }

    /**
     * =========================================================
     * HITUNG DAFTAR MINGGU (SENIN - MINGGU) DALAM 1 BULAN
     * =========================================================
     *
     * Minggu dihitung berdasarkan MINGGU KALENDER (Senin - Minggu),
     * minggu pertama dan terakhir dipotong supaya tidak keluar
     * dari batas bulan.
     *
     * @return array<int, array{mulai: Carbon, selesai: Carbon}>
     */
    private function hitungMingguDalamBulan(int $tahun, int $bulan): array
    {
        $awalBulan = Carbon::createFromDate($tahun, $bulan, 1)->startOfDay();
        $akhirBulan = $awalBulan->copy()->endOfMonth();

        $mingguList = [];
        $kursor = $awalBulan->copy();
        $index = 1;

        while ($kursor->lessThanOrEqualTo($akhirBulan)) {

            $akhirMinggu = $kursor->copy()
                ->endOfWeek(Carbon::SUNDAY);

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
     * HITUNG RENTANG TANGGAL NYATA PERIODE LAPORAN
     * =========================================================
     *
     * @return array{0: Carbon, 1: Carbon} [tanggalMulai, tanggalSelesai]
     */
    private function hitungRentangTanggal(
        string $jenis,
        int $tahun,
        int $bulan,
        ?int $minggu
    ): array {

        $awalBulan = Carbon::createFromDate($tahun, $bulan, 1)->startOfDay();
        $akhirBulan = $awalBulan->copy()->endOfMonth();

        if ($jenis === 'Bulanan') {
            return [$awalBulan, $akhirBulan];
        }

        $mingguDalamBulan = $this->hitungMingguDalamBulan($tahun, $bulan);

        if (!isset($mingguDalamBulan[$minggu])) {
            throw new \InvalidArgumentException(
                "Bulan ini tidak memiliki Minggu ke-{$minggu}."
            );
        }

        return [
            $mingguDalamBulan[$minggu]['mulai'],
            $mingguDalamBulan[$minggu]['selesai'],
        ];
    }
}