<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Detail_pesanan;

class detail_pesanans extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Detail_pesanan::create([
            'id_pesanan' => 1,
            'id_produk' => 1,
            'jumlah' => 1,
            'subtotal' => 450000,
        ]);
        Detail_pesanan::create([
            'id_pesanan' => 2,
            'id_produk' => 2,
            'jumlah' => 1,
            'subtotal' => 300000,
        ]);
        Detail_pesanan::create([
            'id_pesanan' => 3,
            'id_produk' => 3,
            'jumlah' => 1,
            'subtotal' => 150000,
        ]);
        Detail_pesanan::create([
            'id_pesanan' => 4,
            'id_produk' => 4,
            'jumlah' => 1,
            'subtotal' => 250000,
        ]);
        Detail_pesanan::create([
            'id_pesanan' => 5,
            'id_produk' => 5,
            'jumlah' => 1,
            'subtotal' => 350000,
        ]);
    }
}
