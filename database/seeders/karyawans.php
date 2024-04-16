<?php

namespace Database\Seeders;
use App\Models\Karyawan;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class karyawans extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Karyawan::create([
            'id_role' => 1,
            'nama_karyawan' => 'David Gadgetin',
            'no_telp' => '081234567890',
            'email_karyawan' => 'admin@gmail.com',
            'password' => bcrypt('admin123'),
            'status' => 'Aktif',
            'bonus' => 1000000,
        ]);
        Karyawan::create([
            'id_role' => 2,
            'nama_karyawan' => 'Sarah Johnson',
            'no_telp' => '081234567891',
            'email_karyawan' => 'MO@gmail.com',
            'password' => bcrypt('sarah123'),
            'status' => 'Aktif',
            'bonus' => 1500000,
        ]);
        Karyawan::create([
            'id_role' => 3,
            'nama_karyawan' => 'Michael Brown',
            'no_telp' => '081234567892',
            'email_karyawan' => 'michael.brown@example.com',
            'password' => bcrypt('michael123'),
            'status' => 'Aktif',
            'bonus' => 0,
        ]);
        Karyawan::create([
            'id_role' => 4,
            'nama_karyawan' => 'Emily Wilson',
            'no_telp' => '081234567893',
            'status' => 'Aktif',
            'bonus' => 500000,
        ]);
        Karyawan::create([
            'id_role' => 4,
            'nama_karyawan' => 'John Anderson',
            'no_telp' => '081234567894',
            'status' => 'Aktif',
            'bonus' => 700000,
        ]);
        Karyawan::create([
            'id_role' => 4,
            'nama_karyawan' => 'Jane Martinez',
            'no_telp' => '081234567895',
            'status' => 'Aktif',
            'bonus' => 750000,
        ]);

    }
}
