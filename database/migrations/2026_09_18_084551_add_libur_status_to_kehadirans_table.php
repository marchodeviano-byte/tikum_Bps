<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE kehadirans
            MODIFY status ENUM(
                'Hadir',
                'Izin',
                'Dinas',
                'Sakit',
                'Tanpa Keterangan',
                'Libur'
            ) NOT NULL
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE kehadirans
            MODIFY status ENUM(
                'Hadir',
                'Izin',
                'Dinas',
                'Sakit',
                'Tanpa Keterangan'
            ) NOT NULL
        ");
    }
};