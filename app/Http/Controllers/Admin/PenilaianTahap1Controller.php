<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Penilaian;
use App\Models\PenilaianPengecualian;
use App\Models\PenilaianTahap1;
use App\Models\PenilaianTahap1Detail;
use App\Models\SuratKeputusanPanitia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PenilaianTahap1Controller extends Controller
{
    /**
     * ============================================================
     * INDEX
     * ============================================================
     *
     * Mengatur seluruh tampilan Step 1, Step 2, Step 3
     * sekaligus menampilkan riwayat Penilaian Tahap 1.
     */
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | STEP
        |--------------------------------------------------------------------------
        */

        $step = (int) $request->input('step', 1);

        if (!in_array($step, [1, 2, 3], true)) {
            $step = 1;
        }

        /*
        |--------------------------------------------------------------------------
        | TAHUN
        |--------------------------------------------------------------------------
        */

        $tahun = (int) $request->input(
            'tahun',
            now()->year
        );

        /*
        |--------------------------------------------------------------------------
        | TRIWULAN
        |--------------------------------------------------------------------------
        */

        $triwulan = (int) $request->input(
            'triwulan',
            1
        );

        if (!in_array($triwulan, [1, 2, 3, 4], true)) {
            $triwulan = 1;
        }

        /*
        |--------------------------------------------------------------------------
        | SURAT KEPUTUSAN
        |--------------------------------------------------------------------------
        */

        $suratKeputusanId = $request->input(
            'surat_keputusan_id'
        );

        if (
            $suratKeputusanId !== null &&
            $suratKeputusanId !== ''
        ) {
            $suratKeputusanId = (int) $suratKeputusanId;
        } else {
            $suratKeputusanId = null;
        }

        /*
        |--------------------------------------------------------------------------
        | DAFTAR TAHUN
        |--------------------------------------------------------------------------
        */

        $tahunSekarang = now()->year;

        $tahunOptions = range(
            $tahunSekarang - 2,
            $tahunSekarang + 1
        );

        /*
        |--------------------------------------------------------------------------
        | DAFTAR TRIWULAN
        |--------------------------------------------------------------------------
        */

        $triwulanOptions = [
            1 => 'Triwulan 1',
            2 => 'Triwulan 2',
            3 => 'Triwulan 3',
            4 => 'Triwulan 4',
        ];

        /*
        |--------------------------------------------------------------------------
        | DAFTAR SURAT KEPUTUSAN PANITIA
        |--------------------------------------------------------------------------
        |
        | PENTING:
        | Nama variabel dibuat $daftarSuratKeputusan agar sama
        | dengan yang digunakan oleh Blade.
        |
        */

        $daftarSuratKeputusan = SuratKeputusanPanitia::with('user')
            ->orderByDesc('tanggal_sk')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | SURAT KEPUTUSAN TERPILIH
        |--------------------------------------------------------------------------
        */

        $suratKeputusan = null;

        if ($suratKeputusanId) {
            $suratKeputusan = SuratKeputusanPanitia::with('user')
                ->find($suratKeputusanId);
        }

        $penilaianAktif = null;

        if ($suratKeputusanId) {
            $penilaianAktif = PenilaianTahap1::with([
                'suratKeputusan',
                'creator',
                'approver',
                'details.user',
            ])
                ->where('tahun', $tahun)
                ->where('triwulan', $triwulan)
                ->where('surat_keputusan_id', $suratKeputusanId)
                ->latest('id')
                ->first();
        }

        /*
        |--------------------------------------------------------------------------
        | PEGAWAI
        |--------------------------------------------------------------------------
        */

        $pegawai = collect();
        $pegawaiTampil = collect();

        /*
        |--------------------------------------------------------------------------
        | PEGAWAI YANG DIKECUALIKAN
        |--------------------------------------------------------------------------
        |
        | Pengecualian bersifat:
        |
        | user_id + tahun + triwulan
        |
        */

        $pengecualianIds = collect();

        if ($suratKeputusanId) {

            $pengecualianIds = PenilaianPengecualian::query()
                ->where('tahun', $tahun)
                ->where('triwulan', $triwulan)
                ->pluck('user_id')
                ->map(
                    fn($id) => (int) $id
                )
                ->values();

            /*
            |--------------------------------------------------------------------------
            | SEMUA PEGAWAI AKTIF
            |--------------------------------------------------------------------------
            |
            | Semua tetap ditampilkan.
            | Yang dikecualikan hanya ditandai melalui checkbox.
            |
            */

            $pegawai = User::with([
                'role',
                'jabatan',
            ])
                ->where('status', true)
                ->orderBy('nama')
                ->get();

            // Tabel utama Step 2 hanya menampilkan pegawai yang sudah
            // disimpan sebagai pengecualian. Modal pengecualian tetap
            // memakai $pegawai agar semua pegawai aktif tetap tersedia.
            $pegawaiTampil = $pengecualianIds->isNotEmpty()
                ? $pegawai->whereIn('id', $pengecualianIds->all())->values()
                : $pegawai;
        }

        /*
        |--------------------------------------------------------------------------
        | RIWAYAT PENILAIAN TAHAP 1
        |--------------------------------------------------------------------------
        */

        $riwayat = PenilaianTahap1::with([
            'suratKeputusan',
            'creator',
            'approver',
            'details.user',
        ])
            ->where('status', '!=', 'draft')
            ->latest()
            ->get();

        /*
        |--------------------------------------------------------------------------
        | PERSETUJUAN DI HALAMAN DEPAN
        |--------------------------------------------------------------------------
        |
        | Hanya hasil yang masih menunggu persetujuan yang ditampilkan.
        | Setelah disetujui/ditolak, status berubah sehingga otomatis
        | menghilang dari tabel ini.
        |
        */

        $persetujuan = PenilaianTahap1::with([
            'suratKeputusan',
            'creator',
            'approver',
            'details.user',
        ])
            ->where('status', 'menunggu_persetujuan')
            ->latest()
            ->get();

        /* Login belum dibuat, jadi sementara approval dibuka. */
        $bolehMenyetujui = true;

        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.sipeter.penilaian.penilaian-tahap1.index',
            compact(
                'step',
                'tahun',
                'triwulan',
                'tahunOptions',
                'triwulanOptions',
                'daftarSuratKeputusan',
                'suratKeputusan',
                'suratKeputusanId',
                'pegawai',
                'pegawaiTampil',
                'pengecualianIds',
                'riwayat',
                'persetujuan',
                'penilaianAktif',
                'bolehMenyetujui'
            )
        );
    }


    /**
     * ============================================================
     * PROSES
     * ============================================================
     *
     * Method ini dipertahankan karena route sebelumnya menggunakan:
     *
     * POST /proses
     *
     * Fungsinya mengarahkan:
     *
     * Step 1 -> Step 2
     *
     * atau mempertahankan Step 2 ketika SK sudah dipilih.
     */
    public function proses(Request $request)
    {
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
                'between:1,4',
            ],

            'surat_keputusan_id' => [
                'nullable',
                'integer',
                'exists:surat_keputusan_panitia,id',
            ],
        ]);

        $tahun = (int) $validated['tahun'];

        $triwulan = (int) $validated['triwulan'];

        /*
        |--------------------------------------------------------------------------
        | STEP 1 -> STEP 2
        |--------------------------------------------------------------------------
        */

        if (
            empty($validated['surat_keputusan_id'])
        ) {
            return redirect()->route(
                'admin.sipeter.penilaian.penilaian-tahap1.index',
                [
                    'step' => 2,
                    'tahun' => $tahun,
                    'triwulan' => $triwulan,
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | STEP 2
        |--------------------------------------------------------------------------
        */

        $suratKeputusanId = (int) $validated['surat_keputusan_id'];

        SuratKeputusanPanitia::findOrFail(
            $suratKeputusanId
        );

        return redirect()->route(
            'admin.sipeter.penilaian.penilaian-tahap1.index',
            [
                'step' => 2,
                'tahun' => $tahun,
                'triwulan' => $triwulan,
                'surat_keputusan_id' => $suratKeputusanId,
            ]
        );
    }


    /**
     * ============================================================
     * PILIH PERIODE
     * ============================================================
     *
     * Step 1:
     *
     * Tahun + Triwulan
     *
     * Setelah OK -> Step 2
     */
    public function pilihPeriode(Request $request)
    {
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
                'between:1,4',
            ],
        ]);

        return redirect()->route(
            'admin.sipeter.penilaian.penilaian-tahap1.index',
            [
                'step' => 2,
                'tahun' => $validated['tahun'],
                'triwulan' => $validated['triwulan'],
            ]
        );
    }


    /**
     * ============================================================
     * PILIH SURAT KEPUTUSAN
     * ============================================================
     *
     * Step 2:
     *
     * Pilih Surat Keputusan Panitia
     *
     * Setelah Lanjut -> Step 3
     */
    public function pilihSuratKeputusan(Request $request)
    {
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
                'between:1,4',
            ],

            'surat_keputusan_id' => [
                'required',
                'integer',
                'exists:surat_keputusan_panitia,id',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Pastikan SK memang ada
        |--------------------------------------------------------------------------
        */

        SuratKeputusanPanitia::findOrFail(
            $validated['surat_keputusan_id']
        );

        /*
        |--------------------------------------------------------------------------
        | Masuk ke STEP 3
        |--------------------------------------------------------------------------
        */

        return redirect()->route(
            'admin.sipeter.penilaian.penilaian-tahap1.index',
            [
                'step' => 3,
                'tahun' => $validated['tahun'],
                'triwulan' => $validated['triwulan'],
                'surat_keputusan_id' =>
                $validated['surat_keputusan_id'],
            ]
        );
    }


    /**
     * ============================================================
     * SIMPAN PENGECUALIAN
     * ============================================================
     *
     * Menyimpan pegawai yang tidak ikut dalam proses Top 5.
     *
     * Pengecualian berdasarkan:
     *
     * user_id + tahun + triwulan
     */
    public function simpanPengecualian(Request $request)
    {
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
                'between:1,4',
            ],

            'surat_keputusan_id' => [
                'required',
                'integer',
                'exists:surat_keputusan_panitia,id',
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

        $tahun = (int) $validated['tahun'];

        $triwulan = (int) $validated['triwulan'];

        $suratKeputusanId = (int) $validated['surat_keputusan_id'];

        /*
        |--------------------------------------------------------------------------
        | ID PEGAWAI YANG DIPILIH
        |--------------------------------------------------------------------------
        */

        $userIds = collect(
            $validated['pengecualian'] ?? []
        )
            ->map(
                fn($id) => (int) $id
            )
            ->unique()
            ->values();

        /*
        |--------------------------------------------------------------------------
        | HANYA PEGAWAI AKTIF
        |--------------------------------------------------------------------------
        */

        $userIdsAktif = User::query()
            ->where('status', true)
            ->whereIn('id', $userIds)
            ->pluck('id')
            ->map(
                fn($id) => (int) $id
            )
            ->values();

        /*
        |--------------------------------------------------------------------------
        | SIMPAN
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $tahun,
            $triwulan,
            $userIdsAktif
        ) {

            /*
            |--------------------------------------------------------------------------
            | Hapus data pengecualian periode tersebut
            |--------------------------------------------------------------------------
            */

            PenilaianPengecualian::query()
                ->where('tahun', $tahun)
                ->where('triwulan', $triwulan)
                ->delete();

            /*
            |--------------------------------------------------------------------------
            | Jika tidak ada pengecualian
            |--------------------------------------------------------------------------
            */

            if ($userIdsAktif->isEmpty()) {
                return;
            }

            /*
            |--------------------------------------------------------------------------
            | Insert pengecualian baru
            |--------------------------------------------------------------------------
            */

            $now = now();

            $rows = $userIdsAktif
                ->map(
                    fn($userId) => [
                        'user_id' => $userId,
                        'tahun' => $tahun,
                        'triwulan' => $triwulan,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]
                )
                ->all();

            PenilaianPengecualian::insert(
                $rows
            );
        });

        /*
        |--------------------------------------------------------------------------
        | Kembali ke STEP 3
        |--------------------------------------------------------------------------
        */

        return redirect()->route(
            'admin.sipeter.penilaian.penilaian-tahap1.index',
            [
                'step' => 2,
                'tahun' => $tahun,
                'triwulan' => $triwulan,
                'surat_keputusan_id' =>
                $suratKeputusanId,
            ]
        )->with(
            'success',
            'Pengecualian pegawai berhasil disimpan.'
        );
    }


    /**
     * ============================================================
     * PROSES 5 PEGAWAI TERBAIK
     * ============================================================
     *
     * Mengambil nilai dari tabel penilaians.
     *
     * Tidak melakukan input nilai baru.
     */
    public function prosesTerbaik(Request $request)
    {
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
                'between:1,4',
            ],

            'surat_keputusan_id' => [
                'required',
                'integer',
                'exists:surat_keputusan_panitia,id',
            ],
        ]);

        $tahun = (int) $validated['tahun'];

        $triwulan = (int) $validated['triwulan'];

        $suratKeputusanId = (int) $validated['surat_keputusan_id'];

        /*
        |--------------------------------------------------------------------------
        | CEK SK
        |--------------------------------------------------------------------------
        */

        SuratKeputusanPanitia::findOrFail(
            $suratKeputusanId
        );

        /*
        |--------------------------------------------------------------------------
        | CEK APAKAH SUDAH PERNAH DIPROSES
        |--------------------------------------------------------------------------
        */

        $penilaianSudahFinal = PenilaianTahap1::query()
            ->where('surat_keputusan_id', $suratKeputusanId)
            ->where('tahun', $tahun)
            ->where('triwulan', $triwulan)
            ->where('status', '!=', 'draft')
            ->exists();

        if ($penilaianSudahFinal) {
            return back()->with(
                'error',
                'Penilaian Tahap 1 untuk periode dan Surat Keputusan tersebut sudah pernah diproses.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | AMBIL PEGAWAI YANG DIKECUALIKAN
        |--------------------------------------------------------------------------
        */

        $pengecualianIds = PenilaianPengecualian::query()
            ->where('tahun', $tahun)
            ->where('triwulan', $triwulan)
            ->pluck('user_id')
            ->map(
                fn($id) => (int) $id
            )
            ->values();

        /*
        |--------------------------------------------------------------------------
        | AMBIL DATA PENILAIAN
        |--------------------------------------------------------------------------
        */

        $penilaians = Penilaian::with('user')
            ->where('tahun', $tahun)
            ->where('triwulan', $triwulan)
            ->when(
                $pengecualianIds->isNotEmpty(),
                function ($query) use (
                    $pengecualianIds
                ) {
                    $query->whereNotIn(
                        'user_id',
                        $pengecualianIds->all()
                    );
                }
            )
            ->get();

        /*
        |--------------------------------------------------------------------------
        | HITUNG NILAI AKHIR
        |--------------------------------------------------------------------------
        */

        $hasil = collect();

        foreach ($penilaians as $penilaian) {

            $nilaiAkhir = null;

            /*
            |--------------------------------------------------------------------------
            | METODE BULANAN
            |--------------------------------------------------------------------------
            |
            | Nilai akhir = rata-rata 3 bulan.
            |
            */

            if (
                $penilaian->metode_input === 'bulanan'
                &&
                $penilaian->nilai_bulan_1 !== null
                &&
                $penilaian->nilai_bulan_2 !== null
                &&
                $penilaian->nilai_bulan_3 !== null
            ) {

                $nilaiAkhir = round(
                    (
                        (float) $penilaian->nilai_bulan_1 +
                        (float) $penilaian->nilai_bulan_2 +
                        (float) $penilaian->nilai_bulan_3
                    ) / 3,
                    2
                );
            }

            /*
            |--------------------------------------------------------------------------
            | METODE TRIWULAN
            |--------------------------------------------------------------------------
            */ elseif (
                $penilaian->nilai_triwulan !== null
            ) {

                $nilaiAkhir = (float) $penilaian->nilai_triwulan;
            }

            /*
            |--------------------------------------------------------------------------
            | NILAI KOSONG / NOL
            |--------------------------------------------------------------------------
            */

            if (
                $nilaiAkhir === null ||
                $nilaiAkhir <= 0
            ) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | MASUK HASIL
            |--------------------------------------------------------------------------
            */

            $hasil->push([
                'user_id' => $penilaian->user_id,
                'nilai' => $nilaiAkhir,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | URUTKAN NILAI TERTINGGI
        |--------------------------------------------------------------------------
        */

        $top5 = $hasil
            ->sortByDesc('nilai')
            ->take(5)
            ->values();

        /*
        |--------------------------------------------------------------------------
        | TIDAK ADA DATA
        |--------------------------------------------------------------------------
        */

        if ($top5->isEmpty()) {
            return back()->with(
                'error',
                'Belum terdapat data nilai yang dapat diproses.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SIMPAN HASIL
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $suratKeputusanId,
            $tahun,
            $triwulan,
            $top5
        ) {

            $penilaianTahap1 = PenilaianTahap1::query()
                ->where('surat_keputusan_id', $suratKeputusanId)
                ->where('tahun', $tahun)
                ->where('triwulan', $triwulan)
                ->where('status', 'draft')
                ->latest('id')
                ->first();

            if ($penilaianTahap1) {
                // Jika kembali ke Step 2 lalu memproses ulang, draft lama
                // diganti dengan hasil Top 5 terbaru.
                $penilaianTahap1->details()->delete();
            } else {
                $penilaianTahap1 = PenilaianTahap1::create([
                    'surat_keputusan_id' => $suratKeputusanId,
                    'tahun' => $tahun,
                    'triwulan' => $triwulan,
                    'status' => 'draft',
                    'created_by' => 1,
                    'approved_by' => null,
                    'approved_at' => null,
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | SIMPAN DETAIL TOP 5
            |--------------------------------------------------------------------------
            */

            foreach (
                $top5 as $index => $item
            ) {

                PenilaianTahap1Detail::create([
                    'penilaian_tahap1_id' =>
                    $penilaianTahap1->id,

                    'user_id' =>
                    $item['user_id'],

                    'nilai' =>
                    $item['nilai'],

                    'peringkat' =>
                    $index + 1,
                ]);
            }
        });

        /*
        |--------------------------------------------------------------------------
        | KEMBALI KE STEP 3
        |--------------------------------------------------------------------------
        */

        return redirect()->route(
            'admin.sipeter.penilaian.penilaian-tahap1.index',
            [
                'step' => 3,
                'tahun' => $tahun,
                'triwulan' => $triwulan,
                'surat_keputusan_id' =>
                $suratKeputusanId,
            ]
        )->with(
            'success',
            '5 pegawai terbaik berhasil diproses.'
        );
    }


    /**
     * ============================================================
     * PROSES FINAL
     * ============================================================
     *
     * Tombol Proses pada Step 3 mengubah draft menjadi
     * menunggu_persetujuan. Setelah itu barulah masuk riwayat.
     */
    public function prosesFinal(Request $request)
    {
        $validated = $request->validate([
            'penilaian_tahap1_id' => ['required', 'integer', 'exists:penilaian_tahap1,id'],
            'tahun' => ['required', 'integer', 'min:2000', 'max:2100'],
            'triwulan' => ['required', 'integer', 'between:1,4'],
            'surat_keputusan_id' => ['required', 'integer', 'exists:surat_keputusan_panitia,id'],
        ]);

        $penilaian = PenilaianTahap1::query()
            ->where('id', $validated['penilaian_tahap1_id'])
            ->where('tahun', $validated['tahun'])
            ->where('triwulan', $validated['triwulan'])
            ->where('surat_keputusan_id', $validated['surat_keputusan_id'])
            ->firstOrFail();

        if ($penilaian->status !== 'draft') {
            return back()->with(
                'error',
                'Hasil ini sudah diproses dan tidak dapat diproses ulang dari tahap ini.'
            );
        }

        $penilaian->update([
            'status' => 'menunggu_persetujuan',
        ]);

        return redirect()->route(
            'admin.sipeter.penilaian.penilaian-tahap1.index',
            [
                'step' => 3,
                'tahun' => $validated['tahun'],
                'triwulan' => $validated['triwulan'],
                'surat_keputusan_id' => $validated['surat_keputusan_id'],
            ]
        )->with(
            'success',
            'Hasil 5 pegawai terbaik berhasil diproses dan menunggu persetujuan.'
        );
    }


    /**
     * ============================================================
     * CEK HAK APPROVAL
     * ============================================================
     *
     * Yang boleh menyetujui / menolak:
     *
     * - Admin
     * - Kepala / Kepala BPS
     */
    private function bolehMenyetujui(): bool
    {
        // Sementara login belum tersedia, approval dibuka.
        // Nanti setelah fitur login aktif, kembalikan pengecekan role di sini.
        return true;

        /*
        $user = Auth::user();

        if (!$user) {
            return false;
        }

        $roleName = strtolower(
            trim(
                $user->role->name ??
                    $user->role->nama_role ??
                    ''
            )
        );

        return in_array(
            $roleName,
            [
                'admin',
                'panitia',
                'kepala bps',
            ],
            true
        );
        */
    }


    /**
     * ============================================================
     * SETUJUI
     * ============================================================
     */
    public function setujui($id)
    {
        /*
        |--------------------------------------------------------------------------
        | CEK HAK
        |--------------------------------------------------------------------------
        */

        if (!$this->bolehMenyetujui()) {
            abort(
                403,
                'Anda tidak memiliki hak untuk menyetujui penilaian.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | AMBIL DATA
        |--------------------------------------------------------------------------
        */

        $penilaian =
            PenilaianTahap1::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | UPDATE
        |--------------------------------------------------------------------------
        */

        $penilaian->update([
            'status' =>
            'disetujui',

            'approved_by' =>
            Auth::id(),

            'approved_at' =>
            now(),
        ]);

        return back()->with(
            'success',
            'Penilaian Tahap 1 berhasil disetujui.'
        );
    }


    /**
     * ============================================================
     * TOLAK
     * ============================================================
     */
    public function tolak($id)
    {
        /*
        |--------------------------------------------------------------------------
        | CEK HAK
        |--------------------------------------------------------------------------
        */

        if (!$this->bolehMenyetujui()) {
            abort(
                403,
                'Anda tidak memiliki hak untuk menolak penilaian.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | AMBIL DATA
        |--------------------------------------------------------------------------
        */

        $penilaian =
            PenilaianTahap1::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | UPDATE
        |--------------------------------------------------------------------------
        */

        $penilaian->update([
            'status' =>
            'ditolak',

            'approved_by' =>
            Auth::id(),

            'approved_at' =>
            now(),
        ]);

        return back()->with(
            'success',
            'Penilaian Tahap 1 berhasil ditolak.'
        );
    }


    /**
     * ============================================================
     * LIHAT DETAIL
     * ============================================================
     *
     * Digunakan oleh modal AJAX.
     */
    public function lihat($id)
    {
        $penilaian = PenilaianTahap1::with([
            'suratKeputusan',
            'creator',
            'approver',
            'details.user',
        ])->findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | URUTKAN HASIL BERDASARKAN NILAI TERBESAR
        |--------------------------------------------------------------------------
        |
        | Popup detail harus selalu menampilkan hasil dengan urutan:
        | 1, 2, 3, 4, 5 berdasarkan nilai tertinggi.
        | Nama pegawai diambil langsung dari relasi user.
        |
        */
        $details = $penilaian->details
            ->sort(function ($a, $b) {
                $nilaiA = (float) ($a->nilai ?? 0);
                $nilaiB = (float) ($b->nilai ?? 0);

                if ($nilaiA !== $nilaiB) {
                    return $nilaiB <=> $nilaiA;
                }

                return ((int) ($a->peringkat ?? 999)) <=>
                    ((int) ($b->peringkat ?? 999));
            })
            ->values()
            ->map(function ($detail, $index) {
                return [
                    'id' => $detail->id,
                    'user_id' => $detail->user_id,
                    'nama' => $detail->user?->nama ?? '-',
                    'nilai' => $detail->nilai,
                    'peringkat' => $index + 1,
                ];
            })
            ->all();

        $data = [
            'id' => $penilaian->id,
            'tahun' => $penilaian->tahun,
            'triwulan' => $penilaian->triwulan,
            'status' => $penilaian->status,
            'created_by' => $penilaian->created_by,
            'approved_by' => $penilaian->approved_by,
            'approved_at' => $penilaian->approved_at,
            'created_at' => $penilaian->created_at,
            'updated_at' => $penilaian->updated_at,

            'surat_keputusan' => $penilaian->suratKeputusan ? [
                'id' => $penilaian->suratKeputusan->id,
                'no_sk' => $penilaian->suratKeputusan->no_sk,
                'tanggal_sk' => optional($penilaian->suratKeputusan->tanggal_sk)->format('Y-m-d'),
                'perihal' => $penilaian->suratKeputusan->perihal,
            ] : null,

            'creator' => $penilaian->creator ? [
                'id' => $penilaian->creator->id,
                'nama' => $penilaian->creator->nama,
            ] : null,

            'approver' => $penilaian->approver ? [
                'id' => $penilaian->approver->id,
                'nama' => $penilaian->approver->nama,
            ] : null,

            'details' => $details,
        ];

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }


    /**
     * ============================================================
     * HAPUS
     * ============================================================
     */
    public function destroy($id)
    {
        $penilaian =
            PenilaianTahap1::findOrFail($id);

        DB::transaction(
            function () use ($penilaian) {

                /*
                |--------------------------------------------------------------------------
                | Hapus detail terlebih dahulu
                |--------------------------------------------------------------------------
                */

                $penilaian
                    ->details()
                    ->delete();

                /*
                |--------------------------------------------------------------------------
                | Hapus parent
                |--------------------------------------------------------------------------
                */

                $penilaian->delete();
            }
        );

        return back()->with(
            'success',
            'Riwayat Penilaian Tahap 1 berhasil dihapus.'
        );
    }
}