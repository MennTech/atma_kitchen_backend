<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Pesanan extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'pesanans';
    protected $primaryKey = 'id_pesanan';
    protected $fillable = [
        'id_customer',
        'tanggal_pesan',
        'tanggal_ambil',
        'tanggal_lunas',
        'alamat',
        'delivery',
        'total',
        'ongkos_kirim',
        'jarak',
        'tip',
        'status',
        'jumlah_pembayaran',
        'poin_dipakai',
        'poin_didapat',
        'bukti_pembayaran'
    ];

    public function pesananCustomer()
    {
        return $this->belongsTo(Customer::class, 'id_customer');
    }

    public function detailPesanan()
    {
        return $this->hasMany(Detail_Pesanan::class, 'id_pesanan');
    }
}
