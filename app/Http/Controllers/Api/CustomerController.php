<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use App\Models\Pesanan;
use App\Models\Detail_Pesanan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
class CustomerController extends Controller
{
    public function index(){
        $customer = Customer::all();
        if($customer->isEmpty()){
            return response()->json([
                'message' => 'Data Customer Kosong'
            ],404);
        }
        return response([
            'message' => 'all Customer retrived',
            'data' => $customer
        ],200);
    }

    public function show(){
        $customer = Auth::user();
        if($customer == null){
            return response()->json([
                'message' => 'Customer Tidak Ditemukan'
            ]);
        }
        return response()->json($customer);
    }

    public function update(Request $request){
        $customer = Customer::where('id_customer', Auth::user()->id_customer)->first();
        if($customer == null){
            return response()->json([
                'message' => 'Customer Tidak Ditemukan'
            ]);
        }
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'nama_customer' => 'required',
            'no_telp' => 'required|between:10,13',
        ]);
        if($validate->fails())
            return response(['message' => $validate->errors()], 400);
        $customer->update($storeData);
        return response()->json($customer);
    }

    public function orderHistory(){
        $history = Pesanan::where('id_customer', Auth::user()->id_customer)->where('status', '!=', 'Keranjang')->orderBy('id_pesanan', 'desc')->get()->load('detailPesanan.produk', 'detailPesanan.hampers');
        if($history->isEmpty()){
            return response()->json([
                'message' => 'History Order Kosong',
                'data' => null
            ], 400);
        }
        return response()->json([
            'message' => 'History Order',
            'data' => $history
        ], 200);
    }
    public function detailOrderHistory($id_pesanan)
    {
        $history = Detail_Pesanan::with(['produk', 'hampers'])->where('id_pesanan', $id_pesanan)->get()->load('produk', 'hampers');
        if($history->isEmpty()){
            return response()->json([
                'message' => 'Detail Pesanan Kosong'
            ]);
        }
        return response([
            'message' => 'all Detail Pesanan retrieved',
            'data' => $history
        ],200);
    }
    public function orderHistorybyUser(string $id_customer){
        $history = Pesanan::where('id_customer', $id_customer)->get();
        if($history->isEmpty()){
            return response()->json([
                'message' => 'History Order Kosong'
            ]);
        }
        return response([
            'message' => 'all Pesanan retrived',
            'data' => $history
        ],200);
    }

    public function getAlamatUser(){
        $customer = Auth::user();
        if($customer == null){
            return response()->json([
                'success' => false,
                'message' => 'Customer Tidak Ditemukan'
            ]);
        }
        return response()->json([
            'success' => true,
            'message' => 'Alamat Customer',
            'data' => $customer->alamat
        ]);
    }

    public function showOrderMustbePaid(){
        $history = Pesanan::where('id_customer', Auth::user()->id_customer)->where('status', 'Menunggu Pembayaran')->orWhere('status', 'Sedang Dikirim')->orWhere('status', 'Sudah Di-pickup')->get()->load('detailPesanan.produk', 'detailPesanan.hampers');
        if($history->isEmpty()){
            return response()->json([
                'message' => 'History Order Kosong'
            ]);
        }
        return response([
            'message' => 'all Pesanan retrived',
            'data' => $history
        ],200);
    }
    public function BuktiPembayaran(Request $request){
        $updateData = $request->all();
        $pesanan = Pesanan::find($updateData['id_pesanan']);
        if($pesanan == null){
            return response()->json([
                'message' => 'Pesanan Tidak Ditemukan'
            ]);
        }
        if($request->hasFile('bukti_pembayaran')){
            $uploadFolder = 'bukti_pembayaran';
            $bukti_pembayaran = $request->file('bukti_pembayaran');
            $image_uploaded_path = $bukti_pembayaran->store($uploadFolder, 'public');
            $uploadedImageResponse = basename($image_uploaded_path);

            $updateData['bukti_pembayaran'] = $uploadedImageResponse;
            $updateData['status'] ='Menunggu Konfirmasi Admin';

            $updateData['tanggal_lunas'] = Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s');
            $pesanan->update($updateData);
            return response([
                'message' => 'Content Updated Successfully',
                'data' => $pesanan,
            ],200);
        }else{
            return response([
                'message' => 'Bukti Pembayaran Tidak Ditemukan'
            ],400);
        }

    }

    public function showPesananDikirimSudahPickup(){
        $pesanan = Pesanan::where('id_customer', Auth::user()->id_customer)->where(
            function($query){
                $query->where('status', 'Sedang Dikirim')->orWhere('status', 'Sudah Di-pickup');
            })->orderBy('id_pesanan', 'desc')->get()->load('detailPesanan.produk', 'detailPesanan.hampers');

        if($pesanan == null){
            return response()->json([
                'message' => 'Pesanan tidak ada',
                'status' => false,
                'data' => null
            ],404);
        }

        return response()->json([
            'message' => 'Pesanan ditemukan',
            'status' => true,
            'data' => $pesanan
        ],200);
    }
}
