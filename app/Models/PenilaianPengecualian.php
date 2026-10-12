<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class PenilaianPengecualian extends Model
{
    use HasFactory;

    protected $table = 'penilaian_pengecualians';

    protected $fillable = [
        'user_id',
        'tahun',
        'triwulan',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'tahun' => 'integer',
        'triwulan' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}