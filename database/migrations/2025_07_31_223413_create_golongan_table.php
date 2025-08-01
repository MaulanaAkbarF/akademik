<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('golongan', function (Blueprint $table) {
            $table->id('id_golongan');
            $table->string('nama_gol', 10);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('golongan');
    }
};
