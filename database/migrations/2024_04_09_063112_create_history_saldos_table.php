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
        Schema::create('history_saldos', function (Blueprint $table) {
            $table->id('id_history_saldo')->autoIncrement();
            $table->foreignId('id_customer')->constrained('customers', 'id_customer');
            $table->date('tanggal');
            $table->string('status');
            $table->double('nominal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_saldos');
    }
};
