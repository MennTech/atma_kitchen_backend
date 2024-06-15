<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Presensi;
use App\Models\Karyawan;

class LaporanPresensiController extends Controller
{
    public function monthlyReport(Request $request)
    {
        $request->validate([
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:1970|max:2100',
        ]);
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');
        $presensi = Presensi::whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->get();
        $karyawans = Karyawan::where('id_karyawan','!=','3')->get();
        $laporanPresensi = [];
        foreach ($karyawans as $karyawan) {
            
            $hadir = $presensi->where('id_karyawan', $karyawan->id_karyawan)
                ->where('status', 'Hadir')
                ->count();
            $tidakHadir = $presensi->where('id_karyawan', $karyawan->id_karyawan)
                ->where('status', 'Absen')
                ->count();
            $role = $karyawan->role;
            $jumlahGaji = ($role ? $role->gaji : 0) * $hadir;
            if ($tidakHadir <= 4) {
                $total = $jumlahGaji + $karyawan->bonus;
                $bonus = $karyawan->bonus;
            }
            $laporanPresensi[] = [
                'id_karyawan' => $karyawan->id_karyawan,
                'nama_karyawan' => $karyawan->nama_karyawan,
                'jumlah_hadir' => $hadir,
                'jumlah_tidak_hadir' => $tidakHadir,
                'jumlah_gaji' => $jumlahGaji,
                'bonus' => $bonus,
                'total' => $total,
            ];
        }
        return response([
            'message' => 'Laporan Presensi retrieved',
            'data' => $laporanPresensi,
        ], 200);
    }
}
