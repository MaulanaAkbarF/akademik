<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('jadwal_akademik', function (Blueprint $table) {
            $table->time('jam_mulai')->after('hari');
            $table->time('jam_selesai')->after('jam_mulai');
        });
    }

    public function down()
    {
        Schema::table('jadwal_akademik', function (Blueprint $table) {
            $table->dropColumn(['jam_mulai', 'jam_selesai']);
        });
    }
};