<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detail_Resep extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'detail_reseps';
    protected $fillable = [
        'id_resep',
        'id_bahan_baku',
        'jumlah_bahan'
    ];
}
