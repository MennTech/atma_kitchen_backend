<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detail_Resep extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = null;
    protected $table = 'detail_reseps';
    protected $fillable = [
        'id_resep',
        'id_bahan_baku',
        'jumlah_bahan'
    ];

    public function resep()
    {
        return $this->belongsToMany(Resep::class, 'detail_reseps');
    }

    public function bahanBaku()
    {
        return $this->belongsToMany(Bahan_Baku::class, 'detail_reseps');
    }
}
