<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skp extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'diupload_oleh',
        'jenis',
        'tahun',
        'bulan',
        'triwulan',
        'nama_file',
        'path_file',
        'ukuran_file',

        'status',
        'keterangan',
        'diproses_oleh',
        'diproses_at',
    ];

    protected $casts = [
        'diproses_at' => 'datetime',
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
     * URL FILE
     * =========================================================
     *
     * Pakai asset() biasa (bukan Storage::disk('public')->url()),
     * supaya tidak memicu warning "undefined method" dari IDE /
     * static analyzer -- lihat catatan yang sama di model
     * Pelaporan.
     */
    public function getUrlFileAttribute()
    {
        if (!$this->path_file) {
            return null;
        }

        return asset('storage/' . $this->path_file);
    }

    /**
     * =========================================================
     * LABEL PERIODE
     * =========================================================
     *
     * Contoh:
     *
     * Bulanan  -> "September 2026"
     * Triwulan -> "Triwulan 3 (Juli - September) 2026"
     * Tahunan  -> "Tahun 2026"
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

        $rentangTriwulan = [
            1 => 'Januari - Maret',
            2 => 'April - Juni',
            3 => 'Juli - September',
            4 => 'Oktober - Desember',
        ];

        if ($this->jenis === 'Bulanan') {
            $bulan = $namaBulan[$this->bulan] ?? '-';
            return "{$bulan} {$this->tahun}";
        }

        if ($this->jenis === 'Triwulan') {
            $rentang = $rentangTriwulan[$this->triwulan] ?? '-';
            return "Triwulan {$this->triwulan} ({$rentang}) {$this->tahun}";
        }

        return "Tahun {$this->tahun}";
    }
}