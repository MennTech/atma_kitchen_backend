<?php

namespace App\Models;

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
}
