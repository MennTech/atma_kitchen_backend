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
        Schema::create('detail_pesanans', function (Blueprint $table) {
            $table->foreignId('id_pesanan')->constrained('pesanans', 'id_pesanan');
            $table->foreignId('id_produk')->nullable()->constrained('produks', 'id_produk');
            $table->foreignId('id_hampers')->nullable()->constrained('hampers', 'id_hampers');
            $table->double('jumlah');
            $table->double('subtotal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pesanans');
    }
};
