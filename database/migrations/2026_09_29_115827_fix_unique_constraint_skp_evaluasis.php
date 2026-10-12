<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('skp_evaluasis', function (Blueprint $table) {

            // Buat unique index baru terlebih dahulu.
            // Index ini juga tetap diawali dengan user_id,
            // sehingga foreign key user_id tetap memiliki index.
            $table->unique(
                [
                    'user_id',
                    'tahun',
                    'jenis',
                    'bulan',
                    'triwulan',
                ],
                'skp_evaluasis_periode_unique'
            );

            // Setelah unique baru tersedia, baru hapus unique lama.
            $table->dropUnique('skp_evaluasis_user_tahun_unique');
        });
    }

    public function down(): void
    {
        Schema::table('skp_evaluasis', function (Blueprint $table) {

            // Buat kembali unique lama terlebih dahulu.
            $table->unique(
                ['user_id', 'tahun'],
                'skp_evaluasis_user_tahun_unique'
            );

            // Setelah unique lama tersedia, hapus unique periode.
            $table->dropUnique('skp_evaluasis_periode_unique');
        });
    }
};