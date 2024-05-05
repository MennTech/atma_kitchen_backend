<?php

namespace Database\Seeders;

use App\Models\Limit_Produk;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class limit_produks extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 15; $i++) {
            Limit_Produk::create([
                'id_produk' => $i,
                'tanggal' => '2024-05-01',
                'stok' => 20,
            ]);
        }
    }
}
