<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PenilaianTahap1;
use App\Models\SipeterPenilaianTahap2;
use App\Models\SipeterPertanyaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PenilaianTahap2PanitiaController extends Controller
{
    private const ROUTE_INDEX = 'admin.sipeter.penilaian.penilaian-tahap2.panitia.index';

    /**
     * ============================================================
     * INDEX (Step 1, 2, 3)
     * ============================================================
     */
    public function index(Request $request)
    {
        $step = (int) $request->input('step', 1);

        if (!in_array($step, [1, 2, 3], true)) {
            $step = 1;
        }

        $tahun = (int) $request->input('tahun', now()->year);

        $triwulan = (int) $request->input('triwulan', 1);

        if (!in_array($triwulan, [1, 2, 3, 4], true)) {
            $triwulan = 1;
        }

        $tahunSekarang = now()->year;

        $tahunOptions = range($tahunSekarang - 2, $tahunSekarang + 1);

        $penilaian = null;
        $pertanyaanList = collect();
        $terpilihIds = [];
        $pegawaiTerbaik = collect();
        $tahap1 = null;

        /*
        |----------------------------------------------------------
        | Sesi Tahap 2 untuk periode ini (kalau sudah ada)
        |----------------------------------------------------------
        */
        if ($step >= 2) {
            $penilaian = SipeterPenilaianTahap2::with('pertanyaan')
                ->where('tahun', $tahun)
                ->where('triwulan', $triwulan)
                ->first();
        }

        /*
        |----------------------------------------------------------
        | STEP 2 - pilih pertanyaan
        |----------------------------------------------------------
        */
        if ($step === 2) {

            if ($penilaian && $penilaian->status !== 'draft') {
                return redirect()
                    ->route(self::ROUTE_INDEX, [
                        'step' => 3,
                        'tahun' => $tahun,
                        'triwulan' => $triwulan,
                    ])
                    ->with('error', 'Penilaian periode ini sudah dimulai, pertanyaan tidak dapat diubah lagi.');
            }

            $pertanyaanList = SipeterPertanyaan::orderBy('id')->get();

            if ($penilaian) {
                $terpilihIds = $penilaian->pertanyaan
                    ->pluck('sipeter_pertanyaan_id')
                    ->filter()
                    ->map(fn($id) => (int) $id)
                    ->values()
                    ->all();
            }
        }

        /*
        |----------------------------------------------------------
        | STEP 3 - konfirmasi
        |----------------------------------------------------------
        */
        if ($step === 3) {

            if (!$penilaian || $penilaian->pertanyaan->isEmpty()) {
                return redirect()
                    ->route(self::ROUTE_INDEX, [
                        'step' => 2,
                        'tahun' => $tahun,
                        'triwulan' => $triwulan,
                    ])
                    ->with('error', 'Pilih minimal satu pertanyaan terlebih dahulu.');
            }

            // 5 pegawai terbaik = hasil Penilaian Tahap 1 yang sudah disetujui
            $tahap1 = PenilaianTahap1::with('details.user')
                ->where('tahun', $tahun)
                ->where('triwulan', $triwulan)
                ->where('status', 'disetujui')
                ->latest('id')
                ->first();

            $pegawaiTerbaik = $tahap1
                ? $tahap1->details->sortBy('peringkat')->values()
                : collect();
        }

        return view(
            'admin.sipeter.penilaian.penilaian-tahap2.panitia.index',
            compact(
                'step',
                'tahun',
                'triwulan',
                'tahunOptions',
                'penilaian',
                'pertanyaanList',
                'terpilihIds',
                'tahap1',
                'pegawaiTerbaik'
            )
        );
    }


    /**
     * ============================================================
     * STEP 1 -> STEP 2
     * ============================================================
     */
    public function proses(Request $request)
    {
        $validated = $request->validate([
            'tahun' => ['required', 'integer', 'min:2000', 'max:2100'],
            'triwulan' => ['required', 'integer', 'between:1,4'],
        ], [
            'tahun.required' => 'Tahun wajib dipilih.',
            'triwulan.required' => 'Triwulan wajib dipilih.',
        ]);

        $sudahBerjalan = SipeterPenilaianTahap2::query()
            ->where('tahun', $validated['tahun'])
            ->where('triwulan', $validated['triwulan'])
            ->where('status', '!=', 'draft')
            ->exists();

        // Periode yang sudah dimulai langsung ke Step 3 (hanya lihat)
        return redirect()->route(self::ROUTE_INDEX, [
            'step' => $sudahBerjalan ? 3 : 2,
            'tahun' => $validated['tahun'],
            'triwulan' => $validated['triwulan'],
        ]);
    }


    /**
     * ============================================================
     * STEP 2 -> STEP 3 (simpan pertanyaan terpilih)
     * ============================================================
     */
    public function simpanPertanyaan(Request $request)
    {
        $tabel = (new SipeterPertanyaan)->getTable();

        $validated = $request->validate([
            'tahun' => ['required', 'integer', 'min:2000', 'max:2100'],
            'triwulan' => ['required', 'integer', 'between:1,4'],
            'pertanyaan_ids' => ['required', 'array', 'min:1'],
            'pertanyaan_ids.*' => ['integer', 'distinct', "exists:{$tabel},id"],
        ], [
            'pertanyaan_ids.required' => 'Pilih minimal satu pertanyaan.',
            'pertanyaan_ids.min' => 'Pilih minimal satu pertanyaan.',
            'pertanyaan_ids.*.exists' => 'Ada pertanyaan yang tidak ditemukan.',
        ]);

        $tahun = (int) $validated['tahun'];
        $triwulan = (int) $validated['triwulan'];

        $existing = SipeterPenilaianTahap2::query()
            ->where('tahun', $tahun)
            ->where('triwulan', $triwulan)
            ->first();

        if ($existing && $existing->status !== 'draft') {
            return redirect()
                ->route(self::ROUTE_INDEX, [
                    'step' => 3,
                    'tahun' => $tahun,
                    'triwulan' => $triwulan,
                ])
                ->with('error', 'Penilaian periode ini sudah dimulai, pertanyaan tidak dapat diubah lagi.');
        }

        DB::transaction(function () use ($existing, $tahun, $triwulan, $validated) {

            $penilaian = $existing ?: SipeterPenilaianTahap2::create([
                'tahun' => $tahun,
                'triwulan' => $triwulan,
                'status' => 'draft',
                // Login belum dibuat, sama seperti Tahap 1
                'created_by' => Auth::id() ?? 1,
            ]);

            // Ganti seluruh pilihan lama dengan pilihan terbaru
            $penilaian->pertanyaan()->delete();

            $pertanyaan = SipeterPertanyaan::query()
                ->whereIn('id', $validated['pertanyaan_ids'])
                ->orderBy('id')
                ->get();

            foreach ($pertanyaan as $i => $item) {
                $penilaian->pertanyaan()->create([
                    'sipeter_pertanyaan_id' => $item->id,
                    'urutan' => $i + 1,
                    'pertanyaan' => $item->pertanyaan,
                ]);
            }
        });

        return redirect()
            ->route(self::ROUTE_INDEX, [
                'step' => 3,
                'tahun' => $tahun,
                'triwulan' => $triwulan,
            ])
            ->with('success', 'Pertanyaan berhasil dipilih.');
    }


    /**
     * ============================================================
     * STEP 3 - MULAI PENILAIAN
     * ============================================================
     */
    public function mulai(Request $request)
    {
        $validated = $request->validate([
            'penilaian_tahap2_id' => ['required', 'integer', 'exists:sipeter_penilaian_tahap2,id'],
            'tahun' => ['required', 'integer', 'min:2000', 'max:2100'],
            'triwulan' => ['required', 'integer', 'between:1,4'],
        ]);

        $penilaian = SipeterPenilaianTahap2::withCount('pertanyaan')
            ->where('id', $validated['penilaian_tahap2_id'])
            ->where('tahun', $validated['tahun'])
            ->where('triwulan', $validated['triwulan'])
            ->firstOrFail();

        if ($penilaian->status !== 'draft') {
            return back()->with('error', 'Penilaian periode ini sudah dimulai.');
        }

        if ($penilaian->pertanyaan_count < 1) {
            return back()->with('error', 'Belum ada pertanyaan yang dipilih.');
        }

        $tahap1Disetujui = PenilaianTahap1::query()
            ->where('tahun', $penilaian->tahun)
            ->where('triwulan', $penilaian->triwulan)
            ->where('status', 'disetujui')
            ->whereHas('details')
            ->exists();

        if (!$tahap1Disetujui) {
            return back()->with(
                'error',
                'Penilaian Tahap 1 periode ini belum disetujui, 5 pegawai terbaik belum tersedia.'
            );
        }

        $penilaian->update([
            'status' => 'berjalan',
            'started_at' => now(),
        ]);

        return redirect()
            ->route(self::ROUTE_INDEX, [
                'step' => 3,
                'tahun' => $penilaian->tahun,
                'triwulan' => $penilaian->triwulan,
            ])
            ->with('success', 'Penilaian Tahap 2 dimulai. Pertanyaan sekarang tersedia untuk diisi pegawai.');
    }
}