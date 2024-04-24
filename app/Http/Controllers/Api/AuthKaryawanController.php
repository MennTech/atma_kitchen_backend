<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthKaryawanController extends Controller
{
    public function login(Request $request){
        $requestData = $request->all();
        $validate = Validator::make($requestData, [
            'email_karyawan' => 'required|email',
            'password' => 'required'
        ],[
            'email_karyawan.required' => 'Email wajib diisi',
            'email_karyawan.email' => 'Email tidak valid',
            'password.required' => 'Password wajib diisi'
        ]);

        if($validate->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Login Gagal',
                'error' => $validate->errors()
            ], 400);
        }

        if(!Auth::guard('api_karyawan')->attempt($requestData)){
            return response()->json([
                'success' => false,
                'message' => 'Login Gagal',
                'error' => 'Email atau password salah'
            ], 401);
        }

        /** @var \App\Models\Karyawan $karyawan **/
        $karyawan = Auth::guard('api_karyawan')->user();

        if($karyawan->status !== 'Aktif'){
            return response()->json([
                'success' => false,
                'message' => 'Login Gagal',
                'error' => 'Anda Bukan Karyawan Aktif'
            ], 401);
        }

        $token = $karyawan->createToken('karyawan-token')->plainTextToken;
        return response()->json([
            'success' => true,
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
                'success' => false,
                'message' => 'Anda belum login'
            ], 401);
        }
        if($token->name !== 'karyawan-token'){
            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid'
            ], 401);
        }
        $karyawan->tokens()->delete();
        return response()->json([
            'success' => true,
            'message' => 'Logout Success'
        ]);
    }
}
