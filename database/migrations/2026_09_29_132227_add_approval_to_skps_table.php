<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('skps', function (Blueprint $table) {
            $table->enum('status', [
                'belum_upload',
                'menunggu',
                'disetujui',
                'ditolak',
            ])
                ->default('belum_upload')
                ->after('ukuran_file');

            $table->text('keterangan')->nullable()->after('status');

            $table->foreignId('diproses_oleh')
                ->nullable()
                ->after('keterangan')
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('diproses_at')->nullable()->after('diproses_oleh');
        });

        // Dokumen yang sudah ada sebelum fitur approval diberlakukan
        // dianggap menunggu persetujuan, karena file fisiknya sudah tersedia.
        DB::table('skps')
            ->whereNotNull('path_file')
            ->update(['status' => 'menunggu']);
    }

    public function down(): void
    {
        Schema::table('skps', function (Blueprint $table) {
            $table->dropForeign(['diproses_oleh']);
            $table->dropColumn([
                'status',
                'keterangan',
                'diproses_oleh',
                'diproses_at',
            ]);
        });
    }
};