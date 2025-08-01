<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('krs', function (Blueprint $table) {
            $table->id();
            $table->string('nim', 20);
            $table->string('kode_mk', 20);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('krs');
    }
};
