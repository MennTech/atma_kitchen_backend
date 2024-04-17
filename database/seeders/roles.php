<?php

namespace Database\Seeders;
use App\Models\Role;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class roles extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'jabatan' => 'Admin',
            'gaji' => 5000000,
        ]);
        Role::create([
            'jabatan' => 'Manager Operational',
            'gaji' => 6000000,
        ]);
        Role::create([
            'jabatan' => 'Owner',
            'gaji' => 0,
        ]);
        Role::create([
            'jabatan' => 'Karyawan',
            'gaji' => 4000000,
        ]);
    }
}
