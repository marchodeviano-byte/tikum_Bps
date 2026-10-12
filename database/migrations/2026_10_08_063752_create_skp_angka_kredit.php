<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
        | Satu baris = angka kredit satu pegawai pada satu tahun.
        |
        | - angka_kredit_awal : NULL  -> otomatis = angka kredit akhir tahun sebelumnya
        |                       diisi -> nilai manual (dipakai untuk angka dasar
        |                                atau koreksi)
        | - semester_1 / semester_2
        | - Angka kredit akhir TIDAK disimpan, selalu dihitung:
        |   awal + semester 1 + semester 2
        */
        Schema::create('skp_angka_kredit', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->unsignedSmallInteger('tahun');

            $table->decimal('angka_kredit_awal', 10, 3)->nullable();
            $table->decimal('semester_1', 10, 3)->default(0);
            $table->decimal('semester_2', 10, 3)->default(0);

            $table->timestamps();

            $table->unique(['user_id', 'tahun']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skp_angka_kredit');
    }
};