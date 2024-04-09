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
        Schema::create('pembelian_bahan_bakus', function (Blueprint $table) {
            $table->id('id_pembelian_bahan_baku')->autoIncrement();
            $table->foreignId('id_bahan_baku')->constrained('bahan_bakus', 'id_bahan_baku');
            $table->date('tanggal');
            $table->integer('jumlah');
            $table->double('harga');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelian_bahan_bakus');
    }
};
