<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Detail_Pesanan;
use App\Models\Penitip;

class LaporanPenitip extends Controller
{
    public function monthlyReport(Request $request)
    {
        $request->validate([
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:1970|max:2100',
        ]);

        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');
        $penitip = Penitip::all();
        $laporanPenitip = [];

        $pesanan = Pesanan::whereYear('tanggal_lunas', $tahun)
            ->whereMonth('tanggal_lunas', $bulan)
            ->where('status', '!=', 'Pesanan Ditolak')
            ->where('status', '!=', 'Menunggu Konfirmasi Admin')
            ->where('status', '!=', 'Keranjang')
            ->where('status', '!=', 'Menunggu Konfirmasi Pesanan')
            ->get();

        foreach ($penitip as $penitp) {
            $subtotal_penitip = 0;
            $komisi_penitip = 0;
            $total_penitip = 0;
            $penitipData = [
                'id_penitip' => $penitp->id_penitip,
                'nama_penitip' => $penitp->nama_penitip,
                'produk' => []
            ];

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
                        }
                    }
                }
            }

            foreach ($produkList as $produkItem) {
                $penitipData['produk'][] = $produkItem;
            }

            if (!empty($penitipData['produk'])) {
                $laporanPenitip[] = $penitipData;
            }
        }


        return response()->json([
            'success' => true,
            'data' => $laporanPenitip
        ]);
    }
}
