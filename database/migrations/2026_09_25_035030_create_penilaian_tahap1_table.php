<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('penilaian_tahap1', function (Blueprint $table) {
            /*
            |--------------------------------------------------------------------------
            | Primary Key
            |--------------------------------------------------------------------------
            */
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Relasi Surat Keputusan
            |--------------------------------------------------------------------------
            |
            | Mengarah ke:
            | surat_keputusan_panitia.id
            |
            */
            $table->foreignId('surat_keputusan_id')
                ->constrained('surat_keputusan_panitia')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Periode Penilaian
            |--------------------------------------------------------------------------
            */
            $table->unsignedSmallInteger('tahun');

            $table->unsignedTinyInteger('triwulan');

            /*
            |--------------------------------------------------------------------------
            | Status Proses
            |--------------------------------------------------------------------------
            |
            | draft
            | menunggu_persetujuan
            | disetujui
            | ditolak
            |
            */
            $table->string('status', 30)
                ->default('draft');

            /*
            |--------------------------------------------------------------------------
            | User yang melakukan proses
            |--------------------------------------------------------------------------
            |
            | Mengarah ke users.id
            |
            */
            $table->foreignId('created_by')
                ->constrained('users')
                ->restrictOnDelete();

            /*
            |--------------------------------------------------------------------------
            | User yang melakukan persetujuan
            |--------------------------------------------------------------------------
            |
            | Nullable karena pada awal proses belum ada
            | Kepala Kantor yang melakukan persetujuan.
            |
            */
            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Waktu Persetujuan
            |--------------------------------------------------------------------------
            */
            $table->timestamp('approved_at')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Timestamp Laravel
            |--------------------------------------------------------------------------
            */
            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Mencegah proses yang sama dibuat dua kali
            |--------------------------------------------------------------------------
            |
            | Satu SK hanya boleh memiliki satu proses
            | untuk tahun + triwulan yang sama.
            |
            */
            $table->unique(
                ['surat_keputusan_id', 'tahun', 'triwulan'],
                'penilaian_tahap1_sk_tahun_triwulan_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaian_tahap1');
    }
};