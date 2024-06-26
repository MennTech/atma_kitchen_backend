<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'produks';
    protected $primaryKey = 'id_produk';
    protected $fillable = [
        'id_penitip',
        'gambar_produk',
        'nama_produk',
        'deskripsi_produk',
        'harga',
        'kategori',
        'status',
        'stok_tersedia',
        'id_resep'
    ];

    public function penitip()
    {
        return $this->belongsTo(Penitip::class, 'id_penitip');
    }

    public function resep()
    {
        return $this->belongsTo(Resep::class, 'id_resep');
    }

    public function hampers()
    {
        return $this->belongsToMany(Hampers::class, 'detail_hampers', 'id_produk', 'id_hampers')->withPivot('id_bahan_baku');
    }

    // blom fix
    public function hampersPesanan()
    {
        return $this->belongsToMany(Hampers::class, 'detail_pesanans', 'id_produk', 'id_hampers')->withPivot('id_pesanan', 'jumlah', 'subtotal');
    }

    public function pesanan()
    {
        return $this->belongsToMany(Pesanan::class, 'detail_pesanans', 'id_produk', 'id_pesanan')->withPivot('id_hampers' , 'jumlah', 'subtotal');
    }
}
