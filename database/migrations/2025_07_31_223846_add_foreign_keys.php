<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add foreign keys for pengampu table
        Schema::table('pengampu', function (Blueprint $table) {
            $table->foreign('kode_mk')->references('kode_mk')->on('matakuliah')->onDelete('cascade');
            $table->foreign('nip')->references('nip')->on('dosen')->onDelete('cascade');
        });

        // Add foreign keys for krs table
        Schema::table('krs', function (Blueprint $table) {
            $table->foreign('nim')->references('nim')->on('mahasiswa')->onDelete('cascade');
            $table->foreign('kode_mk')->references('kode_mk')->on('matakuliah')->onDelete('cascade');
        });

        // Add foreign keys for presensi_akademik table
        Schema::table('presensi_akademik', function (Blueprint $table) {
            $table->foreign('nim')->references('nim')->on('mahasiswa')->onDelete('cascade');
            $table->foreign('kode_mk')->references('kode_mk')->on('matakuliah')->onDelete('cascade');
        });

        // Add foreign key for mahasiswa id_user_mahasiswa
        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->foreign('id_user_mahasiswa')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('pengampu', function (Blueprint $table) {
            $table->dropForeign(['kode_mk']);
            $table->dropForeign(['nip']);
        });

        Schema::table('krs', function (Blueprint $table) {
            $table->dropForeign(['nim']);
            $table->dropForeign(['kode_mk']);
        });

        Schema::table('presensi_akademik', function (Blueprint $table) {
            $table->dropForeign(['nim']);
            $table->dropForeign(['kode_mk']);
        });

        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->dropForeign(['id_user_mahasiswa']);
        });
    }
};