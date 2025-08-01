<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sks_saldo', function (Blueprint $table) {
            $table->id();
            $table->string('nim')->unique();
            $table->integer('sks_tersedia')->default(0);
            $table->integer('sks_terpakai')->default(0);
            $table->integer('total_sks_dibeli')->default(0);
            $table->timestamps();

            $table->foreign('nim')->references('nim')->on('mahasiswa')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sks_saldo');
    }
};
