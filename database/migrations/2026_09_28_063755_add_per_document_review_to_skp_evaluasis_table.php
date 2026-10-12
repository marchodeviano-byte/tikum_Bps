<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('skp_evaluasis', function (Blueprint $table) {

            $table->enum('status_dokumen_evaluasi', [
                'belum_upload',
                'menunggu',
                'disetujui',
                'ditolak',
            ])
            ->default('belum_upload')
            ->after('dokumen_evaluasi_ukuran');

            $table->text('keterangan_dokumen_evaluasi')
                ->nullable()
                ->after('status_dokumen_evaluasi');

            $table->enum('status_dokumen_kualitatif', [
                'belum_upload',
                'menunggu',
                'disetujui',
                'ditolak',
            ])
            ->default('belum_upload')
            ->after('dokumen_kualitatif_ukuran');

            $table->text('keterangan_dokumen_kualitatif')
                ->nullable()
                ->after('status_dokumen_kualitatif');
        });
    }

    public function down(): void
    {
        Schema::table('skp_evaluasis', function (Blueprint $table) {
            $table->dropColumn([
                'status_dokumen_evaluasi',
                'keterangan_dokumen_evaluasi',
                'status_dokumen_kualitatif',
                'keterangan_dokumen_kualitatif',
            ]);
        });
    }
};