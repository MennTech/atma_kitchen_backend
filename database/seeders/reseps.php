<?php

namespace Database\Seeders;
use App\Models\Resep;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class reseps extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Resep::create([
            'nama_resep' => 'Lapis Legit',
        ]);
        Resep::create([
            'nama_resep' => 'Lapis Surabaya',
        ]);
        Resep::create([
            'nama_resep' => 'Brownies',
        ]);
        Resep::create([
            'nama_resep' => 'Mandarin',
        ]);
        Resep::create([
            'nama_resep' => 'Spikoe',
        ]);
        Resep::create([
            'nama_resep' => 'Roti Sosis',
        ]);
        Resep::create([
            'nama_resep' => 'Milk Bun',
        ]);
        Resep::create([
            'nama_resep' => 'Roti Keju',
        ]);
        Resep::create([
            'nama_resep' => 'Choco Creamy Latte',
        ]);
        Resep::create([
            'nama_resep' => 'Matcha Creamy Latte',
        ]);
    }
}
