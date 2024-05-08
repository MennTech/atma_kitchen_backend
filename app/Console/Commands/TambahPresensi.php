<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Presensi;
use App\Models\Karyawan;
use Carbon\Carbon;
class TambahPresensi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tambah:presensi';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate presensi karyawan';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d');
        $presensiHariIni = Presensi::where('tanggal', $date)->count();
        if ($presensiHariIni == 0) {
            $karyawan = Karyawan::all();
            foreach ($karyawan as $karyawan) {
                Presensi::create([
                    'id_karyawan' => $karyawan->id_karyawan,
                    'tanggal' => $date,
                    'status' => 'Hadir',
                ]);
            }
            $this->info('Data absensi untuk hari ini telah diperbarui.');
        } else {
            $this->info('Data absensi untuk hari ini sudah ada.');
        }
    }
}