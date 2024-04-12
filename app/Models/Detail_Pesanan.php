<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detail_Pesanan extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'detail_pesanans';
    protected $fillable=[
        'id_pesanan',
        'id_produk',
        'id_hampers',
        'jumlah',
        'subtotal'
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }

    public function hampers()
    {
        return $this->belongsTo(Hampers::class, 'id_hampers');
    }
}
