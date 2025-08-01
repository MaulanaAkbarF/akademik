<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matakuliah', function (Blueprint $table) {
            $table->string('kode_mk', 15)->primary();
            $table->string('nama_mk', 255);
            $table->integer('sks');
            $table->integer('semester');
            $table->integer('stok');
            $table->boolean('is_open')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matakuliah');
    }
};