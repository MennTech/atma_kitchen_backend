<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pesanan;

class pesanans extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pesanan::create([
            'id_customer' => 1,
            'tanggal_pesan' => '2024-04-01',
            'tanggal_ambil' => '2024-04-05',
            'tanggal_lunas' => '2024-04-01',
            'alamat' => 'Jl. Raya No. 1',
            'delivery' => 'Pickup',
            'total' => 850000,
            'ongkos_kirim' => 0,
            'status' => 'Pembayaran Valid',
            'jumlah_pembayaran' => 850000,
            'poin_dipakai' => 0,
            'poin_didapat' => 125,
            'bukti_pembayaran' => 'bukti_pembayaran.jpg',
        ]);
        Pesanan::create([
            'id_customer' => 2,
            'tanggal_pesan' => '2024-04-02',
            'tanggal_ambil' => '2024-04-06',
            'tanggal_lunas' => '2024-04-02',
            'alamat' => 'Jl. Mawar No. 5',
            'delivery' => 'Kurir Atma Kitchen',
            'total' => 600000,
            'ongkos_kirim' => 50000,
            'status' => 'Dikirim',
            'jumlah_pembayaran' => 600000,
            'poin_dipakai' => 0,
            'poin_didapat' => 80,
            'bukti_pembayaran' => 'bukti_pembayaran_2.jpg',
        ]);
        
        Pesanan::create([
            'id_customer' => 3,
            'tanggal_pesan' => '2024-04-03',
            'tanggal_ambil' => '2024-04-07',
            'tanggal_lunas' => '2024-04-03',
            'alamat' => 'Jl. Kenanga No. 10',
            'delivery' => 'Pickup',
            'total' => 250000,
            'ongkos_kirim' => 0,
            'status' => 'Menunggu Pembayaran',
            'jumlah_pembayaran' => 0,
            'poin_dipakai' => 0,
            'poin_didapat' => 0,
            'bukti_pembayaran' => null,
        ]);
        
        Pesanan::create([
            'id_customer' => 4,
            'tanggal_pesan' => '2024-04-04',
            'tanggal_ambil' => '2024-04-08',
            'tanggal_lunas' => '2024-04-04',
            'alamat' => 'Jl. Cempaka No. 3',
            'delivery' => 'Ojek Online',
            'total' => 450000,
            'ongkos_kirim' => 0,
            'status' => 'Dikirim',
            'jumlah_pembayaran' => 450000,
            'poin_dipakai' => 0,
            'poin_didapat' => 65,
            'bukti_pembayaran' => 'bukti_pembayaran_3.jpg',
        ]);
        
        Pesanan::create([
            'id_customer' => 5,
            'tanggal_pesan' => '2024-04-05',
            'tanggal_ambil' => '2024-04-09',
            'tanggal_lunas' => '2024-04-05',
            'alamat' => 'Jl. Anggrek No. 7',
            'delivery' => 'Pickup',
            'total' => 350000,
            'ongkos_kirim' => 0,
            'status' => 'Selesai',
            'jumlah_pembayaran' => 350000,
            'poin_dipakai' => 0,
            'poin_didapat' => 50,
            'bukti_pembayaran' => 'bukti_pembayaran_4.jpg',
        ]);
    }
}
