<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sks_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('nim');
            $table->integer('jumlah_sks');
            $table->decimal('harga_per_sks', 10, 2);
            $table->decimal('total_harga', 12, 2);
            $table->enum('metode_pembayaran', ['transfer_bank', 'e_wallet', 'cash', 'va_bni', 'va_bca', 'va_mandiri']);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('tanggal_transaksi');
            $table->string('bukti_pembayaran')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('nim')->references('nim')->on('mahasiswa')->onDelete('cascade');
            $table->index(['nim', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('sks_transactions');
    }
};
