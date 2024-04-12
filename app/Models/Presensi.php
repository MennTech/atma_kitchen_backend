<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'presensis';
    protected $primaryKey = 'id_presensi';
    protected $fillable = [
        'id_karyawan',
        'tanggal',
        'status'
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan');
    }
}
