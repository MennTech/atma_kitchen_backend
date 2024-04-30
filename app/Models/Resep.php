<?php

namespace App\Models;

use Database\Seeders\detail_reseps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resep extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'reseps';
    protected $primaryKey = 'id_resep';
    protected $fillable = [
        'nama_resep'
    ];

    public function detail_resep(){
        return $this->hasMany(Detail_Resep::class, 'id_resep');
    }
}
