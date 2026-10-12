<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
        | Satu baris = satu sesi Penilaian Tahap 2 untuk satu periode.
        | status: draft (panitia masih memilih pertanyaan)
        |         berjalan (sudah "Mulai Penilaian", masuk ke menu Pegawai)
        */
        Schema::create('sipeter_penilaian_tahap2', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('tahun');
            $table->unsignedTinyInteger('triwulan');
            $table->string('status', 20)->default('draft');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamps();

            $table->unique(['tahun', 'triwulan']);
        });

        /*
        | Pertanyaan yang dipilih panitia untuk sesi tersebut.
        | Teks pertanyaan disalin (snapshot) supaya kalau pertanyaan
        | diedit/dihapus di Manajemen Pertanyaan, penilaian yang sedang
        | berjalan tidak ikut berubah atau rusak.
        */
        Schema::create('sipeter_penilaian_tahap2_pertanyaan', function (Blueprint $table) {
            $table->id();

            $table->foreignId('penilaian_tahap2_id')
                ->constrained('sipeter_penilaian_tahap2')
                ->cascadeOnDelete();

            $table->unsignedBigInteger('sipeter_pertanyaan_id')->nullable()->index();
            $table->unsignedSmallInteger('urutan');
            $table->text('pertanyaan');
            $table->timestamps();

            $table->unique(
                ['penilaian_tahap2_id', 'sipeter_pertanyaan_id'],
                'uniq_tahap2_pertanyaan'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sipeter_penilaian_tahap2_pertanyaan');
        Schema::dropIfExists('sipeter_penilaian_tahap2');
    }
};