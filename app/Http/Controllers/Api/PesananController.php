<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Limit_Produk;
use App\Models\Resep;
use App\Models\Produk;
use Illuminate\Support\Facades\Validator;

class PesananController extends Controller
{
    public function showPesananJarakNull()
    {
        $pesanan = Pesanan::where('jarak', null)->get();

        if ($pesanan->isEmpty()) {
            return response()->json([
                'message' => 'Tidak ada pesanan yang perlu input jarak',
                'status' =>  false,
                'data' => null
            ], 404);
        }

        return response()->json([
            'message' => 'Berhasil menampilkan pesanan yang perlu input jarak',
            'status' => true,
            'data' => $pesanan
        ], 200);
    }

    public function updateJarakPesanan(Request $request, $id)
    {
        $pesanan = Pesanan::find($id);
        if ($pesanan == null) {
            return response()->json([
                'message' => 'Pesanan tidak ditemukan',
                'status' => false,
                'data' => null
            ], 404);
        }

        $updateJarak = $request->all();
        $validator = Validator::make($updateJarak, [
            'jarak' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
                'status' => false,
                'data' => null
            ], 400);
        }
        $pesanan->jarak = $updateJarak['jarak'];

        if ($updateJarak['jarak'] <= 5.0) {
            $pesanan->ongkos_kirim = 10000;
        } else if ($updateJarak['jarak'] >= 5.0 && $updateJarak['jarak'] <= 10.0) {
            $pesanan->ongkos_kirim = 15000;
        } else if ($updateJarak['jarak'] >= 10.0 && $updateJarak['jarak'] <= 15.0) {
            $pesanan->ongkos_kirim = 20000;
        } else {
            $pesanan->ongkos_kirim = 25000;
        }
        $pesanan->total = $pesanan->total + $pesanan->ongkos_kirim;
        $pesanan->status = 'Menunggu Pembayaran';
        $pesanan->save();

