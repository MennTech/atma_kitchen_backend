<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Detail_Hampers;

class detail_hamper extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Detail_Hampers::create([
            'id_hampers' => 1,
            'id_produk' => 6,
            'id_bahan_baku' => 22,
        ]);
        Detail_Hampers::create([
            'id_hampers' => 1,
            'id_produk' => 8,
            'id_bahan_baku' => 22,
        ]);
        Detail_Hampers::create([
            'id_hampers' => 2,
            'id_produk' => 7,
            'id_bahan_baku' => 22,
        ]);
        Detail_Hampers::create([
            'id_hampers' => 2,
            'id_produk' => 11,
            'id_bahan_baku' => 22,
        ]);
        Detail_Hampers::create([
            'id_hampers' => 3,
            'id_produk' => 10,
            'id_bahan_baku' => 22,
        ]);
        Detail_Hampers::create([
            'id_hampers' => 3,
            'id_produk' => 14,
            'id_bahan_baku' => 22,
        ]);
    }
}
