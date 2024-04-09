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
        Schema::create('pengeluaran_lains', function (Blueprint $table) {
            $table->id('id_pengeluaran_lain')->autoIncrement();
            $table->string('nama_pengeluaran');
            $table->date('tanggal');
            $table->double('harga');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengeluaran_lains');
    }
};
