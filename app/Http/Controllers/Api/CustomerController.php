<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use App\Models\Pesanan;
use Illuminate\Support\Facades\Validator;
class CustomerController extends Controller
{
    public function index(){
        $customer = Customer::all();
        if($customer->isEmpty()){
            return response()->json([
                'message' => 'Data Customer Kosong'
            ]);
        }
        return response()->json($customer);
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
            'tanggal_lahir' => 'required|date'
        ]);
        if($validate->fails())
            return response(['message' => $validate->errors()], 400);
        $customer->update($storeData);
        return response()->json($customer);
    }

    public function orderHistory(){
        $history = Pesanan::where('id_customer', Auth::user()->id_customer)->get();
        if($history->isEmpty()){
            return response()->json([
                'message' => 'History Order Kosong'
            ]);
        }
        return response()->json($history);
    }
}
