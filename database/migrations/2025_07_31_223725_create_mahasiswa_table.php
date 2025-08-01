<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->string('nim', 50)->primary();
            $table->string('nama', 50);
            $table->string('alamat', 50);
            $table->string('nohp', 50);
            $table->integer('semester');
            $table->unsignedBigInteger('id_golongan');
            $table->unsignedBigInteger('id_user_mahasiswa');
            $table->timestamps();

            $table->foreign('id_golongan')->references('id_golongan')->on('golongan')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mahasiswa');
    }
};
