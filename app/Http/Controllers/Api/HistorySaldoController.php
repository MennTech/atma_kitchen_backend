<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\History_Saldo;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class HistorySaldoController extends Controller
{
    public function PenarikanSaldo(request $request){
        $storeData = $request->all();
        $customer = Auth::user();
        if($customer->saldo <= 0){
            return response()->json([
                'message' => 'Saldo anda tidak mencukupi',
            ],400);
        }
        $validator = Validator::make($storeData, [
            'nominal' => 'required|numeric|min:1',
            'nomor_rekening' => 'required',
        ], [
            'nominal.required' => 'Nominal harus diisi.',
            'nominal.numeric' => 'Nominal harus berupa angka.',
            'nominal.min' => 'Nominal harus lebih besar dari 0.',
            'nomor_rekening.required' => 'Nomor Rekening harus diisi.'
        ]);
        if($validator->fails()){
            return response([
                'message' => $validator->errors()
            ],400);
        }
        $storeData['tanggal'] = Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s');
        $storeData['id_customer'] = $customer->id_customer;
        $storeData['status'] = 'Pending';
        $history_saldo = History_Saldo::create($storeData);
        return response([
            'message' => 'History Saldo created',
            'data' => $history_saldo
        ],200);

    }
    public function showHistorySaldo(){
        $history = History_Saldo::where('id_customer', Auth::user()->id_customer)->orderBy('id_history_saldo', 'desc')->get();
        if($history->isEmpty()){
            return response()->json([
                'message' => 'History Saldo Kosong',
            ],404);
        }
        return response([
            'message' => 'History Saldo retrieved',
            'data' => $history
        ],200);
    }
    public function index(){
        $history = History_Saldo::where('status', 'Pending')->orderBy('id_history_saldo', 'desc')->get()->load('historySaldoCustomer');
        if($history->isEmpty()){
            return response()->json([
                'message' => 'Tidak ada penarikan saldo yang menunggu konfirmasi',
            ],404);
        }
        return response([
            'message' => 'History Saldo retrieved',
            'data' => $history
        ],200);
    }
    public function konfirmasiPenarikanSaldo($id){
        $history = History_Saldo::find($id);
        if(!$history){
            return response()->json([
                'message' => 'History Saldo not found',
            ],404);
        }
        $history->status = 'Berhasil';
        $history->save();
        $customer = Customer::find($history->id_customer);
        $customer->saldo -= $history->nominal;
        $customer->save();
        return response()->json([
            'message' => 'Penarikan Saldo Berhasil',
            'data' => $history
        ],200);
    }

    public function tolakPenarikanSaldo($id){
        $history = History_Saldo::find($id);
        if(!$history){
            return response()->json([
                'message' => 'History Saldo not found',
            ],404);
        }
        $history->status = 'Ditolak';
        $history->save();
        return response()->json([
            'message' => 'Penarikan Saldo Ditolak',
            'data' => $history
        ],200);
    }
}