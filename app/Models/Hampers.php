<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hampers extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'hampers';
    protected $primaryKey = 'id_hampers';
    protected $fillable = [
        'gambar_hampers',
        'deskripsi_hampers',
        'nama_hampers',
        'harga'
    ];

    public function produk()
    {
        return $this->belongsToMany(Produk::class, 'detail_hampers', 'id_hampers', 'id_produk')->withPivot('id_bahan_baku');
    }

    // blom fix
    public function produkPesanan()
    {
        return $this->belongsToMany(Produk::class, 'detail_pesanans', 'id_hampers', 'id_produk')->withPivot('id_pesanan', 'jumlah', 'subtotal');
    }

    public function pesanan()
    {
        return $this->belongsToMany(Pesanan::class, 'detail_pesanans', 'id_hampers', 'id_pesanan')->withPivot('id_produk','jumlah', 'subtotal');
    }
}
