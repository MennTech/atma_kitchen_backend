<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class LaporanController extends Controller
{

    public function showPenjualanProdukMonthly(Request $request)
    {
        $month = $request->query('bulan');
        $year = $request->query('tahun');
        $pesanan = Pesanan::with('produkPesanan', 'hampersPesanan')
            ->whereMonth('tanggal_pesan', $month)
            ->whereYear('tanggal_pesan', $year)
            ->where('status', 'Selesai')
            ->get();

        if ($pesanan->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak ditemukan',
                'data' => null
            ], 404);
        }

        $produk = [];
        $hampers = [];
        $totalPenjualan = 0;
        foreach ($pesanan as $p) {
            foreach ($p->produkPesanan as $produkPesanan) {
                $id_produk = $produkPesanan->id_produk;
                if (!isset($produk[$id_produk])) {
                    $produk[$id_produk] = [
                        'id_produk' => $id_produk,
                        'nama_produk' => $produkPesanan->nama_produk,
                        'harga' => $produkPesanan->harga,
                        'jumlah' => $produkPesanan->pivot->jumlah,
                        'total' => $produkPesanan->pivot->subtotal
                    ];
                } else {
                    $produk[$id_produk]['jumlah'] += $produkPesanan->pivot->jumlah;
                    $produk[$id_produk]['total'] += $produkPesanan->pivot->subtotal;
                }
            }

            foreach ($p->hampersPesanan as $hampersPesanan) {
                $id_hampers = $hampersPesanan->id_hampers;
                if (!isset($hampers[$id_hampers])) {
                    $hampers[$id_hampers] = [
                        'id_hampers' => $id_hampers,
                        'nama_hampers' => $hampersPesanan->nama_hampers,
                        'harga' => $hampersPesanan->harga,
                        'jumlah' => $hampersPesanan->pivot->jumlah,
                        'total' => $hampersPesanan->pivot->subtotal
                    ];
                } else {
                    $hampers[$id_hampers]['jumlah'] += $hampersPesanan->pivot->jumlah;
                    $hampers[$id_hampers]['total'] += $hampersPesanan->pivot->subtotal;
                }
            }
        }

        // sort by id_produk & hampers
        $produk = array_values($produk);
        usort(
            $produk,
            function ($a, $b) {
                return $a['id_produk'] <=> $b['id_produk'];
            }
        );
        $hampers = array_values($hampers);
        usort(
            $hampers,
            function ($a, $b) {
                return $a['id_hampers'] <=> $b['id_hampers'];
            }
        );

        foreach ($produk as $p) {
            $totalPenjualan += $p['total'];
        }
        foreach ($hampers as $h) {
            $totalPenjualan += $h['total'];
        }

        return response()->json([
            'success' => true,
            'message' => 'Penjualan produk ditemukan',
            'bulan' => $month,
            'tahun' => $year,
            // 'data' => $pesanan,
            'data' => [
                'produk' => $produk,
                'hampers' => $hampers,
                'total_penjualan' => $totalPenjualan
            ]
        ], 200);
    }
}
