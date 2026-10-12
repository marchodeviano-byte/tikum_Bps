<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SkpAngkaKredit extends Model
{
    protected $table = 'skp_angka_kredit';

    protected $fillable = [
        'user_id',
        'tahun',
        'angka_kredit_awal',
        'semester_1',
        'semester_2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}