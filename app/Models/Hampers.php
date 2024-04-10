<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hampers extends Model
{
    use HasFactory;

    protected $table = 'hampers';
    protected $primaryKey = 'id_hamper';
    protected $fillable = [
        'nama_hampers',
        'harga'
    ];
}
