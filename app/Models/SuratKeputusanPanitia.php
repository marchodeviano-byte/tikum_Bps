<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class SuratKeputusanPanitia extends Model
{
    use HasFactory;

    protected $table = 'surat_keputusan_panitia';

    protected $fillable = [
        'user_id',
        'no_sk',
        'tanggal_sk',
        'perihal',
        'file_pdf',
    ];

    protected $casts = [
        'tanggal_sk' => 'date',
    ];

    /**
     * Pegawai yang mengupload SK.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}