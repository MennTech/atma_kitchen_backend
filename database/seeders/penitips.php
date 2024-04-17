<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Penitip;

class penitips extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Penitip::create([
            'nama_penitip' => 'Ani',
            'no_telp' => '081234567891',
        ]);
            
        Penitip::create([
            'nama_penitip' => 'Cindy',
            'no_telp' => '081234567892',
        ]);
        
        Penitip::create([
            'nama_penitip' => 'Dedy',
            'no_telp' => '081234567893',
        ]);
        
        Penitip::create([
            'nama_penitip' => 'Eva',
            'no_telp' => '081234567894',
        ]);
        
        Penitip::create([
            'nama_penitip' => 'Fani',
            'no_telp' => '081234567895',
        ]);
    }
}
