<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skp_evaluasis', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->unsignedSmallInteger('tahun');

            /*
            |--------------------------------------------------------------------------
            | DOKUMEN EVALUASI 1
            |--------------------------------------------------------------------------
            */

            $table->string('dokumen_evaluasi_file')->nullable();
            $table->string('dokumen_evaluasi_path')->nullable();
            $table->unsignedBigInteger('dokumen_evaluasi_ukuran')->nullable();

            /*
            |--------------------------------------------------------------------------
            | DOKUMEN EVALUASI 2
            |--------------------------------------------------------------------------
            */

            $table->string('dokumen_kualitatif_file')->nullable();
            $table->string('dokumen_kualitatif_path')->nullable();
            $table->unsignedBigInteger('dokumen_kualitatif_ukuran')->nullable();

            /*
            |--------------------------------------------------------------------------
            | STATUS PERSETUJUAN OPERATOR
            |--------------------------------------------------------------------------
            */

            $table->enum('status', [
                'belum_lengkap',
                'menunggu_persetujuan',
                'disetujui',
                'ditolak',
            ])->default('belum_lengkap');

            /*
            |--------------------------------------------------------------------------
            | KETERANGAN PENOLAKAN
            |--------------------------------------------------------------------------
            */

            $table->text('keterangan')->nullable();

            /*
            |--------------------------------------------------------------------------
            | OPERATOR YANG MEMPROSES
            |--------------------------------------------------------------------------
            */

            $table->foreignId('diproses_oleh')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('diproses_at')->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | SATU DATA EVALUASI PER PEGAWAI PER TAHUN
            |--------------------------------------------------------------------------
            */

            $table->unique(
                ['user_id', 'tahun'],
                'skp_evaluasis_user_tahun_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skp_evaluasis');
    }
};