<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Presensi;

class presensis extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Presensi::create([
            'id_karyawan' => 1,
            'tanggal' => '2024-04-01',
            'status' => 'Hadir',
        ]);
        Presensi::create([
            'id_karyawan' => 2,
            'tanggal' => '2024-04-01',
            'status' => 'Hadir',
        ]);
        Presensi::create([
            'id_karyawan' => 4,
            'tanggal' => '2024-04-01',
            'status' => 'Hadir',
        ]);
        Presensi::create([
            'id_karyawan' => 5,
            'tanggal' => '2024-04-01',
            'status' => 'Hadir',
        ]);
        Presensi::create([
            'id_karyawan' => 6,
            'tanggal' => '2024-04-01',
            'status' => 'Absen',
        ]);
    }
}
