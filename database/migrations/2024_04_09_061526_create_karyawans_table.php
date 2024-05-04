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
        Schema::create('karyawans', function (Blueprint $table) {
            $table->id('id_karyawan')->autoIncrement();
            $table->foreignId('id_role')->constrained('roles', 'id_role');
            $table->string('nama_karyawan');
            $table->string('no_telp', 13);
            $table->string('email_karyawan')->unique()->nullable();
            $table->string('password')->nullable();
            $table->double('bonus');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawans');
    }
};
