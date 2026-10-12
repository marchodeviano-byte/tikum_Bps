<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SipeterPenilaianTahap2 extends Model
{
    protected $table = 'sipeter_penilaian_tahap2';

    protected $fillable = [
        'tahun',
        'triwulan',
        'status',
        'created_by',
        'started_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
    ];

    public function pertanyaan(): HasMany
    {
        return $this->hasMany(SipeterPenilaianTahap2Pertanyaan::class, 'penilaian_tahap2_id')
            ->orderBy('urutan');
    }
}