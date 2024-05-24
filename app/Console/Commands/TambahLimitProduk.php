<?php

namespace App\Console\Commands;

use App\Models\Limit_Produk;
use App\Models\Produk;
use Carbon\Carbon;
use Illuminate\Console\Command;

class TambahLimitProduk extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:tambah-limit-produk';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Limit PO Produk';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d');
        $limitProdukHariIni = Limit_Produk::where('tanggal', $date)->count();
        if($limitProdukHariIni == 0){
            $atmaProduk = Produk::where('id_resep', '!=', null)->get();
            foreach($atmaProduk as $produk){
                Limit_Produk::create([
                    'id_produk' => $produk->id_produk,
                    'tanggal' => $date,
                    'stok' => 20,
                ]);
            }
            $this->info('Data limit produk untuk hari ini telah diperbarui.');
        }else{
            $this->info('Data limit produk untuk hari ini sudah ada.');
        }
    }
}
