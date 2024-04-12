<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History_Saldo extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'history_saldos';
    protected $primaryKey = 'id_history_saldo';
    protected $fillable = [
        'id_customer',
        'tanggal',
        'status',
        'nominal'
    ];

    public function historySaldoCustomer()
    {
        return $this->belongsTo(Customer::class, 'id_customer');
    }
}
