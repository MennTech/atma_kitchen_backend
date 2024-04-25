<?php

namespace Database\Seeders;

use App\Models\Customer;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Customer::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            bahan_bakus::class,
            reseps::class,
            pembelian_bahan_bakus::class,
            penitips::class,
            produks::class,
            hamper::class,
            detail_hamper::class,
            customers::class,
            roles::class,
            karyawans::class,
            presensis::class,
            pengeluaran_lains::class,
            detail_reseps::class,
            pesanans::class,
            detail_pesanans::class,
            alamats::class,
        ]);
    }
}
