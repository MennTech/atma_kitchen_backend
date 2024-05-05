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
            'gambar_hampers' => 'paket_a.jpg',
            'deskripsi_hampers' => 'Paket A berisi bunga mawar, coklat, dan boneka beruang.',
            'nama_hampers' => 'Paket A',
            'harga' => 650000,
        ]);
        Hampers::create([
            'gambar_hampers' => 'paket_b.jpg',
            'deskripsi_hampers' => 'Paket B berisi bunga mawar, coklat, dan boneka beruang.',
            'nama_hampers' => 'Paket B',
            'harga' => 500000,
        ]);
        Hampers::create([
            'gambar_hampers' => 'paket_c.jpg',
            'deskripsi_hampers' => 'Paket C berisi bunga mawar, coklat, dan boneka beruang.',
            'nama_hampers' => 'Paket C',
            'harga' => 350000,
        ]);
    }
}
