<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\PenilaianTahap1;
use App\Models\User;

class PenilaianTahap1Detail extends Model
{
    use HasFactory;

    /**
     * Nama tabel.
     */
    protected $table = 'penilaian_tahap1_details';

    /**
     * Kolom yang boleh diisi menggunakan create() / update().
     */
    protected $fillable = [
        'penilaian_tahap1_id',
        'user_id',
        'nilai',
        'peringkat',
    ];

    /**
     * Casting tipe data.
     */
    protected $casts = [
        'penilaian_tahap1_id' => 'integer',
        'user_id' => 'integer',
        'nilai' => 'decimal:2',
        'peringkat' => 'integer',
    ];

    /**
     * ============================================================
     * RELASI PENILAIAN TAHAP 1
     * ============================================================
     *
     * Detail ini berasal dari satu proses Penilaian Tahap 1.
     */
    public function penilaianTahap1(): BelongsTo
    {
        return $this->belongsTo(
            PenilaianTahap1::class,
            'penilaian_tahap1_id'
        );
    }

    /**
     * ============================================================
     * RELASI PEGAWAI
     * ============================================================
     *
     * Mengarah ke tabel users.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }
}