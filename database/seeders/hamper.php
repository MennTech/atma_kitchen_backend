<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Hampers;

class hamper extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Hampers::create([
            'nama_hampers' => 'Paket A',
            'harga' => 650000,
        ]);
        Hampers::create([
            'nama_hampers' => 'Paket B',
            'harga' => 500000,
        ]);
        Hampers::create([
            'nama_hampers' => 'Paket C',
            'harga' => 350000,
        ]);
    }
}
