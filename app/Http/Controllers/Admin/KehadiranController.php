<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\KehadiranImport;
use App\Models\Kehadiran;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\KjkExport;

class KehadiranController extends Controller
{
    /**
     * =========================================================
     * INDEX
     * =========================================================
     */
    public function index(Request $request)
    {
        $tahun = (int) $request->get('tahun', now()->year);

        $bulan = $request->filled('bulan')
            ? (int) $request->get('bulan')
            : null;

        $triwulan = $request->filled('triwulan')
            ? (int) $request->get('triwulan')
            : null;

        /*
        |--------------------------------------------------------------------------
        | Query Kehadiran
        |--------------------------------------------------------------------------
        */
        $query = Kehadiran::with([
            'user.jabatan',
            'user.role',
        ])
            ->whereYear('tanggal', $tahun);

        /*
        |--------------------------------------------------------------------------
        | Filter Triwulan
        |--------------------------------------------------------------------------
        */
        if ($triwulan) {
            $bulanAwal = (($triwulan - 1) * 3) + 1;
            $bulanAkhir = $bulanAwal + 2;

            $query->whereMonth('tanggal', '>=', $bulanAwal)
                ->whereMonth('tanggal', '<=', $bulanAkhir);
        }

        /*
        |--------------------------------------------------------------------------
        | Filter Bulan
        |--------------------------------------------------------------------------
        */ elseif ($bulan) {
            $query->whereMonth('tanggal', $bulan);
        }

        /*
        |--------------------------------------------------------------------------
        | Ambil Data
        |--------------------------------------------------------------------------
        */
        $kehadirans = $query
            ->orderBy('tanggal')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Group berdasarkan pegawai
        |--------------------------------------------------------------------------
        */
        $employeeGroups = $kehadirans
            ->filter(fn($item) => $item->user)
            ->groupBy('user_id');

        /*
        |--------------------------------------------------------------------------
        | Bentuk Data Pegawai
        |--------------------------------------------------------------------------
        */
        $employees = $employeeGroups->map(function ($items) use ($tahun, $bulan) {

            $first = $items->first();
            $user = $first->user;

            $dailyData = [];

            foreach ($items as $item) {

                $tanggal = $item->tanggal instanceof Carbon
                    ? $item->tanggal->copy()
                    : Carbon::parse($item->tanggal);

                $tanggalKey = $tanggal->format('Y-m-d');

                /*
                |--------------------------------------------------------------------------
                | WEEKEND = LIBUR
                |--------------------------------------------------------------------------
                */
                if ($tanggal->isWeekend()) {

                    $dailyData[$tanggalKey] = [
                        'id' => $item->id,
                        'tanggal' => $tanggalKey,
                        'hari' => $this->namaHari($tanggal),

                        'status' => 'Libur',

                        'jam_masuk' => null,
                        'jam_pulang' => null,

                        'keterangan' => 'Hari Libur',

                        'status_waktu' => 'Libur',

                        'menit_terlambat' => 0,
                        'menit_pulang_cepat' => 0,
                    ];

                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | KODE LIBUR / STATUS LIBUR
                |--------------------------------------------------------------------------
                */
                if ($item->status === 'Libur') {

                    $dailyData[$tanggalKey] = [
                        'id' => $item->id,
                        'tanggal' => $tanggalKey,
                        'hari' => $this->namaHari($tanggal),

                        'status' => 'Libur',

                        'jam_masuk' => null,
                        'jam_pulang' => null,

                        'keterangan' => 'Hari Libur',

                        'status_waktu' => 'Libur',

                        'menit_terlambat' => 0,
                        'menit_pulang_cepat' => 0,
                    ];

                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | HITUNG WAKTU BERDASARKAN DATA SEBENARNYA
                |--------------------------------------------------------------------------
                */
                $waktu = $this->hitungStatusWaktu(
                    $tanggal,
                    $item->status,
                    $item->jam_masuk,
                    $item->jam_pulang
                );

                $dailyData[$tanggalKey] = [
                    'id' => $item->id,
                    'tanggal' => $tanggalKey,
                    'hari' => $this->namaHari($tanggal),

                    'status' => $item->status,

                    'jam_masuk' => $item->jam_masuk,
                    'jam_pulang' => $item->jam_pulang,

                    'keterangan' => $item->keterangan,

                    'status_waktu' => $waktu['status_waktu'],

                    'menit_terlambat' => $waktu['menit_terlambat'],

                    'menit_pulang_cepat' => $waktu['menit_pulang_cepat'],
                ];
            }

            /*
            |--------------------------------------------------------------------------
            | Tambahkan Weekend yang Belum Ada di Database
            |--------------------------------------------------------------------------
            */
            if ($bulan) {

                $daysInMonth = Carbon::create(
                    $tahun,
                    $bulan,
                    1
                )->daysInMonth;

                for ($day = 1; $day <= $daysInMonth; $day++) {

                    $tanggal = Carbon::create(
                        $tahun,
                        $bulan,
                        $day
                    );

                    if ($tanggal->isWeekend()) {

                        $tanggalKey = $tanggal->format('Y-m-d');

                        if (!isset($dailyData[$tanggalKey])) {

                            $dailyData[$tanggalKey] = [
                                'id' => null,

                                'tanggal' => $tanggalKey,

                                'hari' => $this->namaHari($tanggal),

                                'status' => 'Libur',

                                'jam_masuk' => null,
                                'jam_pulang' => null,

                                'keterangan' => 'Hari Libur',

                                'status_waktu' => 'Libur',

                                'menit_terlambat' => 0,
                                'menit_pulang_cepat' => 0,
                            ];
                        }
                    }
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Urutkan Tanggal
            |--------------------------------------------------------------------------
            */
            ksort($dailyData);

            /*
            |--------------------------------------------------------------------------
            | Summary
            |--------------------------------------------------------------------------
            */
            $hadir = 0;
            $izin = 0;
            $sakit = 0;
            $tanpaKeterangan = 0;
            $dinas = 0;
            $libur = 0;

            /*
            |--------------------------------------------------------------------------
            | Jumlah & Detail Terlambat / Pulang Cepat
            |--------------------------------------------------------------------------
            |
            | Sebelumnya cuma dipakai untuk filter (apakah pegawai
            | PERNAH terlambat/pulang cepat), tapi belum ada yang
            | menghitung BERAPA KALI dan tanggal berapa saja.
            |
            | Ini yang bikin badge "0x" dan kolom "Detail
            | Keterlambatan" di modal Pegawai Terlambat kosong.
            |
            */
            $jumlahTerlambat = 0;
            $jumlahPulangCepat = 0;
            $jumlahTepatWaktu = 0;
            $detailTerlambat = [];
            $detailPulangCepat = [];
            $detailTepatWaktu = [];

            foreach ($dailyData as $data) {

                switch ($data['status']) {

                    case 'Hadir':
                        $hadir++;
                        break;

                    case 'Izin':
                        $izin++;
                        break;

                    case 'Sakit':
                        $sakit++;
                        break;

                    case 'Dinas':
                        $dinas++;
                        break;

                    case 'Libur':
                        $libur++;
                        break;

                    case 'Tanpa Keterangan':
                    default:
                        $tanpaKeterangan++;
                        break;
                }

                if ($data['status'] !== 'Hadir') {
                    continue;
                }

                if ((int) $data['menit_terlambat'] > 0) {

                    $jumlahTerlambat++;

                    /*
                    |--------------------------------------------------------------------------
                    | Key 'menit' (BUKAN 'menit_terlambat') supaya
                    | cocok dengan blade: {{ $detail['menit'] ?? 0 }}
                    |--------------------------------------------------------------------------
                    */
                    $detailTerlambat[] = [
                        'tanggal' => $data['tanggal'],
                        'hari' => $data['hari'],
                        'jam_masuk' => $data['jam_masuk'],
                        'jam_pulang' => $data['jam_pulang'],
                        'menit' => (int) $data['menit_terlambat'],
                    ];
                } elseif ((int) $data['menit_pulang_cepat'] === 0) {

                    /*
                    |--------------------------------------------------------------------------
                    | Tepat Waktu murni: tidak terlambat DAN
                    | tidak pulang cepat.
                    |--------------------------------------------------------------------------
                    */
                    $jumlahTepatWaktu++;

                    $detailTepatWaktu[] = [
                        'tanggal' => $data['tanggal'],
                        'hari' => $data['hari'],
                        'jam_masuk' => $data['jam_masuk'],
                        'jam_pulang' => $data['jam_pulang'],
                    ];
                }

                if ((int) $data['menit_pulang_cepat'] > 0) {

                    $jumlahPulangCepat++;

                    $detailPulangCepat[] = [
                        'tanggal' => $data['tanggal'],
                        'hari' => $data['hari'],
                        'jam_masuk' => $data['jam_masuk'],
                        'jam_pulang' => $data['jam_pulang'],
                        'menit' => (int) $data['menit_pulang_cepat'],
                    ];
                }
            }

            return [
                'user_id' => $user->id,

                'nip' => $user->nip,

                'nama' => $user->nama,

                'jabatan' => $user->jabatan->nama_jabatan ?? '-',

                'role' => $user->role->name ?? '-',

                'kehadiran' => array_values($dailyData),

                'hadir' => $hadir,

                'izin' => $izin,

                'sakit' => $sakit,

                'dinas' => $dinas,

                'tanpa_keterangan' => $tanpaKeterangan,

                'libur' => $libur,

                'total' => count($dailyData),

                /*
                |--------------------------------------------------------------------------
                | Data baru untuk modal Tepat Waktu / Terlambat / Pulang Cepat
                |--------------------------------------------------------------------------
                */
                'jumlah_terlambat' => $jumlahTerlambat,

                'detail_terlambat' => $detailTerlambat,

                'jumlah_pulang_cepat' => $jumlahPulangCepat,

                'detail_pulang_cepat' => $detailPulangCepat,

                'jumlah_tepat_waktu' => $jumlahTepatWaktu,

                'detail_tepat_waktu' => $detailTepatWaktu,
            ];
        })->values();

        /*
        |--------------------------------------------------------------------------
        | PEGAWAI TEPAT WAKTU
        |--------------------------------------------------------------------------
        |
        | Yang dihitung adalah pegawai yang memiliki minimal
        | satu hari hadir dengan jam masuk <= 07:30.
        |
        */
        $tepatWaktuEmployees = $employees->filter(function ($employee) {

            foreach ($employee['kehadiran'] as $data) {

                if (
                    $data['status'] === 'Hadir' &&
                    $data['status_waktu'] === 'Tepat Waktu'
                ) {
                    return true;
                }
            }

            return false;
        })->map(function ($employee) {

            /*
            |--------------------------------------------------------------------------
            | 'details' dipakai blade modal Pegawai Tepat Waktu
            | (index_blade.php baris ~1138 & ~1149).
            |--------------------------------------------------------------------------
            */
            $employee['details'] = $employee['detail_tepat_waktu'];

            return $employee;
        })->values();

        /*
        |--------------------------------------------------------------------------
        | PEGAWAI TERLAMBAT
        |--------------------------------------------------------------------------
        |
        | HANYA dihitung jika benar-benar datang setelah 07:30.
        |
        | Tidak lagi menggunakan:
        |
        | menit_terlambat > 0
        |
        | secara langsung dari accessor model.
        |
        */
        $terlambatEmployees = $employees->filter(function ($employee) {

            foreach ($employee['kehadiran'] as $data) {

                if (
                    $data['status'] === 'Hadir' &&
                    (int) $data['menit_terlambat'] > 0
                ) {
                    return true;
                }
            }

            return false;
        })->map(function ($employee) {

            /*
            |--------------------------------------------------------------------------
            | 'details' dipakai blade modal Pegawai Terlambat
            | (index_blade.php baris ~1313 & ~1324, field 'menit').
            |--------------------------------------------------------------------------
            */
            $employee['details'] = $employee['detail_terlambat'];

            return $employee;
        })->values();

        /*
        |--------------------------------------------------------------------------
        | PEGAWAI PULANG CEPAT
        |--------------------------------------------------------------------------
        |
        | Dihitung jika jam pulang lebih awal dari jam pulang standar.
        |
        */
        $pulangCepatEmployees = $employees->filter(function ($employee) {

            foreach ($employee['kehadiran'] as $data) {

                if (
                    $data['status'] === 'Hadir' &&
                    (int) $data['menit_pulang_cepat'] > 0
                ) {
                    return true;
                }
            }

            return false;
        })->map(function ($employee) {

            /*
            |--------------------------------------------------------------------------
            | 'details' dipakai blade modal Pegawai Pulang Cepat
            | (index_blade.php baris ~1506 & ~1517, field 'menit').
            |--------------------------------------------------------------------------
            */
            $employee['details'] = $employee['detail_pulang_cepat'];

            return $employee;
        })->values();

        /*
        |--------------------------------------------------------------------------
        | Statistik
        |--------------------------------------------------------------------------
        */
        $stats = [
            'total_pegawai' => $employees->count(),

            'tepat_waktu' => $tepatWaktuEmployees->count(),

            'terlambat' => $terlambatEmployees->count(),

            'pulang_cepat' => $pulangCepatEmployees->count(),
        ];

        /*
        |--------------------------------------------------------------------------
        | Daftar Tahun
        |--------------------------------------------------------------------------
        */
        $tahunList = Kehadiran::selectRaw('YEAR(tanggal) as tahun')
            ->distinct()
            ->orderByDesc('tahun')
            ->pluck('tahun');

        if (!$tahunList->contains($tahun)) {
            $tahunList->prepend($tahun);
        }

        /*
        |--------------------------------------------------------------------------
        | Daftar Bulan
        |--------------------------------------------------------------------------
        */
        $bulanList = collect(range(1, 12))
            ->mapWithKeys(function ($item) {

                return [
                    $item => $this->namaBulan($item),
                ];
            });

        /*
        |--------------------------------------------------------------------------
        | KJK (Keterlambatan Jam Kerja) - rekap 1 tahun penuh
        |--------------------------------------------------------------------------
        */
        $kjkTahunan = $this->hitungKjkTahunan($tahun, $bulan, $triwulan);

        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */
        return view(
            'admin.kehadiran.index',
            compact(
                'employees',
                'stats',
                'tahun',
                'bulan',
                'triwulan',
                'tahunList',
                'bulanList',
                'tepatWaktuEmployees',
                'terlambatEmployees',
                'pulangCepatEmployees',
                'kjkTahunan'
            )
        );
    }

    /**
     * =========================================================
    /**
     * =========================================================
     * EXPORT KJK KE EXCEL
     * =========================================================
     *
     * Mengikuti filter tahun/bulan/triwulan yang sama dengan
     * yang sedang aktif di halaman Kehadiran.
     */
    public function exportKjk(Request $request)
    {
        $tahun = (int) $request->get('tahun', now()->year);

        $bulan = $request->filled('bulan')
            ? (int) $request->get('bulan')
            : null;

        $triwulan = $request->filled('triwulan')
            ? (int) $request->get('triwulan')
            : null;

        $bulanList = collect(range(1, 12))
            ->mapWithKeys(fn($item) => [$item => $this->namaBulan($item)]);

        $kjkTahunan = $this->hitungKjkTahunan($tahun, $bulan, $triwulan);

        $namaFile = $bulan
            ? "KJK_{$this->namaBulan($bulan)}_{$tahun}.xlsx"
            : "KJK_{$tahun}.xlsx";

        return Excel::download(
            new KjkExport($kjkTahunan, $bulanList, $tahun),
            $namaFile
        );
    }

    /**
     * =========================================================
     * KJK (KETERLAMBATAN JAM KERJA) - REKAP 1 TAHUN PENUH
     * =========================================================
     *
     * KJK per bulan = total menit terlambat + total menit
     * pulang cepat, dijumlahkan dari SEMUA hari Hadir dalam
     * bulan itu.
     *
     * Contoh: telat 2x (1 menit & 3 menit) + pulang cepat 1x
     * (3 menit) -> KJK bulan itu = 1 + 3 + 3 = 7 menit.
     *
     * PENTING: query di sini MENGIKUTI filter bulan/triwulan yang
     * aktif di halaman Kehadiran. Kalau tidak ada filter bulan
     * maupun triwulan (mode "1 tahun penuh"), maka seluruh
     * Januari-Desember tetap ditampilkan seperti biasa.
     *
     * @return \Illuminate\Support\Collection
     */
    private function hitungKjkTahunan(int $tahun, ?int $bulan = null, ?int $triwulan = null)
    {
        $query = Kehadiran::with('user')
            ->whereYear('tanggal', $tahun);

        if ($bulan) {

            $query->whereMonth('tanggal', $bulan);
        } elseif ($triwulan) {

            $bulanAwal = (($triwulan - 1) * 3) + 1;
            $bulanAkhir = $bulanAwal + 2;

            $query->whereMonth('tanggal', '>=', $bulanAwal)
                ->whereMonth('tanggal', '<=', $bulanAkhir);
        }

        $kehadirans = $query
            ->get()
            ->filter(fn($item) => $item->user);

        return $kehadirans
            ->groupBy('user_id')
            ->map(function ($items) {

                $user = $items->first()->user;

                $kjkPerBulan = array_fill(1, 12, 0);

                foreach ($items as $item) {

                    /*
                    |--------------------------------------------------------------------------
                    | Hanya hari Hadir (bukan weekend/libur/izin/
                    | sakit/dinas/tanpa keterangan) yang bisa
                    | menyumbang menit terlambat/pulang cepat.
                    |--------------------------------------------------------------------------
                    */
                    if ($item->status !== 'Hadir') {
                        continue;
                    }

                    $tanggal = $item->tanggal instanceof Carbon
                        ? $item->tanggal->copy()
                        : Carbon::parse($item->tanggal);

                    if ($tanggal->isWeekend()) {
                        continue;
                    }

                    $waktu = $this->hitungStatusWaktu(
                        $tanggal,
                        $item->status,
                        $item->jam_masuk,
                        $item->jam_pulang
                    );

                    $bulanKe = (int) $tanggal->format('n');

                    $kjkPerBulan[$bulanKe] +=
                        (int) $waktu['menit_terlambat'] +
                        (int) $waktu['menit_pulang_cepat'];
                }

                return [
                    'nip' => $user->nip,
                    'nama' => $user->nama,
                    'per_bulan' => $kjkPerBulan,
                    'total_tahun' => array_sum($kjkPerBulan),
                ];
            })
            ->sortBy('nama')
            ->values();
    }

    /**
     * =========================================================
     * HITUNG STATUS WAKTU
     * =========================================================
     *
     * Aturan:
     *
     * Jam masuk:
     * <= 07:30:00 = Tepat Waktu
     * >  07:30:00 = Terlambat
     *
     * Jam pulang:
     * Senin - Kamis = 16:00:00
     * Jumat         = 16:30:00
     *
     * Jika pulang sebelum standar = Pulang Cepat
     */
    private function hitungStatusWaktu(
        Carbon $tanggal,
        ?string $status,
        $jamMasuk,
        $jamPulang
    ): array {

        /*
        |--------------------------------------------------------------------------
        | Default
        |--------------------------------------------------------------------------
        */
        $hasil = [
            'status_waktu' => null,

            'menit_terlambat' => 0,

            'menit_pulang_cepat' => 0,
        ];

        /*
        |--------------------------------------------------------------------------
        | Hanya Hadir yang dihitung
        |--------------------------------------------------------------------------
        */
        if ($status !== 'Hadir') {

            $hasil['status_waktu'] = $status ?? 'Tanpa Keterangan';

            return $hasil;
        }

        /*
        |--------------------------------------------------------------------------
        | Weekend
        |--------------------------------------------------------------------------
        */
        if ($tanggal->isWeekend()) {

            $hasil['status_waktu'] = 'Libur';

            return $hasil;
        }

        /*
        |--------------------------------------------------------------------------
        | JAM MASUK
        |--------------------------------------------------------------------------
        */
        $masuk = $this->parseTimeForCalculation(
            $jamMasuk
        );

        /*
        |--------------------------------------------------------------------------
        | JAM PULANG
        |--------------------------------------------------------------------------
        */
        $pulang = $this->parseTimeForCalculation(
            $jamPulang
        );

        /*
        |--------------------------------------------------------------------------
        | Standar Jam Masuk
        |--------------------------------------------------------------------------
        */
        $standarMasuk = '07:30:00';

        /*
        |--------------------------------------------------------------------------
        | Standar Jam Pulang
        |--------------------------------------------------------------------------
        |
        | Jumat = 16:30
        | Senin-Kamis = 16:00
        |
        */
        $standarPulang = $tanggal->dayOfWeekIso === 5
            ? '16:30:00'
            : '16:00:00';

        /*
        |--------------------------------------------------------------------------
        | Jika Jam Masuk Tidak Ada
        |--------------------------------------------------------------------------
        */
        if ($masuk === null) {

            $hasil['status_waktu'] = 'Tanpa Jam Masuk';
        } else {

            $masukTime = Carbon::createFromFormat(
                'H:i:s',
                $masuk
            );

            $standarMasukTime = Carbon::createFromFormat(
                'H:i:s',
                $standarMasuk
            );

            /*
            |--------------------------------------------------------------------------
            | BATAS TOLERANSI (akhir menit ke-30)
            |--------------------------------------------------------------------------
            |
            | Toleransi sampai detik terakhir di menit standar.
            |
            | Contoh standar 07:30:
            |
            | 07:30:00 s/d 07:30:59 = Tepat Waktu
            | 07:31:00 ke atas      = Terlambat
            |
            | Jadi baru dianggap Terlambat kalau sudah masuk
            | menit berikutnya, bukan begitu lewat detik ke-0.
            |
            */
            $batasToleransiMasuk = $standarMasukTime
                ->copy()
                ->endOfMinute();

            /*
            |--------------------------------------------------------------------------
            | TERLAMBAT
            |--------------------------------------------------------------------------
            |
            | HANYA positif jika jam masuk sudah melewati
            | menit standar (mis. sudah masuk 07:31:00).
            |
            */
            if ($masukTime->greaterThan($batasToleransiMasuk)) {

                $selisihDetik = $standarMasukTime->diffInSeconds(
                    $masukTime
                );

                /*
                |--------------------------------------------------------------------------
                | Dibulatkan KE BAWAH (floor), bukan ke atas (ceil).
                |--------------------------------------------------------------------------
                |
                | Menit terlambat = jumlah menit PENUH yang sudah
                | lewat dari jam standar.
                |
                | Contoh:
                | 07:32:01 -> selisih 121 detik -> 2 menit penuh
                |             (bukan dibulatkan jadi 3 menit)
                | 07:31:05 -> selisih 65 detik  -> 1 menit penuh
                |             (bukan dibulatkan jadi 2 menit)
                |
                */
                $hasil['menit_terlambat'] = (int) floor(
                    $selisihDetik / 60
                );

                $hasil['status_waktu'] = 'Terlambat';
            } else {

                /*
                |--------------------------------------------------------------------------
                | Datang sebelum / tepat 07:30
                |--------------------------------------------------------------------------
                */
                $hasil['status_waktu'] = 'Tepat Waktu';
            }
        }

        /*
        |--------------------------------------------------------------------------
        | PULANG CEPAT
        |--------------------------------------------------------------------------
        */
        if ($pulang !== null) {

            $pulangTime = Carbon::createFromFormat(
                'H:i:s',
                $pulang
            );

            $standarPulangTime = Carbon::createFromFormat(
                'H:i:s',
                $standarPulang
            );

            /*
            |--------------------------------------------------------------------------
            | HANYA positif jika pulang sebelum standar
            |--------------------------------------------------------------------------
            */
            if ($pulangTime->lessThan($standarPulangTime)) {

                $selisihDetik = $pulangTime->diffInSeconds(
                    $standarPulangTime
                );

                /*
                |--------------------------------------------------------------------------
                | Sama seperti menit_terlambat: floor, bukan ceil,
                | supaya konsisten (menit penuh yang sudah lewat).
                |--------------------------------------------------------------------------
                */
                $hasil['menit_pulang_cepat'] = (int) floor(
                    $selisihDetik / 60
                );
            }
        }

        return $hasil;
    }

    /**
     * =========================================================
     * PARSE TIME UNTUK PERHITUNGAN
     * =========================================================
     */
    private function parseTimeForCalculation(
        $value
    ): ?string {

        if (
            $value === null ||
            $value === ''
        ) {
            return null;
        }

        if (
            $value instanceof \DateTimeInterface
        ) {
            return $value->format('H:i:s');
        }

        if (
            is_numeric($value) &&
            (float) $value >= 0 &&
            (float) $value < 1
        ) {

            $seconds = (int) round(
                (float) $value * 86400
            );

            $seconds = max(
                0,
                min($seconds, 86399)
            );

            return gmdate(
                'H:i:s',
                $seconds
            );
        }

        $value = trim(
            (string) $value
        );

        if (
            preg_match(
                '/^(\d{1,2}):(\d{2}):(\d{2})$/',
                $value,
                $matches
            )
        ) {

            $hour = (int) $matches[1];

            $minute = (int) $matches[2];

            $second = (int) $matches[3];

            if (
                $hour <= 23 &&
                $minute <= 59 &&
                $second <= 59
            ) {

                return sprintf(
                    '%02d:%02d:%02d',
                    $hour,
                    $minute,
                    $second
                );
            }
        }

        if (
            preg_match(
                '/^(\d{1,2}):(\d{2})$/',
                $value,
                $matches
            )
        ) {

            $hour = (int) $matches[1];

            $minute = (int) $matches[2];

            if (
                $hour <= 23 &&
                $minute <= 59
            ) {

                return sprintf(
                    '%02d:%02d:00',
                    $hour,
                    $minute
                );
            }
        }

        if (
            preg_match(
                '/^(\d{1,2})\.(\d{2})$/',
                $value,
                $matches
            )
        ) {

            $hour = (int) $matches[1];

            $minute = (int) $matches[2];

            if (
                $hour <= 23 &&
                $minute <= 59
            ) {

                return sprintf(
                    '%02d:%02d:00',
                    $hour,
                    $minute
                );
            }
        }

        return null;
    }

    /**
     * =========================================================
     * FORM IMPORT
     * =========================================================
     */
    public function importForm()
    {
        return view(
            'admin.kehadiran.import'
        );
    }

    /**
     * =========================================================
     * PREVIEW IMPORT
     * =========================================================
     */
    public function importPreview(
        Request $request
    ) {

        $request->validate([
            'file' => [
                'required',
                'file',
                'mimes:xlsx,xls',
                'max:10240',
            ],
        ], [

            'file.required' =>
            'Silakan pilih file Excel terlebih dahulu.',

            'file.file' =>
            'File yang dikirim tidak valid.',

            'file.mimes' =>
            'File harus berformat .xlsx atau .xls.',

            'file.max' =>
            'Ukuran file maksimal 10 MB.',
        ]);

        $path = null;
        $previewPath = null;

        try {

            /*
            |--------------------------------------------------------------------------
            | Hapus Import Lama
            |--------------------------------------------------------------------------
            */
            $this->clearImportSession();

            /*
            |--------------------------------------------------------------------------
            | Simpan Excel Temporary
            |--------------------------------------------------------------------------
            */
            $file = $request->file('file');

            $filename =
                'kehadiran_' .
                Str::uuid() .
                '.' .
                $file->getClientOriginalExtension();

            $path = $file->storeAs(
                'imports/kehadiran',
                $filename,
                'local'
            );

            /*
            |--------------------------------------------------------------------------
            | Baca Excel
            |--------------------------------------------------------------------------
            */
            $sheets = Excel::toArray(
                new KehadiranImport(),
                $path,
                'local'
            );

            if (
                empty($sheets) ||
                empty($sheets[0])
            ) {

                Storage::disk('local')->delete(
                    $path
                );

                return back()
                    ->withInput()
                    ->with(
                        'error_import',
                        'File Excel tidak memiliki data yang dapat dibaca.'
                    );
            }

            /*
            |--------------------------------------------------------------------------
            | Parsing
            |--------------------------------------------------------------------------
            */
            $parsed = $this->parsePresensiBulanan(
                $sheets[0]
            );

            if (
                empty($parsed) ||
                empty($parsed['employees'])
            ) {

                Storage::disk('local')->delete(
                    $path
                );

                return back()
                    ->withInput()
                    ->with(
                        'error_import',
                        'Data kehadiran tidak ditemukan. Pastikan file menggunakan template Presensi Bulanan BPS.'
                    );
            }

            /*
            |--------------------------------------------------------------------------
            | Simpan Preview JSON
            |--------------------------------------------------------------------------
            */
            $previewFilename =
                'preview_' .
                Str::uuid() .
                '.json';

            $previewPath =
                'imports/kehadiran/' .
                $previewFilename;

            Storage::disk('local')->put(
                $previewPath,
                json_encode(
                    $parsed,
                    JSON_UNESCAPED_UNICODE |
                        JSON_PRETTY_PRINT
                )
            );

            /*
            |--------------------------------------------------------------------------
            | Statistik Preview
            |--------------------------------------------------------------------------
            */
            $employees = collect(
                $parsed['employees'] ?? []
            );

            $jumlahPegawai =
                $employees->count();

            $mapped =
                $employees
                ->where('mapped', true)
                ->count();

            $unmapped =
                $employees
                ->where('mapped', false)
                ->count();

            $totalData =
                $employees->sum(
                    function ($employee) {
                        return count(
                            $employee['kehadiran'] ?? []
                        );
                    }
                );

            $tahun =
                $parsed['tahun']
                ?? now()->year;

            $bulan =
                $parsed['bulan']
                ?? now()->month;

            $bulanNama =
                $this->namaBulan(
                    $bulan
                );

            /*
            |--------------------------------------------------------------------------
            | Session
            |--------------------------------------------------------------------------
            */
            session([
                'kehadiran_import_file' =>
                $path,

                'kehadiran_import_preview_file' =>
                $previewPath,

                'kehadiran_import_info' => [

                    'tahun' =>
                    $tahun,

                    'bulan' =>
                    $bulan,

                    'bulan_nama' =>
                    $bulanNama,

                    'jumlah_pegawai' =>
                    $jumlahPegawai,

                    'mapped' =>
                    $mapped,

                    'unmapped' =>
                    $unmapped,

                    'total_data' =>
                    $totalData,
                ],
            ]);

            /*
            |--------------------------------------------------------------------------
            | LANGSUNG TAMPILKAN PREVIEW
            |--------------------------------------------------------------------------
            */
            return view(
                'admin.kehadiran.preview',
                compact(
                    'bulanNama',
                    'tahun',
                    'jumlahPegawai',
                    'totalData',
                    'mapped',
                    'unmapped',
                    'employees'
                )
            );
        } catch (\Throwable $e) {

            if (
                $path &&
                Storage::disk('local')->exists($path)
            ) {

                Storage::disk('local')->delete(
                    $path
                );
            }

            if (
                $previewPath &&
                Storage::disk('local')->exists($previewPath)
            ) {

                Storage::disk('local')->delete(
                    $previewPath
                );
            }

            session()->forget([
                'kehadiran_import_file',
                'kehadiran_import_preview_file',
                'kehadiran_import_info',
            ]);

            return back()
                ->withInput()
                ->with(
                    'error_import',
                    'File gagal diproses: ' .
                        $e->getMessage()
                );
        }
    }

    /**
     * =========================================================
     * CLEAR IMPORT SESSION
     * =========================================================
     */
    private function clearImportSession(): void
    {
        $oldFile = session(
            'kehadiran_import_file'
        );

        $oldPreview = session(
            'kehadiran_import_preview_file'
        );

        /*
        |--------------------------------------------------------------------------
        | Hapus Excel Lama
        |--------------------------------------------------------------------------
        */
        if (
            $oldFile &&
            Storage::disk('local')->exists($oldFile)
        ) {

            Storage::disk('local')->delete(
                $oldFile
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Hapus Preview Lama
        |--------------------------------------------------------------------------
        */
        if (
            $oldPreview &&
            Storage::disk('local')->exists($oldPreview)
        ) {

            Storage::disk('local')->delete(
                $oldPreview
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Hapus Session
        |--------------------------------------------------------------------------
        */
        session()->forget([
            'kehadiran_import_file',
            'kehadiran_import_preview_file',
            'kehadiran_import_info',
        ]);
    }

    /**
     * =========================================================
     * STORE IMPORT
     * =========================================================
     */
    public function importStore(
        Request $request
    ) {

        $previewPath =
            session(
                'kehadiran_import_preview_file'
            );

        $filePath =
            session(
                'kehadiran_import_file'
            );

        /*
        |--------------------------------------------------------------------------
        | Preview Tidak Ada
        |--------------------------------------------------------------------------
        */
        if (
            !$previewPath ||
            !Storage::disk('local')->exists(
                $previewPath
            )
        ) {

            return redirect()
                ->route(
                    'admin.kehadiran.import'
                )
                ->with(
                    'error_import',
                    'Data preview tidak ditemukan. Silakan upload dan preview file Excel kembali.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Baca Preview JSON
        |--------------------------------------------------------------------------
        */
        try {

            $previewJson =
                Storage::disk('local')->get(
                    $previewPath
                );

            $preview =
                json_decode(
                    $previewJson,
                    true,
                    512,
                    JSON_THROW_ON_ERROR
                );
        } catch (\Throwable $e) {

            return redirect()
                ->route(
                    'admin.kehadiran.import'
                )
                ->with(
                    'error_import',
                    'Data preview tidak valid. Silakan upload ulang file Excel.'
                );
        }

        if (!is_array($preview)) {

            return redirect()
                ->route(
                    'admin.kehadiran.import'
                )
                ->with(
                    'error_import',
                    'Data preview tidak valid. Silakan upload ulang file Excel.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Tahun & Bulan
        |--------------------------------------------------------------------------
        */
        $tahunImport =
            $preview['tahun']
            ?? now()->year;

        $bulanImport =
            $preview['bulan']
            ?? now()->month;

        $inserted = 0;
        $updated = 0;
        $skipped = 0;

        /*
        |--------------------------------------------------------------------------
        | Transaction
        |--------------------------------------------------------------------------
        */
        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | Loop Pegawai
            |--------------------------------------------------------------------------
            */
            foreach (
                $preview['employees'] ?? []
                as $employee
            ) {

                /*
                |--------------------------------------------------------------------------
                | Tidak Terpetakan
                |--------------------------------------------------------------------------
                */
                if (
                    empty($employee['user_id'])
                ) {

                    $skipped++;

                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Cari User
                |--------------------------------------------------------------------------
                */
                $user =
                    User::find(
                        $employee['user_id']
                    );

                if (!$user) {

                    $skipped++;

                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Loop Kehadiran
                |--------------------------------------------------------------------------
                */
                foreach (
                    $employee['kehadiran'] ?? []
                    as $data
                ) {

                    if (
                        empty($data['tanggal'])
                    ) {
                        continue;
                    }

                    $tanggal =
                        Carbon::parse(
                            $data['tanggal']
                        );

                    $status =
                        $data['status']
                        ?? 'Tanpa Keterangan';

                    $jamMasuk =
                        $data['jam_masuk']
                        ?? null;

                    $jamPulang =
                        $data['jam_pulang']
                        ?? null;

                    $keterangan =
                        $data['keterangan']
                        ?? null;

                    $kode =
                        strtoupper(
                            trim(
                                (string) (
                                    $data['kode']
                                    ?? ''
                                )
                            )
                        );

                    /*
                    |--------------------------------------------------------------------------
                    | WEEKEND = LIBUR
                    |--------------------------------------------------------------------------
                    */
                    if (
                        $tanggal->isWeekend()
                    ) {

                        $status =
                            'Libur';

                        $jamMasuk =
                            null;

                        $jamPulang =
                            null;

                        $keterangan =
                            'Hari Libur';
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | KODE L = LIBUR
                    |--------------------------------------------------------------------------
                    |
                    | Ini sengaja diprioritaskan.
                    |
                    */ elseif (
                        $kode === 'L' ||
                        $status === 'Libur'
                    ) {

                        $status =
                            'Libur';

                        $jamMasuk =
                            null;

                        $jamPulang =
                            null;

                        $keterangan =
                            'Hari Libur';
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Payload
                    |--------------------------------------------------------------------------
                    */
                    $payload = [

                        'user_id' =>
                        $user->id,

                        'tanggal' =>
                        $tanggal->format(
                            'Y-m-d'
                        ),

                        'status' =>
                        $status,

                        'jam_masuk' =>
                        $jamMasuk,

                        'jam_pulang' =>
                        $jamPulang,

                        'keterangan' =>
                        $keterangan,
                    ];

                    /*
                    |--------------------------------------------------------------------------
                    | Cari Data Lama
                    |--------------------------------------------------------------------------
                    */
                    $existing =
                        Kehadiran::where(
                            'user_id',
                            $user->id
                        )
                        ->whereDate(
                            'tanggal',
                            $tanggal->format(
                                'Y-m-d'
                            )
                        )
                        ->first();

                    /*
                    |--------------------------------------------------------------------------
                    | UPDATE
                    |--------------------------------------------------------------------------
                    */
                    if ($existing) {

                        $existing->update(
                            $payload
                        );

                        $updated++;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | INSERT
                    |--------------------------------------------------------------------------
                    */ else {

                        Kehadiran::create(
                            $payload
                        );

                        $inserted++;
                    }
                }
            }

            DB::commit();
        } catch (\Throwable $e) {

            DB::rollBack();

            return redirect()
                ->route(
                    'admin.kehadiran.import'
                )
                ->with(
                    'error_import',
                    'Data gagal disimpan: ' .
                        $e->getMessage()
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Pesan Success
        |--------------------------------------------------------------------------
        */
        $pesan = sprintf(
            'Data kehadiran berhasil disimpan. %d data baru ditambahkan dan %d data diperbarui.',
            $inserted,
            $updated
        );

        /*
        |--------------------------------------------------------------------------
        | Hapus Excel Temporary
        |--------------------------------------------------------------------------
        */
        if (
            $filePath &&
            Storage::disk('local')->exists(
                $filePath
            )
        ) {

            Storage::disk('local')->delete(
                $filePath
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Hapus Preview
        |--------------------------------------------------------------------------
        */
        if (
            $previewPath &&
            Storage::disk('local')->exists(
                $previewPath
            )
        ) {

            Storage::disk('local')->delete(
                $previewPath
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Hapus Session
        |--------------------------------------------------------------------------
        */
        session()->forget([
            'kehadiran_import_file',
            'kehadiran_import_preview_file',
            'kehadiran_import_info',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Redirect Index
        |--------------------------------------------------------------------------
        */
        return redirect()
            ->route(
                'admin.kehadiran.index',
                [
                    'tahun' =>
                    $tahunImport,

                    'bulan' =>
                    $bulanImport,
                ]
            )
            ->with(
                'success',
                $pesan
            )
            ->with(
                'import_skipped',
                $skipped
            );
    }

    /**
     * =========================================================
     * PARSE PRESENSI BULANAN
     * =========================================================
     */
    private function parsePresensiBulanan(
        array $rows
    ): array {

        $tahun = now()->year;

        $bulan = now()->month;

        /*
        |--------------------------------------------------------------------------
        | Cari Tahun
        |--------------------------------------------------------------------------
        */
        foreach ($rows as $row) {

            foreach ($row as $cell) {

                $value =
                    $this->cleanExcelValue(
                        $cell
                    );

                if (
                    preg_match(
                        '/Tahun\s*:\s*(\d{4})/i',
                        $value,
                        $matches
                    )
                ) {

                    $tahun =
                        (int) $matches[1];
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Cari Bulan
        |--------------------------------------------------------------------------
        */
        foreach ($rows as $row) {

            foreach ($row as $cell) {

                $value =
                    $this->cleanExcelValue(
                        $cell
                    );

                if (
                    preg_match(
                        '/Bulan\s*:\s*(.+)/i',
                        $value,
                        $matches
                    )
                ) {

                    $bulan =
                        $this->namaBulanKeAngka(
                            trim(
                                $matches[1]
                            )
                        );
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Cari Header
        |--------------------------------------------------------------------------
        */
        $headerRow = null;

        foreach (
            $rows as $index => $row
        ) {

            $values =
                array_map(
                    fn($value) => strtoupper(
                        trim(
                            $this->cleanExcelValue(
                                $value
                            )
                        )
                    ),
                    $row
                );

            $hasNip =
                in_array(
                    'NIP',
                    $values,
                    true
                );

            $hasNama =
                in_array(
                    'NAMA',
                    $values,
                    true
                );

            if (
                $hasNip &&
                $hasNama
            ) {

                $headerRow =
                    $index;

                break;
            }
        }

        if ($headerRow === null) {

            throw new \RuntimeException(
                'Header NIP dan NAMA tidak ditemukan.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Ambil Header
        |--------------------------------------------------------------------------
        */
        $header =
            $rows[$headerRow];

        $nipColumn = null;

        $namaColumn = null;

        foreach (
            $header as $column => $value
        ) {

            $value =
                strtoupper(
                    trim(
                        $this->cleanExcelValue(
                            $value
                        )
                    )
                );

            if ($value === 'NIP') {
                $nipColumn = $column;
            }

            if ($value === 'NAMA') {
                $namaColumn = $column;
            }
        }

        if (
            $nipColumn === null ||
            $namaColumn === null
        ) {

            throw new \RuntimeException(
                'Kolom NIP atau NAMA tidak ditemukan.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Cari Kolom Tanggal
        |--------------------------------------------------------------------------
        */
        $tanggalColumns = [];

        foreach (
            $header as $column => $value
        ) {

            $value =
                trim(
                    $this->cleanExcelValue(
                        $value
                    )
                );

            if (
                preg_match(
                    '/^(0?[1-9]|[12][0-9]|3[01])$/',
                    $value
                )
            ) {

                $day =
                    (int) $value;

                if (
                    $day >= 1 &&
                    $day <= 31
                ) {

                    $tanggalColumns[$column] =
                        $day;
                }
            }
        }

        if (
            empty($tanggalColumns)
        ) {

            throw new \RuntimeException(
                'Kolom tanggal 01 sampai 31 tidak ditemukan.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Cari BARIS PEGAWAI PERTAMA
        |--------------------------------------------------------------------------
        |
        | Kita cari dulu supaya pencarian kode S/M/L
        | tidak salah mengambil data pegawai.
        |
        */
        $firstEmployeeRow = null;

        for (
            $i = $headerRow + 1;
            $i < count($rows);
            $i++
        ) {

            $candidate =
                $rows[$i] ?? [];

            $nipCandidate =
                $this->normalizeNip(
                    $this->cleanExcelValue(
                        $candidate[$nipColumn] ?? ''
                    )
                );

            $namaCandidate =
                trim(
                    $this->cleanExcelValue(
                        $candidate[$namaColumn] ?? ''
                    )
                );

            /*
            |--------------------------------------------------------------------------
            | NIP minimal 5 digit + Nama
            |--------------------------------------------------------------------------
            |
            | Sebelumnya syarat minimal 10 digit, padahal NIP
            | internal satker ini hanya 9 digit (mis. 340013240).
            | Akibatnya firstEmployeeRow tidak pernah ketemu.
            | Diturunkan ke 5 digit supaya tetap aman membedakan
            | dari teks non-NIP (misal "Kode Libur"), tapi tidak
            | menolak NIP pendek yang valid.
            |
            */
            if (
                $nipCandidate !== '' &&
                strlen($nipCandidate) >= 5 &&
                $namaCandidate !== ''
            ) {

                $firstEmployeeRow =
                    $i;

                break;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | KUMPULKAN KODE LIBUR DARI EXCEL
        |--------------------------------------------------------------------------
        |
        | Tidak lagi hanya mencari maksimal 5 baris.
        |
        | Semua baris sebelum pegawai pertama diperiksa.
        |
        | Jika tanggal memiliki L:
        |
        | tanggal tersebut = LIBUR.
        |
        */
        $kodeLiburByColumn = [];

        $holidaySearchEnd =
            $firstEmployeeRow !== null
            ? $firstEmployeeRow - 1
            : count($rows) - 1;

        for (
            $i = $headerRow + 1;
            $i <= $holidaySearchEnd;
            $i++
        ) {

            $candidate =
                $rows[$i] ?? [];

            $nipCandidate =
                $this->normalizeNip(
                    $this->cleanExcelValue(
                        $candidate[$nipColumn] ?? ''
                    )
                );

            $namaCandidate =
                trim(
                    $this->cleanExcelValue(
                        $candidate[$namaColumn] ?? ''
                    )
                );

            /*
            |--------------------------------------------------------------------------
            | Baris pegawai bukan baris kode libur
            |--------------------------------------------------------------------------
            |
            | PENTING: nipCandidate WAJIB melalui normalizeNip(),
            | bukan cleanExcelValue() mentah.
            |
            | Baris "Kode Libur" punya teks "Kode Libur" di
            | kolom NIP. Kalau tidak dinormalisasi, teks itu
            | dianggap NIP valid (non-empty) sehingga baris ini
            | ikut ke-skip dan kodeLiburByColumn selalu kosong.
            |
            | normalizeNip("Kode Libur") = '' (tidak ada digit),
            | sehingga baris ini baru bisa lolos sebagai baris
            | legenda kode libur, sementara baris pegawai asli
            | (NIP-nya mengandung digit) tetap ke-skip seperti
            | seharusnya.
            |
            */
            if (
                $nipCandidate !== '' ||
                $namaCandidate !== ''
            ) {

                continue;
            }

            foreach (
                $tanggalColumns as $column => $day
            ) {

                $kode =
                    strtoupper(
                        trim(
                            $this->cleanExcelValue(
                                $candidate[$column] ?? ''
                            )
                        )
                    );

                if (
                    in_array(
                        $kode,
                        ['S', 'M', 'L'],
                        true
                    )
                ) {

                    $kodeLiburByColumn[$column] =
                        $kode;
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Baris Awal Pegawai
        |--------------------------------------------------------------------------
        */
        $startRow =
            $firstEmployeeRow !== null
            ? $firstEmployeeRow
            : $headerRow + 1;

        /*
        |--------------------------------------------------------------------------
        | Ambil User
        |--------------------------------------------------------------------------
        */
        $users =
            User::with([
                'jabatan',
                'role',
            ])->get();

        /*
        |--------------------------------------------------------------------------
        | Mapping NIP
        |--------------------------------------------------------------------------
        */
        $usersByNip =
            $users
            ->filter(
                fn($user) =>
                !empty($user->nip)
            )
            ->keyBy(
                function ($user) {

                    return $this->normalizeNip(
                        $user->nip
                    );
                }
            );

        /*
        |--------------------------------------------------------------------------
        | Mapping Nama
        |--------------------------------------------------------------------------
        */
        $usersByNama =
            $users->groupBy(
                function ($user) {

                    return $this->normalizeNama(
                        $user->nama
                    );
                }
            );

        /*
        |--------------------------------------------------------------------------
        | Employees
        |--------------------------------------------------------------------------
        */
        $employees = [];

        for (
            $rowIndex = $startRow;
            $rowIndex < count($rows);
            $rowIndex++
        ) {

            $row =
                $rows[$rowIndex];

            /*
            |--------------------------------------------------------------------------
            | NIP
            |--------------------------------------------------------------------------
            */
            $nipExcel =
                $this->normalizeNip(
                    $this->cleanExcelValue(
                        $row[$nipColumn] ?? ''
                    )
                );

            /*
            |--------------------------------------------------------------------------
            | Nama
            |--------------------------------------------------------------------------
            */
            $namaExcel =
                trim(
                    $this->cleanExcelValue(
                        $row[$namaColumn] ?? ''
                    )
                );

            /*
            |--------------------------------------------------------------------------
            | Skip kosong
            |--------------------------------------------------------------------------
            */
            if (
                $nipExcel === '' &&
                $namaExcel === ''
            ) {

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Mapping User
            |--------------------------------------------------------------------------
            */
            $user = null;

            $mappingStatus =
                'Tidak ditemukan';

            /*
            |--------------------------------------------------------------------------
            | Mapping NIP
            |--------------------------------------------------------------------------
            */
            if (
                $nipExcel !== '' &&
                isset(
                    $usersByNip[$nipExcel]
                )
            ) {

                $user =
                    $usersByNip[$nipExcel];

                $mappingStatus =
                    'NIP cocok';
            }

            /*
            |--------------------------------------------------------------------------
            | Mapping Nama
            |--------------------------------------------------------------------------
            */
            if (
                !$user &&
                $namaExcel !== ''
            ) {

                $namaNormalized =
                    $this->normalizeNama(
                        $namaExcel
                    );

                $namaMatches =
                    $usersByNama->get(
                        $namaNormalized,
                        collect()
                    );

                if (
                    $namaMatches->count() === 1
                ) {

                    $user =
                        $namaMatches->first();

                    $mappingStatus =
                        'Nama cocok';
                }
            }

            $mappedStatus =
                $user !== null;

            /*
            |--------------------------------------------------------------------------
            | Data Kehadiran
            |--------------------------------------------------------------------------
            */
            $kehadiran = [];

            foreach (
                $tanggalColumns as $column => $day
            ) {

                /*
                |--------------------------------------------------------------------------
                | Validasi tanggal
                |--------------------------------------------------------------------------
                */
                if (
                    !checkdate(
                        $bulan,
                        $day,
                        $tahun
                    )
                ) {

                    continue;
                }

                $tanggal =
                    Carbon::create(
                        $tahun,
                        $bulan,
                        $day
                    );

                /*
                |--------------------------------------------------------------------------
                | Kode Libur dari Excel
                |--------------------------------------------------------------------------
                */
                $kodeLibur =
                    strtoupper(
                        trim(
                            $kodeLiburByColumn[$column]
                                ?? ''
                        )
                    );

                /*
                |--------------------------------------------------------------------------
                | Cell Pegawai
                |--------------------------------------------------------------------------
                */
                $cellValue =
                    $row[$column] ?? '';

                $cellClean =
                    $this->cleanExcelValue(
                        $cellValue
                    );

                $cellCode =
                    strtoupper(
                        trim(
                            $cellClean
                        )
                    );

                /*
                |--------------------------------------------------------------------------
                | PRIORITAS STATUS
                |--------------------------------------------------------------------------
                |
                | 1. Weekend
                | 2. Kode L dari Excel
                | 3. Kode L pada cell pegawai
                | 4. S/M dari baris libur
                | 5. Presensi normal
                |
                */
                if (
                    $tanggal->isWeekend()
                ) {

                    $parsed = [

                        'status' =>
                        'Libur',

                        'jam_masuk' =>
                        null,

                        'jam_pulang' =>
                        null,

                        'keterangan' =>
                        'Hari Libur',

                        'kode' =>
                        $kodeLibur ?: null,
                    ];
                } elseif (
                    $kodeLibur === 'L'
                ) {

                    /*
                    |--------------------------------------------------------------------------
                    | INI YANG MEMASTIKAN 17 & 25 AGUSTUS
                    | MENJADI LIBUR JIKA EXCEL MEMBERI KODE L
                    |--------------------------------------------------------------------------
                    */
                    $parsed = [

                        'status' =>
                        'Libur',

                        'jam_masuk' =>
                        null,

                        'jam_pulang' =>
                        null,

                        'keterangan' =>
                        'Hari Libur',

                        'kode' =>
                        'L',
                    ];
                } elseif (
                    in_array(
                        $kodeLibur,
                        ['S', 'M'],
                        true
                    )
                ) {

                    $parsed = [

                        'status' =>
                        'Libur',

                        'jam_masuk' =>
                        null,

                        'jam_pulang' =>
                        null,

                        'keterangan' =>
                        'Hari Libur',

                        'kode' =>
                        $kodeLibur,
                    ];
                } elseif (
                    $cellCode === 'L'
                ) {

                    $parsed = [

                        'status' =>
                        'Libur',

                        'jam_masuk' =>
                        null,

                        'jam_pulang' =>
                        null,

                        'keterangan' =>
                        'Hari Libur',

                        'kode' =>
                        'L',
                    ];
                } else {

                    $parsed =
                        $this->parsePresensiCell(
                            $cellValue
                        );
                }

                /*
                |--------------------------------------------------------------------------
                | Simpan Data
                |--------------------------------------------------------------------------
                */
                if (
                    $parsed['status'] !== null ||
                    $parsed['jam_masuk'] !== null ||
                    $parsed['jam_pulang'] !== null
                ) {

                    $kehadiran[] = [

                        'tanggal' =>
                        $tanggal->format(
                            'Y-m-d'
                        ),

                        'hari' =>
                        $this->namaHari(
                            $tanggal
                        ),

                        'status' =>
                        $parsed['status'],

                        'jam_masuk' =>
                        $parsed['jam_masuk'],

                        'jam_pulang' =>
                        $parsed['jam_pulang'],

                        'keterangan' =>
                        $parsed['keterangan'],

                        'kode' =>
                        $parsed['kode']
                            ?? null,
                    ];
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Simpan Pegawai
            |--------------------------------------------------------------------------
            */
            if (
                !empty($kehadiran)
            ) {

                $employees[] = [

                    'user_id' =>
                    $user?->id,

                    'nip' =>
                    $nipExcel,

                    'nama_excel' =>
                    $namaExcel,

                    'nama' =>
                    $user?->nama
                        ?? $namaExcel,

                    'jabatan' =>
                    $user?->jabatan?->nama_jabatan
                        ?? null,

                    'role' =>
                    $user?->role?->name
                        ?? null,

                    'mapped' =>
                    $mappedStatus,

                    'mapping' =>
                    $mappingStatus,

                    'kehadiran' =>
                    $kehadiran,

                    /*
                    |--------------------------------------------------------------------------
                    | Alias data
                    |--------------------------------------------------------------------------
                    */
                    'data' =>
                    $kehadiran,
                ];
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Urutkan Nama
        |--------------------------------------------------------------------------
        */
        usort(
            $employees,
            function ($a, $b) {

                return strcasecmp(
                    $a['nama'],
                    $b['nama']
                );
            }
        );

        /*
        |--------------------------------------------------------------------------
        | Return
        |--------------------------------------------------------------------------
        */
        return [

            'tahun' =>
            $tahun,

            'bulan' =>
            $bulan,

            'bulan_nama' =>
            $this->namaBulan(
                $bulan
            ),

            'employees' =>
            $employees,
        ];
    }

    /**
     * =========================================================
     * PARSE CELL PRESENSI
     * =========================================================
     */
    private function parsePresensiCell(
        $value
    ): array {

        /*
        |--------------------------------------------------------------------------
        | Tangkap Excel Time Numeric / DateTime sebelum dibersihkan
        |--------------------------------------------------------------------------
        */
        $directTime =
            $this->normalizeTime(
                $value
            );

        if (
            $directTime !== null
        ) {

            return [

                'status' =>
                'Hadir',

                'jam_masuk' =>
                $directTime,

                'jam_pulang' =>
                null,

                'keterangan' =>
                null,

                'kode' =>
                null,
            ];
        }

        $clean =
            $this->cleanExcelValue(
                $value
            );

        /*
        |--------------------------------------------------------------------------
        | Kosong
        |--------------------------------------------------------------------------
        */
        if (
            trim($clean) === ''
        ) {

            return [

                'status' =>
                null,

                'jam_masuk' =>
                null,

                'jam_pulang' =>
                null,

                'keterangan' =>
                null,

                'kode' =>
                null,
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Pecah baris
        |--------------------------------------------------------------------------
        */
        $lines =
            preg_split(
                '/\r\n|\r|\n/',
                $clean
            );

        $lines =
            array_values(
                array_filter(
                    array_map(
                        fn($line) =>
                        trim($line),
                        $lines
                    ),
                    fn($line) =>
                    $line !== ''
                )
            );

        $first =
            strtoupper(
                trim(
                    $lines[0] ?? ''
                )
            );

        /*
        |--------------------------------------------------------------------------
        | Izin
        |--------------------------------------------------------------------------
        */
        if (
            $first === 'I'
        ) {

            return [

                'status' =>
                'Izin',

                'jam_masuk' =>
                null,

                'jam_pulang' =>
                null,

                'keterangan' =>
                'Izin',

                'kode' =>
                'I',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Sakit
        |--------------------------------------------------------------------------
        */
        if (
            $first === 'S'
        ) {

            return [

                'status' =>
                'Sakit',

                'jam_masuk' =>
                null,

                'jam_pulang' =>
                null,

                'keterangan' =>
                'Sakit',

                'kode' =>
                'S',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Libur
        |--------------------------------------------------------------------------
        */
        if (
            $first === 'L'
        ) {

            return [

                'status' =>
                'Libur',

                'jam_masuk' =>
                null,

                'jam_pulang' =>
                null,

                'keterangan' =>
                'Hari Libur',

                'kode' =>
                'L',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Cari Jam
        |--------------------------------------------------------------------------
        */
        $jam = [];

        foreach (
            $lines as $line
        ) {

            $normalized =
                $this->normalizeTime(
                    $line
                );

            if (
                $normalized !== null
            ) {

                $jam[] =
                    $normalized;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Ada Jam
        |--------------------------------------------------------------------------
        */
        if (
            !empty($jam)
        ) {

            $jamMasuk =
                $jam[0]
                ?? null;

            $jamPulang =
                $jam[1]
                ?? null;

            /*
            |--------------------------------------------------------------------------
            | Keterangan
            |--------------------------------------------------------------------------
            */
            $keterangan = null;

            foreach (
                $lines as $line
            ) {

                $upper =
                    strtoupper(
                        trim($line)
                    );

                if (
                    !preg_match(
                        '/^\d{1,2}[:.]\d{2}(:\d{2})?$/',
                        $upper
                    ) &&
                    $upper !== '-'
                ) {

                    $keterangan =
                        $upper;

                    break;
                }
            }

            return [

                'status' =>
                'Hadir',

                'jam_masuk' =>
                $jamMasuk,

                'jam_pulang' =>
                $jamPulang,

                'keterangan' =>
                $keterangan,

                'kode' =>
                $keterangan,
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Kode Lain
        |--------------------------------------------------------------------------
        */
        return [

            'status' =>
            'Tanpa Keterangan',

            'jam_masuk' =>
            null,

            'jam_pulang' =>
            null,

            'keterangan' =>
            'Kode presensi: ' .
                $first,

            'kode' =>
            $first,
        ];
    }

    /**
     * =========================================================
     * NORMALIZE TIME
     * =========================================================
     */
    private function normalizeTime(
        $value
    ): ?string {

        if (
            $value === null ||
            $value === ''
        ) {

            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | DateTime
        |--------------------------------------------------------------------------
        */
        if (
            $value instanceof \DateTimeInterface
        ) {

            return $value->format(
                'H:i:s'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Excel Numeric Time
        |--------------------------------------------------------------------------
        */
        if (
            is_numeric($value) &&
            (float) $value >= 0 &&
            (float) $value < 1
        ) {

            $seconds =
                (int) round(
                    (float) $value * 86400
                );

            $seconds =
                max(
                    0,
                    min(
                        $seconds,
                        86399
                    )
                );

            return gmdate(
                'H:i:s',
                $seconds
            );
        }

        $value =
            trim(
                (string) $value
            );

        /*
        |--------------------------------------------------------------------------
        | HH:MM:SS
        |--------------------------------------------------------------------------
        */
        if (
            preg_match(
                '/^(\d{1,2}):(\d{2}):(\d{2})$/',
                $value,
                $matches
            )
        ) {

            $hour =
                (int) $matches[1];

            $minute =
                (int) $matches[2];

            $second =
                (int) $matches[3];

            if (
                $hour <= 23 &&
                $minute <= 59 &&
                $second <= 59
            ) {

                return sprintf(
                    '%02d:%02d:%02d',
                    $hour,
                    $minute,
                    $second
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | HH:MM
        |--------------------------------------------------------------------------
        */
        if (
            preg_match(
                '/^(\d{1,2}):(\d{2})$/',
                $value,
                $matches
            )
        ) {

            $hour =
                (int) $matches[1];

            $minute =
                (int) $matches[2];

            if (
                $hour <= 23 &&
                $minute <= 59
            ) {

                return sprintf(
                    '%02d:%02d:00',
                    $hour,
                    $minute
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | HH.MM
        |--------------------------------------------------------------------------
        */
        if (
            preg_match(
                '/^(\d{1,2})\.(\d{2})$/',
                $value,
                $matches
            )
        ) {

            $hour =
                (int) $matches[1];

            $minute =
                (int) $matches[2];

            if (
                $hour <= 23 &&
                $minute <= 59
            ) {

                return sprintf(
                    '%02d:%02d:00',
                    $hour,
                    $minute
                );
            }
        }

        return null;
    }

    /**
     * =========================================================
     * CLEAN EXCEL VALUE
     * =========================================================
     */
    private function cleanExcelValue(
        $value
    ): string {

        if (
            $value === null
        ) {

            return '';
        }

        if (
            $value instanceof \DateTimeInterface
        ) {

            return $value->format(
                'Y-m-d H:i:s'
            );
        }

        $value =
            preg_replace(
                '/\x{00A0}/u',
                ' ',
                (string) $value
            );

        return trim(
            $value
        );
    }

    /**
     * =========================================================
     * NORMALIZE NIP
     * =========================================================
     */
    private function normalizeNip(
        $value
    ): string {

        return preg_replace(
            '/\D/',
            '',
            (string) $value
        );
    }

    /**
     * =========================================================
     * NORMALIZE NAMA
     * =========================================================
     */
    private function normalizeNama(
        $nama
    ): string {

        $nama =
            strtoupper(
                trim(
                    (string) $nama
                )
            );

        $nama =
            preg_replace(
                '/\s+/',
                ' ',
                $nama
            );

        return $nama;
    }

    /**
     * =========================================================
     * NAMA HARI
     * =========================================================
     */
    private function namaHari(
        Carbon $tanggal
    ): string {

        $hari = [

            1 => 'Senin',

            2 => 'Selasa',

            3 => 'Rabu',

            4 => 'Kamis',

            5 => 'Jumat',

            6 => 'Sabtu',

            7 => 'Minggu',
        ];

        return
            $hari[$tanggal->dayOfWeekIso]
            ?? '-';
    }

    /**
     * =========================================================
     * NAMA BULAN
     * =========================================================
     */
    private function namaBulan(
        int $bulan
    ): string {

        $bulanList = [

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

        return
            $bulanList[$bulan]
            ?? '-';
    }

    /**
     * =========================================================
     * BULAN INDONESIA -> ANGKA
     * =========================================================
     */
    private function namaBulanKeAngka(
        string $bulan
    ): int {

        $bulan =
            strtolower(
                trim($bulan)
            );

        $bulanList = [

            'januari' => 1,

            'februari' => 2,

            'maret' => 3,

            'april' => 4,

            'mei' => 5,

            'juni' => 6,

            'juli' => 7,

            'agustus' => 8,

            'september' => 9,

            'oktober' => 10,

            'november' => 11,

            'desember' => 12,
        ];

        return
            $bulanList[$bulan]
            ?? now()->month;
    }
}
