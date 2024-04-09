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
        Schema::create('produks', function (Blueprint $table) {
            $table->id('id_produk')->autoIncrement();
            $table->foreignId('id_penitip')->nullable()->constrained('penitips', 'id_penitip');
            $table->string('gambar_produk');
            $table->string('nama_produk');
            $table->string('deskripsi_produk');
            $table->double('harga');
            $table->string('kategori');
            $table->string('status');
            $table->integer('stok_tersedia');
            $table->foreignId('id_resep')->nullable()->constrained('reseps', 'id_resep');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produks');
    }
};
