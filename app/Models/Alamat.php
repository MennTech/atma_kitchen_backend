<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alamat extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'alamats';
    protected $primaryKey = 'id_alamat';
    protected $fillable = [
        'id_customer',
        'nama_jalan',
        'kode_pos'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer');
    }
}
