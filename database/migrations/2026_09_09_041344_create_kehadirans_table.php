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
        Schema::create('kehadirans', function (Blueprint $table) {
            $table->id();

            // Relasi ke pegawai
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Tanggal kehadiran
            $table->date('tanggal');

            // Status sesuai template Excel
            $table->enum('status', [
                'Hadir',
                'Izin',
                'Sakit',
                'Tanpa Keterangan',
            ]);

            // Jam masuk
            $table->time('jam_masuk')->nullable();

            // Jam pulang
            $table->time('jam_pulang')->nullable();

            // Keterangan tambahan
            $table->text('keterangan')->nullable();

            $table->timestamps();

            // Satu pegawai hanya memiliki satu
            // data kehadiran pada tanggal yang sama
            $table->unique(['user_id', 'tanggal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kehadirans');
    }
};