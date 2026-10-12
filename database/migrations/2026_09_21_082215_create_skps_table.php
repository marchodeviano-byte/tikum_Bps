<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skps', function (Blueprint $table) {

            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('diupload_oleh')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Jenis periode SKP.
            |--------------------------------------------------------------------------
            */
            $table->enum('jenis', ['Bulanan', 'Triwulan', 'Tahunan']);

            $table->unsignedSmallInteger('tahun');

            /*
            |--------------------------------------------------------------------------
            | Hanya diisi sesuai jenisnya:
            |
            | Bulanan  -> bulan diisi (1-12), triwulan NULL
            | Triwulan -> triwulan diisi (1-4), bulan NULL
            | Tahunan  -> bulan & triwulan NULL
            |--------------------------------------------------------------------------
            */
            $table->unsignedTinyInteger('bulan')->nullable();

            $table->unsignedTinyInteger('triwulan')->nullable();

            $table->string('nama_file');

            $table->string('path_file');

            $table->unsignedBigInteger('ukuran_file')->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Satu pegawai hanya punya SATU dokumen per periode.
            |--------------------------------------------------------------------------
            |
            | Upload ulang di periode yang sama akan menimpa
            | (lihat updateOrCreate di controller).
            |--------------------------------------------------------------------------
            */
            $table->unique(
                ['user_id', 'jenis', 'tahun', 'bulan', 'triwulan'],
                'skps_periode_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skps');
    }
};
