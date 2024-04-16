<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pengeluaran_lain;

class pengeluaran_lains extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pengeluaran_lain::create([
            'nama_pengeluaran' => 'Listrik',
            'tanggal' => '2024-04-01',
            'harga' => 500000,
        ]);
        Pengeluaran_lain::create([
            'nama_pengeluaran' => 'Air',
            'tanggal' => '2024-04-02',
            'harga' => 300000,
        ]);
        
        Pengeluaran_lain::create([
        'nama_pengeluaran' => 'Gaji Karyawan',
        'tanggal' => '2024-04-03',
        'harga' => 15000000,
        ]);
        
        Pengeluaran_lain::create([
        'nama_pengeluaran' => 'Pajak',
        'tanggal' => '2024-04-04',
        'harga' => 2000000,
        ]);
        
        Pengeluaran_lain::create([
        'nama_pengeluaran' => 'Peralatan Toko',
        'tanggal' => '2024-04-05',
        'harga' => 10000000,
        ]);
    }
}
