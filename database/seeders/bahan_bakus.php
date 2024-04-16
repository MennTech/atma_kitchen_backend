<?php

namespace Database\Seeders;
use App\Models\Bahan_Baku;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class bahan_bakus extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Bahan_Baku::create([
            'nama_bahan_baku' => 'Butter',
            'stok' => 5000,
            'satuan' => 'gr',
        ]);
        Bahan_Baku::create([
            'nama_bahan_baku' => 'Creamer',
            'stok' => 5000,
            'satuan' => 'gr',
        ]);
        Bahan_Baku::create([
            'nama_bahan_baku' => 'Telur',
            'stok' => 1000,
            'satuan' => 'butir',
        ]);
        Bahan_Baku::create([
            'nama_bahan_baku' => 'Gula Pasir',
            'stok' => 5000,
            'satuan' => 'gr',
        ]);
        Bahan_Baku::create([
            'nama_bahan_baku' => 'Susu Bubuk',
            'stok' => 5000,
            'satuan' => 'gr',
        ]);
        Bahan_Baku::create([
            'nama_bahan_baku' => 'Tepung Terigu',
            'stok' => 5000,
            'satuan' => 'gr',
        ]);
        Bahan_Baku::create([
            'nama_bahan_baku' => 'Garam',
            'stok' => 5000,
            'satuan' => 'gr',
        ]);
        Bahan_Baku::create([
            'nama_bahan_baku' => 'Coklat Bubuk',
            'stok' => 5000,
            'satuan' => 'gr',
        ]);
        Bahan_Baku::create([
            'nama_bahan_baku' => 'Selai Strawberry',
            'stok' => 5000,
            'satuan' => 'gr',
        ]);
        Bahan_Baku::create([
            'nama_bahan_baku' => 'Coklat Batang',
            'stok' => 5000,
            'satuan' => 'gr',
        ]);
        Bahan_Baku::create([
            'nama_bahan_baku' => 'Minyak Goreng',
            'stok' => 5000,
            'satuan' => 'ml',
        ]);
        Bahan_Baku::create([
            'nama_bahan_baku' => 'Tepung Maizena',
            'stok' => 5000,
            'satuan' => 'gr',
        ]);
        Bahan_Baku::create([
            'nama_bahan_baku' => 'Baking Powder',
            'stok' => 5000,
            'satuan' => 'gr',
        ]);
        Bahan_Baku::create([
            'nama_bahan_baku' => 'Kacang Kenari',
            'stok' => 5000,
            'satuan' => 'gr',
        ]);
        Bahan_Baku::create([
            'nama_bahan_baku' => 'Ragi',
            'stok' => 5000,
            'satuan' => 'gr',
        ]);
        Bahan_Baku::create([
            'nama_bahan_baku' => 'Susu Cair',
            'stok' => 5000,
            'satuan' => 'ml',
        ]);
        Bahan_Baku::create([
            'nama_bahan_baku' => 'Sosis Blackpapper',
            'stok' => 5000,
            'satuan' => 'buah',
        ]);
        Bahan_Baku::create([
            'nama_bahan_baku' => 'Whipped Cream',
            'stok' => 5000,
            'satuan' => 'ml',
        ]);
        Bahan_Baku::create([
            'nama_bahan_baku' => 'Susu Full Cream',
            'stok' => 5000,
            'satuan' => 'ml',
        ]);
        Bahan_Baku::create([
            'nama_bahan_baku' => 'Keju Mozarella',
            'stok' => 5000,
            'satuan' => 'gr',
        ]);
        Bahan_Baku::create([
            'nama_bahan_baku' => 'Matcha Bubuk',
            'stok' => 5000,
            'satuan' => 'gr',
        ]);
        Bahan_Baku::create([
            'nama_bahan_baku' => 'Exclusive Box and Card',
            'stok' => 1000,
            'satuan' => 'buah',
        ]);
        
    }
}
