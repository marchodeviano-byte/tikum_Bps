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
        Schema::create('penilaian_tahap1_details', function (Blueprint $table) {
            /*
            |--------------------------------------------------------------------------
            | Primary Key
            |--------------------------------------------------------------------------
            */
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Relasi Penilaian Tahap 1
            |--------------------------------------------------------------------------
            |
            | Mengarah ke:
            | penilaian_tahap1.id
            |
            */
            $table->foreignId('penilaian_tahap1_id')
                ->constrained('penilaian_tahap1')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Pegawai
            |--------------------------------------------------------------------------
            |
            | Mengarah ke:
            | users.id
            |
            */
            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Nilai
            |--------------------------------------------------------------------------
            |
            | Contoh:
            | 95.50
            | 87.25
            | 100.00
            |
            */
            $table->decimal('nilai', 5, 2);

            /*
            |--------------------------------------------------------------------------
            | Peringkat
            |--------------------------------------------------------------------------
            |
            | Hanya digunakan untuk ranking 1 sampai 5.
            |
            */
            $table->unsignedTinyInteger('peringkat');

            /*
            |--------------------------------------------------------------------------
            | Timestamp Laravel
            |--------------------------------------------------------------------------
            */
            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Satu pegawai tidak boleh muncul dua kali
            |--------------------------------------------------------------------------
            | dalam satu proses Penilaian Tahap 1.
            |
            */
            $table->unique(
                ['penilaian_tahap1_id', 'user_id'],
                'penilaian_tahap1_detail_user_unique'
            );

            /*
            |--------------------------------------------------------------------------
            | Satu peringkat hanya boleh dimiliki satu pegawai
            |--------------------------------------------------------------------------
            |
            | Contoh:
            | Rank 1 hanya boleh ada satu orang.
            |
            */
            $table->unique(
                ['penilaian_tahap1_id', 'peringkat'],
                'penilaian_tahap1_detail_peringkat_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaian_tahap1_details');
    }
};