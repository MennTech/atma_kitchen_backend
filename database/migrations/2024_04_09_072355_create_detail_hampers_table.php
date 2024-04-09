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
        Schema::create('detail_hampers', function (Blueprint $table) {
            $table->foreignId('id_hampers')->constrained('hampers', 'id_hampers');
            $table->foreignId('id_produk')->nullable()->constrained('produks', 'id_produk');
            $table->foreignId('id_bahan_baku')->nullable()->constrained('bahan_bakus', 'id_bahan_baku');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_hampers');
    }
};
