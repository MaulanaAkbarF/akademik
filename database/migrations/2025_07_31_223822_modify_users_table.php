<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'dosen', 'mahasiswa'])->after('email');
            $table->string('nip', 20)->nullable()->after('role');
            $table->string('nim', 50)->nullable()->after('nip');
            
            // Add foreign key constraints
            $table->foreign('nip')->references('nip')->on('dosen')->onDelete('set null');
            $table->foreign('nim')->references('nim')->on('mahasiswa')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['nip']);
            $table->dropForeign(['nim']);
            $table->dropColumn(['role', 'nip', 'nim']);
        });
    }
};
