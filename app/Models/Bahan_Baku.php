<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bahan_Baku extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'bahan_bakus';
    protected $primaryKey = 'id_bahan_baku';
    protected $fillable = [
        'nama_bahan_baku',
        'stok',
        'satuan'
    ];
}
