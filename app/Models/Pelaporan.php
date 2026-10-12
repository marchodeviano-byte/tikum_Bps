<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelaporan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'diupload_oleh',
        'jenis',
        'tahun',
        'bulan',
        'minggu',
        'tanggal_mulai',
        'tanggal_selesai',
        'nama_file',
        'path_file',
        'ukuran_file',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    /**
     * =========================================================
     * RELASI
     * =========================================================
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pengunggah()
    {
        return $this->belongsTo(User::class, 'diupload_oleh');
    }

    /**
     * =========================================================
     * URL FILE (untuk tombol download/lihat di Monitoring)
     * =========================================================
     */
    public function getUrlFileAttribute()
    {
        if (!$this->path_file) {
            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | Pakai asset() + trim 'public/' dari path, bukan
        | Storage::disk('public')->url().
        |--------------------------------------------------------------------------
        |
        | Hasilnya sama-sama mengarah ke symlink public/storage
        | (hasil `php artisan storage:link`), tapi asset() adalah
        | helper global biasa sehingga tidak memicu warning
        | "undefined method" dari IDE/static analyzer seperti
        | halnya url() pada kontrak Filesystem.
        |
        */
        return asset('storage/' . $this->path_file);
    }

    /**
     * =========================================================
     * LABEL PERIODE (untuk ditampilkan di Monitoring Pelaporan)
     * =========================================================
     *
     * Contoh:
     *
     * Mingguan -> "Minggu 2 (7 - 13 Sep 2026)"
     * Bulanan  -> "September 2026"
     */
    public function getLabelPeriodeAttribute()
    {
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

        $bulan = $namaBulan[$this->bulan] ?? '-';

        if ($this->jenis === 'Mingguan') {

            $rentang = $this->tanggal_mulai->format('j')
                . ' - '
                . $this->tanggal_selesai->format('j M Y');

            return "Minggu {$this->minggu} ({$rentang})";
        }

        return "{$bulan} {$this->tahun}";
    }
}
