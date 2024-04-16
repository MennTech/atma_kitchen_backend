<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pembelian_Bahan_Baku;

class pembelian_bahan_bakus extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pembelian_Bahan_Baku::create([
            'id_bahan_baku' => 1,
            'tanggal' => '2024-03-31',
            'jumlah' => 1000,
            'harga' => 500000,
        ]);
        Pembelian_Bahan_Baku::create([
            'id_bahan_baku' => 2,
            'tanggal' => '2024-04-01',
            'jumlah' => 800,
            'harga' => 400000,
        ]);
        
        Pembelian_Bahan_Baku::create([
        'id_bahan_baku' => 3,
        'tanggal' => '2024-04-02',
        'jumlah' => 1200,
        'harga' => 600000,
        ]);
        
        Pembelian_Bahan_Baku::create([
        'id_bahan_baku' => 4,
        'tanggal' => '2024-04-03',
        'jumlah' => 600,
        'harga' => 300000,
        ]);
        
        Pembelian_Bahan_Baku::create([
        'id_bahan_baku' => 5,
        'tanggal' => '2024-04-04',
        'jumlah' => 1500,
        'harga' => 750000,
        ]);
    }
}
