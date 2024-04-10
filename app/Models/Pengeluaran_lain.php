<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran_lain extends Model
{
    use HasFactory;

    protected $table = 'pengeluaran_lains';
    protected $primaryKey = 'id_pengeluaran_lain';
    protected $fillable = [
        'nama_pengeluaran',
        'tanggal',
        'harga'
    ];
}
