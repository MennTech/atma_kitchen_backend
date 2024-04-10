<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detail_Hampers extends Model
{
    use HasFactory;

    protected $table = 'detail_hampers';
    protected $fillable = [
        'id_hampers',
        'id_produk',
        'id_bahan_baku'
    ];

    public function hampers()
    {
        return $this->belongsTo(Hampers::class, 'id_hampers');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }

    public function bahanBaku()
    {
        return $this->belongsTo(Bahan_Baku::class, 'id_bahan_baku');
    }
}
