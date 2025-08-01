<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_akademik', function (Blueprint $table) {
            $table->id('id_jadwal');
            $table->string('hari', 15);
            $table->string('kode_mk', 15);
            $table->unsignedBigInteger('id_ruang');
            $table->unsignedBigInteger('id_gol');
            $table->timestamps();

            $table->foreign('id_ruang')->references('id_ruang')->on('ruang')->onDelete('cascade');
            $table->foreign('id_gol')->references('id_golongan')->on('golongan')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_akademik');
    }
};