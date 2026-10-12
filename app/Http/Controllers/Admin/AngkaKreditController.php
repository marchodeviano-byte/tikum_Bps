<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SkpAngkaKredit;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AngkaKreditController extends Controller
{
    private const ROUTE_INDEX = 'admin.skp.angka-kredit.index';

    private const BATAS_ATAS = 9999999.999;

    /**
     * =========================================================
     * INDEX ANGKA KREDIT
     * =========================================================
     *
     * Hanya memakai filter tahun.
     *
     * Angka kredit bersifat KUMULATIF:
     *
     *   akhir (tahun N) = awal (tahun N) + semester 1 + semester 2
     *   awal  (tahun N) = akhir (tahun N-1), kecuali diisi manual
     */
    public function index(Request $request)
    {
        $tahunSekarang = now('Asia/Jakarta')->year;

        $tahun = (int) $request->input('tahun', $tahunSekarang);

        if ($tahun < 2000 || $tahun > 2100) {
            $tahun = $tahunSekarang;
        }

        /*
        | Pilihan tahun: tahun yang sudah punya data + tahun berjalan
        | + 1 tahun berikutnya (sama seperti halaman Penilaian).
        */
        $tahunOptions = SkpAngkaKredit::query()
            ->select('tahun')
            ->distinct()
            ->pluck('tahun')
            ->map(fn($item) => (int) $item)
            ->push($tahunSekarang)
            ->push($tahunSekarang + 1)
            ->push($tahun)
            ->unique()
            ->sort()
            ->values();

        $pegawai = User::query()
            ->where('status', true)
            ->orderBy('nama')
            ->get();

        $dataAngkaKredit = $this->hitungKumulatif(
            $pegawai->pluck('id')->all(),
            $tahun
        );

        return view(
            'admin.skp.angka-kredit.index',
            compact(
                'pegawai',
                'dataAngkaKredit',
                'tahun',
                'tahunOptions'
            )
        );
    }


    /**
     * =========================================================
     * SIMPAN
     * =========================================================
     *
     * Menyimpan angka kredit awal (opsional), semester 1, dan
     * semester 2 untuk seluruh pegawai pada tahun terpilih.
     *
     * - Angka kredit awal dikosongkan -> otomatis dari akhir tahun lalu
     * - Semua isian satu pegawai kosong/0 -> datanya dihapus
     */
    public function simpan(Request $request): RedirectResponse
    {
        $request->validate([
            'tahun' => ['required', 'integer', 'min:2000', 'max:2100'],
            'awal' => ['nullable', 'array'],
            'semester_1' => ['nullable', 'array'],
            'semester_2' => ['nullable', 'array'],
        ]);

        $tahun = (int) $request->input('tahun');

        $pegawai = User::query()
            ->where('status', true)
            ->orderBy('nama')
            ->get();

        $existing = SkpAngkaKredit::query()
            ->where('tahun', $tahun)
            ->get()
            ->keyBy('user_id');

        $paket = [];

        foreach ($pegawai as $user) {

            $awal = $this->normalisasiAngka($request->input("awal.{$user->id}"));
            $sem1 = $this->normalisasiAngka($request->input("semester_1.{$user->id}"));
            $sem2 = $this->normalisasiAngka($request->input("semester_2.{$user->id}"));

            foreach ([
                'Angka kredit awal' => $awal,
                'Semester 1' => $sem1,
                'Semester 2' => $sem2,
            ] as $label => $nilai) {

                if ($nilai === false) {
                    return back()
                        ->withInput()
                        ->with('error', "{$label} {$user->nama} harus berupa angka (gunakan koma untuk desimal).");
                }

                if ($nilai !== null && ($nilai < 0 || $nilai > self::BATAS_ATAS)) {
                    return back()
                        ->withInput()
                        ->with('error', "{$label} {$user->nama} harus berada di antara 0 sampai " . number_format(self::BATAS_ATAS, 3, ',', '.') . '.');
                }
            }

            $paket[$user->id] = [
                'awal' => $awal,
                'sem1' => $sem1 ?? 0.0,
                'sem2' => $sem2 ?? 0.0,
            ];
        }

        $disimpan = 0;

        DB::transaction(function () use ($paket, $existing, $tahun, &$disimpan) {

            foreach ($paket as $userId => $nilai) {

                $kosong = $nilai['awal'] === null
                    && $nilai['sem1'] == 0.0
                    && $nilai['sem2'] == 0.0;

                if ($kosong) {

                    if ($existing->has($userId)) {
                        $existing->get($userId)->delete();
                    }

                    continue;
                }

                SkpAngkaKredit::updateOrCreate(
                    [
                        'user_id' => $userId,
                        'tahun' => $tahun,
                    ],
                    [
                        'angka_kredit_awal' => $nilai['awal'],
                        'semester_1' => $nilai['sem1'],
                        'semester_2' => $nilai['sem2'],
                    ]
                );

                $disimpan++;
            }
        });

        $pesan = $disimpan > 0
            ? "Angka kredit tahun {$tahun} berhasil disimpan."
            : "Tidak ada angka kredit yang disimpan pada tahun {$tahun}.";

        return redirect()
            ->route(self::ROUTE_INDEX, ['tahun' => $tahun])
            ->with('success', $pesan);
    }


    /**
     * =========================================================
     * RESET
     * =========================================================
     *
     * - dengan user_id -> reset satu pegawai pada tahun terpilih
     * - tanpa user_id  -> reset semua pegawai pada tahun terpilih
     *
     * Karena sifatnya kumulatif, angka kredit awal tahun berikutnya
     * (yang otomatis) ikut menyesuaikan.
     */
    public function reset(Request $request): RedirectResponse
    {
        $request->validate([
            'tahun' => ['required', 'integer', 'min:2000', 'max:2100'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
        ]);

        $tahun = (int) $request->input('tahun');

        $query = SkpAngkaKredit::query()->where('tahun', $tahun);

        $nama = 'semua pegawai';

        if ($request->filled('user_id')) {

            $userId = (int) $request->input('user_id');

            $query->where('user_id', $userId);

            $nama = User::whereKey($userId)->value('nama') ?? 'pegawai';
        }

        $jumlah = $query->delete();

        if ($jumlah === 0) {
            return redirect()
                ->route(self::ROUTE_INDEX, ['tahun' => $tahun])
                ->with('error', "Tidak ada data angka kredit tahun {$tahun} untuk {$nama} yang bisa direset.");
        }

        return redirect()
            ->route(self::ROUTE_INDEX, ['tahun' => $tahun])
            ->with('success', "Angka kredit tahun {$tahun} untuk {$nama} berhasil direset.");
    }


    /**
     * =========================================================
     * HITUNG ANGKA KREDIT KUMULATIF
     * =========================================================
     *
     * Dihitung dari tahun pertama yang punya data sampai tahun
     * terpilih. Tahun tanpa data tetap meneruskan angka (semester
     * dianggap 0), sehingga tidak ada angka yang "putus" kalau ada
     * tahun yang terlewat.
     *
     * @param  array<int, int>  $userIds
     * @return array<int, array{
     *     awal: float, awal_manual: ?float, sumber: string,
     *     semester_1: float, semester_2: float, akhir: float, ada_data: bool
     * }>
     */
    private function hitungKumulatif(array $userIds, int $tahun): array
    {
        $baris = SkpAngkaKredit::query()
            ->whereIn('user_id', $userIds)
            ->where('tahun', '<=', $tahun)
            ->get()
            ->groupBy('user_id');

        $hasil = [];

        foreach ($userIds as $userId) {

            $rows = ($baris->get($userId) ?? collect())
                ->keyBy(fn($row) => (int) $row->tahun);

            $info = [
                'awal' => 0.0,
                'awal_manual' => null,
                'sumber' => 'kosong',
                'semester_1' => 0.0,
                'semester_2' => 0.0,
                'akhir' => 0.0,
                'ada_data' => false,
            ];

            $tahunMulai = $rows->keys()->min();

            if ($tahunMulai !== null) {

                $akhirSebelumnya = 0.0;

                for ($y = (int) $tahunMulai; $y <= $tahun; $y++) {

                    $row = $rows->get($y);

                    $manual = ($row && $row->angka_kredit_awal !== null)
                        ? (float) $row->angka_kredit_awal
                        : null;

                    $awal = $manual ?? $akhirSebelumnya;
                    $sem1 = $row ? (float) $row->semester_1 : 0.0;
                    $sem2 = $row ? (float) $row->semester_2 : 0.0;
                    $akhir = round($awal + $sem1 + $sem2, 3);

                    if ($y === $tahun) {

                        $info = [
                            'awal' => round($awal, 3),
                            'awal_manual' => $manual,
                            'sumber' => $manual !== null
                                ? 'manual'
                                : ($y > (int) $tahunMulai ? 'otomatis' : 'kosong'),
                            'semester_1' => $sem1,
                            'semester_2' => $sem2,
                            'akhir' => $akhir,
                            'ada_data' => $row !== null,
                        ];
                    }

                    $akhirSebelumnya = $akhir;
                }
            }

            $hasil[$userId] = $info;
        }

        return $hasil;
    }


    /**
     * Ubah isian pengguna menjadi angka.
     *
     * Koma = desimal ("12,5"), titik juga dibaca desimal kalau tidak
     * ada koma ("12.5"). Kalau ada koma, titik dianggap pemisah ribuan
     * ("1.234,5").
     *
     * @return float|null|false  null = kosong, false = bukan angka
     */
    private function normalisasiAngka(mixed $nilai): float|null|false
    {
        $nilai = trim((string) $nilai);

        if ($nilai === '') {
            return null;
        }

        $nilai = str_replace(' ', '', $nilai);

        if (str_contains($nilai, ',')) {
            $nilai = str_replace('.', '', $nilai);
            $nilai = str_replace(',', '.', $nilai);
        }

        if (!is_numeric($nilai)) {
            return false;
        }

        return round((float) $nilai, 3);
    }
}