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
        Schema::create('penilaians', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | RELASI PEGAWAI
            |--------------------------------------------------------------------------
            */
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | PERIODE PENILAIAN
            |--------------------------------------------------------------------------
            */
            $table->unsignedSmallInteger('tahun');

            $table->unsignedTinyInteger('triwulan');

            /*
            |--------------------------------------------------------------------------
            | METODE INPUT
            |--------------------------------------------------------------------------
            | bulanan  = Januari/Februari/Maret, dst.
            | triwulan = langsung satu nilai
            */
            $table->enum('metode_input', [
                'bulanan',
                'triwulan',
            ]);

            /*
            |--------------------------------------------------------------------------
            | NILAI PER BULAN
            |--------------------------------------------------------------------------
            */
            $table->decimal('nilai_bulan_1', 5, 2)
                ->nullable();

            $table->decimal('nilai_bulan_2', 5, 2)
                ->nullable();

            $table->decimal('nilai_bulan_3', 5, 2)
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | NILAI LANGSUNG TRIWULAN
            |--------------------------------------------------------------------------
            */
            $table->decimal('nilai_triwulan', 5, 2)
                ->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | PENTING
            |--------------------------------------------------------------------------
            | Satu pegawai hanya boleh mempunyai satu penilaian
            | untuk satu tahun + satu triwulan.
            */
            $table->unique(
                ['user_id', 'tahun', 'triwulan'],
                'penilaian_user_tahun_triwulan_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaians');
    }
};
