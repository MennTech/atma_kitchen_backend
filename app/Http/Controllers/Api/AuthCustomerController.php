<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use Illuminate\Support\Facades\Validator;

class AuthCustomerController extends Controller
{

    public function register(Request $request){
        $storeData = $request->all();
        $validator= Validator::make($storeData, [
            'nama_customer' => 'required',
            'email_customer' => 'required|email|unique:customers,email_customer',
            'password' => 'required',
            'tanggal_lahir' => 'required|date',
            'no_telp' => 'required|between:10,13'
        ]);
        
        if($validator->fails()){
            return response([
                'message' => $validator->errors()
            ],400);
        }
        $storeData['poin'] = 0;
        $storeData['saldo'] = 0;
        $storeData['password'] = bcrypt($storeData['password']);
        $customer = Customer::create($storeData);
        return response()->json([
            'message' => 'Register Success',
            'customer' => $customer,
        ]);
    }

    public function login (Request $request){
        $requestData = $request->all();
        $validator = Validator::make($requestData, [
            'email_customer' => 'required|email',
            'password' => 'required'
        ],[
            'email_customer.required' => 'Email wajib diisi',
            'email_customer.email' => 'Email tidak valid',
            'password.required' => 'Password wajib diisi'
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Login Gagal',
                'error' => $validator->errors()
            ], 400);
        }

        if(!Auth::guard('api_customer')->attempt($requestData)){
            return response()->json([
                'success' => false,
                'message' => 'Login Gagal',
                'error' => 'Email atau Password Salah'
            ], 401);
        }
        /** @var \App\Models\Customer $customer **/
        $customer = Auth::guard('api_customer')->user();
        $token = $customer->createToken('customer-token')->plainTextToken;
        return response()->json([
            'success' => true,
            'message' => 'Login sukses',
            'customer' => $customer,
            'token_type' => 'Bearer',
            'token' => $token
        ]);
    }

    public function logout(Request $request){
        /** @var \App\Models\Customer $customer **/
        $customer = Auth::user();
        $token = $customer->currentAccessToken();
        if(!$customer){
            return response()->json([
                'success' => false,
                'message' => 'Anda belum login'
            ], 401);
        }
        if($token->name !== 'customer-token'){
            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid'
            ], 401);
        }
        $customer->tokens()->delete();
        return response()->json([
            'success' => true,
            'message' => 'Logout sukses'
        ]);
    }
}
