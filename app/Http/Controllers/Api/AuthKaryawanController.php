<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthKaryawanController extends Controller
{
    public function login(Request $request){
        $data = $request->validate([
            'email_karyawan' => 'required|email',
            'password' => 'required'
        ]);

        if(!Auth::guard('api_karyawan')->attempt($data)){
            return response()->json([
                'message' => 'Email atau password salah'
            ], 401);
        }

        /** @var \App\Models\Karyawan $karyawan **/
        $karyawan = Auth::guard('api_karyawan')->user();

        if($karyawan->status !== 'Aktif'){
            return response()->json([
                'message' => 'Anda Bukan Karyawan Aktif'
            ], 401);
        }

        $token = $karyawan->createToken('karyawan-token')->plainTextToken;
        return response()->json([
            'message' => 'Login Success',
            'karyawan' => $karyawan,
            'role' => $karyawan->role->jabatan,
            'token_type' => 'Bearer',
            'token' => $token
        ]);
    }

    public function logout(Request $request){
        /** @var \App\Models\Karyawan $karyawan **/
        $karyawan = Auth::user();
        $token = $karyawan->currentAccessToken();
        if(!$karyawan){
            return response()->json([
                'message' => 'Anda belum login'
            ], 401);
        }
        if($token->name !== 'karyawan-token'){
            return response()->json([
                'message' => 'Token tidak valid'
            ], 401);
        }
        $karyawan->tokens()->delete();
        return response()->json([
            'message' => 'Logout Success'
        ]);
    }
}
