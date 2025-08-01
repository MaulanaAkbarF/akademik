<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presensi_akademik', function (Blueprint $table) {
            $table->id();
            $table->string('hari', 10);
            $table->date('tanggal');
            $table->string('status_kehadiran', 15);
            $table->string('nim', 15);
            $table->string('kode_mk', 15);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presensi_akademik');
    }
};
