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
        Schema::create('limit_produks', function (Blueprint $table) {
            $table->id('id_limit_produk')->autoIncrement();
            $table->foreignId('id_produk')->constrained('produks', 'id_produk');
            $table->date('tanggal');
            $table->integer('stok');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('limit_produks');
    }
};
