<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Pengeluaran_lain;
use App\Models\Pembelian_Bahan_Baku;

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
            ->whereMonth('tanggal_lunas', $bulan)
            ->sum('total');
        $totalTip = Pesanan::whereYear('tanggal_lunas', $tahun)
            ->whereMonth('tanggal_lunas', $bulan)
            ->sum('tip');

        $pemasukan = [];
        if ($totalPesanan > 0 || $totalTip > 0) {
            $pemasukan = [
                ['nama' => 'Penjualan', 'jumlah' => $totalPesanan],
                ['nama' => 'Tip', 'jumlah' => $totalTip]
            ];
        }

        $pengeluaran = [];
        if ($totalPembelianBahanBaku > 0 || $pengeluaranLain->isNotEmpty()) {
            $pengeluaran = [
                ['nama' => 'Pembelian Bahan Baku', 'jumlah' => $totalPembelianBahanBaku]
            ];
            foreach ($pengeluaranLain as $item) {
                $pengeluaran[] = ['nama' => $item->nama_pengeluaran, 'jumlah' => $item->harga];
            }
        }

        $totalPengeluaran = array_reduce($pengeluaran, function($carry, $item) {
            return $carry + $item['jumlah'];
        }, 0);
        $totalPemasukan = array_reduce($pemasukan, function($carry, $item) {
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
