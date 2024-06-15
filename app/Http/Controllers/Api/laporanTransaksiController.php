<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Pengeluaran_lain;
use App\Models\Pembelian_Bahan_Baku;
use App\Models\Karyawan;
use App\Models\Presensi;
use App\Models\Penitip;
use App\Models\Detail_Pesanan;
use App\Models\Produk;

class LaporanTransaksiController extends Controller
{
    public function monthlyReport(Request $request)
    {
        $request->validate([
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:1970|max:2100',
        ]);

        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');

        $pengeluaranLain = Pengeluaran_lain::whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->get(['nama_pengeluaran', 'harga']);

        $totalPembelianBahanBaku = Pembelian_Bahan_Baku::whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->sum('harga');
        $totalPesanan = Pesanan::whereYear('tanggal_lunas', $tahun)
            ->whereMonth('tanggal_lunas', $bulan)->where('status','Selesai')
            ->sum('total');
        $totalTip = Pesanan::whereYear('tanggal_lunas', $tahun)
            ->whereMonth('tanggal_lunas', $bulan)
            ->sum('tip');

        $presensi = Presensi::whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->get();
        $karyawans = Karyawan::all();
        $penitip = Penitip::all();
        $pesanan = Pesanan::whereYear('tanggal_lunas', $tahun)
            ->whereMonth('tanggal_lunas', $bulan)
            ->where('status', '!=', 'Pesanan Ditolak')
            ->where('status', '!=', 'Menunggu Konfirmasi Admin')
            ->where('status', '!=', 'Keranjang')
            ->where('status', '!=', 'Menunggu Konfirmasi Pesanan')
            ->get();
        $totgaji =0;
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
                $totgaji += $total;
            }else{
                $totgaji += $jumlahGaji;
            }
        }
        $total_semua=0;
        foreach ($penitip as $penitp) {
            $produkList = [];

            foreach ($pesanan as $pes) {
                $detail_pesanan = Detail_Pesanan::where('id_pesanan', $pes->id_pesanan)->get();
                foreach ($detail_pesanan as $detail) {
                    if ($detail->id_produk != null) {
                        $produk = Produk::where('id_produk', $detail->id_produk)->first();
                        if ($produk->id_penitip == $penitp->id_penitip) {
                            $key = $produk->id_produk;
                            if (!isset($produkList[$key])) {
                                $produkList[$key] = [
                                    'nama_produk' => $produk->nama_produk,
                                    'jumlah' => 0,
                                    'harga' => $produk->harga,
                                    'subtotal' => 0,
                                    'komisi' => 0,
                                    'total' => 0,
                                ];
                            }

                            $produkList[$key]['jumlah'] += $detail->jumlah;
                            $produkList[$key]['subtotal'] += $detail->jumlah * $produk->harga;
                            $produkList[$key]['komisi'] += ($detail->jumlah * $produk->harga) * 0.2;
                            $produkList[$key]['total'] += ($detail->jumlah * $produk->harga) - (($detail->jumlah * $produk->harga) * 0.2);
                            $total_semua += $produkList[$key]['total'];
                        }
                    }
                }
            }
        }

        $pemasukan = [];
        if ($totalPesanan > 0 || $totalTip > 0) {
            $pemasukan = [
                ['nama' => 'Penjualan', 'jumlah' => $totalPesanan],
                ['nama' => 'Tip', 'jumlah' => $totalTip]
            ];
        }

        $pengeluaran = [];
        if ($totalPembelianBahanBaku > 0 || $pengeluaranLain->isNotEmpty() || $total_semua > 0) {
            if ($totalPembelianBahanBaku > 0) {
                $pengeluaran[] = ['nama' => 'Pembelian Bahan Baku', 'jumlah' => $totalPembelianBahanBaku];
            }
        
            foreach ($pengeluaranLain as $item) {
                if ($item->harga > 0) {
                    $pengeluaran[] = ['nama' => $item->nama_pengeluaran, 'jumlah' => $item->harga];
                }
            }
        
            if ($total_semua > 0) {
                $pengeluaran[] = ['nama' => 'Bayar Penitip', 'jumlah' => $total_semua];
            }
            if($totgaji > 0){
                $pengeluaran[] = ['nama' => 'Gaji Karyawan', 'jumlah' => $totgaji];
            }
        }
        $totalPengeluaran = array_reduce($pengeluaran, function ($carry, $item) {
            return $carry + $item['jumlah'];
        }, 0);
        $totalPemasukan = array_reduce($pemasukan, function ($carry, $item) {
            return $carry + $item['jumlah'];
        }, 0);

        if (!empty($pemasukan)) {
            $pemasukan[] = ['nama' => 'Total', 'jumlah' => $totalPemasukan];
        }
        if (!empty($pengeluaran)) {
            $pengeluaran[] = ['nama' => 'Total', 'jumlah' => $totalPengeluaran];
        }

        return response()->json([
            'message' => 'Laporan Pemasukan dan Pengeluaran retrieved',
            'data' => [
                'pemasukan' => $pemasukan,
                'pengeluaran' => $pengeluaran,
            ],
        ], 200);
    }
}
