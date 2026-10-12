<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * =========================================================
     * JALANKAN MIGRATION
     * =========================================================
     */
    public function up(): void
    {
        Schema::create('pelaporans', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Pegawai pemilik laporan.
            |--------------------------------------------------------------------------
            |
            | Kalau ada admin yang upload-in punya pegawai lain,
            | 'diupload_oleh' menyimpan siapa yang benar-benar
            | menekan tombol upload.
            |
            */
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('diupload_oleh')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Jenis periode laporan.
            |--------------------------------------------------------------------------
            */
            $table->enum('jenis', ['Mingguan', 'Bulanan']);

            $table->unsignedSmallInteger('tahun');

            $table->unsignedTinyInteger('bulan');

            /*
            |--------------------------------------------------------------------------
            | Hanya diisi kalau jenis = Mingguan (1-5).
            |--------------------------------------------------------------------------
            */
            $table->unsignedTinyInteger('minggu')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Tanggal nyata periode laporan.
            |--------------------------------------------------------------------------
            |
            | Dihitung otomatis di controller berdasarkan
            | jenis + tahun + bulan (+ minggu), BUKAN diinput
            | manual, supaya selalu sinkron dengan kalender asli.
            |
            */
            $table->date('tanggal_mulai');

            $table->date('tanggal_selesai');

            /*
            |--------------------------------------------------------------------------
            | File.
            |--------------------------------------------------------------------------
            */
            $table->string('nama_file');

            $table->string('path_file');

            $table->unsignedBigInteger('ukuran_file')->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Satu pegawai hanya punya SATU file per periode.
            |--------------------------------------------------------------------------
            |
            | Upload ulang di periode yang sama akan menimpa
            | (lihat updateOrCreate di controller), bukan
            | menumpuk data baru.
            |
            */
            $table->unique(
                ['user_id', 'jenis', 'tahun', 'bulan', 'minggu'],
                'pelaporans_periode_unique'
            );
        });
    }

    /**
     * =========================================================
     * ROLLBACK MIGRATION
     * =========================================================
     */
    public function down(): void
    {
        Schema::dropIfExists('pelaporans');
    }
};
