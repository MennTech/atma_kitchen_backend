<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pesanan;
use Illuminate\Support\Facades\Validator;

class PesananController extends Controller
{
    public function showPesanan(){
        $pesanan = Pesanan::where('status', 'Menunggu Konfirmasi Pesanan')->orWhere('status', 'Menunggu Konfirmasi Admin')->get()->load('customer');

        if($pesanan->isEmpty()){
            return response()->json([
                'message' => 'Tidak ada pesanan',
                'status' => false,
                'data' => null
            ], 404);
        }

        return response()->json([
            'message' => 'Berhasil menampilkan pesanan',
            'status' => true,
            'data' => $pesanan
        ], 200);
    }
    
    public function updateJarakPesanan(Request $request, $id){
        $pesanan = Pesanan::find($id);
        if($pesanan == null){
            return response()->json([
                'message' => 'Pesanan tidak ditemukan',
                'status' => false,
                'data' => null
            ], 404);
        }

        $updateJarak = $request->all();
        $validator = Validator::make($updateJarak, [
            'jarak' => 'required|min:1'
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => $validator->errors(),
                'status' => false,
                'data' => null
            ], 400);
        }
        $pesanan->jarak = $updateJarak['jarak'];

        if($updateJarak['jarak'] <= 5.0){
            $pesanan->ongkos_kirim = 10000;
        } else if($updateJarak['jarak'] >=5.0 && $updateJarak['jarak'] <= 10.0){
            $pesanan->ongkos_kirim = 15000;
        } else if($updateJarak['jarak'] >=10.0 && $updateJarak['jarak'] <= 15.0){
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

    public function updateJumlahBayarPesanan(Request $request, $id){
        $pesanan = Pesanan::find($id);

        if($pesanan == null){
            return response()->json([
                'message' => 'Pesanan tidak ditemukan',
                'status' => false,
                'data' => null
            ], 404);
        }

        $updateJumlahBayar = $request->all();
        $validator = Validator::make($updateJumlahBayar, [
            'jumlah_pembayaran' => 'required|gte:'.$pesanan->total
        ], [
            'jumlah_pembayaran.gte' => 'Jumlah pembayaran harus lebih dari atau sama dengan total harga'
        ]);

        if($validator->fails()){
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
}
