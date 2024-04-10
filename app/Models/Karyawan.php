<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;

    protected $table = 'karyawans';
    protected $primaryKey = 'id_karyawan';
    protected $fillable = [
        'id_role',
        'nama_karyawan',
        'no_telp',
        'email_karyawan',
        'password',
        'status',
        'bonus'
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role');
    }
}
