<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;


class Penilaian extends Model
{
    use HasFactory;

    protected $table = 'penilaians';

    protected $fillable = [
        'user_id',
        'tahun',
        'triwulan',
        'metode_input',
        'nilai_bulan_1',
        'nilai_bulan_2',
        'nilai_bulan_3',
        'nilai_triwulan',
    ];

    protected $casts = [
        'tahun' => 'integer',
        'triwulan' => 'integer',

        'nilai_bulan_1' => 'decimal:2',
        'nilai_bulan_2' => 'decimal:2',
        'nilai_bulan_3' => 'decimal:2',
        'nilai_triwulan' => 'decimal:2',
    ];

    /**
     * Relasi ke pegawai/user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
