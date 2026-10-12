<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class SkpEvaluasi extends Model
{
    use HasFactory;

    protected $table = 'skp_evaluasis';

    protected $fillable = [
        'user_id',
        'tahun',

        'dokumen_evaluasi_file',
        'dokumen_evaluasi_path',
        'dokumen_evaluasi_ukuran',
        'status_dokumen_evaluasi',
        'keterangan_dokumen_evaluasi',

        'dokumen_kualitatif_file',
        'dokumen_kualitatif_path',
        'dokumen_kualitatif_ukuran',
        'status_dokumen_kualitatif',
        'keterangan_dokumen_kualitatif',

        'status',
        'keterangan',
        'diproses_oleh',
        'diproses_at',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'tahun' => 'integer',

        'dokumen_evaluasi_ukuran' => 'integer',
        'dokumen_kualitatif_ukuran' => 'integer',

        'diproses_oleh' => 'integer',
        'diproses_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function operator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'diproses_oleh'
        );
    }

    public function getDokumenEvaluasiUrlAttribute()
    {
        return $this->dokumen_evaluasi_path
            ? asset(
                'storage/' . $this->dokumen_evaluasi_path
            )
            : null;
    }

    public function getDokumenKualitatifUrlAttribute()
    {
        return $this->dokumen_kualitatif_path
            ? asset(
                'storage/' . $this->dokumen_kualitatif_path
            )
            : null;
    }

    public function getKeteranganStatusAttribute(): string
    {
        if ($this->status === 'disetujui') {
            return 'Setuju';
        }

        if ($this->status === 'ditolak') {
            return $this->keterangan
                ? 'Tolak: ' . $this->keterangan
                : 'Tolak';
        }

        if ($this->status === 'menunggu_persetujuan') {
            return 'Menunggu persetujuan';
        }

        return 'Belum lengkap';
    }
}