<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SipeterPenilaianTahap2Pertanyaan extends Model
{
    protected $table = 'sipeter_penilaian_tahap2_pertanyaan';

    protected $fillable = [
        'penilaian_tahap2_id',
        'sipeter_pertanyaan_id',
        'urutan',
        'pertanyaan',
    ];

    public function penilaian(): BelongsTo
    {
        return $this->belongsTo(SipeterPenilaianTahap2::class, 'penilaian_tahap2_id');
    }
}