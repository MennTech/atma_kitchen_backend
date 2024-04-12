<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Limit_Produk extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'limit_produks';
    protected $primaryKey = 'id_limit_produk';
    protected $fillable = [
        'id_produk',
        'tanggal',
        'stok'
    ];

    public function limitProduk()
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }
}
