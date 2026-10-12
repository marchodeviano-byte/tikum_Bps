<?php

namespace App\Models;

use App\Models\Role;
use App\Models\Jabatan;
use App\Models\Kehadiran;
use App\Models\Penilaian;
use App\Models\SuratKeputusanPanitia;
use App\Models\PenilaianTahap1;
use App\Models\PenilaianTahap1Detail;
use App\Models\SkpEvaluasi;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nip',
        'nama',
        'email',
        'password',
        'jabatan_id',
        'role_id',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    protected function casts(): array
    {
        return [
            'status' => 'boolean',
            'password' => 'hashed',
        ];
    }
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }
    public function kehadirans()
    {
        return $this->hasMany(Kehadiran::class);
    }
    public function penilaians()
    {
        return $this->hasMany(Penilaian::class);
    }
    public function suratKeputusanPanitia()
    {
        return $this->hasMany(SuratKeputusanPanitia::class);
    }
    public function penilaianTahap1Dibuat()
    {
        return $this->hasMany(
            PenilaianTahap1::class,
            'created_by'
        );
    }
    public function penilaianTahap1Disetujui()
    {
        return $this->hasMany(
            PenilaianTahap1::class,
            'approved_by'
        );
    }
    public function penilaianTahap1Details()
    {
        return $this->hasMany(
            PenilaianTahap1Detail::class,
            'user_id'
        );
    }
    public function skpEvaluasis()
    {
        return $this->hasMany(
            SkpEvaluasi::class
        );
    }
    public function skpEvaluasiTahun($tahun)
    {
        return $this->hasOne(
            SkpEvaluasi::class
        )->where('tahun', $tahun);
    }
}