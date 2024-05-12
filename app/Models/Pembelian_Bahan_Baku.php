<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian_Bahan_Baku extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $table = 'pembelian_bahan_bakus';
    protected $primaryKey = 'id_pembelian_bahan_baku';
    protected $fillable = [
        'id_bahan_baku',
        'tanggal',
        'jumlah',
        'harga'
    ];
    
    public function bahanBaku()
    {
        return $this->belongsTo(Bahan_Baku::class, 'id_bahan_baku');
    }
}
