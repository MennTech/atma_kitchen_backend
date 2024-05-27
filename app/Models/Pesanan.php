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
        'metode_pesan',
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

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer');
    }

    public function detailPesanan()
    {
        return $this->hasMany(Detail_Pesanan::class, 'id_pesanan');
    }

    // blom fix
    public function produkPesanan()
    {
        return $this->belongsToMany(Produk::class, 'detail_pesanans', 'id_pesanan', 'id_produk')->withPivot('id_hampers', 'jumlah', 'subtotal');
    }

    public function hampersPesanan()
    {
        return $this->belongsToMany(Hampers::class, 'detail_pesanans', 'id_pesanan', 'id_hampers')->withPivot('id_produk', 'jumlah', 'subtotal');
    }
}
