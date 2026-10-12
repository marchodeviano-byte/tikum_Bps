<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use App\Models\PenilaianTahap1Detail;

class PenilaianTahap1 extends Model
{
    use HasFactory;

    /**
     * Nama tabel.
     */
    protected $table = 'penilaian_tahap1';

    /**
     * Kolom yang boleh diisi menggunakan create() / update().
     */
    protected $fillable = [
        'surat_keputusan_id',
        'tahun',
        'triwulan',
        'status',
        'created_by',
        'approved_by',
        'approved_at',
    ];

    /**
     * Casting tipe data.
     */
    protected $casts = [
        'surat_keputusan_id' => 'integer',
        'tahun' => 'integer',
        'triwulan' => 'integer',
        'created_by' => 'integer',
        'approved_by' => 'integer',
        'approved_at' => 'datetime',
    ];

    /**
     * ============================================================
     * RELASI SURAT KEPUTUSAN
     * ============================================================
     *
     * Satu Penilaian Tahap 1 memiliki satu Surat Keputusan.
     */
    public function suratKeputusan(): BelongsTo
    {
        return $this->belongsTo(
            SuratKeputusanPanitia::class,
            'surat_keputusan_id'
        );
    }

    /**
     * ============================================================
     * RELASI USER PEMBUAT PROSES
     * ============================================================
     *
     * User yang membuat / memproses Penilaian Tahap 1.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    /**
     * ============================================================
     * RELASI USER YANG MENYETUJUI
     * ============================================================
     *
     * User Kepala Kantor yang melakukan persetujuan.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'approved_by'
        );
    }

    /**
     * ============================================================
     * RELASI DETAIL
     * ============================================================
     *
     * Satu proses Penilaian Tahap 1 memiliki maksimal
     * 5 pegawai terbaik.
     */
    public function details(): HasMany
    {
        return $this->hasMany(
            PenilaianTahap1Detail::class,
            'penilaian_tahap1_id'
        );
    }
}