        return response()->json([
            'message' => 'Berhasil update jarak pesanan',
            'status' => true,
            'data' => $pesanan
        ], 200);
    }

    public function showPesananJumlahBayarNull()
    {
        $pesanan = Pesanan::where('jumlah_pembayaran', null)->get();

        if ($pesanan->isEmpty()) {
            return response()->json([
                'message' => 'Tidak ada pesanan yang perlu input jumlah bayar',
                'status' =>  false,
                'data' => null
            ], 404);
        }

        return response()->json([
            'message' => 'Berhasil menampilkan pesanan yang perlu input jumlah bayar',
            'status' => true,
            'data' => $pesanan
        ], 200);
    }

    public function updateJumlahBayarPesanan(Request $request, $id)
    {
        $pesanan = Pesanan::find($id);

        if ($pesanan == null) {
            return response()->json([
                'message' => 'Pesanan tidak ditemukan',
                'status' => false,
                'data' => null
            ], 404);
        }

        $updateJumlahBayar = $request->all();
        $validator = Validator::make($updateJumlahBayar, [
            'jumlah_pembayaran' => 'required|gte:' . $pesanan->total
        ], [
            'jumlah_pembayaran.gte' => 'Jumlah pembayaran harus lebih dari atau sama dengan total pesanan'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
                'status' => false,
                'data' => null
            ], 400);
        }

        $pesanan->jumlah_pembayaran = $updateJumlahBayar['jumlah_pembayaran'];
        $pesanan->tip = $updateJumlahBayar['jumlah_pembayaran'] - $pesanan->total;
        $pesanan->status = 'Pembayaran Valid';
        $pesanan->save();

        return response()->json([
            'message' => 'Berhasil update jumlah bayar pesanan',
            'status' => true,
            'data' => $pesanan
        ], 200);
    }
    public function rejectPesanan($id)
    {
        $pesanan = Pesanan::find($id);

        if ($pesanan == null) {
            return response()->json([
                'message' => 'Pesanan tidak ditemukan',
                'data' => null
            ], 404);
        }

        $pesanan->load('detailPesanan.produk', 'detailPesanan.hampers');
        if ($pesanan->metode_pesan == 'PO') {
            $tanggal = $pesanan->tanggal_ambil;

            if ($pesanan->detailPesanan->isEmpty()) {
                return response()->json([
                    'message' => 'Detail pesanan tidak ditemukan',
                    'data' => null
                ], 404);
            }

            foreach ($pesanan->detailPesanan as $detail) {
                if ($detail->produk) {
                    $id_produk = $detail->produk->id_produk;
                    $jumlah = $detail->jumlah;

                    $limit_produk = Limit_Produk::where('id_produk', $id_produk)
                        ->where('tanggal', $tanggal)
                        ->first();

                    if ($limit_produk) {
                        $limit_produk->stok += $jumlah;
                        $limit_produk->save();
                    }
                } else if ($detail->hampers) {
                    $hampers = $detail->hampers;
                    $hampers->load('produk');
                    foreach ($hampers->produk as $hampersProduk) {
                        $id_produk = $hampersProduk->id_produk;
                        $jumlah = $detail->jumlah;
                        $limit_produk = Limit_Produk::where('id_produk', $id_produk)
                            ->where('tanggal', $tanggal)
                            ->first();

                        if ($limit_produk) {
                            $limit_produk->stok += $jumlah;
                            $limit_produk->save();
                        }
                    }
                }
            }
            $pesanan->status = 'Pesanan Ditolak';
            $pesanan->save();
            $id_customer = $pesanan->id_customer;
            $customer = Customer::find($id_customer);
            $customer->saldo += $pesanan->jumlah_pembayaran;
            $customer->save();
            return response()->json([
                'message' => 'Pesanan berhasil ditolak dan stok diperbarui',
                'data' => $pesanan
            ], 200);
        }
        if ($pesanan->metode_pesan == 'Pesan Langsung') {
            if ($pesanan->detailPesanan->isEmpty()) {
                return response()->json([
                    'message' => 'Detail pesanan tidak ditemukan',
                    'data' => null
                ], 404);
            }
            foreach ($pesanan->detailPesanan as $detail) {
                if ($detail->produk) {
                    $id_produk = $detail->produk->id_produk;
                    $jumlah = $detail->jumlah;
                    $produk = Produk::find($id_produk);
                    $produk->stok_tersedia += $jumlah;
                    $produk->save();
                }
            }
            $pesanan->status = 'Pesanan Ditolak';
            $pesanan->save();
            $id_customer = $pesanan->id_customer;
            $customer = Customer::find($id_customer);
            $customer->saldo += $pesanan->jumlah_pembayaran;
            $customer->save();
            return response()->json([
                'message' => 'Pesanan berhasil ditolak dan stok diperbarui',
                'data' => $pesanan
            ], 200);
        }
    }
    public function acceptPesanan($id)
    {
        $pesanan = Pesanan::find($id);
        if ($pesanan == null) {
            return response()->json([
                'message' => 'Pesanan tidak ditemukan',
                'data' => null
            ], 404);
        }

        $pesanan->load('detailPesanan.produk', 'detailPesanan.hampers');

        if ($pesanan->detailPesanan->isEmpty()) {
            return response()->json([
                'message' => 'Detail pesanan tidak ditemukan',
                'data' => null
            ], 404);
        }
        foreach ($pesanan->detailPesanan as $detail) {
            if ($detail->produk) {
                $produk = $detail->produk;
                $id_resep = $produk->id_resep;
                $resep = Resep::find($id_resep);

                if ($resep) {
                    $resep->load('detail_resep');
                    foreach ($resep->detail_resep as $detailResep) {
                        $bahanBaku = $detailResep->bahanBaku;

                        if ($bahanBaku) {
                            $bahanBaku->stok -= $detailResep->jumlah_bahan;
                            $bahanBaku->save();
                        }
                    }
                }
                
            }else if ($detail->hampers) {
                $hampers = $detail->hampers;
                $hampers->load('produk');
                foreach ($hampers->produk as $hampersProduk) {
                    $produk = $hampersProduk;
                    $id_resep = $produk->id_resep;
                    $resep = Resep::find($id_resep);
                    if ($resep) {
                        $resep->load('detail_resep');
                        foreach ($resep->detail_resep as $detailResep) {
                            $bahanBaku = $detailResep->bahanBaku;
                            if ($bahanBaku) {
                                $bahanBaku->stok -= $detailResep->jumlah_bahan;
                                $bahanBaku->save();
                            }
                        }
                    }
                }
            }
        }
        $pesanan->status = 'Pesanan Diterima';
        $pesanan->save();
        return response()->json([
            'message' => 'Pesanan diterima',
            'data' => $pesanan
        ], 200);
    }
    public function showPesananValidPayment(){
        $pesanan = Pesanan::where('status', 'Pembayaran Valid')->get()->load('detailPesanan.produk', 'detailPesanan.hampers');
        if($pesanan->isEmpty()){
            return response()->json([
                'message' => 'Tidak ada Pesanan'
            ]);
        }
        return response([
            'message' => 'all Pesanan retrived',
            'data' => $pesanan
        ],200);
    }
}
