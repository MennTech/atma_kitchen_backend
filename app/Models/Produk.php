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
}
