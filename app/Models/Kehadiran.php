<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kehadiran extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi.
     */
    protected $fillable = [
        'user_id',
        'tanggal',
        'status',
        'jam_masuk',
        'jam_pulang',
        'keterangan',
    ];

    /**
     * Casting atribut.
     */
    protected $casts = [
        'tanggal' => 'date',
    ];

    /**
     * =========================================================
     * RELASI KE USER / PEGAWAI
     * =========================================================
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * =========================================================
     * NAMA HARI DALAM BAHASA INDONESIA
     * =========================================================
     *
     * 1 = Senin
     * 2 = Selasa
     * 3 = Rabu
     * 4 = Kamis
     * 5 = Jumat
     * 6 = Sabtu
     * 7 = Minggu
     */
    public function getNamaHariAttribute()
    {
        if (!$this->tanggal) {
            return '-';
        }

        $hari = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu',
        ];

        return $hari[$this->tanggal->dayOfWeekIso] ?? '-';
    }

    /**
     * =========================================================
     * CEK APAKAH HARI LIBUR / WEEKEND
     * =========================================================
     */
    public function isHariLibur()
    {
        /*
         * Kalau status database sudah Libur,
         * otomatis dianggap hari libur.
         */
        if ($this->status === 'Libur') {
            return true;
        }

        /*
         * Kalau tidak ada tanggal,
         * jangan dianggap sebagai hari kerja.
         */
        if (!$this->tanggal) {
            return false;
        }

        /*
         * Sabtu dan Minggu = Libur.
         */
        return in_array(
            $this->tanggal->dayOfWeekIso,
            [6, 7],
            true
        );
    }

    /**
     * =========================================================
     * JAM MASUK STANDAR
     * =========================================================
     *
     * Senin - Jumat : 07:30
     * Sabtu - Minggu : Tidak ada
     */
    public function jamMasukStandar()
    {
        if (!$this->tanggal) {
            return '07:30';
        }

        /*
         * Sabtu dan Minggu tidak memiliki
         * jam masuk standar.
         */
        if (
            in_array(
                $this->tanggal->dayOfWeekIso,
                [6, 7],
                true
            )
        ) {
            return null;
        }

        return '07:30';
    }

    /**
     * =========================================================
     * JAM PULANG STANDAR
     * =========================================================
     *
     * Senin - Kamis : 16:00
     * Jumat         : 16:30
     * Sabtu/Minggu  : Tidak ada
     */
    public function jamPulangStandar()
    {
        if (!$this->tanggal) {
            return '16:00';
        }

        /*
         * Sabtu dan Minggu = Libur.
         */
        if (
            in_array(
                $this->tanggal->dayOfWeekIso,
                [6, 7],
                true
            )
        ) {
            return null;
        }

        /*
         * Jumat.
         */
        if (
            $this->tanggal->dayOfWeekIso === 5
        ) {
            return '16:30';
        }

        /*
         * Senin - Kamis.
         */
        return '16:00';
    }

    /**
     * =========================================================
     * PARSE JAM DATABASE KE CARBON
     * =========================================================
     */
    private function parseJam($jam)
    {
        if (!$jam) {
            return null;
        }

        try {
            /*
             * HH:MM
             */
            if (strlen($jam) === 5) {
                return Carbon::createFromFormat(
                    'H:i',
                    $jam
                );
            }

            /*
             * HH:MM:SS
             */
            return Carbon::createFromFormat(
                'H:i:s',
                $jam
            );
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * =========================================================
     * MENIT TERLAMBAT
     * =========================================================
     *
     * Hanya dihitung apabila:
     *
     * - Status Hadir
     * - Bukan Sabtu/Minggu
     * - Jam masuk tersedia
     * - Jam masuk > 07:30
     */
    public function getMenitTerlambatAttribute()
    {
        /*
         * Status selain Hadir tidak dihitung.
         */
        if (
            $this->status !== 'Hadir'
        ) {
            return 0;
        }

        /*
         * Tanggal tidak tersedia.
         */
        if (!$this->tanggal) {
            return 0;
        }

        /*
         * Jam masuk tidak tersedia.
         */
        if (!$this->jam_masuk) {
            return 0;
        }

        /*
         * Sabtu/Minggu = Libur.
         */
        if (
            in_array(
                $this->tanggal->dayOfWeekIso,
                [6, 7],
                true
            )
        ) {
            return 0;
        }

        /*
         * Ambil jam masuk aktual.
         */
        $jamMasuk =
            $this->parseJam(
                $this->jam_masuk
            );

        if (!$jamMasuk) {
            return 0;
        }

        /*
         * Jam masuk standar.
         */
        $standar =
            $this->jamMasukStandar();

        if (!$standar) {
            return 0;
        }

        $jamStandar =
            Carbon::createFromFormat(
                'H:i',
                $standar
            );

        /*
         * Hitung selisih.
         *
         * Contoh:
         *
         * Standar : 07:30
         * Aktual  : 07:35
         *
         * Hasil = 5 menit
         */
        return max(
            0,
            $jamStandar->diffInMinutes(
                $jamMasuk,
                false
            )
        );
    }

    /**
     * =========================================================
     * MENIT PULANG CEPAT
     * =========================================================
     *
     * Hanya dihitung apabila:
     *
     * - Status Hadir
     * - Bukan Sabtu/Minggu
     * - Jam pulang tersedia
     * - Jam pulang < jam standar
     */
    public function getMenitPulangCepatAttribute()
    {
        /*
         * Status selain Hadir tidak dihitung.
         */
        if (
            $this->status !== 'Hadir'
        ) {
            return 0;
        }

        /*
         * Tanggal tidak tersedia.
         */
        if (!$this->tanggal) {
            return 0;
        }

        /*
         * Jam pulang tidak tersedia.
         */
        if (!$this->jam_pulang) {
            return 0;
        }

        /*
         * Sabtu/Minggu = Libur.
         */
        if (
            in_array(
                $this->tanggal->dayOfWeekIso,
                [6, 7],
                true
            )
        ) {
            return 0;
        }

        /*
         * Ambil jam pulang aktual.
         */
        $jamPulang =
            $this->parseJam(
                $this->jam_pulang
            );

        if (!$jamPulang) {
            return 0;
        }

        /*
         * Ambil jam pulang standar.
         */
        $standar =
            $this->jamPulangStandar();

        if (!$standar) {
            return 0;
        }

        $jamStandar =
            Carbon::createFromFormat(
                'H:i',
                $standar
            );

        /*
         * Contoh Senin-Kamis:
         *
         * Standar : 16:00
         * Aktual  : 15:45
         *
         * Hasil = 15 menit
         */
        return max(
            0,
            $jamPulang->diffInMinutes(
                $jamStandar,
                false
            )
        );
    }

    /**
     * =========================================================
     * STATUS WAKTU
     * =========================================================
     *
     * Kemungkinan:
     *
     * Libur
     * Hadir
     * Izin
     * Sakit
     * Tanpa Keterangan
     * Data Tidak Lengkap
     * Tepat Waktu
     * Terlambat
     * Pulang Cepat
     * Terlambat & Pulang Cepat
     */
    public function getStatusWaktuAttribute()
    {
        /*
         * =====================================================
         * STATUS LIBUR DARI DATABASE
         * =====================================================
         *
         * Ini penting untuk kode L dari Excel
         * yang sudah dikonversi menjadi Libur.
         */
        if (
            $this->status === 'Libur'
        ) {
            return 'Libur';
        }

        /*
         * =====================================================
         * TANGGAL TIDAK ADA
         * =====================================================
         */
        if (!$this->tanggal) {
            return 'Data Tidak Lengkap';
        }

        /*
         * =====================================================
         * SABTU / MINGGU
         * =====================================================
         *
         * Walaupun status di database berbeda,
         * Sabtu/Minggu tetap dianggap Libur.
         */
        if (
            in_array(
                $this->tanggal->dayOfWeekIso,
                [6, 7],
                true
            )
        ) {
            return 'Libur';
        }

        /*
         * =====================================================
         * STATUS SELAIN HADIR
         * =====================================================
         */
        if (
            $this->status !== 'Hadir'
        ) {
            return $this->status;
        }

        /*
         * =====================================================
         * JAM BELUM LENGKAP
         * =====================================================
         */
        if (
            !$this->jam_masuk ||
            !$this->jam_pulang
        ) {
            return 'Data Tidak Lengkap';
        }

        /*
         * =====================================================
         * CEK TERLAMBAT
         * =====================================================
         */
        $terlambat =
            $this->menit_terlambat > 0;

        /*
         * =====================================================
         * CEK PULANG CEPAT
         * =====================================================
         */
        $pulangCepat =
            $this->menit_pulang_cepat > 0;

        /*
         * =====================================================
         * TERLAMBAT + PULANG CEPAT
         * =====================================================
         */
        if (
            $terlambat &&
            $pulangCepat
        ) {
            return 'Terlambat & Pulang Cepat';
        }

        /*
         * =====================================================
         * TERLAMBAT
         * =====================================================
         */
        if ($terlambat) {
            return 'Terlambat';
        }

        /*
         * =====================================================
         * PULANG CEPAT
         * =====================================================
         */
        if ($pulangCepat) {
            return 'Pulang Cepat';
        }

        /*
         * =====================================================
         * TEPAT WAKTU
         * =====================================================
         */
        return 'Tepat Waktu';
    }

    /**
     * =========================================================
     * KETERANGAN TERLAMBAT
     * =========================================================
     */
    public function getKeteranganTerlambatAttribute()
    {
        if (
            $this->menit_terlambat <= 0
        ) {
            return null;
        }

        return
            $this->menit_terlambat .
            ' menit';
    }

    /**
     * =========================================================
     * KETERANGAN PULANG CEPAT
     * =========================================================
     */
    public function getKeteranganPulangCepatAttribute()
    {
        if (
            $this->menit_pulang_cepat <= 0
        ) {
            return null;
        }

        return
            $this->menit_pulang_cepat .
            ' menit';
    }

    /**
     * =========================================================
     * STATUS LABEL
     * =========================================================
     *
     * Bisa dipakai Blade untuk menentukan label/badge.
     */
    public function getStatusLabelAttribute()
    {
        switch ($this->status) {

            case 'Hadir':
                return 'Hadir';

            case 'Izin':
                return 'Izin';

            case 'Sakit':
                return 'Sakit';

            case 'Tanpa Keterangan':
                return 'Tanpa Keterangan';

            case 'Libur':
                return 'Libur';

            default:
                return $this->status ?? '-';
        }
    }
}
