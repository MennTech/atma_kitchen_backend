<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthCustomerController extends Controller
{
    public function login (Request $request){
        $data = $request->validate([
            'email_customer' => 'required|email',
            'password' => 'required'
        ]);

        if(!Auth::guard('api_customer')->attempt($data)){
            return response()->json([
                'message' => 'Email atau password salah'
            ], 401);
        }
        /** @var \App\Models\Customer $customer **/
        $customer = Auth::guard('api_customer')->user();
        $token = $customer->createToken('customer-token')->plainTextToken;
        return response()->json([
            'message' => 'Login Success',
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
                'message' => 'Anda belum login'
            ], 401);
        }
        if($token->name !== 'customer-token'){
            return response()->json([
                'message' => 'Token tidak valid'
            ], 401);
        }
        $customer->tokens()->delete();
        return response()->json([
            'message' => 'Logout Success'
        ]);
    }
}
