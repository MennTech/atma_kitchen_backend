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

    public function LaporanPenjualanBulanan($tahun){
        $penjualan = Pesanan::selectRaw('MONTH(tanggal_pesan) as bulan, COUNT(*) as jumlah_transaksi, SUM(total) as jumlah_uang')
            ->where('status', 'Selesai')
            ->whereYear('tanggal_pesan', $tahun)
            ->groupByRaw('MONTH(tanggal_pesan)')
            ->get();

        return response()->json([
            'message' => 'Berhasil menampilkan laporan penjualan bulanan',
            'data' => $penjualan
        ], 200);
    }

    public function LaporanPenggunaanBahanBaku($startDate, $endDate){
        $pesanan = Pesanan::whereBetween('tanggal_pesan', [$startDate, $endDate])
            ->whereIN('status', ['Selesai', 'sedang dikirim', 'diterima', 'siap di-pickup', 'diproses', 'sudah di-pickup'])
            ->get()->load('detailPesanan.produk.resep.detail_resep.bahanBaku', 'detailPesanan.hampers.produk.produk.resep.detail_resep.bahanBaku');

        $bahanBakuPenggunaan = [];

        foreach ($pesanan as $order) {
            foreach ($order->detailPesanan as $detail) {
                if ($detail->produk) {
                    foreach ($detail->produk->resep->detail_resep as $detailResep) {
                        $bahanBaku = $detailResep->bahanBaku;
                        if (!isset($bahanBakuPenggunaan[$bahanBaku->id_bahan_baku])) {
                            $bahanBakuPenggunaan[$bahanBaku->id_bahan_baku] = [
                                'nama_bahan_baku' => $bahanBaku->nama_bahan_baku,
                                'satuan' => $bahanBaku->satuan,
                                'digunakan' => 0,
                            ];
                        }
                        $bahanBakuPenggunaan[$bahanBaku->id_bahan_baku]['digunakan'] += $detailResep->jumlah_bahan * $detail->jumlah;
                    }
                }
                
                if ($detail->hampers) {
                    foreach ($detail->hampers->produk as $productInHampers) {
                        foreach ($productInHampers->resep->detail_resep as $detailResep) {
                            $bahanBaku = $detailResep->bahanBaku;
                            if (!isset($bahanBakuPenggunaan[$bahanBaku->id_bahan_baku])) {
                                $bahanBakuPenggunaan[$bahanBaku->id_bahan_baku] = [
                                    'nama_bahan_baku' => $bahanBaku->nama_bahan_baku,
                                    'satuan' => $bahanBaku->satuan,
                                    'digunakan' => 0,
                                ];
                            }
                            $bahanBakuPenggunaan[$bahanBaku->id_bahan_baku]['digunakan'] += $detailResep->jumlah_bahan * $detail->jumlah;
                        }
                    }
                }
            }
        }

        $newData = [];

        foreach ($bahanBakuPenggunaan as $bahanBaku) {
            $newData[] = $bahanBaku;
        }

        return response()->json([
            'message' => 'Berhasil menampilkan laporan penggunaan bahan baku',
            'status' => true,
            'data' => $newData
        ],200);
    }
}