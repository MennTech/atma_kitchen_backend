<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Karyawan;
use Illuminate\Support\Facades\Validator;
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

    public function changePassword(Request $request){
        $karyawan = Karyawan::where('id_karyawan', Auth::user()->id_karyawan)->first();

        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'new_password' => 'required'
        ]);
        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $karyawan->update([
            'password' => bcrypt($storeData['new_password'])
        ]);
        return response()->json([
            'message' => 'Password berhasil diubah'
        ]);
    }
}
