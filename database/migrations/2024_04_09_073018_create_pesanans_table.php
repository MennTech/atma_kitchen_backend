<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pesanans', function (Blueprint $table) {
            $table->id('id_pesanan')->autoIncrement();
            $table->foreignId('id_customer')->constrained('customers', 'id_customer');
            $table->dateTime('tanggal_pesan')->nullable();
            $table->dateTime('tanggal_ambil')->nullable();
            $table->dateTime('tanggal_lunas')->nullable();
            $table->string('metode_pesan')->nullable();
            $table->string('alamat')->nullable();
            $table->string('delivery')->nullable();
            $table->double('total')->nullable();
            $table->double('ongkos_kirim')->nullable();
            $table->double('jarak')->nullable();
            $table->double('tip')->nullable();
            $table->string('status')->nullable();
            $table->double('jumlah_pembayaran')->nullable();
            $table->integer('poin_dipakai')->nullable();
            $table->integer('poin_didapat')->nullable();
            $table->string('bukti_pembayaran')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanans');
    }
};
