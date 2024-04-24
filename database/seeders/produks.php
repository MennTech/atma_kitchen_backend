<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Produk;

class produks extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Produk::create([
            'gambar_produk' => 'https://www.masakapahariini.com/wp-content/uploads/2019/11/Resep-Lapis-Legit-Keju-1.jpg',
            'nama_produk' => 'Lapis Legit',
            'deskripsi_produk' => 'Kue lapis legit adalah kue lapis yang terbuat dari bahan-bahan yang sama dengan kue lapis pada umumnya, namun kue lapis legit memiliki tekstur yang lebih padat dan lembut.',
            'harga' => 850000,
            'kategori' => 'Cake',
            'status' => 'Tersedia',
            'stok_tersedia' => 0,
            'id_resep' => 1,
        ]);
        Produk::create([
            'gambar_produk' => 'Lapis_Surabaya.jpg',
            'nama_produk' => 'Lapis Surabaya',
            'deskripsi_produk' => 'Kue lapis surabaya adalah kue lapis yang terbuat dari bahan-bahan yang sama dengan kue lapis pada umumnya, namun kue lapis surabaya memiliki tekstur yang lebih lembut dan rasa yang lebih manis.',
            'harga' => 550000,
            'kategori' => 'Cake',
            'status' => 'Tersedia',
            'stok_tersedia' => 0,
            'id_resep' => 2,
        ]);
        Produk::create([
            'gambar_produk' => 'Brownies.jpg',
            'nama_produk' => 'Brownies',
            'deskripsi_produk' => 'Brownies adalah kue yang terbuat dari bahan-bahan yang sama dengan kue pada umumnya, namun brownies memiliki tekstur yang lebih padat dan rasa yang lebih manis.',
            'harga' => 250000,
            'kategori' => 'Cake',
            'status' => 'Tersedia',
            'stok_tersedia' => 0,
            'id_resep' => 3,
        ]);
        Produk::create([
            'gambar_produk' => 'Manadarin.jpg',
            'nama_produk' => 'Mandarin',
            'deskripsi_produk' => 'Mandarin adalah kue yang terbuat dari bahan-bahan yang sama dengan kue pada umumnya, namun mandarin memiliki tekstur yang lebih lembut dan rasa yang lebih manis.',
            'harga' => 450000,
            'kategori' => 'Cake',
            'status' => 'Tersedia',
            'stok_tersedia' => 0,
            'id_resep' => 4,
        ]);
        Produk::create([
            'gambar_produk' => 'Spikoe.jpg',
            'nama_produk' => 'Spikoe',
            'deskripsi_produk' => 'Spikoe adalah kue yang terbuat dari bahan-bahan yang sama dengan kue pada umumnya, namun spikoe memiliki tekstur yang lebih lembut dan rasa yang lebih manis.',
            'harga' => 350000,
            'kategori' => 'Cake',
            'status' => 'Tersedia',
            'stok_tersedia' => 0,
            'id_resep' => 5,
        ]);
        Produk::create([
            'gambar_produk' => 'https://www.masakapahariini.com/wp-content/uploads/2019/11/Resep-Lapis-Legit-Keju-1.jpg',
            'nama_produk' => 'Lapis Legit 1/2 Loyang',
            'deskripsi_produk' => 'Kue lapis legit adalah kue lapis yang terbuat dari bahan-bahan yang sama dengan kue lapis pada umumnya, namun kue lapis legit memiliki tekstur yang lebih padat dan lembut.',
            'harga' => 450000,
            'kategori' => 'Cake',
            'status' => 'Tersedia',
            'stok_tersedia' => 0,
            'id_resep' => 1,
        ]);
        Produk::create([
            'gambar_produk' => 'Lapis_Surabaya.jpg',
            'nama_produk' => 'Lapis Surabaya 1/2 Loyang',
            'deskripsi_produk' => 'Kue lapis surabaya adalah kue lapis yang terbuat dari bahan-bahan yang sama dengan kue lapis pada umumnya, namun kue lapis surabaya memiliki tekstur yang lebih lembut dan rasa yang lebih manis.',
            'harga' => 300000,
            'kategori' => 'Cake',
            'status' => 'Tersedia',
            'stok_tersedia' => 0,
            'id_resep' => 2,
        ]);
        Produk::create([
            'gambar_produk' => 'Brownies.jpg',
            'nama_produk' => 'Brownies 1/2 Loyang',
            'deskripsi_produk' => 'Brownies adalah kue yang terbuat dari bahan-bahan yang sama dengan kue pada umumnya, namun brownies memiliki tekstur yang lebih padat dan rasa yang lebih manis.',
            'harga' => 150000,
            'kategori' => 'Cake',
            'status' => 'Tersedia',
            'stok_tersedia' => 0,
            'id_resep' => 3,
        ]);
        Produk::create([
            'gambar_produk' => 'Manadarin.jpg',
            'nama_produk' => 'Mandarin 1/2 Loyang',
            'deskripsi_produk' => 'Mandarin adalah kue yang terbuat dari bahan-bahan yang sama dengan kue pada umumnya, namun mandarin memiliki tekstur yang lebih lembut dan rasa yang lebih manis.',
            'harga' => 250000,
            'kategori' => 'Cake',
            'status' => 'Tersedia',
            'stok_tersedia' => 0,
            'id_resep' => 4,
        ]);
        Produk::create([
            'gambar_produk' => 'Spikoe.jpg',
            'nama_produk' => 'Spikoe 1/2 Loyang',
            'deskripsi_produk' => 'Spikoe adalah kue yang terbuat dari bahan-bahan yang sama dengan kue pada umumnya, namun spikoe memiliki tekstur yang lebih lembut dan rasa yang lebih manis.',
            'harga' => 200000,
            'kategori' => 'Cake',
            'status' => 'Tersedia',
            'stok_tersedia' => 0,
            'id_resep' => 5,
        ]);
        Produk::create([
            'gambar_produk' => 'Roti_Sosis.jpg',
            'nama_produk' => 'Roti Sosis',
            'deskripsi_produk' => 'Roti Sosis adalah roti yang terbuat dari bahan-bahan yang sama dengan roti pada umumnya, namun roti sosis memiliki tekstur yang lebih lembut dan rasa yang lebih gurih.',
            'harga' => 180000,
            'kategori' => 'Roti',
            'status' => 'Tersedia',
            'stok_tersedia' => 0,
            'id_resep' => 6,
        ]);
        Produk::create([
            'gambar_produk' => 'milk_bun.jpg',
            'nama_produk' => 'Milk Bun',
            'deskripsi_produk' => 'Milk Bun adalah roti yang terbuat dari bahan-bahan yang sama dengan roti pada umumnya, namun Milk Bun memiliki tekstur yang lebih lembut dan rasa yang lebih gurih.',
            'harga' => 120000,
            'kategori' => 'Roti',
            'status' => 'Tersedia',
            'stok_tersedia' => 0,
            'id_resep' => 7,
        ]);
        Produk::create([
            'gambar_produk' => 'Roti_Keju.jpg',
            'nama_produk' => 'Roti Keju',
            'deskripsi_produk' => 'Roti Keju adalah roti yang terbuat dari bahan-bahan yang sama dengan roti pada umumnya, namun roti keju memiliki tekstur yang lebih lembut dan rasa yang lebih gurih.',
            'harga' => 150000,
            'kategori' => 'Roti',
            'status' => 'Tersedia',
            'stok_tersedia' => 0,
            'id_resep' => 8,
        ]);
        Produk::create([
            'gambar_produk' => 'Matcha_Creamy_Latte.jpg',
            'nama_produk' => 'Matcha Creamy Latte',
            'deskripsi_produk' => 'Matcha Creamy Latte adalah minuman yang terbuat dari bahan-bahan yang sama dengan minuman pada umumnya, namun Matcha Creamy Latte memiliki rasa matcha yang lebih pekat dan tekstur yang lebih creamy.',
            'harga' => 100000,
            'kategori' => 'Minuman',
            'status' => 'Tersedia',
            'stok_tersedia' => 0,
            'id_resep' => 10,
        ]);
        Produk::create([
            'gambar_produk' => 'chocholate_creamy_latte.jpg',
            'nama_produk' => 'Chocolate Creamy Latte',
            'deskripsi_produk' => 'Chocolate Creamy Latte adalah minuman yang terbuat dari bahan-bahan yang sama dengan minuman pada umumnya, namun Chocolate Creamy Latte memiliki rasa coklat yang lebih pekat dan tekstur yang lebih creamy.',
            'harga' => 75000,
            'kategori' => 'Minuman',
            'status' => 'Tersedia',
            'stok_tersedia' => 0,
            'id_resep' => 9,
        ]);
        Produk::create([
            'id_penitip' => 1,
            'gambar_produk' => 'Keripik_Kentang.jpg',
            'nama_produk' => 'Keripik Kentang 250gr',
            'deskripsi_produk' => 'Keripik Kentang adalah keripik yang terbuat dari bahan-bahan yang sama dengan keripik pada umumnya, namun Keripik Kentang memiliki rasa yang lebih gurih dan renyah.',
            'harga' => 75000,
            'kategori' => 'Titipan',
            'status' => 'Tersedia',
            'stok_tersedia' => 10,
        ]);
        Produk::create([
            'id_penitip' => 2,
            'gambar_produk' => 'Kopi_Luwak.jpg',
            'nama_produk' => 'Kopi Luwak Bubuk 250gr',
            'deskripsi_produk' => 'Kopi Luwak adalah kopi yang terbuat dari biji kopi yang telah dimakan oleh musang luwak, biji kopi yang telah dimakan oleh musang luwak memiliki rasa yang lebih enak dan aroma yang lebih harum.',
            'harga' => 250000,
            'kategori' => 'Titipan',
            'status' => 'Tersedia',
            'stok_tersedia' => 10,
        ]);
        Produk::create([
            'id_penitip' => 3,
            'gambar_produk' => 'Matcha_Bubuk.jpg',
            'nama_produk' => 'Matcha Organik Bubuk 100gr',
            'deskripsi_produk' => 'Matcha Organik adalah matcha yang terbuat dari daun teh hijau yang diolah secara organik, matcha organik memiliki rasa yang lebih pekat dan aroma yang lebih harum.',
            'harga' => 300000,
            'kategori' => 'Titipan',
            'status' => 'Tersedia',
            'stok_tersedia' => 10,
        ]);
        Produk::create([
            'id_penitip' => 4,
            'gambar_produk' => 'chocolate_bar.jpg',
            'nama_produk' => 'Chocolate Bar 100gr',
            'deskripsi_produk' => 'Chocolate Bar adalah coklat batangan yang terbuat dari bahan-bahan yang sama dengan coklat pada umumnya, namun Chocolate Bar memiliki rasa yang lebih manis dan tekstur yang lebih creamy.',
            'harga' => 120000,
            'kategori' => 'Titipan',
            'status' => 'Tersedia',
            'stok_tersedia' => 10,
        ]);
    }
}
