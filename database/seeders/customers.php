<?php

namespace Database\Seeders;

use App\Models\Customer;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class customers extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::create([
            'nama_customer' => 'John Doe',
            'email' => 'john@gmail.com',
            'password' => bcrypt('john123'),
            'tanggal_lahir' => '1990-01-01',
            'no_telp' => '081234567890',
            'poin' => 0,
            'saldo' => 0,
        ]);
        Customer::create([
            'nama_customer' => 'Jane Smith',
            'email' => 'jane.smith@example.com',
            'password' => bcrypt('jane123'),
            'tanggal_lahir' => '1985-05-15',
            'no_telp' => '081234567891',
            'poin' => 10,
            'saldo' => 0,
        ]);
        Customer::create([
            'nama_customer' => 'David Johnson',
            'email' => 'david.johnson@example.com',
            'password' => bcrypt('david123'),
            'tanggal_lahir' => '1978-10-20',
            'no_telp' => '081234567892',
            'poin' => 30,
            'saldo' => 0,
        ]);
        Customer::create([
            'nama_customer' => 'Emily Brown',
            'email' => 'emily.brown@example.com',
            'password' => bcrypt('emily123'),
            'tanggal_lahir' => '1993-03-28',
            'no_telp' => '081234567893',
            'poin' => 0,
            'saldo' => 100000,
        ]);
        Customer::create([
            'nama_customer' => 'Michael Wilson',
            'email' => 'michael.wilson@example.com',
            'password' => bcrypt('michael123'),
            'tanggal_lahir' => '1980-08-10',
            'no_telp' => '081234567894',
            'poin' => 50,
            'saldo' => 200000,
        ]);
    }
}
