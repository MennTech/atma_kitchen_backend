<?php

namespace Database\Seeders;
use App\Models\Alamat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class alamats extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Alamat::create([
            'id_customer' => 1,
            'nama_jalan' => 'Jl. Raya No. 1',
            'kode_pos' => 1151
        ]);
        Alamat::create([
            'id_customer' => 2,
            'nama_jalan' => 'Jl. Mawar No. 5',
            'kode_pos' => 1152
        ]);
        
        Alamat::create([
            'id_customer' => 3,
            'nama_jalan' => 'Jl. Kenanga No. 10',
            'kode_pos' => 1153
        ]);
        
        Alamat::create([
            'id_customer' => 4,
            'nama_jalan' => 'Jl. Cempaka No. 3',
            'kode_pos' => 1154
        ]);
        
        Alamat::create([
            'id_customer' => 5,
            'nama_jalan' => 'Jl. Anggrek No. 7',
            'kode_pos' => 1155
        ]);
    }
}
