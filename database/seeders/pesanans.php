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
            'metode_pesan' => 'PO',
            'alamat' => 'Jl. Raya No. 1',
            'delivery' => 'Pickup',
            'total' => 850000,
            'ongkos_kirim' => 0,
            'jarak' => 0,
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
            'metode_pesan' => 'PO',
            'alamat' => 'Jl. Mawar No. 5',
            'delivery' => 'Kurir Atma Kitchen',
            'total' => 600000,
            'ongkos_kirim' => 50000,
            'jarak' => 16,
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
            'metode_pesan' => 'PO',
            'alamat' => 'Jl. Kenanga No. 10',
            'delivery' => 'Pickup',
            'total' => 250000,
            'ongkos_kirim' => 0,
            'jarak' => 0,
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
            'metode_pesan' => 'PO',
            'alamat' => 'Jl. Cempaka No. 3',
            'delivery' => 'Ojek Online',
            'total' => 450000,
            'ongkos_kirim' => 0,
            'jarak' => 0,
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
            'metode_pesan' => 'PO',
            'alamat' => 'Jl. Anggrek No. 7',
            'delivery' => 'Pickup',
            'total' => 350000,
            'ongkos_kirim' => 0,
            'jarak' => 0,
            'status' => 'Selesai',
            'jumlah_pembayaran' => 350000,
            'poin_dipakai' => 0,
            'poin_didapat' => 50,
            'bukti_pembayaran' => 'bukti_pembayaran_4.jpg',
        ]);
        Pesanan::create([
            'id_customer' => 1,
            'tanggal_pesan' => '2024-04-02',
            'tanggal_ambil' => '2024-04-06',
            'tanggal_lunas' => '2024-04-02',
            'metode_pesan' => 'PO',
            'alamat' => 'Jl. Harapan No. 5',
            'delivery' => 'Pickup',
            'total' => 720000,
            'ongkos_kirim' => 0,
            'jarak' => 0,
            'status' => 'Pembayaran Valid',
            'jumlah_pembayaran' => 720000,
            'poin_dipakai' => 0,
            'poin_didapat' => 100,
            'bukti_pembayaran' => 'bukti_pembayaran_2.jpg',
        ]);
        
        Pesanan::create([
            'id_customer' => 1,
            'tanggal_pesan' => '2024-04-03',
            'tanggal_ambil' => '2024-04-07',
            'tanggal_lunas' => '2024-04-03',
            'metode_pesan' => 'PO',
            'alamat' => 'Jl. Merdeka No. 10',
            'delivery' => 'Pickup',
            'total' => 950000,
            'ongkos_kirim' => 0,
            'jarak' => 0,
            'status' => 'Pembayaran Valid',
            'jumlah_pembayaran' => 950000,
            'poin_dipakai' => 0,
            'poin_didapat' => 150,
            'bukti_pembayaran' => 'bukti_pembayaran_3.jpg',
        ]);
        
        Pesanan::create([
            'id_customer' => 1,
            'tanggal_pesan' => '2024-04-04',
            'tanggal_ambil' => '2024-04-08',
            'tanggal_lunas' => '2024-04-04',
            'metode_pesan' => 'PO',
            'alamat' => 'Jl. Pahlawan No. 15',
            'delivery' => 'Pickup',
            'total' => 820000,
            'ongkos_kirim' => 0,
            'jarak' => 0,
            'status' => 'Pembayaran Valid',
            'jumlah_pembayaran' => 820000,
            'poin_dipakai' => 0,
            'poin_didapat' => 110,
            'bukti_pembayaran' => 'bukti_pembayaran_4.jpg',
        ]);
        
        Pesanan::create([
            'id_customer' => 1,
            'tanggal_pesan' => '2024-04-05',
            'tanggal_ambil' => '2024-04-09',
            'tanggal_lunas' => '2024-04-05',
            'metode_pesan' => 'PO',
            'alamat' => 'Jl. Surya No. 20',
            'delivery' => 'Pickup',
            'total' => 700000,
            'ongkos_kirim' => 0,
            'jarak' => 0,
            'status' => 'Pembayaran Valid',
            'jumlah_pembayaran' => 700000,
            'poin_dipakai' => 0,
            'poin_didapat' => 90,
            'bukti_pembayaran' => 'bukti_pembayaran_5.jpg',
        ]);
        Pesanan::create([
            'id_customer' => 2,
            'tanggal_pesan' => '2024-04-02',
            'tanggal_ambil' => '2024-04-06',
            'tanggal_lunas' => '2024-04-02',
            'metode_pesan' => 'PO',
            'alamat' => 'Jl. Mawar No. 5',
            'delivery' => 'Pickup',
            'total' => 620000,
            'ongkos_kirim' => 0,
            'jarak' => 0,
            'status' => 'Pembayaran Valid',
            'jumlah_pembayaran' => 620000,
            'poin_dipakai' => 0,
            'poin_didapat' => 80,
            'bukti_pembayaran' => 'bukti_pembayaran_6.jpg',
        ]);
        
        Pesanan::create([
            'id_customer' => 2,
            'tanggal_pesan' => '2024-04-03',
            'tanggal_ambil' => '2024-04-07',
            'tanggal_lunas' => '2024-04-03',
            'metode_pesan' => 'PO',
            'alamat' => 'Jl. Melati No. 10',
            'delivery' => 'Pickup',
            'total' => 750000,
            'ongkos_kirim' => 0,
            'jarak' => 0,
            'status' => 'Pembayaran Valid',
            'jumlah_pembayaran' => 750000,
            'poin_dipakai' => 0,
            'poin_didapat' => 120,
            'bukti_pembayaran' => 'bukti_pembayaran_7.jpg',
        ]);
        
        Pesanan::create([
            'id_customer' => 2,
            'tanggal_pesan' => '2024-04-04',
            'tanggal_ambil' => '2024-04-08',
            'tanggal_lunas' => '2024-04-04',
            'metode_pesan' => 'PO',
            'alamat' => 'Jl. Anggrek No. 15',
            'delivery' => 'Pickup',
            'total' => 920000,
            'ongkos_kirim' => 0,
            'jarak' => 0,
            'status' => 'Pembayaran Valid',
            'jumlah_pembayaran' => 920000,
            'poin_dipakai' => 0,
            'poin_didapat' => 130,
            'bukti_pembayaran' => 'bukti_pembayaran_8.jpg',
        ]);
        
        Pesanan::create([
            'id_customer' => 2,
            'tanggal_pesan' => '2024-04-05',
            'tanggal_ambil' => '2024-04-09',
            'tanggal_lunas' => '2024-04-05',
            'metode_pesan' => 'PO',
            'alamat' => 'Jl. Cempaka No. 20',
            'delivery' => 'Pickup',
            'total' => 850000,
            'ongkos_kirim' => 0,
            'jarak' => 0,
            'status' => 'Pembayaran Valid',
            'jumlah_pembayaran' => 850000,
            'poin_dipakai' => 0,
            'poin_didapat' => 100,
            'bukti_pembayaran' => 'bukti_pembayaran_9.jpg',
        ]);
        Pesanan::create([
            'id_customer' => 3,
            'tanggal_pesan' => '2024-04-02',
            'tanggal_ambil' => '2024-04-06',
            'tanggal_lunas' => '2024-04-02',
            'metode_pesan' => 'PO',
            'alamat' => 'Jl. Dahlia No. 5',
            'delivery' => 'Pickup',
            'total' => 720000,
            'ongkos_kirim' => 0,
            'jarak' => 0,
            'status' => 'Pembayaran Valid',
            'jumlah_pembayaran' => 720000,
            'poin_dipakai' => 0,
            'poin_didapat' => 90,
            'bukti_pembayaran' => 'bukti_pembayaran_10.jpg',
        ]);
        
        Pesanan::create([
            'id_customer' => 3,
            'tanggal_pesan' => '2024-04-03',
            'tanggal_ambil' => '2024-04-07',
            'tanggal_lunas' => '2024-04-03',
            'metode_pesan' => 'PO',
            'alamat' => 'Jl. Kenanga No. 10',
            'delivery' => 'Pickup',
            'total' => 850000,
            'ongkos_kirim' => 0,
            'jarak' => 0,
            'status' => 'Pembayaran Valid',
            'jumlah_pembayaran' => 850000,
            'poin_dipakai' => 0,
            'poin_didapat' => 110,
            'bukti_pembayaran' => 'bukti_pembayaran_11.jpg',
        ]);
        
        Pesanan::create([
            'id_customer' => 3,
            'tanggal_pesan' => '2024-04-04',
            'tanggal_ambil' => '2024-04-08',
            'tanggal_lunas' => '2024-04-04',
            'metode_pesan' => 'PO',
            'alamat' => 'Jl. Teratai No. 15',
            'delivery' => 'Pickup',
            'total' => 920000,
            'ongkos_kirim' => 0,
            'jarak' => 0,
            'status' => 'Pembayaran Valid',
            'jumlah_pembayaran' => 920000,
            'poin_dipakai' => 0,
            'poin_didapat' => 120,
            'bukti_pembayaran' => 'bukti_pembayaran_12.jpg',
        ]);
        
        Pesanan::create([
            'id_customer' => 3,
            'tanggal_pesan' => '2024-04-05',
            'tanggal_ambil' => '2024-04-09',
            'tanggal_lunas' => '2024-04-05',
            'metode_pesan' => 'PO',
            'alamat' => 'Jl. Bunga No. 20',
            'delivery' => 'Pickup',
            'total' => 700000,
            'ongkos_kirim' => 0,
            'jarak' => 0,
            'status' => 'Pembayaran Valid',
            'jumlah_pembayaran' => 700000,
            'poin_dipakai' => 0,
            'poin_didapat' => 80,
            'bukti_pembayaran' => 'bukti_pembayaran_13.jpg',
        ]);
        Pesanan::create([
            'id_customer' => 4,
            'tanggal_pesan' => '2024-04-02',
            'tanggal_ambil' => '2024-04-06',
            'tanggal_lunas' => '2024-04-02',
            'metode_pesan' => 'PO',
            'alamat' => 'Jl. Flamboyan No. 5',
            'delivery' => 'Pickup',
            'total' => 820000,
            'ongkos_kirim' => 0,
            'jarak' => 0,
            'status' => 'Pembayaran Valid',
            'jumlah_pembayaran' => 820000,
            'poin_dipakai' => 0,
            'poin_didapat' => 110,
            'bukti_pembayaran' => 'bukti_pembayaran_14.jpg',
        ]);
        
        Pesanan::create([
            'id_customer' => 4,
            'tanggal_pesan' => '2024-04-03',
            'tanggal_ambil' => '2024-04-07',
            'tanggal_lunas' => '2024-04-03',
            'metode_pesan' => 'PO',
            'alamat' => 'Jl. Melur No. 10',
            'delivery' => 'Pickup',
            'total' => 950000,
            'ongkos_kirim' => 0,
            'jarak' => 0,
            'status' => 'Pembayaran Valid',
            'jumlah_pembayaran' => 950000,
            'poin_dipakai' => 0,
            'poin_didapat' => 130,
            'bukti_pembayaran' => 'bukti_pembayaran_15.jpg',
        ]);
        
        Pesanan::create([
            'id_customer' => 4,
            'tanggal_pesan' => '2024-04-04',
            'tanggal_ambil' => '2024-04-08',
            'tanggal_lunas' => '2024-04-04',
            'metode_pesan' => 'PO',
            'alamat' => 'Jl. Rafflesia No. 15',
            'delivery' => 'Pickup',
            'total' => 720000,
            'ongkos_kirim' => 0,
            'jarak' => 0,
            'status' => 'Pembayaran Valid',
            'jumlah_pembayaran' => 720000,
            'poin_dipakai' => 0,
            'poin_didapat' => 100,
            'bukti_pembayaran' => 'bukti_pembayaran_16.jpg',
        ]);
        
        Pesanan::create([
            'id_customer' => 4,
            'tanggal_pesan' => '2024-04-05',
            'tanggal_ambil' => '2024-04-09',
            'tanggal_lunas' => '2024-04-05',
            'metode_pesan' => 'PO',
            'alamat' => 'Jl. Kamboja No. 20',
            'delivery' => 'Pickup',
            'total' => 850000,
            'ongkos_kirim' => 0,
            'jarak' => 0,
            'status' => 'Pembayaran Valid',
            'jumlah_pembayaran' => 850000,
            'poin_dipakai' => 0,
            'poin_didapat' => 120,
            'bukti_pembayaran' => 'bukti_pembayaran_17.jpg',
        ]);
        Pesanan::create([
            'id_customer' => 5,
            'tanggal_pesan' => '2024-04-02',
            'tanggal_ambil' => '2024-04-06',
            'tanggal_lunas' => '2024-04-02',
            'metode_pesan' => 'PO',
            'alamat' => 'Jl. Kenari No. 5',
            'delivery' => 'Pickup',
            'total' => 720000,
            'ongkos_kirim' => 0,
            'jarak' => 0,
            'status' => 'Pembayaran Valid',
            'jumlah_pembayaran' => 720000,
            'poin_dipakai' => 0,
            'poin_didapat' => 90,
            'bukti_pembayaran' => 'bukti_pembayaran_18.jpg',
        ]);
        
        Pesanan::create([
            'id_customer' => 5,
            'tanggal_pesan' => '2024-04-03',
            'tanggal_ambil' => '2024-04-07',
            'tanggal_lunas' => '2024-04-03',
            'metode_pesan' => 'PO',
            'alamat' => 'Jl. Mangga No. 10',
            'delivery' => 'Pickup',
            'total' => 850000,
            'ongkos_kirim' => 0,
            'jarak' => 0,
            'status' => 'Pembayaran Valid',
            'jumlah_pembayaran' => 850000,
            'poin_dipakai' => 0,
            'poin_didapat' => 110,
            'bukti_pembayaran' => 'bukti_pembayaran_19.jpg',
        ]);
        
        Pesanan::create([
            'id_customer' => 5,
            'tanggal_pesan' => '2024-04-04',
            'tanggal_ambil' => '2024-04-08',
            'tanggal_lunas' => '2024-04-04',
            'metode_pesan' => 'PO',
            'alamat' => 'Jl. Jeruk No. 15',
            'delivery' => 'Pickup',
            'total' => 920000,
            'ongkos_kirim' => 0,
            'jarak' => 0,
            'status' => 'Pembayaran Valid',
            'jumlah_pembayaran' => 920000,
            'poin_dipakai' => 0,
            'poin_didapat' => 120,
            'bukti_pembayaran' => 'bukti_pembayaran_20.jpg',
        ]);
        
        Pesanan::create([
            'id_customer' => 5,
            'tanggal_pesan' => '2024-04-05',
            'tanggal_ambil' => '2024-04-09',
            'tanggal_lunas' => '2024-04-05',
            'metode_pesan' => 'PO',
            'alamat' => 'Jl. Durian No. 20',
            'delivery' => 'Pickup',
            'total' => 700000,
            'ongkos_kirim' => 0,
            'jarak' => 0,
            'status' => 'Pembayaran Valid',
            'jumlah_pembayaran' => 700000,
            'poin_dipakai' => 0,
            'poin_didapat' => 80,
            'bukti_pembayaran' => 'bukti_pembayaran_21.jpg',
        ]); 
        Pesanan::create([
            'id_customer' => 1,
            'status' => 'Keranjang',
        ]); 
    }
}
